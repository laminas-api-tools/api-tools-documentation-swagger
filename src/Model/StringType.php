<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Documentation\Swagger\Model;

use function is_string;

class StringType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function match($target)
    {
        return is_string($target);
    }

    /**
     * {@inheritDoc}
     */
    public function generate($target)
    {
        return [
            'type' => 'string',
        ];
    }
}
