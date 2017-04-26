<?php

/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-26
 * Time: 03:51
 */
class ElementsGenerate extends ActionTree
{
    function __construct($db)
    {
        $this->db = $db;
    }

    function elements_put(int $id)
    {
        $res = $this->db->prepare("SELECT id, name FROM elements WHERE folder = :id");
        $res->bindValue(':id', $id, PDO::PARAM_INT);
        $res->execute();

        $return = '"tags": [';
        while ($row = $res->fetch()) {
            $return .= '"' . $row['name'] . ' <a onclick=delete_element(' . $row['id'] . ');>X</a>",';
        }

        $return .= "]";
        $return = str_replace(",]", "]", $return);

        return $return;
    }

    function add_element(string $name, int $folder)
    {
        $res = $this->db->prepare("INSERT INTO `elements` (`id`, `name`, `folder`) VALUES ('', :name, :folder)");
        $res->bindValue(':name', $name, PDO::PARAM_INT);
        $res->bindValue(':folder', $folder, PDO::PARAM_INT);
        $res->execute();

        $this->session_refresh($folder);
        return 'Added element, name: ' . $name;
    }

    function delete_element(int $id)
    {
        $res = $this->db->prepare("DELETE FROM `elements` WHERE `elements`.`id` = :id");
        $res->bindValue(':id', $id, PDO::PARAM_INT);
        $res->execute();

        return 'Remove element, id: ' . $id;
    }
}