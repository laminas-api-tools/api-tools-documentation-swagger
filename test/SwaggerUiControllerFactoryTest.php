<?php

namespace LaminasTest\ApiTools\Documentation\Swagger;

use Laminas\ApiTools\Configuration\ModuleUtils;
use Laminas\ApiTools\Documentation\ApiFactory;
use Laminas\ApiTools\Documentation\Swagger\SwaggerUiController;
use Laminas\ApiTools\Documentation\Swagger\SwaggerUiControllerFactory;
use Laminas\ApiTools\Provider\ApiToolsProviderInterface;
use Laminas\ModuleManager\ModuleManager;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;

class SwaggerUiControllerFactoryTest extends TestCase
{
    /** @var SwaggerUiControllerFactory */
    protected $factory;

    /** @var ServiceManager */
    protected $services;

    protected function setUp(): void
    {
        $this->factory  = new SwaggerUiControllerFactory();
        $this->services = new ServiceManager();
    }

    public function testExceptionThrownOnMissingApiCreatorClass(): void
    {
        $smFactory = $this->factory;
        $this->expectException(ServiceNotCreatedException::class);
        $smFactory($this->services, SwaggerUiController::class);
    }

    public function testCreatesServiceWithDefaults(): void
    {
        $mockModule = $this->createMock(ApiToolsProviderInterface::class);

        $moduleManager = $this->createMock(ModuleManager::class);
        $moduleManager->method('getModules')->willReturn(['Test']);
        $moduleManager->method('getModule')->with('Test')->willReturn($mockModule);
        $moduleUtils = $this->createMock(ModuleUtils::class);
        $moduleUtils->method('getModuleConfigPath')->with('Test')->willReturn([]);

        $apiFactory = new ApiFactory(
            $moduleManager,
            [],
            $moduleUtils
        );

        $this->services->setService(ApiFactory::class, $apiFactory);

        /** @var SwaggerUiControllerFactory $smFactory */
        $smFactory = $this->factory;
        $this->assertInstanceOf(SwaggerUiControllerFactory::class, $smFactory);

        $controller = $smFactory($this->services, SwaggerUiController::class);
        $this->assertInstanceOf(SwaggerUiController::class, $controller);
    }
}
