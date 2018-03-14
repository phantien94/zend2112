<?php
namespace Form;

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
            'form' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/form[/:controller][/:action]',
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
            Controller\ContactController::class => InvokableFactory::class,
            Controller\UploadFileController::class => InvokableFactory::class,
        ],
        'aliases'=>[
            'index'=>Controller\IndexController::class,
            'contact'=>Controller\ContactController::class,
            'upload-file'=>Controller\UploadFileController::class,
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
