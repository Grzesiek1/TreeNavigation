<?php

/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-22
 * Time: 00:29
 * Class allows to operate on elements tree
 */

declare(strict_types = 1);

class ActionTree
{
    function __construct($db)
    {
        $this->db = $db;
    }

    /*
     * Method use to add new elements tree
     */
    function add(string $name, int $parent = 0)
    {
        //check whether selected parent exist
        if ($parent != 0) {
            $res = $this->db->prepare("SELECT COUNT(id) FROM tree WHERE id = :parent");
            $res->bindValue(':parent', $parent, PDO::PARAM_STR);
            $res->execute();

            if ($res->fetchColumn() <= 0) {
                return 'Error. Parent not exist.';
            }
        }

        // Counts the number of elements in a branch
        $res = $this->db->prepare("SELECT MAX(display_order) FROM tree WHERE parent = :parent");
        $res->bindValue(':parent', $parent, PDO::PARAM_STR);
        $res->execute();
        $position_new_element = $res->fetchColumn() + 1;

        // Adds a new tree element
        try {
            $res = $this->db->prepare("INSERT INTO `tree` (`id`, `name`, `parent`, `display_order`) VALUES ('', :name, :parent, :display_order)");
            $res->bindValue(':name', $name, PDO::PARAM_STR);
            $res->bindValue(':parent', $parent, PDO::PARAM_INT);
            $res->bindValue(':display_order', $position_new_element, PDO::PARAM_INT);
            $res->execute();
        } catch (Exception $e) {
            if (strpos($e->getMessage(), '1062') !== false) {
                return 'Can not add. Such an element already exists.';
            } else {
                return 'Error. Can not add.';
            }
        }
        return 'Add new element, name:' . $name;
    }

    /*
     * Method using to remove element tree
     */
    function remove(int $id)
    {
        try {
            $result = $this->db->prepare("DELETE FROM `tree` WHERE `tree`.`id` = :id ");
            $result->bindValue(':id', $id, PDO::PARAM_INT);
            $result->execute();
        } catch (Exception $e) {
            return 'Error. Can not remove.';
        }

        $this->remove_lost_items();
        $this->rebuild_index_display();

        return 'Remove element id:' . $id;
    }

    /*
     * This method repairs the index of the order.
     * (Need in case delete items or moved element from another branch)
     */
    private function rebuild_index_display()
    {

        $res = $this->db->prepare("SELECT id, parent, display_order FROM tree ORDER BY parent ASC, display_order");
        $res->execute();
        // get all element tree
        while ($row = $res->fetch()) {
            $array['id'][] = $row['id'];
            $array['parent'][] = $row['parent'];
            $array['display_order'][] = $row['display_order'];
        }

        // view items
        $parent = 0;
        $x = 1;
        $query = 'INSERT INTO tree (id,parent,display_order) VALUES';

        // move to the new array in the correct order
        foreach ($array['id'] as $value => $key) {
            if ($parent != $array['parent'][$value]) {
                $parent = $array['parent'][$value];
                $x = 1;
            }
            $array_new['id'][$value] = $array['id'][$value];
            $array_new['parent'][$value] = $array['parent'][$value];
            // assign a new display index
            $array_new['display_order'][$value] = $x;

            // did not use prepare PDO. Check whether it is int!
            $query .= " (" . (int)$array_new['id'][$value] . ", " . (int)$array_new['parent'][$value] . ", " . (int)$array_new['display_order'][$value] . "),";

            $x++;
        }
        $query = rtrim($query, ",");
        $query .= ' ON DUPLICATE KEY UPDATE display_order = VALUES(display_order);';

        //set new values in database table
        try {
            $res = $this->db->query($query);
            $res->execute();
        } catch (Exception $e) {
            return 'Error query in rebuild_index_display()';
        }
        return true;
    }

    /*
     * This method remove elements without exist branches (from deleted branch)
     */
    private function remove_lost_items()
    {
        $res = $this->db->prepare("SELECT id, parent FROM tree");
        $res->execute();
        //Get all element tree
        while ($row = $res->fetch()) {
            $array['id'][] = $row['id'];
            $array['parent'][] = $row['parent'];
        }

        //view items
        foreach ($array['id'] as $value => $key) {

            //get parent
            $res = $this->db->prepare("SELECT COUNT(id) FROM tree WHERE id = :parent");
            $res->bindValue(':parent', $array['parent'][$value], PDO::PARAM_INT);
            $res->execute();

            // if parent not exist(result 0) $row[id] - remove element (exception if element is root [parent==0])
            if ($res->fetchColumn() == 0) {
                $result = $this->db->prepare("DELETE FROM `tree` WHERE `tree`.`id` = :id AND parent > 0");
                $result->bindValue(':id', $array['id'][$value], PDO::PARAM_INT);
                $result->execute();
            }
        }
        return 'The tree was cleared';
    }

