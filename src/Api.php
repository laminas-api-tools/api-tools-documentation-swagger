<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-swagger for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\Swagger;

use Laminas\ApiTools\Documentation\Api as BaseApi;

class Api extends BaseApi
{
    /**
     * @var BaseApi
     */
    protected $api;

    /**
     * @param BaseApi $api
     */
    public function __construct(BaseApi $api)
    {
        $this->api = $api;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $services = array();
        foreach ($this->api->services as $service) {
            $services[] = array(
                'description' => ($description = $service->getDescription())
                ? $description
                : 'Operations for ' . $service->getName(),
                'path' => '/' . $service->getName()
            );
        }

        return array(
            'apiVersion' => $this->api->version,
            'swaggerVersion' => '1.2',
            /*
            'basePath' => '/api',
            'resourcePath' => '/' . $this->api->name,
            */
            'apis' => $services,
        );
    }
}
