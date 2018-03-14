<?php
namespace Database;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'database' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/database[/:controller][/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\HomeController::class => InvokableFactory::class,
            Controller\DdlController::class => InvokableFactory::class
        ],
        'aliases'=>[
            'index'=>Controller\IndexController::class,
            'home'=>Controller\HomeController::class,
            'ddl'=>Controller\DdlController::class
        ]
    ],
    // 'view_manager' => [
    //     'template_path_stack' => [
    //         __DIR__ . '/../view',
    //     ],
    // ],
];
