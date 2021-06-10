<?php

namespace Laminas\ApiTools\Documentation\Swagger;

use Laminas\ApiTools\ContentNegotiation\JsonModel;
use Traversable;

use function array_key_exists;

class ViewModel extends JsonModel
{
    /**
     * @return array|Traversable
     */
    public function getVariables()
    {
        if (! array_key_exists('type', $this->variables) || empty($this->variables['type'])) {
            return $this->variables;
        }

        switch ($this->variables['type']) {
            case 'api-list':
                return $this->variables['documentation'];
            case 'api':
                $model = new Api($this->variables['documentation']);
                return $model->toArray();
        }
    }
}
