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

    $object = new Main($db);

    if ($_GET['action'] == 'show_jquery') {
        
        $smarty->display('show_jquery.tpl');

    } elseif ($_GET['action'] == 'show_html') {
        $object = new GenerateTree($db);

        $smarty->assign('data', $object->generate_tree());
        $smarty->display('show_html.tpl');
    }

} else {
    $smarty->display('index.tpl');
}

$smarty->display('footer.tpl');