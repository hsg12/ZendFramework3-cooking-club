<?php

namespace Admin;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'admin' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/admin',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => 'true',
                'child_routes' => [
                    'categories' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'       => '/categories[/page/:page][/:action[/:id]]',
                            'constraints' => [
                                'action' => '[a-z]*',
                                'id'     => '[0-9]+',
                                'page'   => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\CategoryController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'articles' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'       => '/articles[/page/:page][/:action[/:id]]',
                            'constraints' => [
                                'action' => '[a-z]*',
                                'id'     => '[0-9]+',
                                'page'   => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\ArticleController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'comments' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'       => '/comments[/:article][/:action[/:id]]',
                            'constraints' => [
                                'action'  => '[a-z]*',
                                'id'      => '[0-9]+',
                                'article' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\CommentController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'users' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'       => '/users[/:action[/:id]][/:page]',
                            'constraints' => [
                                'action' => '[a-z]*',
                                'id'     => '[0-9]+',
                                'page'   => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'admin/index/index' => __DIR__ . '/../view/admin/index/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
