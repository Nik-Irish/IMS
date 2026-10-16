<?php

define('LOGIN_CONTROLLER', true);

require __DIR__ . '/login/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actionMap = [
        'register' => 'register',
        'forgot_email' => 'forgot_email',
        'forgot_otp' => 'forgot_otp',
        'forgot_newpass' => 'forgot_newpass',
        'authenticate' => 'authenticate',
    ];

    if ($mode === 'register') {
        $action = 'register';
    } elseif ($mode === 'forgot') {
        $action = 'forgot_' . $resetStage;
    } else {
        $action = 'authenticate';
    }

    $actionFile = $actionMap[$action] ?? 'authenticate';

    require __DIR__ . '/login/actions/' . $actionFile . '.php';
}

require __DIR__ . '/login/views/login_page.php';
