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
    private $api;

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
        $output = [
            'swagger' => '2.0',
            'info' => [
                'title' => $this->api->getName(),
                'version' => (string) $this->api->getVersion()
            ]
        ];

        foreach ($this->api->services as $service) {
            $outputService = new Service($service);
            $output = array_merge_recursive($output, $outputService->toArray());
        }

        return $output;
    }
}
