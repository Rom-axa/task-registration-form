<?php
if( !defined('DOMAIN') ) { exit('Not index.php');  }

//основные ф-ии 

//запрос к бд , данные для подключения в config.php
//@param string $query запрос с плэйсхолдерами, 
//@param array $options набор ключ-значение = плэйсхолдер-значеие 
//или плэйсхолдер-массив в котором параметр 'value' - значение и 
//'type' - константа PDO определяющая тип данных значения ключа 'value'
//@param BOOLEAN $return_rows при true вернёт fetch_assoc, при false - id полседне записи
//по задумке при TRUE - запрос должен быть на выборку, инчае на изменение состояни талблицы
//примеры
//q('SELECT * FROM `users` WHERE `username` = :username', [
//    ':username' => [
//        'value' => 'igor',
//        'type' => PDO::PARAM_STR
//    ]
//]);
//q("SELECT * FROM `users` WHERE `username` = :username", [
//    ':username' => [
//        'value' => 'ALEX',
//        'type' => PDO::PARAM_STR
//    ]
//]);

function q($query, array $options = [], $return_rows = TRUE) {
    try {
        $dsn = "mysql:host=" .HOST. ";dbname=" .DATABASE;
        $pdo = new PDO($dsn, USER, PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare($query);
        if( !empty($options) ) {
            foreach($options as $placeholder => $value) {
                if(is_array($value)) {
                    $stmt->bindValue($placeholder, $value['value'], $value['type']);
                }else{
                    $stmt->bindValue($placeholder, $value, PDO::PARAM_STR);
                }
            }
        }

        $res = $stmt->execute();
        if( $return_rows ) {
            return $stmt->fetchAll();
        }
        return $res? $pdo->lastInsertId() : FALSE;
            
    }catch(PDOException $e) {
        exit($e->getMessage());
    }
}
function create_new_user($form_data) {
    if(is_null($form_data['image']) ) {
        $form_data['image'] = 'no-img.png';
    }
    $CREATE_USER_QUERY = "
        INSERT INTO `users`(`username`, `email`, `name`, `surname`, `image`, `password`) 
        VALUES (:username, :email, :name, :surname, :image, :password)
    ";
    $options = array(
        ':username' => $form_data['username'],
        ':email' => $form_data['email'],
        ':name' => $form_data['name'],
        ':surname' => $form_data['surname'],
        ':image' => $form_data['image'],
        ':password' => $form_data['password'],
    );
    $id = q($CREATE_USER_QUERY, $options, FALSE);
    return $id;
}
function get_user($id) {
    $row = q("SELECT * FROM `users` WHERE `id` = :id", [
        ':id' => [
            'value' => $id,
            'type' => PDO::PARAM_INT,
        ]
    ]);
    return empty($row)? NULL : $row[0];
}
function get_user_by_username($username) {
    $row = q("SELECT * FROM `users` WHERE `username` = :username", [
        ':username' => $username
    ]);
    return empty($row)? NULL : $row[0];
}
function check_user_by_username($username) { //используюется при реристрации
    $row = q("SELECT COUNT(*) `exist` FROM `users` WHERE `username` = :username", [
        ':username' => [
            'value' => $username,
            'type' => PDO::PARAM_STR,
        ]
    ]);
    return (int)$row[0]['exist'];
}

function check_user_by_email($email) { //используюется при реристрации
    $row = q("SELECT COUNT(*) `exist` FROM `users` WHERE `email` = :email", [
        ':email' => [
            'value' => $email,
            'type' => PDO::PARAM_STR,
        ]
    ]);
    return (int)$row[0]['exist']; //[0] потому что в q() возвращает PDO::fetchAll() 
}
function getRandomString($length = 17) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ=|';
    $characters_l = strlen($characters);
    $str = '';
    for ($i = 0; $i < $length; $i++) {
        $str .= $characters[rand(0, $characters_l - 1)];
    }
    return $str;
}
function getPasswordHash($password) {
    return md5(crypt($password, 'A9cl1bc'));
}
function getAuthHash($str) {
    return md5(crypt($str , 'ysaj1DiO8'));
}
function send_mail($to, $subject, $message) {
    $headers = "From: box-name@" .DOMAIN. ".com";

    mail($to, $subject, $message, $headers);
}
//@param string $page - имя страницы
//@param array $params - ассоц.массив, ключи значения буду сконвертированы в гет-параметры для урл
//возвращает путь, начиная с папки DOMAIN(константа определена в индеск.пхп)
function get_url($page, $params = []) {
    $get_params_ar = [];
    foreach ($params as $k => $v) {
        array_push($get_params_ar, $k. '=' .$v);
    }
    $get_params_str = implode('&', $get_params_ar);
    if( !empty($get_params_ar) ) {
        $get_params_str = '&' .$get_params_str;
    }
    return '/' .DOMAIN. '/index.php?page=' .$page .$get_params_str;
}

function length_validator($str, $min, $max) {
    $l = strlen($str);
    if( $l >= $min && $l <= $max ) {
        return TRUE;
    }
    return FALSE;
}




