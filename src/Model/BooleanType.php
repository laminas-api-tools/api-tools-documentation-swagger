<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Documentation\Swagger\Model;

use function is_bool;

class BooleanType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function match($target)
    {
        return is_bool($target);
    }

    /**
     * {@inheritDoc}
     */
    public function generate($target)
    {
        return [
            'type' => 'boolean',
        ];
    }
}
