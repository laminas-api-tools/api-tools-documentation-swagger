<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-swagger for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\ApiTools\Documentation\Swagger;

use Laminas\ApiTools\Configuration\ModuleUtils;
use Laminas\ApiTools\Documentation\ApiFactory;
use Laminas\ApiTools\Documentation\Swagger\Api;
use Laminas\ApiTools\Provider\ApiToolsProviderInterface;
use Laminas\ModuleManager\ModuleManager;
use PHPUnit\Framework\TestCase;

abstract class BaseApiFactoryTest extends TestCase
{
    /**
     * @var ApiFactory
     */
    protected $apiFactory;

    public function setUp()
    {
        $mockModule = $this->prophesize(ApiToolsProviderInterface::class)->reveal();

        $moduleManager = $this->prophesize(ModuleManager::class);
        $moduleManager->getModules()->willReturn(['Test']);
        $moduleManager->getModule('Test')->willReturn($mockModule);

        $moduleUtils = $this->prophesize(ModuleUtils::class);
        $moduleUtils
            ->getModuleConfigPath('Test')
            ->willReturn(__DIR__ . '/TestAsset/module-config/module.config.php');

        $this->apiFactory = new ApiFactory(
            $moduleManager->reveal(),
            include __DIR__ . '/TestAsset/module-config/module.config.php',
            $moduleUtils->reveal()
        );
        $this->api = new Api($this->apiFactory->createApi('Test', 1));
        parent::setUp();
    }

    protected function getFixture($fixtureFilename)
    {
        $fixturePath = dirname(__FILE__) . '/TestAsset/fixtures/';
        $fixture = file_get_contents($fixturePath . $fixtureFilename);
        return json_decode($fixture, true);
    }

    protected function assertFixture($fixtureFilename, $value)
    {
        $expectedValue = $this->getFixture($fixtureFilename);
        $this->assertEquals($expectedValue, $value);
    }
}
