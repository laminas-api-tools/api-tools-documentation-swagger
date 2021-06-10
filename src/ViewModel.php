<?php

namespace Laminas\ApiTools\Documentation\Swagger;

use ArrayAccess;
use Laminas\ApiTools\ContentNegotiation\JsonModel;
use Traversable;

class ViewModel extends JsonModel
{
    /**
     * @return array|Traversable|ArrayAccess
     */
    public function getVariables()
    {
        if (empty($this->variables['type'] || ! $this->variables->offsetExists('type'))) {
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
