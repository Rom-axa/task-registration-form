<?php
if( !defined('DOMAIN') ) { exit('Not index.php');  }
//блокировка доступа
if( $USER['isGuest'] ) {
    throw new Exception('You have not permission for this action', 403);
}

//все данные в виде будут взяты из переменной $USER