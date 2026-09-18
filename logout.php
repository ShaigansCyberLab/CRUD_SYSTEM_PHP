<?php

require __DIR__ . '/controller/app.php';

if (!is_user_authenticated()) {
    redirect('login.php');
}

if (
    !is_post() ||
    !verify_csrf_token()
) {
    http_response_code(400);
    exit('Invalid request.');
}

destroy_user_session();

redirect('login.php');
