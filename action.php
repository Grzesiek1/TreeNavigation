<?php
/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-22
 * Time: 01:27
 */
require_once('head.php');
$folder = new ActionTree($db);
$files = new Files\ActionFiles($db);

if (isset($_GET['id'])) {

    /*
     * Folder operation
     */
    if ($_GET['id'] == 'add') {
        if (!empty($_POST['add_name']))
            echo $folder->add($_POST['add_name'], (int)$_POST['parent_id']);
    }

    if ($_GET['id'] == 'remove') {
        if (!empty($_POST['id']))
            echo $folder->remove($_POST['id']);
    }

    if ($_GET['id'] == 'rename') {
        if (!empty($_POST['id']))
            echo $folder->rename($_POST['id'], $_POST['new_name']);
    }

    if ($_GET['id'] == 'move_left') {
        if (!empty($_POST['id']))
            echo $folder->move_left($_POST['id']);
    }

    if ($_GET['id'] == 'move_up') {
        if (!empty($_POST['id']))
            echo $folder->move_up($_POST['id']);
    }

    if ($_GET['id'] == 'move_down') {
        if (!empty($_POST['id']))
            echo $folder->move_down($_POST['id']);
    }

    if ($_GET['id'] == 'move_right') {
        if (!empty($_POST['id']))
            echo $folder->move_right($_POST['id']);
    }


    /*
     * Files operation
     */
    if ($_GET['id'] == 'file_add') {
        if (!empty($_POST['new_file']) && !empty($_POST['id'])) {
            echo $files->add($_POST['new_file'], (int)$_POST['id']);
        }

    }

    if ($_GET['id'] == 'file_remove') {
        if (!empty($_POST['id'])) {
            echo $files->remove((int)$_POST['id']);
        }

    }
    if ($_GET['id'] == 'file_rename') {
        if (!empty($_POST['id'])) {
            echo $files->rename((int)$_POST['id'], $_POST['file_new_name']);
        }
    }

    if ($_GET['id'] == 'file_move_up') {
        if (!empty($_POST['id'])) {
            echo $files->move_up((int)$_POST['id'],(int)$_POST['folder']);
        }
    }

    if ($_GET['id'] == 'file_move_down') {
        if (!empty($_POST['id'])) {
            echo $files->move_down((int)$_POST['id'],(int)$_POST['folder']);
        }
    }
}