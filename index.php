<?php
/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-22
 * Time: 00:30
 */
require_once('head.php');

$smarty->display('head.tpl');
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'show') {
        $smarty->display('show.tpl');
    } elseif ($_GET['action'] == 'add') {
        $smarty->display('add.tpl');
    }
} else {
    $smarty->display('index.tpl');
}

$smarty->display('footer.tpl');