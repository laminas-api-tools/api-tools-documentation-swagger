<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Documentation\Swagger;

use Interop\Container\ContainerInterface;
use Laminas\ApiTools\Documentation\ApiFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

use function sprintf;

class SwaggerUiControllerFactory implements FactoryInterface
{
    /**
     * Create and return a SwaggerUiController instance.
     *
     * @param string $requestedName
     * @param null|array $options
     * @return SwaggerUiController
     * @throws ServiceNotCreatedException When ApiFactory service is missing.
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        if (
            ! $container->has(ApiFactory::class)
            && ! $container->has(\ZF\Apigility\Documentation\ApiFactory::class)
        ) {
            throw new ServiceNotCreatedException(sprintf(
                '%s requires the service %s, which was not found',
                SwaggerUiController::class,
                ApiFactory::class
            ));
        }

        $apiFactory = $container->has(ApiFactory::class)
            ? $container->get(ApiFactory::class)
            : $container->get(\ZF\Apigility\Documentation\ApiFactory::class);

        return new SwaggerUiController($apiFactory);
    }

    /**
     * Create and return a SwaggerUiController instance.
     *
     * Provided for backwards compatibility; proxies to __invoke().
     *
     * @return SwaggerUiController
     * @throws ServiceNotCreatedException When ApiFactory service is missing.
     */
    public function createService(ServiceLocatorInterface $controllers)
    {
        $container = $controllers->getServiceLocator() ?: $controllers;
        return $this($container, SwaggerUiController::class);
    }
}