    /*
     * Method rename name element.
     */
    function rename(int $id, string $name)
    {
        try {
            $result = $this->db->prepare("UPDATE `tree` SET `name` = :name WHERE `tree`.`id` = :id");
            $result->bindValue(':id', $id, PDO::PARAM_INT);
            $result->bindValue(':name', $name, PDO::PARAM_STR);
            $result->execute();
        } catch (Exception $e) {
            if (strpos($e->getMessage(), '1062') !== false) {
                return 'Can not rename. Given name already exists. The name must be unique';
            } else {
                return 'Error. Can not rename.';
            }

        }
        $this->session_refresh($id);
        return 'Set new name:' . $name . ' for element id:' . $id;
    }

    /*
     * Method for moving tree elements
     */
    function move_left(int $id)
    {
        try {
            try {
                $res = $this->db->prepare("(SELECT parent FROM `tree` WHERE id = (SELECT parent FROM `tree` WHERE id = :id))");
                $res->bindValue(':id', $id, PDO::PARAM_INT);
                $res->execute();
            } catch (Exception $e) {
                return 'Read error. Can not move left.';
            }
            $parent = $res->fetchColumn();

            // You can not become your parent
            if ($id != $parent) {

                // check whether already you where his parent (if he is your parent. you can not be him)
                $result = $this->db->prepare("SELECT COUNT(id) FROM `tree` WHERE id = :id AND parent = :parent");
                $result->bindValue(':id', $parent, PDO::PARAM_INT);
                $result->bindValue(':parent', $id, PDO::PARAM_INT);
                $result->execute();

                // check whether already you where his parent (if he is your parent. you can not be him)
                if ($result->fetchColumn() == 0) {
                    $result = $this->db->prepare("UPDATE `tree` SET `parent` = :parent_id WHERE `tree`.`id` = :id");
                    $result->bindValue(':id', $id, PDO::PARAM_INT);
                    $result->bindValue(':parent_id', $parent, PDO::PARAM_STR);
                    $result->execute();
                }


            }

        } catch (Exception $e) {
            return 'Error writing. Can not move left.';
        }

        $this->rebuild_index_display();
        $this->session_refresh($id);
        return 'Element id:' . $id . ' move left.';
    }

    /*
     * Method for moving tree elements
     */
    function move_up(int $id, int $position_base_element = 0, int $parent = 0)
    {// second parameter only used $this->move_up himself
        try {
            //check current position element
            $res = $this->db->prepare("SELECT display_order, parent FROM `tree` WHERE id = :id");
            $res->bindValue(':id', $id, PDO::PARAM_INT);
            $res->execute();

            if ($position_base_element == 0) {
                while ($row = $res->fetch()) {
                    $position_base_element = $row['display_order'];
                    $parent = $row['parent'];
                }
            }

            //check exist higher element
            if ($position_base_element > 0) {
                $result = $this->db->prepare("SELECT id FROM `tree` WHERE display_order = :display_order AND parent = :parent");
                $result->bindValue(':display_order', $position_base_element - 1, PDO::PARAM_INT);
                $result->bindValue(':parent', $parent, PDO::PARAM_INT);
                $result->execute();
            }
            $position_higher_element = $result->fetchColumn();


            // element exist move down
            if ($position_higher_element > 0) {
                $set = $this->db->prepare("UPDATE `tree` SET display_order = :display_order WHERE id = :id AND parent = :parent");
                $set->bindValue(':display_order', $position_base_element, PDO::PARAM_INT);
                $set->bindValue(':id', $position_higher_element, PDO::PARAM_INT);
                $set->bindValue(':parent', $parent, PDO::PARAM_INT);
                $set->execute();

                $set = $this->db->prepare("UPDATE `tree` SET display_order = :display_order WHERE id = :id AND parent = :parent");
                $set->bindValue(':id', $id, PDO::PARAM_INT);
                $set->bindValue(':display_order', $position_base_element - 1, PDO::PARAM_INT);
                $set->bindValue(':parent', $parent, PDO::PARAM_INT);
                $set->execute();
            } else {

                if ($position_base_element > 1) {
                    $position_base_element = $position_base_element - 1;

                    $this->move_up($id, $position_base_element, $parent);
                }
            }

        } catch (Exception $e) {
            return 'Read error. Can not move up.' . $e;
        }
        $this->session_refresh($id);
        return 'Element place is:' . $res->fetchColumn() . ' move up.';
    }

