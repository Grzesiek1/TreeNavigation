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
    private $array, $json, $return;

    function __construct($db)
    {
        $this->db = $db;
        $this->array = array();
        $this->json = false;
    }


    function generate_tree(bool $json = false)
    {
        $this->json = $json;

        $res = $this->db->prepare("SELECT id, name, parent, display_order FROM tree order by parent ASC, display_order");
        $res->execute();

        if ($this->json == true) {// json return data

            $this->return .= '[';
            while ($row = $res->fetch()) {
                if ($this->check_have_child((int)$row['id'])) {
                    $this->generate_child((int)$row['id'], $row['name']);
                } else {
                    if (!$this->whether_value_occurred($row['name'])) {
                        $this->return .= '{"id":'.$row['id'].', "text": "' . $row['name'] . '"},';
                    }
                }
            }
            $this->return .= ']';

        } else {// <ul><li> return data

            $this->return .= '<ul>';
            while ($row = $res->fetch()) {
                if ($this->check_have_child((int)$row['id'])) {
                    $this->generate_child((int)$row['id'], $row['name']);
                } else {
                    if (!$this->whether_value_occurred($row['name'])) {
                        $this->return .= '<li>' . $row['name'] . '</li>';
                    }
                }
            }
            $this->return .= '</ul>';
        }

        // remove char , before end block
        if ($this->json == true) {
            $this->return = str_replace("},]", "}]", $this->return);
        }

        return $this->return;
    }

    private function generate_child(int $id, string $parent_name)
    {
        if ($this->json == true) {// json return data
            $closed_tag = 0;

            if (!$this->whether_value_occurred($parent_name)) {
                $this->return .= '{"id":'.$id.', "text": "' . $parent_name . '"';
            }

            $res = $this->db->prepare("SELECT id, name, parent, display_order FROM tree WHERE parent = :id order by display_order");
            $res->bindValue(':id', $id, PDO::PARAM_INT);
            $res->execute();

            while ($row = $res->fetch()) {
                if (!$this->whether_value_occurred($row['parent'])) {
                    $this->return .= ',"nodes": [';
                    $closed_tag = 1;
                }

                if (!$this->whether_value_occurred($row['name'])) {
                    if ($this->check_have_child((int)$row['id'])) {
                        $this->return .= '{"id":'.$row['id'].', "text": "' . $row['name'] . '"';
                        $this->generate_child((int)$row['id'], $row['name']);
                    } else {
                        $this->return .= '{"id":'.$row['id'].', "text": "' . $row['name'] . '"},';
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

            $res = $this->db->prepare("SELECT id, name, parent, display_order FROM tree WHERE parent = :id order by display_order");
            $res->bindValue(':id', $id, PDO::PARAM_INT);
            $res->execute();

            while ($row = $res->fetch()) {
                if (!$this->whether_value_occurred($row['parent'])) {
                    $this->return .= '<ul>';
                    $closed_tag = 1;
                }

                if (!$this->whether_value_occurred($row['name'])) {
                    if ($this->check_have_child((int)$row['id'])) {
                        $this->return .= '<li>' . $row['name'] . '</li>';
                        $this->generate_child((int)$row['id'], $row['name']);
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


    private function check_have_child(int $id)
    {
        $res = $this->db->prepare("SELECT COUNT(id) FROM tree WHERE parent = :id");
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

        for ($x = 0; $x < sizeof($this->array); $x++) {
            if ($value == $this->array[$x]) {
                $exist_value = 'yes';
            }
        }

        array_push($this->array, $value);

        if ($exist_value == 'yes') {
            return true;
        } else {
            return false;
        }
    }
}