<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-swagger for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\Swagger;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class SwaggerUiControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllers 
     * @return SwaggerUiController
     * @throws ServiceNotCreatedException if the Laminas\ApiTools\Documentation\ApiFactory service is missing
     */
    public function createService(ServiceLocatorInterface $controllers)
    {
        $services = $controllers->getServiceLocator();
        if (!$services->has('Laminas\ApiTools\Documentation\ApiFactory')) {
            throw new ServiceNotCreatedException(sprintf(
                '%s\SwaggerUiController requires the service Laminas\ApiTools\Documentation\ApiFactory, which was not found',
                __NAMESPACE__
            ));
        }
        return new SwaggerUiController($services->get('Laminas\ApiTools\Documentation\ApiFactory'));
    }
}
