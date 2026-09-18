<?php

require __DIR__ . '/controller/app.php';

if (is_user_authenticated()) {
    redirect('admin/index.php');
}

$view_bag = [
    'title' => 'Login',
];

$email = '';

if (is_post()) {
    $email = request_string($_POST, 'email');
    $password = request_string($_POST, 'password');

    if (!verify_csrf_token()) {
        $view_bag['status'] =
            'Invalid request. Please try again.';
    } elseif (login_is_rate_limited()) {
        $view_bag['status'] =
            'Too many login attempts. Please wait 15 minutes and try again.';
    } else {
        $valid_email = filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        );

        $valid_password_length =
            text_length($password) <= 4096;

        if (
            $valid_email !== false &&
            $valid_password_length &&
            authenticate_user(
                $email,
                $password
            )
        ) {
            session_regenerate_id(true);

            $_SESSION['email'] = $email;
            $_SESSION['role'] = 'admin';
            $_SESSION['last_activity'] = time();
            $_SESSION['last_regeneration'] = time();

            clear_login_failures();

            /*
             * Force a fresh CSRF token after authentication.
             */
            unset($_SESSION['csrf_token']);

            redirect('admin/index.php');
        }

        record_login_failure();

        $view_bag['status'] =
            'Invalid credentials.';
    }
}

view('login', [
    'view_bag' => $view_bag,
    'email'     => $email,
]);
