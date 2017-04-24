<?php
/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-22
 * Time: 00:23
 */
error_reporting(E_ALL);
session_start();
ob_start();

require_once('config.php');

$dbc['dns'] = 'mysql:host=' . $dbc['host'] . ';dbname=' . $dbc['name'];
$dbc['options'] = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => $dbc['encode'],
);

try{
    $db = new PDO($dbc['dns'], $dbc['user'], $dbc['pass'], $dbc['options']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Wystąpił błąd przy łączeniu z bazą danych.');
}

unset($dbc);

require_once('ExternalFiles/libs/Smarty.class.php');
require_once('class/Main.class.php');
require_once('class/GenerateTree.class.php');

$smarty = new Smarty();

