<?php

/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-22
 * Time: 00:29
 */
declare(strict_types = 1);

class Main
{
    function __construct($db)
    {
        $this->db = $db;
    }

    /*
     * Replace index from $arg[x][y] to $arg[y][x]
    */
    function array_change_xy($array)
    {
        $x = 0;
        foreach ($array as $item) {
            foreach ($item as $key => $value) {
                $new_array[$key][$x] = $value;
            }
            $x++;
        }
        if (isset($new_array))
            return $new_array;
    }

    function add(string $name, int $parent = 0)
    {
        //check whether selected parent exist
        if($parent != 0){
            $res = $this->db->prepare("SELECT COUNT(id) FROM tree WHERE id = :parent");
            $res->bindValue(':parent', $parent, PDO::PARAM_STR);
            $res->execute();

            if($res->fetchColumn() <= 0){
                return 'Error. Parent not exist.';
            }
        }


        // Counts the number of elements in a branch
        $res = $this->db->prepare("SELECT MAX(display_order) FROM tree WHERE parent = :parent");
        $res->bindValue(':parent', $parent, PDO::PARAM_STR);
        $res->execute();
        $position_new_element = $res->fetchColumn() + 1;



        //Adds a new tree element
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

    function remove(int $id)
    {
        try {
            $result = $this->db->prepare("DELETE FROM `tree` WHERE `tree`.`id` = :id ");
            $result->bindValue(':id', $id, PDO::PARAM_INT);
            $result->execute();
        } catch (Exception $e) {
            return 'Error. Can not remove.';
        }


        return 'Remove element id:' . $id;
    }

    private
    function delete_child()
    {

    }

    function rename($id, $name)
    {
        try {
            $result = $this->db->prepare("UPDATE `tree` SET `name` = :name WHERE `tree`.`id` = :id");
            $result->bindValue(':id', $id, PDO::PARAM_INT);
            $result->bindValue(':name', $name, PDO::PARAM_STR);
            $result->execute();
        } catch (Exception $e) {
            return 'Error. Can not rename.';
        }

        return 'Set new name:' . $name . ' for element id:' . $id;
    }

    function edit()
    {

    }

    function up()
    {

    }

    function down()
    {

    }

}