<?php

if (!defined('DB_HOST')) {
    define('DB_HOST', getenv('DB_HOST') !== false ? (string) getenv('DB_HOST') : 'localhost');
}

if (!defined('DB_PORT')) {
    define('DB_PORT', getenv('DB_PORT') !== false ? (string) getenv('DB_PORT') : '3306');
}

if (!defined('DB_NAME')) {
    define('DB_NAME', getenv('DB_NAME') !== false ? (string) getenv('DB_NAME') : 'crud_usuarios');
}

if (!defined('DB_USER')) {
    define('DB_USER', getenv('DB_USER') !== false ? (string) getenv('DB_USER') : 'root');
}

if (!defined('DB_PASS')) {
    define('DB_PASS', getenv('DB_PASS') !== false ? (string) getenv('DB_PASS') : 'secret');
}

