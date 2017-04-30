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

try {
    $db = new PDO($dbc['dns'], $dbc['user'], $dbc['pass'], $dbc['options']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Wystąpił błąd przy łączeniu z bazą danych.');
}
unset($dbc);

function __autoload_class($class)
{
    // namespace remove from class name
    $class = explode('\\', $class);
    $class = end($class);
    try {
        require_once('class/' . $class . '.class.php');
    } catch (exception $e) {
        try {
            require_once('class/' . strtolower($class) . '.class.php');
        } catch (exception $e) {
            $class = mb_convert_case($class, MB_CASE_TITLE, 'UTF-8');
            require_once('class/' . $class . '.class.php');
        }
    }
}

spl_autoload_register('__autoload_class');

$template = new TemplateSystem();

