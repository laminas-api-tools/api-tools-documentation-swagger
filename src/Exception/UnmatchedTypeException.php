<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Documentation\Swagger\Exception;

use RuntimeException;

use function is_object;
use function sprintf;
use function var_export;

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
            is_object($type) ? $type::class : var_export($type, true)
        ));
    }
}
