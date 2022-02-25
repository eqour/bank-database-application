<?php

return [
    'root' => __DIR__ . DIRECTORY_SEPARATOR . '..',
    'security' => [
        'secret' => '4i6jhy1ufu6q3f6zh4knxmc6frsieyh9'
    ],
    'db' => [
        'dsn' => 'mysql:host=localhost;dbname=bank',
        'login' => 'bank-user-value',
        'password' => 'bank-password-value'
    ],
    'pagination' => [
        'max-records-per-page' => 5
    ],
    'include' => [
        'first' => [
            '@root' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'Controller.php',
            '@root' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'Form.php',
        ],
        'ignore' => [
            '@root' . DIRECTORY_SEPARATOR . 'views',
            '@root' . DIRECTORY_SEPARATOR . 'web',
            '@root' . DIRECTORY_SEPARATOR . 'scripts'
        ]
    ]
];
