<?php

/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-22
 * Time: 00:29
 */
declare(strict_types = 1);

class Trees
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

    function add(String $name, Int $parent = 0)
    {
        // Counts the number of elements in a branch
        $res = $this->db->prepare("SELECT COUNT(id) FROM Trees WHERE parent = :parent");
        $res->bindValue(':parent', $parent, PDO::PARAM_STR);
        $res->execute();
        $position_new_element = $res->fetchColumn() + 1;


        //Adds a new tree element
        $res = $this->db->prepare("INSERT INTO `Trees` (`id`, `name`, `parent`, `display_order`) VALUES ('', :name, :parent, :display_order)");
        $res->bindValue(':name', $name, PDO::PARAM_STR);
        $res->bindValue(':parent', $parent, PDO::PARAM_INT);
        $res->bindValue(':display_order', $position_new_element, PDO::PARAM_INT);
        $res->execute();

        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    function get()
    {
        $res = $this->db->prepare("SELECT id, name, parent, display_order FROM Trees");
        $res->execute();
        return $this->array_change_xy($res->fetchAll());
    }


    function delete()
    {

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