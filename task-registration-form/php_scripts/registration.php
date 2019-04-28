<?php
if( !defined('DOMAIN') ) { exit('Not index.php');  }
//если пользователь авторизован - блокируем доступ
if( !$USER['isGuest'] ) {
    throw new Exception('You have not permission for this action', 403);
}

$errors = array(); 
//в этот массив будут загружены все данные из формы, в случае неудачной валлидации
//данные именно из этого массива будут занесены обратно в форму, крое паролей, они в конце скрипта обнулятся
$form_data = array(
    'username' => '',
    'email' => '',
    'name' => '',
    'surname' => '',
    'image' => NULL,
    'password' => '',
    'repeatpassword' => ''
);

//проверяет логин на допустимый разер->допустимые символы->поиск в бд(для уникальности)
//в случае выявлении ошибки, помещает её описание в массив $errors по ключу == именования своего аттрибута
//@return BOOLEAN
function login_validator($username) {
    global $errors;
    if( !length_validator($username, 5, 40) ) { 
        $errors['username'] = 'incorrect length';
        return FALSE;
    }else if( preg_match('/[^A-Za-z0-9\\-]/', $username) ) {
        $errors['username'] = 'incorrect login';
        return FALSE;
    }else if( check_user_by_username($username) ) {
        $errors['username'] = 'User with such login already registered';
        return FALSE;
    }
    return TRUE;
}
//проверяет email на корректность->существование в бд
//@return BOOLEAN
function email_validator($email) {
    global $errors;
    if( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
        $errors['email'] = 'incorrect E-mail';
        return FALSE;
    }else if( check_user_by_email($email) ) {
        $errors['email'] = 'User with such E-mail already registered';
        return FALSE;
    }
    return TRUE;
}
//находит строку по ключу ==  нужному аттрибуту в массиве $form_data
//проверяет длину->разрешённые символы 
//@return BOOLEAN
function name_validator($key) {
    global $errors, $form_data;
    if( !key_exists($key, $form_data) ) {
        return FALSE;
    }
    if( !length_validator($form_data[$key], 2, 45) ) {
        $errors[$key] = 'incorrect length';
        return FALSE;
    }else if( preg_match('/[^A-Za-zА-Яа-я\\-]/ui', $form_data[$key]) ) {
        $errors[$key] = 'incorrect characters';
        return FALSE;
    }
    return TRUE;
}
//проверяет пароли, на количесвто символов и на соответствие,
//в случае успеха хэширует пароль в $form_data
function password_validator() {
    global $errors, $form_data;
    if( !length_validator($form_data['password'], 8, 255) ) {
        $errors['password'] = 'incorect length';
        return FALSE;
    }
    if( $form_data['password'] !== $form_data['repeatpassword'] ) {
        $errors['repeatpassword'] = 'passwords should match';
        return FALSE;
    }
    $form_data['password'] = getPasswordHash($form_data['password']);
    return TRUE;
}
//@param int $maxsize - макс.размер фото
//Проверяет наличие файла в $_FILES, по имени аттрибута с формы, в случае остутствие 
//вернёт TRUE, так как фото при регистрации необязательно
//->проверит наличие ошибок при загрузке->проверит расширение, список допустимых расширений 
//для фото в массиве $allowed_extension -> загружает фото в папку upload и влзвращает новоё имя фотки
//@return BOOLEAN|SRTING
function image_validator($maxsize = 2000*2000) {
    global $errors, $form_data;
    //вовращает расширение картинки, если она есть в массиве ;allowed_extension
    $get_image_extension = function() {
        $pattern = '/\.[A-z.]+$/i';
        $allowed_extension = array('.png','.jpg','.jpeg');
        $matches = [];
        if( !preg_match($pattern, $_FILES['profileimg']['name'], $matches )){
            return FALSE;
        }
        $extension = strtolower($matches[0]);
        if( in_array($extension, $allowed_extension) ) {
            return $extension;
        }
        return FALSE;
    };
    //если нет фотки, оставляем пустую строку, по дефолту sql втсавит no-image.png 
    if( !isset($_FILES['profileimg']) || empty($_FILES['profileimg']['name']) ) {
        return TRUE;
    } 
    //проверка ошибок при загрузке
    $error = $_FILES['profileimg']['error']; 
    switch( $error ) {
        case 3:
            $errors['image'] = 'The uploaded file was only partially uploaded';
            return FALSE;
        case 4:
            $errors['image'] = 'No file was uploaded';
            return FALSE;
        case 7:
            $errors['image'] = 'Missing a temporary folder, please try again';
            return FALSE;
        default :
            if( in_array($error, [1,2]) || $_FILES['profileimg']['size'] > $maxsize ) {
                $errors['image'] = 'Max image size: ' .$maxsize;
                return FALSE;
            } 
    }
    $image_extension = $get_image_extension();
    if( !$image_extension ) {
        $errors['image'] = 'icorrect extension';
        return FALSE;
    }
    $tmp_name = $_FILES['profileimg']['tmp_name'];
    //уникальное имя для фотки
    $new_name = str_replace(' ', '', getRandomString(). time(). $image_extension);
    //сохраняем фото
    if( move_uploaded_file($tmp_name, 'upload/' .$new_name) ) {
        return $new_name;
    }
    $errors['image'] = 'can not upload image!';
    return FALSE;
}
if( !empty($_POST) ) {
    //презаписывает данные с пост в форм_дата если они совпали
    foreach ($_POST as $k => $v) {
        if( isset($form_data[$k]) ){
            $form_data[$k] = trim($v);
        }
    }
    //основные валидаторы
    login_validator($form_data['username']);
    email_validator($form_data['email']);
    name_validator('name');
    name_validator('surname');
    password_validator();
    
    if( empty($errors) ) {
        //пробуем загрузить фото, если его присалали 
        $image = image_validator();
        if( is_string($image) ) {
            $form_data['image'] = $image;
        }
        //если ошибок нет, в том числе и связанных с загрузкой фото - сохраняем нового пользователя
        if( empty($errors) ) {
            $u_id = create_new_user($form_data);
            if( $u_id ) {
                //строка для подтверждения регистрации
                $auth_hash = getAuthHash($form_data['password']);
                //text-body для псьма на почту
                $confirm_mail_text = "Please confirm registration, on web-site " .DOMAIN. "\r\n" .
                "<a href=\"" .get_url('confirm_user', ['id' => $u_id, 'confirm_key' => $auth_hash]). "\">click here</a>";
                //отрпвка письма
                send_mail($form_data['email'], 'Confirm registration', $confirm_mail_text);
                //выводим ссылку для подтверждение регистрации ( только в для режима разработки )
                echo "<h1>Success</h1>\r\n<br>\r\n<div>On your E-mail was sended next message:<br>\r\n<h3>$confirm_mail_text</h3>\r\n</div><br>";
                exit();
            }
        }
    }
}
//обнуляем пароли 
$form_data['password'] = '';
$form_data['repeatpassword'] = '';