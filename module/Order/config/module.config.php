<?php
/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 29.10.2016
 * Time: 9:44
 */

namespace Order;

use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'orders' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/orders[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller'    => Controller\OrderController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\OrderController::class => Factory\OrderControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\OrderManager::class => Factory\OrderManagerFactory::class,
        ],
    ],
	'session_containers' => [
		'SearchOrderForm'
	],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
];