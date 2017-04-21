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

    function add(String $name, Int $parent = 0)
    {
        // Counts the number of elements in a branch
        $res = $this->db->prepare("SELECT COUNT(id) FROM Trees WHERE parent = :parent");
        $res->bindValue(':parent', $parent, PDO::PARAM_STR);
        $res->execute();
        $position_new_element = $res->fetchColumn()+1;


        //Adds a new tree element
        $res = $this->db->prepare("INSERT INTO `Trees` (`id`, `name`, `parent`, `display_order`) VALUES ('', :name, :parent, :display_order)");
        $res->bindValue(':name', $name, PDO::PARAM_STR);
        $res->bindValue(':parent', $parent, PDO::PARAM_INT);
        $res->bindValue(':display_order', $position_new_element, PDO::PARAM_INT);
        $res->execute();

        header('Location: ' . $_SERVER['HTTP_REFERER']);
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