<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-swagger for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\Swagger;

class SwaggerViewStrategyFactory
{
    /**
     * @param \Laminas\ServiceManager\ServiceLocatorInterface $services
     * @return SwaggerViewStrategy
     */
    public function __invoke($services)
    {
        return new SwaggerViewStrategy($services->get('ViewJsonRenderer'));
    }
}
