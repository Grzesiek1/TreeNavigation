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
        $object->add($_POST['name']);
    }

}