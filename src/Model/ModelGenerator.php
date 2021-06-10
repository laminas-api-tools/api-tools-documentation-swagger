<?php

namespace Laminas\ApiTools\Documentation\Swagger\Model;

use Laminas\ApiTools\Documentation\Swagger\Exception\UnmatchedTypeException;

use function array_merge;
use function json_decode;

class ModelGenerator
{
    /**
     * @var array
     * @psalm-var array{
     *     0: ObjectType,
     *     1: NumberType,
     *     2: IntegerType,
     *     3: StringType,
     *     4: BooleanType,
     *     5: ArrayType
     * }
     */
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
     * @throws UnmatchedTypeException If unable to match any given $target to a known type.
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
     * @throws UnmatchedTypeException If unable to match $target to a known type.
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
