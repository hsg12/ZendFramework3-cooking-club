<?php

return [
    'mail' => [
        'transport' => [
            'options' => [
                'name' => 'smtp.gmail.com',
                'host' => 'smtp.gmail.com',
                'connection_class' => 'plain',
                'connection_config' => [
                    'username' => 'testxamppphp',
                    'password' => 'sendingemail',
                    'ssl' => 'tls',
                ],
            ],
        ],
    ],
];