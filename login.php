<?php

session_start();

require __DIR__ . '/controller/app.php';

if (is_user_authenticated()) {
    redirect('admin/index.php');
}

$view_bag = [
    'title' => 'Login'
];

if (is_post()) {
    if (!verify_csrf_token()) {
        $view_bag['status'] = 'Invalid request. Please try again.';
        view('login', $view_bag);
        exit;
    }

    $email = filter_input(
        INPUT_POST,
        'email',
        FILTER_VALIDATE_EMAIL
    );

    $password = $_POST['password'] ?? '';

    if (
        $email &&
        $password !== '' &&
        authenticate_user($email, $password)
    ) {
        session_regenerate_id(true);

        $_SESSION['email'] = $email;
        $_SESSION['role']  = 'admin';

        unset($_SESSION['csrf_token']);

        redirect('admin/index.php');
    }

    $view_bag['status'] = 'Invalid credentials.';
}

view('login', $view_bag);
