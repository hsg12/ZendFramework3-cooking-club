<?php

namespace Contact;

use Zend\Router\Http\Literal;

return [
    'router' => [
        'routes' => [
            'contact-us' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/contact-us',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'contact/index/index' => __DIR__ . '/../view/contact/index/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
