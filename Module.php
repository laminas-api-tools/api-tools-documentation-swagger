<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-swagger for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\Swagger;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Laminas\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/'
                )
            )
        );
    }

    public function onBootstrap($e)
    {
        $app    = $e->getApplication();
        $events = $app->getEventManager();
        $events->attach('render', array($this, 'onRender'), 100);
    }

    public function onRender($e)
    {
        $model = $e->getResult();
        if (! $model instanceof ViewModel) {
            return;
        }

        $app      = $e->getApplication();
        $services = $app->getServiceManager();
        $view     = $services->get('View');
        $events   = $view->getEventManager();
        $events->attach($services->get(__NAMESPACE__ . '\SwaggerViewStrategy'));
    }
}
