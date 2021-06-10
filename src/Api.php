<?php

namespace Laminas\ApiTools\Documentation\Swagger;

use Laminas\ApiTools\Documentation\Api as BaseApi;

use function array_merge_recursive;

class Api extends BaseApi
{
    /** @var BaseApi */
    private $api;

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
            'info'    => [
                'title'   => $this->api->getName(),
                'version' => (string) $this->api->getVersion(),
            ],
        ];

        foreach ($this->api->services as $service) {
            $outputService = new Service($service);
            $output        = array_merge_recursive($output, $outputService->toArray());
        }

        return $output;
    }
}
