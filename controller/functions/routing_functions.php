<?php

function view(string $name, array $data = []): void
{
    $project_root = dirname(__DIR__, 2);

    $name = trim($name, '/');

    /*
     * Only allow internal view names such as:
     *
     * index
     * detail
     * admin/index
     * admin/edit
     */
    if (
        $name === '' ||
        !preg_match(
            '#^[a-zA-Z0-9_-]+(?:/[a-zA-Z0-9_-]+)*$#',
            $name
        )
    ) {
        $name = 'notfound';
    }

    $view_path =
        $project_root .
        '/view/' .
        $name .
        '.view.php';

    if (!is_file($view_path)) {
        $view_path =
            $project_root .
            '/view/notfound.view.php';
    }

    extract($data, EXTR_SKIP);

    require $project_root . '/view/layout.view.php';
}

function is_user_authenticated(): bool
{
    return (
        isset($_SESSION['email']) &&
        is_string($_SESSION['email']) &&
        isset($_SESSION['role']) &&
        $_SESSION['role'] === 'admin'
    );
}

function ensure_user_is_authenticated(): void
{
    if (!is_user_authenticated()) {
        redirect('../login.php');
    }

    $now = time();

    /*
     * 30-minute inactivity timeout.
     */
    if (
        isset($_SESSION['last_activity']) &&
        ($now - (int) $_SESSION['last_activity']) > 1800
    ) {
        destroy_user_session();
        redirect('../login.php');
    }

    /*
     * Refresh the session ID periodically to reduce
     * the lifetime of a stolen session identifier.
     */
    if (
        !isset($_SESSION['last_regeneration']) ||
        ($now - (int) $_SESSION['last_regeneration']) > 900
    ) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = $now;
    }

    $_SESSION['last_activity'] = $now;
}

function destroy_user_session(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            [
                'expires'  => time() - 42000,
                'path'     => $params['path'],
                'domain'   => $params['domain'],
                'secure'   => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite'] ?? 'Lax',
            ]
        );
    }

    session_destroy();
}

function is_post(): bool
{
    return (
        isset($_SERVER['REQUEST_METHOD']) &&
        $_SERVER['REQUEST_METHOD'] === 'POST'
    );
}

function is_get(): bool
{
    return (
        isset($_SERVER['REQUEST_METHOD']) &&
        $_SERVER['REQUEST_METHOD'] === 'GET'
    );
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/*
 * Read a scalar GET/POST value safely.
 *
 * Arrays such as:
 *
 * ?search[]=test
 *
 * are rejected instead of reaching application logic.
 */
function request_string(
    array $source,
    string $key
): string {
    $value = $source[$key] ?? '';

    if (!is_string($value)) {
        return '';
    }

    return trim($value);
}

function text_length(string $value): int
{
    if (function_exists('mb_strlen')) {
        return mb_strlen($value, 'UTF-8');
    }

    return strlen($value);
}

function limit_text(
    string $value,
    int $max_length
): string {
    if (text_length($value) <= $max_length) {
        return $value;
    }

    if (function_exists('mb_substr')) {
        return mb_substr(
            $value,
            0,
            $max_length,
            'UTF-8'
        );
    }

    return substr($value, 0, $max_length);
}

function validate_glossary_data(
    string $term,
    string $definition
): ?string {
    if ($term === '' || $definition === '') {
        return 'Both term and definition are required.';
    }

    /*
     * Reject malformed UTF-8 rather than silently replacing it.
     */
    if (
        preg_match('//u', $term) !== 1 ||
        preg_match('//u', $definition) !== 1
    ) {
        return 'Invalid character encoding.';
    }

    if (text_length($term) > 255) {
        return 'The term must not exceed 255 characters.';
    }

    if (text_length($definition) > 10000) {
        return 'The definition must not exceed 10,000 characters.';
    }

    return null;
}

function e(mixed $value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5,
        'UTF-8'
    );
}

function csrf_token(): string
{
    if (
        !isset($_SESSION['csrf_token']) ||
        !is_string($_SESSION['csrf_token'])
    ) {
        $_SESSION['csrf_token'] =
            bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return
        '<input type="hidden" name="csrf_token" value="' .
        e(csrf_token()) .
        '">';
}

function verify_csrf_token(): bool
{
    $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token) ||
        $token === '' ||
        !isset($_SESSION['csrf_token']) ||
        !is_string($_SESSION['csrf_token'])
    ) {
        return false;
    }

    return hash_equals(
        $_SESSION['csrf_token'],
        $token
    );
}

/*
 * Escape SQL LIKE wildcard characters.
 *
 * This keeps searches as literal substring searches instead
 * of allowing users to inject '%' and '_' wildcards.
 */
function escape_like_pattern(string $value): string
{
    return addcslashes(
        $value,
        "\\%_"
    );
}

/*
 * Session-based login throttling.
 *
 * This is intentionally lightweight for a small learning project.
 * Production systems should normally use server-side/IP-aware
 * rate limiting or an upstream WAF/reverse proxy.
 */
function login_is_rate_limited(): bool
{
    $window = 900;
    $max_attempts = 5;
    $now = time();

    if (
        !isset($_SESSION['login_attempts']) ||
        !is_array($_SESSION['login_attempts'])
    ) {
        return false;
    }

    $first_attempt =
        (int) (
            $_SESSION['login_attempts']['first'] ?? 0
        );

    $attempts =
        (int) (
            $_SESSION['login_attempts']['count'] ?? 0
        );

    if (
        $first_attempt <= 0 ||
        ($now - $first_attempt) >= $window
    ) {
        unset($_SESSION['login_attempts']);
        return false;
    }

    return $attempts >= $max_attempts;
}

function record_login_failure(): void
{
    $now = time();

    if (
        !isset($_SESSION['login_attempts']) ||
        !is_array($_SESSION['login_attempts'])
    ) {
        $_SESSION['login_attempts'] = [
            'first' => $now,
            'count' => 1,
        ];

        return;
    }

    $first_attempt =
        (int) (
            $_SESSION['login_attempts']['first'] ?? 0
        );

    if (
        $first_attempt <= 0 ||
        ($now - $first_attempt) >= 900
    ) {
        $_SESSION['login_attempts'] = [
            'first' => $now,
            'count' => 1,
        ];

        return;
    }

    $_SESSION['login_attempts']['count'] =
        ((int) $_SESSION['login_attempts']['count']) + 1;
}

function clear_login_failures(): void
{
    unset($_SESSION['login_attempts']);
}

function authenticate_user(
    string $email,
    string $password
): bool {
    $users = CONFIG['users'];

    /*
     * Use a real bcrypt hash even when the email does not
     * exist. This makes username enumeration harder through
     * obvious timing differences.
     */
    $hash = $users[$email] ?? reset($users);

    if (!is_string($hash)) {
        return false;
    }

    return password_verify(
        $password,
        $hash
    );
}
