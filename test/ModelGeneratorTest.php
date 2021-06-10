<?php

namespace LaminasTest\ApiTools\Documentation\Swagger\Model;

use Laminas\ApiTools\Documentation\Swagger\Model\ModelGenerator;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function json_decode;

class ModelGeneratorTest extends TestCase
{
    /** @var string */
    protected $fixturesPath = __DIR__ . '/TestAsset/fixtures/models/';

    /** @var ModelGenerator */
    protected $modelGenerator;

    public function setUp(): void
    {
        $this->modelGenerator = new ModelGenerator();
    }

    /** @psalm-return array{0: string, 1: mixed} */
    private function getFixtureData(string $inputFileName, string $resultFileName): array
    {
        $inputPath  = $this->fixturesPath . $inputFileName;
        $resultPath = $this->fixturesPath . $resultFileName;
        return [
            file_get_contents($inputPath),
            json_decode(file_get_contents($resultPath), true),
        ];
    }

    public function testShouldBeCreated()
    {
        $this->assertNotNull($this->modelGenerator);
    }

    /** @psalm-return array<array-key, array{0: null|string}> */
    public function generateInvalidInputDataProvider(): array
    {
        return [
            ['adfadfadf'],
            [''],
            [null],
            ['{'],
        ];
    }

    /**
     * @dataProvider generateInvalidInputDataProvider
     */
    public function testShouldReturnsFalseWithAnInvalidJsonInput(?string $input): void
    {
        $swaggerModel = $this->modelGenerator->generate($input);
        $this->assertFalse($swaggerModel);
    }

    /** @psalm-return array<array-key, array{0: string, 1: mixed}> */
    public function generateDataProvider(): array
    {
        return [
            $this->getFixtureData('types-input.json', 'types-result.json'),
            $this->getFixtureData('nested-input.json', 'nested-result.json'),
            $this->getFixtureData('hal-input.json', 'hal-result.json'),
        ];
    }

    /**
     * @dataProvider generateDataProvider
     * @param mixed $expectedModel
     */
    public function testShouldGenerateASwaggerModel(string $input, $expectedModel): void
    {
        $swaggerModel = $this->modelGenerator->generate($input);
        $this->assertEquals($expectedModel, $swaggerModel);
    }
}