    /*
    * Method for moving tree elements
    */
    function move_down(int $id)
    {
        //check current position element
        $res = $this->db->prepare("SELECT display_order, parent FROM `tree` WHERE id = :id");
        $res->bindValue(':id', $id, PDO::PARAM_INT);
        $res->execute();

        while ($row = $res->fetch()) {
            $current_position = $row['display_order'];
            $parent = $row['parent'];
        }

        //check exist upper element
        $upper_position = $current_position + 1;
        $result = $this->db->prepare("SELECT id FROM `tree` WHERE display_order = :display_order AND parent = :parent");
        $result->bindValue(':display_order', $upper_position, PDO::PARAM_INT);
        $result->bindValue(':parent', $parent, PDO::PARAM_INT);
        $result->execute();

        $upper_id = $result->fetchColumn();
        //check exist upper element
        if ($upper_id > 0) {
            $set = $this->db->prepare("UPDATE `tree` SET display_order = :display_order WHERE id = :id AND parent = :parent");
            $set->bindValue(':display_order', $current_position, PDO::PARAM_INT);
            $set->bindValue(':id', $upper_id, PDO::PARAM_INT);
            $set->bindValue(':parent', $parent, PDO::PARAM_INT);
            $set->execute();

            $set = $this->db->prepare("UPDATE `tree` SET display_order = :display_order WHERE id = :id AND parent = :parent");
            $set->bindValue(':id', $id, PDO::PARAM_INT);
            $set->bindValue(':display_order', $upper_position, PDO::PARAM_INT);
            $set->bindValue(':parent', $parent, PDO::PARAM_INT);
            $set->execute();

            $this->session_refresh($id);
            return 'Move down element.';
        } else {
            return 'Can not move element';
        }
    }

    /*
    * Method for moving tree elements
    */
    function move_right(int $id, int $move_more = 0)
    {
        $object = new GenerateTreeArrays($this->db);
        $array = $object->generate_tree(true);

        foreach ($array['id'] as $value => $key) {
            if ($array['id'][$value] == $id) {
                if ($move_more > 0) {
                    $id_right = (int)$array['id'][$value + $move_more];
                } else {
                    $id_right = (int)$array['id'][$value + 1];
                }
            }
        }

        if ($this->check_is_conflict($id, $id_right) == false) {
            $set = $this->db->prepare("UPDATE `tree` SET parent = :parent WHERE id = :id");
            $set->bindValue(':id', $id, PDO::PARAM_INT);
            $set->bindValue(':parent', $id_right, PDO::PARAM_INT);
            $set->execute();

            $this->rebuild_index_display();
            $this->session_refresh($id);
            return 'Move element to right';

        } else {
            $move_more++;
         //   $this->move_right($id, $move_more);
            $this->session_refresh($id);
            return "Can not move element \n";
        }


    }


    function check_is_conflict(int $id, int $to)
    {
        
        // check mutual kinship
        $result = $this->db->prepare("SELECT COUNT(id) FROM `tree` WHERE id = :id AND parent = :parent");
        $result->bindValue(':id', $to, PDO::PARAM_INT);
        $result->bindValue(':parent', $id, PDO::PARAM_INT);
        $result->execute();

        // check mutual kinship
        if ($result->fetchColumn() == 0) {



            $list = $this->get_all_parents_this_element($to);
            //print_r($list);
            if (in_array($id, $list) == 0) {
                echo 'allow enter';
            } elseif (in_array($id, $list) == 1) {
                echo 'not allow enter';

            }
            return true;



            $sub_branch = $this->put_in_deep((int)$to);


            if ($sub_branch == 0) {
                return false;
                unset($memory);
            } else {
                //  $this->check_is_conflict((int)$id, (int)$sub_branch);
                echo "block1\n";
                return true;
            }


        } else {
            echo "block2\n";
            return true;
        }

    }


    public $list = array();
    function get_all_parents_this_element(int $id)
    {

        $result = $this->db->prepare("SELECT parent FROM `tree` WHERE id = :id ");
        $result->bindValue(':id', $id, PDO::PARAM_INT);
        $result->execute();

        $parent = $result->fetchColumn();

        array_push($this->list, $parent);

        if ($parent > 0) {
            $this->get_all_parents_this_element((int)$parent);
        }

        return $this->list;
    }

    function put_in_deep(int $id)
    {
        $result = $this->db->prepare("SELECT parent FROM `tree` WHERE id = :id ");
        $result->bindValue(':id', $id, PDO::PARAM_INT);
        $result->execute();

        return $result->fetchColumn();
    }


    /*
    * Determine the location of an item when using moving items using the keyboard.
    * Return number occurrence (number on list in frontend)
    */
    function number_occurrence(int $id_element)
    {
        $object = new GenerateTreeArrays($this->db);
        $return = array_search($id_element, $object->generate_tree(true)['id']);

        return $return;
    }

    /*
     * Method memorizing the last moved item.
     * Need when using moving items using the keyboard.
     */
    private function session_refresh(int $id)
    {
        $_SESSION['id_operation'] = $id;
        return true;
    }


}