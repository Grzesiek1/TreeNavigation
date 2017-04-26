<?php
/**
 * Created by PhpStorm.
 * User: ppp
 * Date: 2017-04-24
 * Time: 19:14
 */
require_once('head.php');

if (isset($_GET['get_position_folder'])) {
    if ($_GET['get_position_folder'] == true) {

        $object = new ActionTree($db);
        echo $object->number_occurrence($_SESSION['selected_folder_id']);
        die;
    }
}

if (isset($_GET['get_position_file'])) {
    if ($_GET['get_position_file'] == true) {

        $object = new ActionTree($db);
        echo $object->number_occurrence($_SESSION['selected_file_id']);
        die;
    }
}

if (isset($_GET['json'])) {
    if ($_GET['json'] == true) {

        $object = new GenerateTreeArrays($db);
        die($object->generate_tree());
    }
}