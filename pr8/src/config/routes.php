<?php

declare(strict_types=1);

const ROUTES = [
    "" => [
        "controller" => "main",
        "action" => "index",
    ],
    'account/logIn' => [
        'controller' => 'account',
        'action' => 'logIn',
    ],
    'account/register' => [
        'controller' => 'account',
        'action' => 'register',
    ],
];
