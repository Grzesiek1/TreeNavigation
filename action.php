<?php
/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-22
 * Time: 01:27
 */
require_once('head.php');
$object = new Main($db);

if (isset($_GET['id'])) {

    if ($_GET['id'] == 'add') {
        echo $object->add($_POST['add_name'], $_POST['parent_id']);
    }

    if ($_GET['id'] == 'remove') {
        if (!empty($_POST['id']))
            echo $object->remove($_POST['id']);
    }

    if ($_GET['id'] == 'rename') {
        if (!empty($_POST['id']))
            echo $object->rename($_POST['id'], $_POST['new_name']);
    }

    if ($_GET['id'] == 'move_to') {
        if (!empty($_POST['id']) && is_numeric($_POST['new_parent_id']))
            echo $object->move_to($_POST['id'], $_POST['new_parent_id']);
    }

    if ($_GET['id'] == 'move_left') {
        if (!empty($_POST['id']))
            echo $object->move_left($_POST['id']);
    }

    if ($_GET['id'] == 'move_up') {
        if (!empty($_POST['id']))
            echo $object->move_up($_POST['id']);
    }

    if ($_GET['id'] == 'move_down') {
        if (!empty($_POST['id']))
            echo $object->move_down($_POST['id']);
    }

    if ($_GET['id'] == 'move_right') {
        if (!empty($_POST['id']))
            echo $object->move_right($_POST['id']);
    }

}