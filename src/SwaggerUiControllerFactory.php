<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-swagger for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\Swagger;

use Interop\Container\ContainerInterface;
use Laminas\ApiTools\Documentation\ApiFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class SwaggerUiControllerFactory implements FactoryInterface
{
    /**
     * Create and return a SwaggerUiController instance.
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return SwaggerUiController
     * @throws ServiceNotCreatedException when ApiFactory service is missing.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (! $container->has(ApiFactory::class)
            && ! $container->has(\ZF\Apigility\Documentation\ApiFactory::class)
        ) {
            throw new ServiceNotCreatedException(sprintf(
                '%s requires the service %s, which was not found',
                SwaggerUiController::class,
                ApiFactory::class
            ));
        }

        return new SwaggerUiController($container->has(ApiFactory::class) ? $container->get(ApiFactory::class) : $container->get(\ZF\Apigility\Documentation\ApiFactory::class));
    }

    /**
     * Create and return a SwaggerUiController instance.
     *
     * Provided for backwards compatibility; proxies to __invoke().
     *
     * @param ServiceLocatorInterface $controllers
     * @return SwaggerUiController
     * @throws ServiceNotCreatedException when ApiFactory service is missing.
     */
    public function createService(ServiceLocatorInterface $controllers)
    {
        $container = $controllers->getServiceLocator() ?: $controllers;
        return $this($container, SwaggerUiController::class);
    }
}
