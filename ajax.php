<?php
/**
 * Created by PhpStorm.
 * User: ppp
 * Date: 2017-04-24
 * Time: 19:14
 */
require_once('head.php');

if (isset($_GET['get_position'])) {
    if ($_GET['get_position'] == true) {

        $object = new ActionTree($db);
        echo $object->number_occurrence($_SESSION['id_operation']);
        die;
    }
}

if (isset($_GET['json'])) {
    if ($_GET['json'] == true) {

        $object = new GenerateTreeArrays($db);
        die($object->generate_tree());
    }
}