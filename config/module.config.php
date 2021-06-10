<?php

namespace Laminas\ApiTools\Documentation\Swagger;

use Laminas\ApiTools\Documentation\Controller;

return [
    'router'                        => [
        'routes' => [
            'api-tools' => [
                'child_routes' => [
                    'swagger' => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'    => '/swagger',
                            'defaults' => [
                                'controller' => SwaggerUi::class,
                                'action'     => 'list',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'api' => [
                                'type'          => 'segment',
                                'options'       => [
                                    'route'    => '/:api',
                                    'defaults' => [
                                        'action' => 'show',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager'               => [
        // Legacy Zend Framework aliases
        'aliases'   => [
            \ZF\Apigility\Documentation\Swagger\SwaggerViewStrategy::class => SwaggerViewStrategy::class,
        ],
        'factories' => [
            SwaggerViewStrategy::class => SwaggerViewStrategyFactory::class,
        ],
    ],
    'controllers'                   => [
        // Legacy Zend Framework aliases
        'aliases'   => [
            \ZF\Apigility\Documentation\Swagger\SwaggerUi::class => SwaggerUi::class,
        ],
        'factories' => [
            SwaggerUi::class => SwaggerUiControllerFactory::class,
        ],
    ],
    'view_manager'                  => [
        'template_path_stack' => [
            'api-tools-documentation-swagger' => __DIR__ . '/../view',
        ],
    ],
    'asset_manager'                 => [
        'resolver_configs' => [
            'paths' => [
                __DIR__ . '/../asset',
            ],
        ],
    ],
    'api-tools-content-negotiation' => [
        'accept_whitelist' => [
            Controller::class => [
                0 => 'application/vnd.swagger+json',
            ],
        ],
        'selectors'        => [
            'Documentation' => [
                ViewModel::class => [
                    'application/vnd.swagger+json',
                ],
            ],
        ],
    ],
];
