<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-swagger for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/LICENSE.md New BSD License
 */

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
    /**
     * @var SwaggerUiControllerFactory
     */
    protected $factory;

    /**
     * @var ServiceManager
     */
    protected $services;

    protected function setUp()
    {
        $this->factory = new SwaggerUiControllerFactory();
        $this->services = $services = new ServiceManager();
    }

    /**
     * @expectedException \Laminas\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testExceptionThrownOnMissingApiCreatorClass()
    {
        $smFactory = $this->factory;
        $this->expectException(ServiceNotCreatedException::class);
        $factory = $smFactory($this->services, SwaggerUiController::class);
    }

    public function testCreatesServiceWithDefaults()
    {
        $mockModule = $this->prophesize(ApiToolsProviderInterface::class)->reveal();

        $moduleManager = $this->prophesize(ModuleManager::class);
        $moduleManager->getModules()->willReturn(['Test']);
        $moduleManager->getModule('Test')->willReturn($mockModule);
        $moduleUtils = $this->prophesize(ModuleUtils::class);
        $moduleUtils
            ->getModuleConfigPath('Test')
            ->willReturn([]);

        $apiFactory = new ApiFactory(
            $moduleManager->reveal(),
            [],
            $moduleUtils->reveal()
        );

        $this->services->setService(ApiFactory::class, $apiFactory);

        /** @var SwaggerUiControllerFactory $service */
        $smFactory = $this->factory;
        $this->assertInstanceOf(SwaggerUiControllerFactory::class, $smFactory);

        $controller = $smFactory($this->services, SwaggerUiController::class);
        $this->assertInstanceOf(SwaggerUiController::class, $controller);
    }
}
