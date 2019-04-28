<?php

const DATABASE = 'task-reg-form';
const USER = 'root';
const PASSWORD = '';
const HOST = 'localhost';

const CREATE_USERS_TABLE = '
    CREATE TABLE IF NOT EXISTS `users` 
	(
            `id` INT UNSIGNED AUTO_INCREMENT,
            `username` VARCHAR(100) UNIQUE,
            `email` VARCHAR(100) UNIQUE NOT NULL,
            `name` VARCHAR(100) NOT NULL,
            `surname` VARCHAR(100) NOT NULL,
            `image` VARCHAR(255)NOT NULL DEFAULT "no-image.png",
            `password` VARCHAR(255) NOT NULL,
            `auth_key` VARCHAR(255),
            `confirmed` BOOLEAN NOT NULL DEFAULT 0,
            PRIMARY KEY(`id`)
        )
';