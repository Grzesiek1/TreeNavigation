<?php

/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-22
 * Time: 15:02
 */
declare(strict_types = 1);

class GenerateTree
{
    private $array_value, $array_name, $json, $return;

    function __construct($db)
    {
        $this->db = $db;
        $this->array_value = array();
        $this->array_name = array();
        $this->json = false;
    }


    function generate_tree(Bool $json = false)
    {
        $this->json = $json;

        $res = $this->db->prepare("SELECT id, name, parent, display_order FROM Trees order by parent");
        $res->execute();

        if ($this->json == true) {// json return data

            $this->return .= '[';
            while ($row = $res->fetch()) {
                if ($this->check_have_child($row['id'])) {
                    $this->generate_child($row['id'], $row['name']);
                } else {
                    if (!$this->whether_value_occurred($row['name'])) {
                        $this->return .= '{"text": "' . $row['name'] . '"},';
                    }
                }
            }
            $this->return .= ']';

        } else {// <ul><li> return data

            $this->return .= '<ul>';
            while ($row = $res->fetch()) {
                if ($this->check_have_child($row['id'])) {
                    $this->generate_child($row['id'], $row['name']);
                } else {
                    if (!$this->whether_value_occurred($row['name'])) {
                        $this->return .= '<li>' . $row['name'] . '</li>';
                    }
                }
            }
            $this->return .= '</ul>';
        }

        // remove char , before end block
        if ($this->json == true){
            $this->return = str_replace("},]", "}]", $this->return);
        }

        return $this->return;
    }

    private function generate_child($id, String $parent_name)
    {
        if ($this->json == true) {// json return data
            $closed_tag = 0;

            if (!$this->whether_value_occurred($parent_name)) {
                $this->return .= '{ "text": "' . $parent_name . '"';
            }

            $res = $this->db->prepare("SELECT id, name, parent, display_order FROM Trees WHERE parent = :id order by display_order");
            $res->bindValue(':id', $id, PDO::PARAM_INT);
            $res->execute();

            while ($row = $res->fetch()) {
                if (!$this->whether_value_occurred($row['parent'])) {
                    $this->return .= ',"nodes": [';
                    $closed_tag = 1;
                }

                if (!$this->whether_value_occurred($row['name'])) {
                    if ($this->check_have_child($row['id'])) {
                        $this->return .= '{ "text": "' . $row['name'] . '"';
                        $this->generate_child($row['id'], $row['name']);
                    } else {
                        $this->return .= '{ "text": "' . $row['name'] . '"},';
                    }
                }
            }
            if ($closed_tag == 1) {
                $this->return .= ']';
                $this->return .= '},';
            }
        } else {// <ul><li> return data
            $closed_tag = 0;

            if (!$this->whether_value_occurred($parent_name)) {
                $this->return .= '<li>' . $parent_name;
            }

            $res = $this->db->prepare("SELECT id, name, parent, display_order FROM Trees WHERE parent = :id order by display_order");
            $res->bindValue(':id', $id, PDO::PARAM_INT);
            $res->execute();

            while ($row = $res->fetch()) {
                if (!$this->whether_value_occurred($row['parent'])) {
                    $this->return .= '<ul>';
                    $closed_tag = 1;
                }

                if (!$this->whether_value_occurred($row['name'])) {
                    if ($this->check_have_child($row['id'])) {
                        $this->return .= '<li>' . $row['name'] . '</li>';
                        $this->generate_child($row['id'], $row['name']);
                    } else {
                        $this->return .= '<li>' . $row['name'] . '</li>';
                    }
                }
            }
            if ($closed_tag == 1) {
                $this->return .= '</ul>';
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