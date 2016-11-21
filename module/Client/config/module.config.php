<?php
/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 29.10.2016
 * Time: 9:44
 */

namespace Client;

use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'clients' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/clients[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller'    => Controller\ClientController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\ClientController::class => Factory\ClientControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\ClientManager::class => Factory\ClientManagerFactory::class,
        ],
    ],
    'session_containers' => [
        'SearchClientForm'
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