<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-swagger for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\Swagger\Model;

use Laminas\ApiTools\Documentation\Swagger\Exception\UnmatchedTypeException;

class ModelGenerator
{
    private $types;

    public function __construct()
    {
        $this->types = [
            new ObjectType($this),
            new NumberType(),
            new IntegerType(),
            new StringType(),
            new BooleanType(),
            new ArrayType($this),
        ];
    }

    /**
     * @param string $jsonInput
     * @return array
     * @throws UnmatchedTypeException if unable to match any given $target to a
     *     known type.
     */
    public function generate($jsonInput)
    {
        $target = json_decode($jsonInput);

        if (! $target) {
            return false;
        }

        return array_merge(
            $this->generateType($target),
            ['example' => json_decode($jsonInput, true)]
        );
    }

    /**
     * @param mixed $target
     * @return TypeInterface
     * @throws UnmatchedTypeException if unable to match $target to a known type.
     */
    public function generateType($target)
    {
        foreach ($this->types as $type) {
            if ($type->match($target)) {
                return $type->generate($target);
            }
        }

        throw UnmatchedTypeException::forType($target);
    }
}
