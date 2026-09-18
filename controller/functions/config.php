<?php

function required_env(string $name): string
{
    $value = getenv($name);

    if ($value === false || trim($value) === '') {
        throw new RuntimeException(
            "Required environment variable '{$name}' is not set."
        );
    }

    return trim($value);
}

$db_user = required_env('DB_USER');
$db_password = required_env('DB_PASS');
$admin_password_hash = required_env('ADMIN_PASSWORD_HASH');

if (
    !is_string($admin_password_hash) ||
    password_get_info($admin_password_hash)['algo'] === 0
) {
    throw new RuntimeException(
        'ADMIN_PASSWORD_HASH is not a valid password hash.'
    );
}

$admin_email = getenv('ADMIN_EMAIL');

if (
    $admin_email === false ||
    !filter_var($admin_email, FILTER_VALIDATE_EMAIL)
) {
    $admin_email = 'admin@admin.com';
}

define('CONFIG', [
    'data_file' => APP_NAME . 'data.json',

    'db' =>
        'mysql:dbname=glossary;' .
        'host=127.0.0.1;' .
        'port=3306;' .
        'charset=utf8mb4',

    'db_user' => $db_user,

    'db_password' => $db_password,

    'users' => [
        $admin_email => $admin_password_hash,
    ],
]);
