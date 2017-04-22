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
    public $array_value, $array_name;

    function __construct($db)
    {
        $this->db = $db;
        $this->array_value = array();
        $this->array_name = array();
    }


    function show()
    {
        $res = $this->db->prepare("SELECT id, name, parent, display_order FROM Trees order by parent");
        $res->execute();

        echo '<ul>';

        while ($row = $res->fetch()) {

            if ($this->check_have_parent($row['id'])) {
                if (!$this->check_used_string($row['name'])) {
                    echo '<li>' . $row['name'];
                }

                $res2 = $this->db->prepare("SELECT id, name, parent, display_order FROM Trees WHERE parent = :id order by display_order");
                $res2->bindValue(':id', $row['id'], PDO::PARAM_INT);
                $res2->execute();

                while ($row2 = $res2->fetch()) {
                    if (!$this->check_used_value($row['parent'])) {
                        echo '<ul>';
                    }

                    if (!$this->check_used_string($row2['name'])) {
                        echo '<li>' . $row2['name'] . '</li>';
                    }

                }
                echo '</ul>';
                echo '</li>';
            } else {
                if (!$this->check_used_string($row['name'])) {
                    echo '<li>' . $row['name'] . '</li>';
                }
            }
        }
        echo '</ul>';
    }

    function check_have_parent($id)
    {
        $res = $this->db->prepare("SELECT COUNT(id) FROM Trees WHERE parent = :id");
        $res->bindValue(':id', $id, PDO::PARAM_INT);
        $res->execute();

        if ($res->fetchColumn() > 0) {
            return true;
        }else{
            return false;
        }
    }

    function check_used_string($value)
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

    function check_used_value($value)
    {
        $exist_value = 'no';

        for ($x = 0; $x < sizeof($this->array_value); $x++) {
            if ($value == $this->array_value[$x]) {
                $exist_value = 'yes';
            }
        }

        array_push($this->array_value, $value);

        if ($exist_value == 'yes') {
            return true;
        } else {
            return false;
        }
    }


}

$object = new GenerateTree($db);
$object->show();










