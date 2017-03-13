<?php

use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'params' => [
                    'host'     => 'mysql.hostinger.in',
                    'user'     => 'u459036520_hayk',
                    'password' => 'UIsxdZ028xdyUCUH',
                    'dbname'   => 'u459036520_simpl',
                ]
            ],
        ],
    ],
];
