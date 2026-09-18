<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
error_log("DB_USER=" . getenv('DB_USER'));

define(
    'APP_NAME',
    dirname(__DIR__) . '/'
);

/*
 * Detect HTTPS before configuring the session cookie.
 *
 * Secure cookies are enabled automatically when the application
 * is served over HTTPS, while local HTTP development continues
 * to work normally.
 */
$is_https = (
    isset($_SERVER['HTTPS']) &&
    $_SERVER['HTTPS'] !== '' &&
    $_SERVER['HTTPS'] !== 'off'
);

/*
 * Session hardening.
 */
ini_set('session.use_only_cookies', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => $is_https,
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/*
 * Security response headers.
 */
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

header(
    "Content-Security-Policy: " .
    "default-src 'self'; " .
    "style-src 'self' https://cdn.jsdelivr.net; " .
    "script-src 'none'; " .
    "img-src 'self' data:; " .
    "font-src 'self' https://cdn.jsdelivr.net data:; " .
    "connect-src 'self'; " .
    "object-src 'none'; " .
    "base-uri 'self'; " .
    "frame-ancestors 'none'; " .
    "form-action 'self'"
);

if ($is_https) {
    header(
        'Strict-Transport-Security: ' .
        'max-age=31536000; includeSubDomains'
    );
}

require_once __DIR__ . '/functions/config.php';
require_once __DIR__ . '/functions/GlossaryTerm.class.php';
require_once __DIR__ . '/functions/dataprovider.class.php';
require_once __DIR__ . '/functions/data.class.php';
require_once __DIR__ . '/functions/filedataprovider.class.php';
require_once __DIR__ . '/functions/mysqldataprovider.class.php';
require_once __DIR__ . '/functions/routing_functions.php';

Data::initialize(
    new MySqlDataProvider(CONFIG['db'])
);
