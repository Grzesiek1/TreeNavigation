<?php
/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-24
 * Time: 14:58
 */

require_once('head.php');
$object = new GenerateTree($db);

echo $object->generate_tree(true);