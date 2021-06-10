<?php

namespace Laminas\ApiTools\Documentation\Swagger\Model;

use Laminas\ApiTools\Documentation\Swagger\Exception\UnmatchedTypeException;

use function array_keys;
use function array_merge;
use function array_reduce;
use function get_object_vars;
use function is_object;

class ObjectType implements TypeInterface
{
    /** @var ModelGenerator */
    private $modelGenerator;

    public function __construct(ModelGenerator $modelGenerator)
    {
        $this->modelGenerator = $modelGenerator;
    }

    /**
     * {@inheritDoc}
     */
    public function match($target)
    {
        return is_object($target);
    }

    /**
     * {@inheritDoc}
     *
     * @throws UnmatchedTypeException If any given property cannot be resolved to a known type.
     */
    public function generate($target)
    {
        return [
            'type'       => 'object',
            'properties' => array_reduce(
                array_keys(get_object_vars($target)),
                function ($types, $key) use ($target) {
                    return array_merge(
                        $types,
                        [$key => $this->modelGenerator->generateType($target->$key)]
                    );
                },
                []
            ),
        ];
    }
}
