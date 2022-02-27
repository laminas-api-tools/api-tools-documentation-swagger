<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Documentation\Swagger;

use Laminas\ApiTools\Documentation\Field;
use Laminas\ApiTools\Documentation\Operation;
use Laminas\ApiTools\Documentation\Service as BaseService;
use Laminas\ApiTools\Documentation\Swagger\Model\ModelGenerator;

use function array_filter;
use function array_merge;
use function array_replace_recursive;
use function array_values;
use function in_array;
use function intval;
use function is_array;
use function method_exists;
use function preg_match_all;
use function str_replace;
use function strtolower;

class Service extends BaseService
{
    public const DEFAULT_TYPE = 'string';
    public const ARRAY_TYPE   = 'array';

    /** @var BaseService */
    protected $service;

    /** @var ModelGenerator */
    private $modelGenerator;

    public function __construct(BaseService $service)
    {
        $this->service        = $service;
        $this->modelGenerator = new ModelGenerator();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->cleanEmptyValues([
            'tags'        => $this->getTags(),
            'paths'       => $this->cleanEmptyValues($this->getPaths()),
            'definitions' => $this->getDefinitions(),
        ]);
    }

    private function getTags(): array
    {
        return [
            $this->cleanEmptyValues([
                'name'        => $this->service->getName(),
                'description' => $this->service->getDescription(),
            ]),
        ];
    }

    private function getPaths(): array
    {
        $route = $this->getRouteWithReplacements();
        if ($this->isRestService()) {
            return $this->getRestPaths($route);
        }
        return $this->getOtherPaths($route);
    }

    private function getRouteWithReplacements(): string
    {
        // routes and parameter mangling ([:foo] will become {foo}
        $search  = ['[', ']', '{/', '{:'];
        $replace = ['{', '}', '/{', '{'];
        return str_replace($search, $replace, $this->service->route);
    }

    private function isRestService(): bool
    {
        return ! empty($this->service->routeIdentifierName);
    }

    /**
     * @psalm-return array<string, array>
     */
    private function getRestPaths(string $route): array
    {
        $entityOperations     = $this->getEntityOperationsData($route);
        $collectionOperations = $this->getCollectionOperationsData($route);
        $collectionPath       = str_replace('/{' . $this->service->routeIdentifierName . '}', '', $route);
        if ($collectionPath === $route) {
            return [
                $collectionPath => array_merge($collectionOperations, $entityOperations),
            ];
        }
        return [
            $collectionPath => $collectionOperations,
            $route          => $entityOperations,
        ];
    }

    /** @psalm-return array<string, array> */
    private function getOtherPaths(string $route): array
    {
        $operations = $this->getOtherOperationsData($route);
        return [$route => $operations];
    }

    private function getEntityOperationsData(string $route): array
    {
        $urlParameters = $this->getURLParametersRequired($route);
        $operations    = $this->service->getEntityOperations();
        return $this->getOperationsData($operations, $urlParameters);
    }

    private function getCollectionOperationsData(string $route): array
    {
        $urlParameters = $this->getURLParametersNotRequired($route);
        unset($urlParameters[$this->service->routeIdentifierName]);
        $operations = $this->service->operations;
        return $this->getOperationsData($operations, $urlParameters);
    }

    private function getOtherOperationsData(string $route): array
    {
        $urlParameters = $this->getURLParametersRequired($route);
        $operations    = $this->service->operations;
        return $this->getOperationsData($operations, $urlParameters);
    }

    private function getOperationsData(array $operations, array $urlParameters): array
    {
        $operationsData = [];
        foreach ($operations as $operation) {
            $method     = $this->getMethodFromOperation($operation);
            $parameters = array_values($urlParameters);
            if ($this->isMethodPostPutOrPatch($method)) {
                $parameters[] = $this->getPostPatchPutBodyParameter();
            }
            $pathOperation           = $this->getPathOperation($operation, $parameters);
            $operationsData[$method] = $pathOperation;
        }
        return $operationsData;
    }

    private function getURLParametersRequired(string $route): array
    {
        return $this->getURLParameters($route, true);
    }

    private function getURLParametersNotRequired(string $route): array
    {
        return $this->getURLParameters($route, false);
    }

    private function getURLParameters(string $route, bool $required): array
    {
        // find all parameters in Swagger naming format
        preg_match_all('#{([\w\d_-]+)}#', $route, $parameterMatches);

        $templateParameters = [];
        foreach ($parameterMatches[1] as $paramSegmentName) {
            $templateParameters[$paramSegmentName] = [
                'in'          => 'path',
                'name'        => $paramSegmentName,
                'description' => 'URL parameter ' . $paramSegmentName,
                'type'        => 'string',
                'required'    => $required,
            ];
        }
        return $templateParameters;
    }

    /** @psalm-return array<string, mixed> */
    private function getPostPatchPutBodyParameter(): array
    {
        return [
            'in'       => 'body',
            'name'     => 'body',
            'required' => true,
            'schema'   => [
                '$ref' => '#/definitions/' . $this->service->getName(),
            ],
        ];
    }

