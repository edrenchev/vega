<?php
/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 29.10.2016
 * Time: 9:44
 */

namespace City;

use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'cities' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/cities[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller'    => Controller\CityController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\CityController::class => Factory\CityControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\CityManager::class => Factory\CityManagerFactory::class,
        ],
    ],
	'session_containers' => [
		'SearchCityForm'
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