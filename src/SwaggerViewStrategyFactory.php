<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-swagger for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\Swagger;

use Interop\Container\ContainerInterface;

class SwaggerViewStrategyFactory
{
    /**
     * @param ContainerInterface $container
     * @return SwaggerViewStrategy
     */
    public function __invoke(ContainerInterface $container)
    {
        return new SwaggerViewStrategy($container->get('ViewJsonRenderer'));
    }
}
