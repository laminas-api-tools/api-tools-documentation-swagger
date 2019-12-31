<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-swagger for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\Swagger\Model;

use Laminas\ApiTools\Documentation\Swagger\Exception\UnmatchedTypeException;

class ObjectType implements TypeInterface
{
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
     * @throws UnmatchedTypeException if any given property cannot be resolved
     *     to a known type.
     */
    public function generate($target)
    {
        return [
            'type' => 'object',
            'properties' => array_reduce(
                array_keys(get_object_vars($target)),
                function ($types, $key) use ($target) {
                    return array_merge(
                        $types,
                        [$key => $this->modelGenerator->generateType($target->$key)]
                    );
                },
                []
            )
        ];
    }
}
