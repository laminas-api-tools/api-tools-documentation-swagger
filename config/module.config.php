<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-swagger for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\Swagger;

return [
    'router' => [
        'routes' => [
            'api-tools' => [
                'child_routes' => [
                    'swagger' => [
                        'type' => 'segment',
                        'options' => [
                            'route'    => '/swagger',
                            'defaults' => [
                                'controller' => SwaggerUi::class,
                                'action'     => 'list',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'api' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => '/:api',
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

    'service_manager' => [
        // Legacy Zend Framework aliases
        'aliases' => [
            \ZF\Apigility\Documentation\Swagger\SwaggerViewStrategy::class => SwaggerViewStrategy::class,
        ],
        'factories' => [
            SwaggerViewStrategy::class => SwaggerViewStrategyFactory::class,
        ],
    ],

    'controllers' => [
        // Legacy Zend Framework aliases
        'aliases' => [
            \ZF\Apigility\Documentation\Swagger\SwaggerUi::class => SwaggerUi::class,
        ],
        'factories' => [
            SwaggerUi::class => SwaggerUiControllerFactory::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'api-tools-documentation-swagger' => __DIR__ . '/../view',
        ],
    ],

    'asset_manager' => [
        'resolver_configs' => [
            'paths' => [
                __DIR__ . '/../asset',
            ],
        ],
    ],

    'api-tools-content-negotiation' => [
        'accept_whitelist' => [
            'Laminas\ApiTools\Documentation\Controller' => [
                0 => 'application/vnd.swagger+json',
            ],
        ],
        'selectors' => [
            'Documentation' => [
                ViewModel::class => [
                    'application/vnd.swagger+json',
                ],
            ],
        ],
    ],
];