    private function isMethodPostPutOrPatch(string $method): bool
    {
        return in_array(strtolower($method), ['post', 'put', 'patch']);
    }

    private function getMethodFromOperation(Operation $operation): string
    {
        return strtolower($operation->getHttpMethod());
    }

    private function getPathOperation(Operation $operation, array $parameters): array
    {
        return $this->cleanEmptyValues([
            'tags'        => [$this->service->getName()],
            'description' => $operation->getDescription(),
            'parameters'  => $parameters,
            'produces'    => $this->service->getRequestAcceptTypes(),
            'responses'   => $this->getResponsesFromOperation($operation),
        ]);
    }

    private function getResponsesFromOperation(Operation $operation): array
    {
        $responses           = [];
        $responseStatusCodes = $operation->getResponseStatusCodes();
        foreach ($responseStatusCodes as $responseStatusCode) {
            $code             = intval($responseStatusCode['code']);
            $responses[$code] = $this->cleanEmptyValues([
                'description' => $responseStatusCode['message'],
                'schema'      => $this->getResponseSchema($operation, $code),
            ]);
        }
        return $responses;
    }

    /**
     * @return null|array<array-key, mixed> If the return code is neither 200 or
     *     201, returns null. Otherwise, it retrieves the response description,
     *     passes it to the model generator, and uses the returned value.
     */
    private function getResponseSchema(Operation $operation, int $code): ?array
    {
        if ($code === 200 || $code === 201) {
            $schema = $this->modelGenerator->generate($operation->getResponseDescription());

            return $schema === false ? null : $schema;
        }

        return null;
    }

    private function getDefinitions(): array
    {
        if (! $this->serviceContainsPostPutOrPatchMethod()) {
            return [];
        }
        $modelFromFields          = $this->getModelFromFields();
        $modelFromPostDescription = $this->getModelFromFirstPostDescription();
        $model                    = array_replace_recursive($modelFromFields, $modelFromPostDescription);
        return [$this->service->getName() => $model];
    }

    private function serviceContainsPostPutOrPatchMethod(): bool
    {
        foreach ($this->getAllOperations() as $operation) {
            $method = $this->getMethodFromOperation($operation);
            if ($this->isMethodPostPutOrPatch($method)) {
                return true;
            }
        }
        return false;
    }

    private function getModelFromFields(): array
    {
        $required = $properties = [];

        foreach ($this->getFieldsForDefinitions() as $field) {
            if (! $field instanceof Field) {
                continue;
            }

            $properties[$field->getName()] = $this->getFieldProperties($field);
            if ($field->isRequired()) {
                $required[] = $field->getName();
            }
        }

        return $this->cleanEmptyValues([
            'type'       => 'object',
            'properties' => $properties,
            'required'   => $required,
        ]);
    }

    private function getModelFromFirstPostDescription(): array
    {
        $firstPostDescription = $this->getFirstPostRequestDescription();
        if (! $firstPostDescription) {
            return [];
        }
        return $this->modelGenerator->generate($firstPostDescription) ?: [];
    }

    /**
     * @return null|mixed Returns null if no POST operations are discovered;
     *     otherwise, returns the request description from the first POST
     *     operation discovered.
     */
    private function getFirstPostRequestDescription()
    {
        foreach ($this->getAllOperations() as $operation) {
            $method = $this->getMethodFromOperation($operation);
            if ($method === 'post') {
                return $operation->getRequestDescription();
            }
        }
        return null;
    }

    /**
     * @return Field|Field[]
     * @psalm-return Field|array<array-key, Field>
     */
    private function getFieldsForDefinitions()
    {
        // Fields are part of the default input filter when present.
        $fields = $this->service->fields;
        if (isset($fields['input_filter'])) {
            $fields = $fields['input_filter'];
        }
        return $fields;
    }

    private function getFieldProperties(Field $field): array
    {
        $type               = $this->getFieldType($field);
        $properties         = [];
        $properties['type'] = $type;
        if ($type === self::ARRAY_TYPE) {
            $properties['items'] = ['type' => self::DEFAULT_TYPE];
        }
        $properties['description'] = $field->getDescription();
        return $this->cleanEmptyValues($properties);
    }

    private function getFieldType(Field $field): string
    {
        return method_exists($field, 'getFieldType') && ! empty($field->getFieldType())
            ? $field->getFieldType()
            : self::DEFAULT_TYPE;
    }

    private function getAllOperations(): array
    {
        $entityOperations = $this->service->getEntityOperations();
        if (is_array($entityOperations)) {
            return array_merge($this->service->getOperations(), $this->service->getEntityOperations());
        }
        return $this->service->getOperations();
    }

    /**
     * @return array $data omitting empty values
     */
    private function cleanEmptyValues(array $data): array
    {
        return array_filter($data, function ($item) {
            return ! empty($item);
        });
    }
}
