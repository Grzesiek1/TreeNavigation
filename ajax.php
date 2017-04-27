<?php
/**
 * Created by PhpStorm.
 * User: ppp
 * Date: 2017-04-24
 * Time: 19:14
 */
require_once('head.php');

/*
 * Returns data to a tree. Used to refresh tree
 */
if (isset($_GET['json'])) {
    if ($_GET['json'] == true) {

        $object = new GenerateTreeArrays($db);
        die($object->generate_tree());
    }
}

/*
 * Returns id selected folder. Used in frontend
 */
if (isset($_GET['get_position_folder'])) {
    if ($_GET['get_position_folder'] == true) {

        $object = new ActionTree($db);
        echo $object->number_occurrence($_SESSION['selected_folder_id']);
        die;
    }
}

/*
 * Returns id selected file. Used in frontend
 */
if (isset($_GET['get_position_file'])) {
    if ($_GET['get_position_file'] == true) {

        $object = new ActionTree($db);
        echo $object->number_occurrence($_SESSION['selected_file_id']);
        die;
    }
}

/*
 * Returns name selected file. Used to input in frontend
 */
if (isset($_POST['get_file_name'])) {
    if ($_POST['get_file_name'] == true) {

        $object = new ActionFiles($db);
        echo $object->return_name($_POST['id']);
        die;
    }
}

/*
 * Set current selected folder in session
 */
if (isset($_GET['save_element_selected'])) {
    if ($_GET['save_element_selected'] == true) {
        
        $object = new ActionTree($db);
        $object->session_refresh($_POST['id']);
        die;
    }
}
