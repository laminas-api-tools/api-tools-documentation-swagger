<?php

namespace LaminasTest\ApiTools\Documentation\Swagger;

use Laminas\ApiTools\Configuration\ModuleUtils;
use Laminas\ApiTools\Documentation\ApiFactory;
use Laminas\ApiTools\Documentation\Swagger\Api;
use Laminas\ApiTools\Provider\ApiToolsProviderInterface;
use Laminas\ModuleManager\ModuleManager;
use PHPUnit\Framework\TestCase;

use function dirname;
use function file_get_contents;
use function json_decode;

// phpcs:ignore WebimpressCodingStandard.NamingConventions.AbstractClass.Prefix
abstract class BaseApiFactoryTest extends TestCase
{
    /** @var ApiFactory */
    protected $apiFactory;

    public function setUp(): void
    {
        $mockModule = $this->createMock(ApiToolsProviderInterface::class);

        $moduleManager = $this->createMock(ModuleManager::class);
        $moduleManager->method('getModules')->willReturn(['Test']);
        $moduleManager->method('getModule')->with('Test')->willReturn($mockModule);

        $moduleUtils = $this->createMock(ModuleUtils::class);
        $moduleUtils
            ->method('getModuleConfigPath')
            ->with('Test')
            ->willReturn(__DIR__ . '/TestAsset/module-config/module.config.php');

        $this->apiFactory = new ApiFactory(
            $moduleManager,
            include __DIR__ . '/TestAsset/module-config/module.config.php',
            $moduleUtils
        );
        $this->api        = new Api($this->apiFactory->createApi('Test', 1));
        parent::setUp();
    }

    /** @return mixed */
    protected function getFixture(string $fixtureFilename)
    {
        $fixturePath = dirname(__FILE__) . '/TestAsset/fixtures/';
        $fixture     = file_get_contents($fixturePath . $fixtureFilename);
        return json_decode($fixture, true);
    }

    /** @param mixed $value */
    protected function assertFixture(string $fixtureFilename, $value): void
    {
        $expectedValue = $this->getFixture($fixtureFilename);
        $this->assertEquals($expectedValue, $value);
    }
}
