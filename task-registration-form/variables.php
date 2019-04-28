<?php
if( !defined('DOMAIN') ) { exit('Not index.php');  }

/*
 *@variable $page_now  по ней будет подключён скрипт из папки php_scripts 
 *@variable $tpl - вид из папки templates
 *@variable $USER - данные из таблицы текущего юзера  
 */

$page_now = isset($_GET['page'])? $_GET['page'] : 'index';
$tpl = $page_now;

$USER = array(
    'isGuest' => TRUE,
    'id' => NULL,
    'username' => NULL,
    'email' => NULL,
    'image' => NULL,
    'name' => NULL,
    'surname' => NULL,
);
//если сессия с id или куки с id и auth_key юзера существует произойдёт попытка аутентификации
//в случае успеха $USER[isGuest] будет установленна в false и все данные из таблицы users занесены в массив
//$USER, при неудаче сессия или куки будут удалены и пользователь будет перенаправлен на страницу login
if( isset($_SESSION['user.id']) ) {
    $user = get_user($_SESSION['user.id']);
    if( is_null($user) || !$user['confirmed'] ) {
        unset($_SESSION['user.id']);
        session_destroy();
        header('Location: ' .get_url('login'));
        exit();
    }
    $USER['isGuest'] = FALSE;
    foreach($user as $k => $v ) {
        if( key_exists($k, $USER) ){
            $USER[$k] = $v;
        }
    }
}else if( isset($_COOKIE['user_id']) && isset($_COOKIE['user_auth_key']) ) {
    $user = get_user($_COOKIE['user_id']);
    if( is_null($user) || !$user['confirmed'] || $user['auth_key'] !== $_COOKIE['user_auth_key'] ){
        setcookie('user_id', '', -3600);
        setcookie('user_auth_key', '', -3600);
        session_destroy();
        header('Location: ' .get_url('login'));
        exit();
    }
    $USER['isGuest'] = FALSE;
    foreach($user as $k => $v ) {
        if( key_exists($k, $USER) ){
            $USER[$k] = $v;
        }
    }
}
//если пользователь авторизован меняем главную страницу по дефолту
if( !$USER['isGuest'] && $page_now == 'index') {
    $page_now = 'profile';
    $tpl = $page_now;
}


