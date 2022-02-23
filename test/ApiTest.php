<?php

declare(strict_types=1);

namespace LaminasTest\ApiTools\Documentation\Swagger;

use Laminas\ApiTools\Documentation\Swagger\Api;

use function array_key_exists;
use function array_keys;
use function array_values;
use function sort;

class ApiTest extends BaseApiFactoryTest
{
    /** @var Api */
    protected $api;

    /** @var string */
    protected $fixture;

    /** @var array */
    protected $result;

    public function setUp(): void
    {
        parent::setUp();
        $this->api     = new Api($this->apiFactory->createApi('Test', 1));
        $this->fixture = $this->getFixture('swagger2.json');
        $this->result  = $this->api->toArray();
    }

    public function assertEqualsArrays(array $expected, array $actual, string $message = ''): void
    {
        sort($expected);
        sort($actual);
        $this->assertEquals($expected, $actual, $message);
    }

    /** @psalm-param callable(mixed, mixed, string):void $assert */
    public function assertAllFields(string $field, callable $assert): void
    {
        $expectedPaths = $this->fixture['paths'];
        $paths         = $this->result['paths'];
        foreach ($expectedPaths as $expectedPathKey => $expectedPathValue) {
            foreach ($expectedPathValue as $expectedOperationKey => $expectedOperationValue) {
                if (array_key_exists($field, $expectedOperationValue)) {
                    $expected = $expectedOperationValue[$field];
                    $actual   = $paths[$expectedPathKey][$expectedOperationKey][$field];
                    $message  = $expectedPathKey . '-' . $expectedOperationKey;
                    $assert($expected, $actual, $message);
                }
            }
        }
    }

    public function testApiShouldBeCreated(): void
    {
        $this->assertNotNull($this->api);
    }

    public function testApiResultShouldHaveSwaggerVersion(): void
    {
        $this->assertEquals($this->fixture['swagger'], $this->result['swagger']);
    }

    public function testApiResultShouldHaveInfo(): void
    {
        $this->assertEquals($this->fixture['info'], $this->result['info']);
    }

    public function testApiResultShouldHavePaths(): void
    {
        $paths         = array_keys($this->result['paths']);
        $expectedPaths = array_keys($this->fixture['paths']);
        $this->assertEqualsArrays($expectedPaths, $paths);
    }

    public function testApiResultShouldHavePathsWithMethods(): void
    {
        $expectedPaths = $this->fixture['paths'];
        $paths         = $this->result['paths'];
        foreach ($expectedPaths as $expectedPath => $expectedValue) {
            $expectedMethods = array_keys($expectedValue);
            $methods         = array_keys($paths[$expectedPath]);
            $this->assertEqualsArrays($expectedMethods, $methods, $expectedPath);
        }
    }

    public function testApiResultShouldHavePathsWithDescription(): void
    {
        $test = $this;
        $this->assertAllFields('description', function ($expected, $actual, $message) use ($test) {
            $test->assertEquals($expected, $actual, $message);
        });
    }

    public function testApiResultShouldHavePathsWithProduces(): void
    {
        $test = $this;
        $this->assertAllFields('produces', function ($expected, $actual, $message) use ($test) {
            $test->assertEqualsArrays($expected, $actual, $message);
        });
    }

    public function testApiResultShouldHavePathsWithResponses(): void
    {
        $test = $this;
        $this->assertAllFields('responses', function ($expected, $actual, $message) use ($test) {
            $test->assertEqualsArrays(array_keys($expected), array_keys($actual), $message);
            $test->assertEqualsArrays(array_values($expected), array_values($actual), $message);
        });
    }

    public function testApiResultShouldHavePathsWithParameters(): void
    {
        $test = $this;
        $this->assertAllFields('parameters', function ($expected, $actual, $message) use ($test) {
            $test->assertEqualsArrays($expected, $actual, $message);
        });
    }

    public function testApiResultShouldHaveDefinitions(): void
    {
        $this->assertEquals($this->fixture['definitions'], $this->result['definitions']);
    }

    public function testApiResultShouldReturnsExpectedOutput(): void
    {
        $result = $this->api->toArray();
        $this->assertFixture('swagger2.json', $result);
    }
}
