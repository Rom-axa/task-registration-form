<?php
if( !defined('DOMAIN') ) { exit('Not index.php');  }
//блокировка доступа
if( $USER['isGuest'] ) {
    throw new Exception('You have not permission for this action', 403);
}

//удаляет сессии или куки авторизации, в зависимости от того, какие существуют
if( isset($_SESSION['user.id']) ) {
    unset($_SESSION['user.id']);
}else if( isset($_COOKIE['user_id']) && isset($_COOKIE['user_auth_key']) ) {
    setcookie('user_id', '', -3600);
    setcookie('user_auth_key', '', -3600);
}

//переадресация на стр логина
session_destroy();
header('Location: ' .get_url('login'));
exit();


