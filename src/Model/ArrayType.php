<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Documentation\Swagger\Model;

use function is_array;

class ArrayType implements TypeInterface
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
        return is_array($target);
    }

    /**
     * {@inheritDoc}
     */
    public function generate($target)
    {
        return [
            'type'  => 'array',
            'items' => $this->modelGenerator->generateType($target[0]),
        ];
    }
}
