<?php
if( !defined('DOMAIN') ) { exit('Not index.php');  }
//блокировка доступа, для авторизованныъ пользователей, и в случае отсутствия переменной(ых)
if( !isset($_GET['id']) || !isset($_GET['confirm_key']) ) {
    throw new Exception('Bad request', 400);
}else if( !$USER['isGuest'] ) {
    throw new Exception('You have not permission for this action', 403);
}

$id = (int)$_GET['id'];
$confirm_key = $_GET['confirm_key'];
//сравнивает присланный ключ и реальный, ключ создаётся прирегистрации на основе хэша пароля
function check_confirm_key($confirm_key, $password_hash) {
    return strcmp($confirm_key, getAuthHash($password_hash)) === 0;
}
//обновляет запись в бд, меняя статус пользователя на подтверждённый
function confirm_user_registration( $id ) {
    $res = q("UPDATE `users` SET `confirmed` = 1 WHERE `id` = :id", [
        ':id' => [
            'value' => $id,
            'type' => PDO::PARAM_INT 
        ]
    ], FALSE);
}
$user = get_user($id);
//если пользователь существует
//если пользователь не подтверждён,
//если ключи совпадают
//если не было ошибок при обновлении записи в бд
//переадресация на страницу логина
if( !is_null($user) && !$user['confirmed'] && check_confirm_key($confirm_key, $user['password']) && confirm_user_registration($id) !== FALSE ) {
    $url = get_url('login');
    header("Location: $url");
    exit();
}
//в обратном случае выбиваем ненайденную страницу
throw new Exception('Not found', 404);

