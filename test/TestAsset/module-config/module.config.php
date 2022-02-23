<?php

declare(strict_types=1);

use Laminas\InputFilter\CollectionInputFilter;
use Laminas\InputFilter\InputFilter;

return [
    'router'                        => [
        'routes' => [
            'test.rest.foo-bar'            => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/foo-bar[/:foo_bar_id]',
                    'defaults' => [
                        'controller' => 'Test\\V1\\Rest\\FooBar\\Controller',
                    ],
                ],
            ],
            'test.rest.foo-bar-collection' => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/foo-bar-collection[/:foo_bar_collection_id]',
                    'defaults' => [
                        'controller' => 'Test\\V1\\Rest\\FooBarCollection\\Controller',
                    ],
                ],
            ],
            'test.rest.boo-baz'            => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/boo-baz[/:boo_baz_id]',
                    'defaults' => [
                        'controller' => 'Test\\V1\\Rest\\BooBaz\\Controller',
                    ],
                ],
            ],
            'test.rpc.my-rpc'              => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/my-rpc',
                    'defaults' => [
                        'controller' => 'Test\\V1\\Rpc\\MyRpc\\Controller',
                        'action'     => 'myRpc',
                    ],
                ],
            ],
            'test.rpc.ping'                => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/ping',
                    'defaults' => [
                        'controller' => 'Test\\V1\\Rpc\\Ping\\Controller',
                        'action'     => 'ping',
                    ],
                ],
            ],
            'test.rest.entity-fields'      => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/entity-fields',
                    'defaults' => [
                        'controller' => 'Test\\V1\\Rest\\EntityFields\\Controller',
                        'action'     => 'test',
                    ],
                ],
            ],
            'test.rest.only-post'          => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/only-post[/:only_post_id]',
                    'defaults' => [
                        'controller' => 'Test\\V1\\Rest\\OnlyPost\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'api-tools-versioning'          => [
        'uri' => [
            0 => 'test.rest.foo-bar',
            1 => 'test.rest.boo-baz',
            2 => 'test.rpc.my-rpc',
            3 => 'test.rpc.ping',
            4 => 'test.rest.foo-bar-collection',
            5 => 'test.rest.only-post',
        ],
    ],
    'service_manager'               => [
        'invokables' => [
            'Test\\V1\\Rest\\FooBar\\FooBarResource'           => 'Test\\V1\\Rest\\FooBar\\FooBarResource',
            'Test\\V1\\Rest\\FooBarCollection\\FooBarResource' => 'Test\\V1\\Rest\\FooBarCollection\\FooBarResource',
            'Test\\V1\\Rest\\BooBaz\\BooBazResource'           => 'Test\\V1\\Rest\\BooBaz\\BooBazResource',
            'Test\\V1\\Rest\\OnlyPost\\OnlyPostResource'       => 'Test\\V1\\Rest\\OnlyPost\\OnlyPostResource',
        ],
    ],
    'api-tools-rest'                => [
        'Test\\V1\\Rest\\FooBar\\Controller'           => [
            'listener'                   => 'Test\\V1\\Rest\\FooBar\\FooBarResource',
            'route_name'                 => 'test.rest.foo-bar',
            'route_identifier_name'      => 'foo_bar_id',
            'collection_name'            => 'foo_bar',
            'entity_http_methods'        => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods'    => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size'                  => 25,
            'page_size_param'            => null,
            'entity_class'               => 'Test\\V1\\Rest\\FooBar\\FooBarEntity',
            'collection_class'           => 'Test\\V1\\Rest\\FooBar\\FooBarCollection',
            'service_name'               => 'FooBar',
        ],
        'Test\\V1\\Rest\\FooBarCollection\\Controller' => [
            'listener'                   => 'Test\\V1\\Rest\\FooBarCollection\\FooBarResource',
            'route_name'                 => 'test.rest.foo-bar-collection',
            'route_identifier_name'      => 'foo_bar_collection_id',
            'collection_name'            => 'foo_bar_collection',
            'entity_http_methods'        => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods'    => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size'                  => 25,
            'page_size_param'            => null,
            'entity_class'               => 'Test\\V1\\Rest\\FooBarCollection\\FooBarEntity',
            'collection_class'           => 'Test\\V1\\Rest\\FooBarCollection\\FooBarCollection',
            'service_name'               => 'FooBarCollection',
        ],
        'Test\\V1\\Rest\\BooBaz\\Controller'           => [
            'listener'                   => 'Test\\V1\\Rest\\BooBaz\\BooBazResource',
            'route_name'                 => 'test.rest.boo-baz',
            'route_identifier_name'      => 'boo_baz_id',
            'collection_name'            => 'boo_baz',
            'entity_http_methods'        => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods'    => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size'                  => 25,
            'page_size_param'            => null,
            'entity_class'               => 'Test\\V1\\Rest\\BooBaz\\BooBazEntity',
            'collection_class'           => 'Test\\V1\\Rest\\BooBaz\\BooBazCollection',
            'service_name'               => 'BooBaz',
        ],
        'Test\\V1\\Rest\\EntityFields\\Controller'     => [
            'listener'                   => 'Test\\V1\\Rest\\EntityFields\\EntityFieldsResource',
            'route_name'                 => 'test.rest.entity-fields',
            'route_identifier_name'      => 'id',
            'collection_name'            => 'entity_fields',
            'entity_http_methods'        => [
                0 => 'PUT',
            ],
            'collection_http_methods'    => [],
            'collection_query_whitelist' => [],
            'page_size'                  => 25,
            'page_size_param'            => null,
            'entity_class'               => 'Test\\V1\\Rest\\EntityFields\\EntityFieldsEntity',
            'collection_class'           => 'Test\\V1\\Rest\\EntityFields\\EntityFieldsCollection',
            'service_name'               => 'EntityFields',
        ],
        'Test\\V1\\Rest\\OnlyPost\\Controller'         => [
            'listener'                   => 'Test\\V1\\Rest\\OnlyPost\\OnlyPostResource',
            'route_name'                 => 'test.rest.only-post',
            'route_identifier_name'      => 'only_post_id',
            'collection_name'            => 'only_post',
            'entity_http_methods'        => [],
            'collection_http_methods'    => [
                0 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size'                  => 25,
            'page_size_param'            => null,
            'entity_class'               => 'Test\\V1\\Rest\\OnlyPost\\OnlyPostEntity',
            'collection_class'           => 'Test\\V1\\Rest\\OnlyPost\\OnlyPostCollection',
            'service_name'               => 'OnlyPost',
        ],
    ],
    'api-tools-content-negotiation' => [
        'controllers'            => [
            'Test\\V1\\Rest\\FooBar\\Controller'           => 'HalJson',
            'Test\\V1\\Rest\\FooBarCollection\\Controller' => 'HalJson',
            'Test\\V1\\Rest\\BooBaz\\Controller'           => 'HalJson',
            'Test\\V1\\Rpc\\MyRpc\\Controller'             => 'Json',
            'Test\\V1\\Rpc\\Ping\\Controller'              => 'Json',
            'Test\\V1\\Rest\\OnlyPost\\Controller'         => 'HalJson',
        ],
        'accept_whitelist'       => [
            'Test\\V1\\Rest\\FooBar\\Controller'           => [
                0 => 'application/vnd.test.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Test\\V1\\Rest\\FooBarCollection\\Controller' => [
                0 => 'application/vnd.test.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Test\\V1\\Rest\\BooBaz\\Controller'           => [
                0 => 'application/vnd.test.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Test\\V1\\Rpc\\MyRpc\\Controller'             => [
                0 => 'application/vnd.test.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Test\\V1\\Rpc\\Ping\\Controller'              => [
                0 => 'application/vnd.test.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Test\\V1\\Rest\\EntityFields\\Controller'     => [
                0 => 'application/vnd.test.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Test\\V1\\Rest\\OnlyPost\\Controller'         => [
                0 => 'application/vnd.foo.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'Test\\V1\\Rest\\FooBar\\Controller'           => [
                0 => 'application/vnd.test.v1+json',
                1 => 'application/json',
            ],
            'Test\\V1\\Rest\\FooBarCollection\\Controller' => [
                0 => 'application/vnd.test.v1+json',
                1 => 'application/json',
            ],
            'Test\\V1\\Rest\\BooBaz\\Controller'           => [
                0 => 'application/vnd.test.v1+json',
                1 => 'application/json',
            ],
            'Test\\V1\\Rpc\\MyRpc\\Controller'             => [
                0 => 'application/vnd.test.v1+json',
                1 => 'application/json',
            ],
            'Test\\V1\\Rpc\\Ping\\Controller'              => [
                0 => 'application/vnd.test.v1+json',
                1 => 'application/json',
            ],
            'Test\\V1\\Rpc\\EntityFields\\Controller'      => [
                0 => 'application/vnd.test.v1+json',
                1 => 'application/json',
            ],
            'Test\\V1\\Rest\\OnlyPost\\Controller'         => [
                0 => 'application/vnd.foo.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'api-tools-hal'                 => [
        'metadata_map' => [
            'Test\\V1\\Rest\\FooBar\\FooBarEntity'                 => [
                'entity_identifier_name' => 'id',
                'route_name'             => 'test.rest.foo-bar',
                'route_identifier_name'  => 'foo_bar_id',
                'hydrator'               => 'Laminas\\Hydrator\\ArraySerializable',
            ],
            'Test\\V1\\Rest\\FooBar\\FooBarCollection'             => [
                'entity_identifier_name' => 'id',
                'route_name'             => 'test.rest.foo-bar',
                'route_identifier_name'  => 'foo_bar_id',
                'is_collection'          => true,
            ],
            'Test\\V1\\Rest\\BooBaz\\BooBazEntity'                 => [
                'entity_identifier_name' => 'id',
                'route_name'             => 'test.rest.boo-baz',
                'route_identifier_name'  => 'boo_baz_id',
                'hydrator'               => 'Laminas\\Hydrator\\ArraySerializable',
            ],
            'Test\\V1\\Rest\\BooBaz\\BooBazCollection'             => [
                'entity_identifier_name' => 'id',
                'route_name'             => 'test.rest.boo-baz',
                'route_identifier_name'  => 'boo_baz_id',
                'is_collection'          => true,
            ],
            'Test\\V1\\Rest\\EntityFields\\EntityFieldsCollection' => [
                'entity_identifier_name' => 'id',
                'route_name'             => 'test.rest.entity-fields',
                'route_identifier_name'  => 'id',
                'is_collection'          => true,
            ],
            'Test\\V1\\Rest\\OnlyPost\\OnlyPostEntity'             => [
                'entity_identifier_name' => 'id',
                'route_name'             => 'test.rest.only-post',
                'route_identifier_name'  => 'only_post_id',
                'hydrator'               => 'Laminas\\Hydrator\\ArraySerializable',
            ],
            'Test\\V1\\Rest\\OnlyPost\\OnlyPostCollection'         => [
                'entity_identifier_name' => 'id',
                'route_name'             => 'test.rest.only-post',
                'route_identifier_name'  => 'only_post_id',
                'is_collection'          => true,
            ],
        ],
    ],
    'controllers'                   => [
        'invokables' => [
            'Test\\V1\\Rpc\\MyRpc\\Controller' => 'Test\\V1\\Rpc\\MyRpc\\MyRpcController',
            'Test\\V1\\Rpc\\Ping\\Controller'  => 'Test\\V1\\Rpc\\Ping\\PingController',
        ],
    ],
    'api-tools-rpc'                 => [
        'Test\\V1\\Rpc\\MyRpc\\Controller' => [
            'service_name' => 'MyRpc',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name'   => 'test.rpc.my-rpc',
        ],
        'Test\\V1\\Rpc\\Ping\\Controller'  => [
            'service_name' => 'Ping',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name'   => 'test.rpc.ping',
        ],
    ],
    'api-tools-content-validation'  => [
        'Test\\V1\\Rest\\FooBar\\Controller'           => [
            'input_filter' => 'Test\\V1\\Rest\\FooBar\\Validator',
        ],
        'Test\\V1\\Rest\\FooBarCollection\\Controller' => [
            'input_filter' => 'Test\\V1\\Rest\\FooBarCollection\\Validator',
        ],
        'Test\\V1\\Rest\\EntityFields\\Controller'     => [
            'input_filter' => 'Test\\V1\\Rest\\EntityFields\\Validator',
            'PUT'          => 'Test\\V1\\Rest\\EntityFields\\Validator\\Put',
        ],
        'Test\\V1\\Rest\\OnlyPost\\Controller'         => [
            'input_filter' => 'Foo\\V1\\Rest\\OnlyPost\\Validator',
        ],
    ],
    'input_filter_specs'            => [
        'Test\\V1\\Rest\\FooBar\\Validator'            => [
            0                => [
                'name'        => 'goober',
                'required'    => true,
                'filters'     => [],
                'validators'  => [],
                'description' => 'This is the description for goober.',
            ],
            1                => [
                'name'       => 'bergoo',
                'required'   => true,
                'filters'    => [],
                'validators' => [],
            ],
            'foogoober'      => [
                'type'      => InputFilter::class,
                'subgoober' => [
                    'name'       => 'subgoober',
                    'required'   => true,
                    'filters'    => [],
                    'validators' => [],
                ],
            ],
            'foofoogoober'   => [
                'type'      => InputFilter::class,
                'subgoober' => [
                    'type'      => InputFilter::class,
                    'subgoober' => [
                        'name'       => 'subgoober',
                        'required'   => true,
                        'filters'    => [],
                        'validators' => [],
                    ],
                ],
            ],
            'companyDetails' => [
                'type'        => InputFilter::class,
                'name'        => [
                    'name'              => 'name',
                    'required'          => true,
                    'validators'        => [],
                    'description'       => '',
                    'allow_empty'       => false,
                    'continue_if_empty' => false,
                ],
                'required'    => [
                    'allow_empty'       => false,
                    'continue_if_empty' => false,
                ],
                'description' => [
                    'name'              => 'website',
                    'required'          => false,
                    'validators'        => [],
                    'allow_empty'       => false,
                    'continue_if_empty' => false,
                ],
            ],
        ],
        'Test\\V1\\Rest\\FooBarCollection\\Validator'  => [
            'FooBarCollection'  => [
                'type'         => CollectionInputFilter::class,
                'required'     => true,
                'count'        => 1,
                'input_filter' => [
                    'type'       => InputFilter::class,
                    'name'       => 'FooBar',
                    'required'   => true,
                    'filters'    => [],
                    'validators' => [],
                ],
            ],
            'AnotherCollection' => [
                'type'         => CollectionInputFilter::class,
                'required'     => true,
                'count'        => 1,
                'input_filter' => [
                    'type'       => InputFilter::class,
                    'name'       => 'FooBar',
                    'required'   => true,
                    'filters'    => [],
                    'validators' => [],
                ],
            ],
        ],
        'Test\\V1\\Rest\\EntityFields\\Validator'      => [
            0 => [
                'required'    => true,
                'validators'  => [],
                'filters'     => [],
                'name'        => 'test',
                'description' => 'test',
            ],
        ],
        'Test\\V1\\Rest\\EntityFields\\Validator\\Put' => [
            0 => [
                'required'    => true,
                'validators'  => [],
                'filters'     => [],
                'name'        => 'test_put',
                'description' => 'test_put',
            ],
        ],
        'Foo\\V1\\Rest\\OnlyPost\\Validator'           => [
            0 => [
                'required'    => true,
                'validators'  => [],
                'filters'     => [],
                'name'        => 'mylist',
                'description' => 'My list field',
                'field_type'  => 'array',
            ],
            1 => [
                'required'   => true,
                'validators' => [],
                'filters'    => [],
                'name'       => 'mysecondlist',
                'field_type' => 'array',
            ],
        ],
    ],
    'api-tools-mvc-auth'            => [
        'authentication' => [
            'http' => [
                'realm'    => 'api',
                'htpasswd' => __DIR__ . '/htpasswd',
            ],
        ],
        'authorization'  => [
            'Test\V1\Rest\FooBar\Controller'           => [
                'entity'     => [
                    'DELETE' => true,
                    'GET'    => false,
                    'PATCH'  => true,
                    'POST'   => false,
                    'PUT'    => true,
                ],
                'collection' => [
                    'DELETE' => false,
                    'GET'    => false,
                    'PATCH'  => false,
                    'POST'   => true,
                    'PUT'    => false,
                ],
            ],
            'Test\V1\Rest\FooBarCollection\Controller' => [
                'entity'     => [
                    'DELETE' => true,
                    'GET'    => false,
                    'PATCH'  => true,
                    'POST'   => false,
                    'PUT'    => true,
                ],
                'collection' => [
                    'DELETE' => false,
                    'GET'    => false,
                    'PATCH'  => false,
                    'POST'   => true,
                    'PUT'    => false,
                ],
            ],
            'Test\V1\Rest\BooBaz\Controller'           => [
                'entity'     => [
                    'DELETE' => true,
                    'GET'    => false,
                    'PATCH'  => true,
                    'POST'   => false,
                    'PUT'    => true,
                ],
                'collection' => [
                    'DELETE' => false,
                    'GET'    => false,
                    'PATCH'  => false,
                    'POST'   => false,
                    'PUT'    => false,
                ],
            ],
            'Test\V1\Rpc\MyRpc\Controller'             => [
                'actions' => [
                    'myRpc' => [
                        'DELETE' => false,
                        'GET'    => true,
                        'PATCH'  => false,
                        'POST'   => false,
                        'PUT'    => false,
                    ],
                ],
            ],
            'Test\V1\Rpc\Ping\Controller'              => [
                'actions' => [
                    'ping' => [
                        'DELETE' => false,
                        'GET'    => false,
                        'PATCH'  => false,
                        'POST'   => false,
                        'PUT'    => false,
                    ],
                ],
            ],
        ],
    ],
];
