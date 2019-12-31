<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-swagger for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\Swagger\Exception;

use RuntimeException;

class UnmatchedTypeException extends RuntimeException implements ExceptionInterface
{
    /**
     * @param mixed $type
     * @return self
     */
    public static function forType($type)
    {
        return new self(sprintf(
            'Unable to generate type for value (%s); perhaps the value is invalid?',
            is_object($type) ? get_class($type) : var_export($type, true)
        ));
    }
}
