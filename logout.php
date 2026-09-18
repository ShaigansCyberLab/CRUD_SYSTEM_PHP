<?php

session_start();

$_SESSION = [];

$params = session_get_cookie_params();

setcookie(
    session_name(),
    '',
    time() - 42000,
    $params['path'],
    $params['domain'],
    $params['secure'],
    $params['httponly']
);

session_destroy();

require_once __DIR__ . '/controller/app.php';

redirect('login.php');
