<?php

declare(strict_types=1);

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
        'factories' => [
            SwaggerViewStrategy::class => SwaggerViewStrategyFactory::class,
        ],
    ],
    'controllers'                   => [
        'factories' => [
            // Legacy naming that omits Controller suffix
            'Laminas\ApiTools\Documentation\Swagger\SwaggerUi' => SwaggerUiControllerFactory::class,
            SwaggerUiController::class                         => SwaggerUiControllerFactory::class,
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
