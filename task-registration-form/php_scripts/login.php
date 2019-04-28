<?php
if( !defined('DOMAIN') ) { exit('Not index.php');  }
//все по соответствии со страницой регистрации
if( !$USER['isGuest'] ) {
    throw new Exception('You have not permission for this action', 403);
}
$errors = array();

$form_data = array(
    'username' => '',
    'password' => '',
    'rememberMe' => false,
);
function required_validator($key) {
    global $errors, $form_data;
    if( key_exists($key, $form_data) ) {
        if( !empty($form_data[$key]) ) {
            return TRUE;
        }
        $errors[$key] = 'Please fill this field';
        return FALSE;
    }
}
function form_validate() {
    global $errors, $form_data;
    if( !length_validator($form_data['username'],5 , 40) ) {
        $errors['username'] = 'incorrect length';
        return FALSE;
    }
    if( !length_validator($form_data['password'], 8 , 255) ) {
        $errors['password'] = 'incorrect length';
        return FALSE;
    }
    return TRUE;
}
//авторизует пользователя по сессии или куки, в зависимости от того был ли выбран
//чекбокс при авторизации 
//переадресует на главноу страницу
//@return 
function login($user) {
    global $form_data;
    if( $form_data['rememberMe'] ) {
        $auth_key = str_replace(' ', '', getRandomString() .time());
        q("UPDATE `users` SET `auth_key` = :auth_key WHERE `id` = :id", [
            ':id' => [
                'value' => $user['id'],
                'type' => PDO::PARAM_INT,
            ],
            ':auth_key' => [
                'value' => $auth_key,
                'type' => PDO::PARAM_STR,
            ]
        ], FALSE);
        setcookie("user_id", $user['id'], time()+3600*12);
        setcookie("user_auth_key", $auth_key, time()+3600*12);
    } else {
        $_SESSION['user.id'] = $user['id'];
    }
    header('Location: ' .get_url('index'));
    exit();
}
//по аналогии со страничкой регистрации
if( !empty($_POST) ) {
    foreach ($_POST as $k => $v) {
        if( isset($form_data[$k]) ){
            $form_data[$k] = trim($v);
            required_validator($k);
        }
    }
    if( empty($errors) && form_validate() ) {
        $user = get_user_by_username($form_data['username']);

        if( is_null($user) || $user['password'] !== getPasswordHash($form_data['password']) || !$user['confirmed'] ) {
            $errors['username'] = 'User with such username or password does not exists!';
        }else {
            login($user);
        }
    }
}
