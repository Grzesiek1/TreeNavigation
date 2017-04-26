<?php

/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-26
 * Time: 03:51
 */
class ActionFiles extends ActionTree
{

    function __construct($db)
    {
        $this->db = $db;
    }

    function files_generate(int $id)
    {
        $res = $this->db->prepare("SELECT id, name FROM files WHERE folder = :id");
        $res->bindValue(':id', $id, PDO::PARAM_INT);
        $res->execute();

        $return = '"tags": [';
        while ($row = $res->fetch()) {
            $return .= '"<a class=no_link onclick=file_selected(' . $row['id'] . ');>' . $row['name'] . '</a> <a onclick=file_remove(' . $row['id'] . ');>X</a>",';
        }

        $return .= "]";
        $return = str_replace(",]", "]", $return);

        return $return;
    }

    function file_add(string $name, int $folder)
    {
        $res = $this->db->prepare("INSERT INTO `files` (`id`, `name`, `folder`) VALUES ('', :name, :folder)");
        $res->bindValue(':name', $name, PDO::PARAM_INT);
        $res->bindValue(':folder', $folder, PDO::PARAM_INT);
        $res->execute();

        $this->session_refresh($folder);
        return 'Added file, name: ' . $name;
    }

    function file_remove(int $id)
    {
        $name = $this->return_name((int)$id);
        $res = $this->db->prepare("DELETE FROM `files` WHERE `files`.`id` = :id");
        $res->bindValue(':id', $id, PDO::PARAM_INT);
        $res->execute();

        return 'Remove file, id: ' . $id . ' name: ' . $name;
    }

    function return_name($id)
    {
        $result = $this->db->prepare("SELECT name FROM `files` WHERE id = :id ");
        $result->bindValue(':id', $id, PDO::PARAM_INT);
        $result->execute();

        return $result->fetchColumn();
    }

    function file_rename(int $id, string $name)
    {
        try {
            $result = $this->db->prepare("UPDATE `files` SET `name` = :name WHERE `files`.`id` = :id");
            $result->bindValue(':id', $id, PDO::PARAM_INT);
            $result->bindValue(':name', $name, PDO::PARAM_STR);
            $result->execute();
        } catch (Exception $e) {
            return 'Error. Can not rename file.';
        }
        $this->session_refresh(0);
        return 'Set new name: ' . $name . ' for file id: ' . $id;
    }

    function file_move_up($id, $folder)
    {
        $object = new GenerateTreeArrays($this->db);
        $array = $object->generate_tree(true);

        foreach ($array['id'] as $value => $key) {
            if ($array['id'][$value] == $folder) {
                $folder = $array['id'][$value - 1];
                $set = $this->db->prepare("UPDATE `files` SET folder = :folder WHERE id = :id");
                $set->bindValue(':id', $id, PDO::PARAM_INT);
                $set->bindValue(':folder', $folder, PDO::PARAM_INT);
                $set->execute();

                $this->session_refresh($array['id'][$value - 1]);
                return 'Moved up, file id: ' . $id . ' name: ' . $this->return_name($id);
            }
        }
    }

    function file_move_down($id, $folder)
    {
        $object = new GenerateTreeArrays($this->db);
        $array = $object->generate_tree(true);

        foreach ($array['id'] as $value => $key) {
            if ($array['id'][$value] == $folder) {
                $folder = $array['id'][$value + 1];
                $set = $this->db->prepare("UPDATE `files` SET folder = :folder WHERE id = :id");
                $set->bindValue(':id', $id, PDO::PARAM_INT);
                $set->bindValue(':folder', $folder, PDO::PARAM_INT);
                $set->execute();

                $this->session_refresh($array['id'][$value + 1]);
                return 'Moved up, file id: ' . $id . ' name: ' . $this->return_name($id);
            }
        }
    }

}