<?php

/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-22
 * Time: 15:02
 * GENERATE_TREE - FILE TEMPORARY
 */

require_once('../head.php');

class GenerateTree
{
    private $array_value, $array_name, $json;

    function __construct($db)
    {
        $this->db = $db;
        $this->array_value = array();
        $this->array_name = array();
        $this->json = false;
    }


    function generate_tree($json = false)
    {
        $this->json = $json;

        $res = $this->db->prepare("SELECT id, name, parent, display_order FROM Trees order by parent");
        $res->execute();

        if ($this->json == true) {// json return data
            
            echo '[';
            while ($row = $res->fetch()) {
                if ($this->check_have_child($row['id'])) {
                    $this->generate_child($row['id'], $row['name']);
                } else {
                    if (!$this->whether_value_occurred($row['name'])) {
                        echo '{text: "' . $row['name'] . '",},';
                    }
                }
            }
            echo ']';

        } else {// <ul><li> return data

            echo '<ul>';
            while ($row = $res->fetch()) {
                if ($this->check_have_child($row['id'])) {
                    $this->generate_child($row['id'], $row['name']);
                } else {
                    if (!$this->whether_value_occurred($row['name'])) {
                        echo '<li>' . $row['name'] . '</li>';
                    }
                }
            }
            echo '</ul>';
        }
    }

    private function generate_child($id, $parent_name)
    {
        if ($this->json == true) {// json return data
            $closed_tag = 0;

            if (!$this->whether_value_occurred($parent_name)) {
                echo '{ text: "' . $parent_name . '"';
            }

            $res = $this->db->prepare("SELECT id, name, parent, display_order FROM Trees WHERE parent = :id order by display_order");
            $res->bindValue(':id', $id, PDO::PARAM_INT);
            $res->execute();

            while ($row = $res->fetch()) {
                if (!$this->whether_value_occurred($row['parent'])) {
                    echo ',nodes: [';
                    $closed_tag = 1;
                }

                if (!$this->whether_value_occurred($row['name'])) {
                    if ($this->check_have_child($row['id'])) {
                        echo '{ text: "' . $row['name'] . '"';
                        $this->generate_child($row['id'], $row['name']);
                    } else {
                        echo '{ text: "' . $row['name'] . '",},';
                    }
                }
            }
            if ($closed_tag == 1) {
                echo ']';
                echo '},';
            }
        } else {// <ul><li> return data
            $closed_tag = 0;

            if (!$this->whether_value_occurred($parent_name)) {
                echo '<li>' . $parent_name;
            }

            $res = $this->db->prepare("SELECT id, name, parent, display_order FROM Trees WHERE parent = :id order by display_order");
            $res->bindValue(':id', $id, PDO::PARAM_INT);
            $res->execute();

            while ($row = $res->fetch()) {
                if (!$this->whether_value_occurred($row['parent'])) {
                    echo '<ul>';
                    $closed_tag = 1;
                }

                if (!$this->whether_value_occurred($row['name'])) {
                    if ($this->check_have_child($row['id'])) {
                        echo '<li>' . $row['name'] . '</li>';
                        $this->generate_child($row['id'], $row['name']);
                    } else {
                        echo '<li>' . $row['name'] . '</li>';
                    }
                }
            }
            if ($closed_tag == 1) {
                echo '</ul>';
                echo '</li>';
            }
        }
    }


    private function check_have_child($id)
    {
        $res = $this->db->prepare("SELECT COUNT(id) FROM Trees WHERE parent = :id");
        $res->bindValue(':id', $id, PDO::PARAM_INT);
        $res->execute();

        if ($res->fetchColumn() > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function whether_value_occurred($value)
    {
        $exist_value = 'no';

        for ($x = 0; $x < sizeof($this->array_name); $x++) {
            if ($value == $this->array_name[$x]) {
                $exist_value = 'yes';
            }
        }

        array_push($this->array_name, $value);

        if ($exist_value == 'yes') {
            return true;
        } else {
            return false;
        }
    }
}

$object = new GenerateTree($db);
$object->generate_tree(true);










