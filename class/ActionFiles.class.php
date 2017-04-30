<?php

/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-26
 * Time: 03:51
 */
declare(strict_types=1);

namespace Files;

use PDO;

class ActionFiles extends \GenerateTreeBased
{
    /*
     * Generate 'files' elements for class GenerateTreeArrays
     */
    public function files_generate(int $id): string
    {
        $res = $this->db->prepare("SELECT id, name FROM files WHERE folder = :id");
        $res->bindValue(':id', $id, PDO::PARAM_INT);
        $res->execute();

        $return = '"tags": [';
        while ($row = $res->fetch()) {
            $return .= '"<a class=no_link onclick=file_selected(' . $row['id'] . ');>' . filter_var($row['name'], FILTER_SANITIZE_STRING) . '</a> <a onclick=file_remove(' . $row['id'] . ');>X</a>",';
        }

        $return .= "]";
        $return = str_replace(",]", "]", $return);

        return $return;
    }

    /*
    * Method used to add new file on list
    */
    public function add(string $name, int $folder): string
    {
        $res = $this->db->prepare("INSERT INTO `files` (`id`, `name`, `folder`) VALUES ('', :name, :folder)");
        $res->bindValue(':name', $name, PDO::PARAM_INT);
        $res->bindValue(':folder', $folder, PDO::PARAM_INT);
        $res->execute();

        $session = new \ActionTree($this->db);
        $session->session_refresh($folder);
        return 'Added file, name: ' . $name;
    }

    /*
    * Method remove file on list
    */
    public function remove(int $id): string
    {
        $name = $this->return_name((int)$id);
        $res = $this->db->prepare("DELETE FROM `files` WHERE `files`.`id` = :id");
        $res->bindValue(':id', $id, PDO::PARAM_INT);
        $res->execute();

        return 'Remove file, id: ' . $id . ' name: ' . $name;
    }

    /*
    * Method gets id and return name file
    */
    public function return_name($id): string
    {
        $result = $this->db->prepare("SELECT name FROM `files` WHERE id = :id ");
        $result->bindValue(':id', $id, PDO::PARAM_INT);
        $result->execute();

        return $result->fetchColumn();
    }

    /*
    * Method used to rename file
    */
    public function rename(int $id, string $name): string
    {
        try {
            $result = $this->db->prepare("UPDATE `files` SET `name` = :name WHERE `files`.`id` = :id");
            $result->bindValue(':id', $id, PDO::PARAM_INT);
            $result->bindValue(':name', $name, PDO::PARAM_STR);
            $result->execute();
        } catch (\Exception $e) {
            return 'Error. Can not rename file.';
        }

        $session = new \ActionTree($this->db);
        $session->session_refresh(0);
        return 'Set new name: ' . $name . ' for file id: ' . $id;
    }

    /*
     * Method based for move_up and move_down file
     */
    private function core_move(int $id, int $folder, int $move): bool
    {
        $object = new \GenerateTreeArrays($this->db);
        $array = $object->generate_tree(true);

        try {
            foreach ($array['id'] as $value => $key) {
                if ($array['id'][$value] == $folder) {
                    if (isset($array['id'][$value + $move])) {
                        $folder = $array['id'][$value + $move];
                        $set = $this->db->prepare("UPDATE `files` SET folder = :folder WHERE id = :id");
                        $set->bindValue(':id', $id, PDO::PARAM_INT);
                        $set->bindValue(':folder', $folder, PDO::PARAM_INT);
                        $set->execute();

                        $session = new \ActionTree($this->db);
                        $session->session_refresh((int)$array['id'][$value + $move]);
                        return true;
                    }
                }
            }
        } catch (\Exception $e) {
            return false;
        }

    }

    /*
     * Method used to move up file. Based on core_move()
     */
    public function move_up($id, $folder): string
    {
        if ($this->core_move($id, $folder, -1) == true)
            return 'Moved up, file id: ' . $id . ' name: ' . $this->return_name($id);
    }

    /*
     * Method used to move down file. Based on core_move()
     */
    public function move_down($id, $folder): string
    {
        if ($this->core_move($id, $folder, +1) == true)
            return 'Moved down, file id: ' . $id . ' name: ' . $this->return_name($id);
    }

}