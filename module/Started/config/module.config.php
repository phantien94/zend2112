<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Started;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
return [
    'router' => [
        'routes' => [
            // 'started' => [ //name route
            //     'type' => Literal::class, //type route
            //     'options' => [
            //         'route'    => '/started/index', //url
            //         'defaults' => [
            //             'controller' => Controller\UserController::class,
            //             'action'     => 'index', //function chạy mặc định
            //         ],
            //     ],
            // ],

            // 'started_2' => [ //name route
            //     'type' => Literal::class, //type route
            //     'options' => [
            //         'route'    => '/started/admin/index', //url
            //         'defaults' => [
            //             'controller' => Controller\AdminController::class,
            //             'action'     => 'index', //function chạy mặc định
            //         ],
            //     ],
            // ]

            // 'started_login' => [ //name route
            //     'type' => Literal::class, //type route
            //     'options' => [
            //         'route'    => '/started/admin/login', //url
            //         'defaults' => [
            //             'controller' => Controller\AdminController::class,
            //             'action'     => 'index', //function chạy mặc định
            //         ],
            //     ],
            // ],

            // 'started' => [ //name route
            //     'type' => Segment::class, //type route
            //     'options' => [
            //         'route'    => '/started[/:action][/:id]', //url
            //         'defaults' => [
            //             'controller' => Controller\UserController::class,
            //             'action'     => 'index', //function chạy mặc định
            //         ],

            //         'constraints'=>[
	           //      	'action' => '[a-zA-Z]*',
	           //      	'id' => '[0-9]*',
            //     	],

            //     ],

            // ],

            // 'started' => [ //name route
            //     'type' => Segment::class, //type route
            //     'options' => [
            //         'route'    => '/started[/:controller][/:action][/:id]', //url
            //         'defaults' => [
            //             'controller' => 'User',
            //             'action'     => 'index', //function chạy mặc định
            //         ],

            //         'constraints'=>[
	           //      	'action' => '[a-zA-Z]*',
	           //      	'id' => '[0-9]*',
            //     	],
                	
            //     ],

            // ],


            'started' => [ //name route
                'type' => Segment::class, //type route
                'options' => [
                    'route'    => '/started', //url
                    'defaults' => [
                        'controller' => 'User',
                        'action'     => 'index', //function chạy mặc định
                    ],

                    'constraints'=>[
	                	'action' => '[a-zA-Z]*',
	                	'id' => '[0-9]*',
                	],
                	
                ],

                'may_terminate' => false, //Cho phép route con phụ thuộc route cha

                'child_routes'=>[
                	'sub_route' => [ //name route
                	'type' => Segment::class, //type route
                	'options' => [
                    'route'    => '/huong[/:action][/:id]', //url: started/huong
                    'defaults' => [
                    	'controller' => 'Admin',
                        'action' 	 => 'login', //function chạy mặc định
                    ],

                    'constraints'=>[
	                	'id' => '[0-9]*',
                	],
                	
                ],
              	],

            ],
        ],
    ],
],

    'controllers' => [
        'factories' => [
            Controller\UserController::class => InvokableFactory::class,
            Controller\AdminController::class => InvokableFactory::class
        ],        
        'aliases'=>[
        	'User'=>Controller\UserController::class,
        	'Admin'=>Controller\AdminController::class,

        ],
    ],

    'view_manager' => [
    	 'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];