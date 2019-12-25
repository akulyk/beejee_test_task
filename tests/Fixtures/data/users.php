<?php

use app\helpers\HashHelper;

return [
    [
        'username' => 'admin',
        'password' => HashHelper::crypt('123'),
        'token' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
    ],
    [
        'username' => 'username1',
        'password' => HashHelper::crypt('NewVeryHardPassword1'),
        'token' => 'ASDFGHJKLzxcvbnmqwertyuiop',
    ],
    [
        'username' => 'username2',
        'password' => HashHelper::crypt('NewVeryHardPassword2'),
        'token' => 'ZXCVBNMlkjhgfdsa0987654321',
    ],
    [
        'username' => 'username3',
        'password' => HashHelper::crypt('NewVeryHardPassword3'),
        'token' => null,
    ],
];
