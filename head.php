<?php
/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-22
 * Time: 00:23
 */
error_reporting(E_ALL);

require_once('config.php');
require_once('libs/Smarty.class.php');
require_once('class/main.class.php');

$dbc['dns'] = 'mysql:host=' . $dbc['host'] . ';dbname=' . $dbc['name'];
$dbc['options'] = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => $dbc['encode'],
);
$db = new PDO($dbc['dns'], $dbc['user'], $dbc['pass'], $dbc['options']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
unset($dbc);

$smarty = new Smarty();

