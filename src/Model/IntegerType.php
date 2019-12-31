<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-swagger for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\Swagger\Model;

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
