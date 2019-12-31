<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-swagger for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\ApiTools\Documentation\Swagger\Model;

use Laminas\ApiTools\Documentation\Swagger\Model\ModelGenerator;
use PHPUnit\Framework\TestCase;

class ModelGeneratorTest extends TestCase
{
    /**
     * @var string
     */
    protected $fixturesPath = __DIR__ . '/TestAsset/fixtures/models/';

    /**
     * @var ModelGenerator
     */
    protected $modelGenerator;

    public function setUp()
    {
        $this->modelGenerator = new ModelGenerator();
    }

    private function getFixtureData($inputFileName, $resultFileName)
    {
        $inputPath = $this->fixturesPath . $inputFileName;
        $resultPath = $this->fixturesPath . $resultFileName;
        return [
            file_get_contents($inputPath),
            json_decode(file_get_contents($resultPath), true)
        ];
    }

    public function testShouldBeCreated()
    {
        $this->assertNotNull($this->modelGenerator);
    }

    public function generateInvalidInputDataProvider()
    {
        return [
            ['adfadfadf'],
            [''],
            [null],
            ['{']
        ];
    }

    /**
     * @dataProvider generateInvalidInputDataProvider
     */
    public function testShouldReturnsFalseWithAnInvalidJsonInput($input)
    {
        $swaggerModel = $this->modelGenerator->generate($input);
        $this->assertFalse($swaggerModel);
    }

    public function generateDataProvider()
    {
        return [
            $this->getFixtureData('types-input.json', 'types-result.json'),
            $this->getFixtureData('nested-input.json', 'nested-result.json'),
            $this->getFixtureData('hal-input.json', 'hal-result.json')
        ];
    }

    /**
     * @dataProvider generateDataProvider
     */
    public function testShouldGenerateASwaggerModel($input, $expectedModel)
    {
        $swaggerModel = $this->modelGenerator->generate($input);
        $this->assertEquals($expectedModel, $swaggerModel);
    }
}
