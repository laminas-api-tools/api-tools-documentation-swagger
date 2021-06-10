<?php

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
