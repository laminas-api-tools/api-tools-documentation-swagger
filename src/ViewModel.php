<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-swagger for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\Swagger;

use Laminas\ApiTools\ContentNegotiation\JsonModel;

class ViewModel extends JsonModel
{
    /**
     * @return array|\Traversable
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
