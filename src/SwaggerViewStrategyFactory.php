<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Documentation\Swagger;

use Psr\Container\ContainerInterface;

class SwaggerViewStrategyFactory
{
    /**
     * @return SwaggerViewStrategy
     */
    public function __invoke(ContainerInterface $container)
    {
        return new SwaggerViewStrategy($container->get('ViewJsonRenderer'));
    }
}
