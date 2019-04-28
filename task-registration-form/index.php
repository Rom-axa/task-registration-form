<?php
error_reporting(-1);
header('Content-type: text/html; charset=utf-8;');
session_start();
//для единой точки входа, ибо нет htaccess
define('DOMAIN', 'task-registration-form');

//что бы не писать всё в индексе
include 'config.php'; //для подкл. к БД
include 'main.php'; //общие функции
include 'variables.php'; //определение глобальных переменных

try{
    //подключение главного скрипта скрипта, еслли нет 404
    if( file_exists('php_scripts/' .$page_now. '.php') ) {
        include 'php_scripts/' .$page_now. '.php';
    }else {
        throw new Exception('Not found', 404);
    }
}catch(Exception $e) {
    http_response_code($e->getCode());
    $error_message = $e->getMessage();
    $tpl = 'error';
}
//подключение вида для главного скрипта если такой существует 
ob_start();
if( file_exists('templates/' .$tpl. '.tpl') ) {
    include_once 'templates/' .$tpl. '.tpl';
}
$content = ob_get_clean();

//шаблон
include 'index.html';

