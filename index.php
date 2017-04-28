<?php
/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-22
 * Time: 00:30
 */
require_once('head.php');

$template->display('head.tpl');

if (isset($_GET['action'])) {

    if ($_GET['action'] == 'show_jquery') {
        
        $template->display('show_jquery.tpl');

    } elseif ($_GET['action'] == 'show_html') {
        $object = new GenerateTreeHtml($db);

        $template->assign('data', $object->generate_tree());
        $template->display('show_html.tpl');
    }

} else {
    $template->display('index.tpl');
}
$template->display('footer.tpl');