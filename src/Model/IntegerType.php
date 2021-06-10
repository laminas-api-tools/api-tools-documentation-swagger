<?php

namespace Laminas\ApiTools\Documentation\Swagger\Model;

use function is_int;

class IntegerType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function match($target)
    {
        return is_int($target);
    }

    /**
     * {@inheritDoc}
     */
    public function generate($target)
    {
        return [
            'type' => 'integer',
        ];
    }
}
