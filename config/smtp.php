<?php

if (!defined('SMTP_HOST')) {
    define('SMTP_HOST', getenv('SMTP_HOST') !== false ? (string) getenv('SMTP_HOST') : 'smtp.gmail.com');
}

if (!defined('SMTP_PORT')) {
    define('SMTP_PORT', (int) (getenv('SMTP_PORT') !== false ? getenv('SMTP_PORT') : 587));
}

if (!defined('SMTP_USERNAME')) {
    define('SMTP_USERNAME', getenv('SMTP_USERNAME') !== false ? (string) getenv('SMTP_USERNAME') : 'owenbadel19@gmail.com');
}

if (!defined('SMTP_PASSWORD')) {
    define('SMTP_PASSWORD', getenv('SMTP_PASSWORD') !== false ? (string) getenv('SMTP_PASSWORD') : 'zxxw ldng cytt jyuy');
}

if (!defined('SMTP_FROM_ADDRESS')) {
    define('SMTP_FROM_ADDRESS', getenv('SMTP_FROM_ADDRESS') !== false ? (string) getenv('SMTP_FROM_ADDRESS') : 'owenbadel19@gmail.com');
}

if (!defined('SMTP_FROM_NAME')) {
    define('SMTP_FROM_NAME', getenv('SMTP_FROM_NAME') !== false ? (string) getenv('SMTP_FROM_NAME') : 'javaemail');
}
