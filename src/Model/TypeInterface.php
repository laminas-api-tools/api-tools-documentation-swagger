<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-swagger for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\Swagger\Model;

interface TypeInterface
{
    /**
     * @param mixed $target Value to attempt to match to the given type.
     * @return bool
     */
    public function match($target);

    /**
     * @param mixed $target Value to generate documentation for.
     * @return array Specification for the given type.
     */
    public function generate($target);
}
