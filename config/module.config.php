<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-swagger for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/LICENSE.md New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'api-tools' => array(
                'child_routes' => array(
                    'swagger' => array(
                        'type' => 'Laminas\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => '/swagger',
                            'defaults' => array(
                                'controller' => 'Laminas\ApiTools\Documentation\Swagger\SwaggerUi',
                                'action'     => 'list',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'api' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:api',
                                    'defaults' => array(
                                        'action' => 'show',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'Laminas\ApiTools\Documentation\Swagger\SwaggerViewStrategy' => 'Laminas\ApiTools\Documentation\Swagger\SwaggerViewStrategyFactory',
        ),
    ),

    'controllers' => array(
        'factories' => array(
            'Laminas\ApiTools\Documentation\Swagger\SwaggerUi' => 'Laminas\ApiTools\Documentation\Swagger\SwaggerUiControllerFactory',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'api-tools-documentation-swagger' => __DIR__ . '/../view',
        ),
    ),

    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../asset',
            ),
        ),
    ),

    'api-tools-content-negotiation' => array(
        'accept_whitelist' => array(
            'Laminas\ApiTools\Documentation\Controller' => array(
                0 => 'application/vnd.swagger+json',
            ),
        ),
        'selectors' => array(
            'Documentation' => array(
                'Laminas\ApiTools\Documentation\Swagger\ViewModel' => array(
                    'application/vnd.swagger+json',
                ),
            )
        )
    ),
);
