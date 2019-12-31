Swagger Documentation Provider for Laminas API Tools
============================================

[![Build Status](https://travis-ci.org/laminas-api-tools/api-tools-documentation-swagger.png)](https://travis-ci.org/laminas-api-tools/api-tools-documentation-swagger)

Introduction
------------

This module provides Laminas API Tools the ability to show API documentation through a
[Swagger UI](http://swagger.wordnik.com/).

The Swagger UI is immediately accessible after enabling this module at the URI path `/api-tools/swagger`.

In addition to providing the HTML UI, this module also plugs into the main Laminas API Tools documentation
resource (at the path `/api-tools/documentation`) in order to allow returning a documentation
payload in the `application/vnd.swagger+json` media type; this resource is what feeds the Swagger
UI. You can access this representation by passing the media type `application/vnd.swagger+json` for
the `Accept` header via the path `/api-tools/documentation/:module/:service`.

Requirements
------------
  
Please see the [composer.json](composer.json) file.

Installation
------------

Run the following `composer` command:

```console
$ composer require "laminas-api-tools/api-tools-documentation-swagger:~1.0-dev"
```

Alternately, manually add the following to your `composer.json`, in the `require` section:

```javascript
"require": {
    "laminas-api-tools/api-tools-documentation-swagger": "~1.0-dev"
}
```

And then run `composer update` to ensure the module is installed.

Finally, add the module name to your project's `config/application.config.php` under the `modules`
key:

```php
return array(
    /* ... */
    'modules' => array(
        /* ... */
        'Laminas\ApiTools\Documentation\Swagger',
    ),
    /* ... */
);
```

Routes
------

### `/api-tools/swagger`

Shows the Swagger UI JavaScript application.

### Assets: `/api-tools-documentation-swagger/`

Various CSS, images, and JavaScript libraries required to deliver the Swagger UI client
application.

Configuration
-------------

### System Configuration

The following is required to ensure the module works within a Laminas and/or Laminas API Tools-enabled
application:

```php
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
```

Laminas Events
----------

### Listeners

#### `Laminas\ApiTools\Documentation\Swagger\Module`

This listener is attached to the `MvcEvent::EVENT_RENDER` event at priority `100`.  Its purpose is
to conditionally attach a view strategy to the view system in cases where the controller response is
a `Laminas\ApiTools\Documentation\Swagger\ViewModel` view model (likely selected as the
content-negotiated view model based off of `Accept` media types).

Laminas Services
------------

### View Models

#### `Laminas\ApiTools\Documentation\Swagger\ViewModel`

This view model is responsible for translating the available `Laminas\ApiTools\Documentation` models
into Swagger-specific models, and further casting them to arrays for later rendering as JSON.
