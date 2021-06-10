<?php

namespace Laminas\ApiTools\Documentation\Swagger\Model;

use function is_float;

class NumberType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function match($target)
    {
        return is_float($target);
    }

    /**
     * {@inheritDoc}
     */
    public function generate($target)
    {
        return [
            'type' => 'number',
        ];
    }
}
