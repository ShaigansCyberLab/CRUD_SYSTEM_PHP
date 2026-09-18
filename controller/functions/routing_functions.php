<?php

function view(string $name, array $data = []): void
{
    $project_root = dirname(__DIR__, 2);

    /*
     * Keep subdirectories.
     *
     * admin/index  -> view/admin/index.view.php
     * admin/edit   -> view/admin/edit.view.php
     * index        -> view/index.view.php
     */
    $name = trim($name, '/');

    $view_path = $project_root . '/view/' . $name . '.view.php';

    if (!file_exists($view_path)) {
        $view_path = $project_root . '/view/notfound.view.php';
    }

    extract($data, EXTR_SKIP);

    /*
     * layout.view.php expects:
     *   $name       = requested view name
     *   $view_path  = actual view file
     */
    require $project_root . '/view/layout.view.php';
}

function is_user_authenticated(): bool
{
    return isset($_SESSION['email']);
}

function ensure_user_is_authenticated(): void
{
    if (!is_user_authenticated()) {
        redirect('../login.php');
    }
}

function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function is_get(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function sanitize(string $value): string
{
    return trim(
        htmlspecialchars(
            $value,
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        )
    );
}

function e($value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
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
    return '<input type="hidden" name="csrf_token" value="' .
        e(csrf_token()) .
        '">';
}

function verify_csrf_token(): bool
{
    $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token) ||
        $token === '' ||
        !isset($_SESSION['csrf_token'])
    ) {
        return false;
    }

    return hash_equals(
        $_SESSION['csrf_token'],
        $token
    );
}

function authenticate_user(
    string $email,
    string $password
): bool {
    $users = CONFIG['users'];

    if (!isset($users[$email])) {
        return false;
    }

    return password_verify(
        $password,
        $users[$email]
    );
}
