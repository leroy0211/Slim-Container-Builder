<?php

namespace Flexsounds\Slim\ContainerBuilder\Tests;

use Flexsounds\Slim\ContainerBuilder\ContainerBuilder;
use Flexsounds\Slim\ContainerBuilder\Loader\FileLoader;
use Flexsounds\Slim\ContainerBuilder\Tests\fixtures\DummyClass;
use Slim\Collection;
use Slim\Container;

class ContainerBuilderTest extends \PHPUnit_Framework_TestCase
{

    public function testContainerBuilderWithoutAnything()
    {
        $containerBuilder = new ContainerBuilder();
        $this->assertInstanceOf('Interop\Container\ContainerInterface', $containerBuilder->getContainer());
        $this->assertInstanceOf('Slim\Container', $containerBuilder->getContainer());
    }

    public function testContainerBuilderToCreateAService()
    {
        $containerBuilder = new ContainerBuilder();

        $loader = $this->getMockBuilder(FileLoader::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $loader->expects($this->once())
            ->method('load')
            ->willReturn(array(
                'services' => array(
                    'test.hello.world' => array(
                        'class' => DummyClass::class
                    )
                )
            ))
        ;
        $containerBuilder->setLoader($loader);
        $container = $containerBuilder->getContainer();
        $this->assertInstanceOf(DummyClass::class, $container->get('test.hello.world'));
    }

    private function getContainerFromFile($file = 'config.yml')
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->setLoader($loader = new FileLoader(__DIR__. '/fixtures/'));

        $loader->addFile($file);

        $container = $containerBuilder->getContainer();
        return $container;
    }

    public function testFilebasedContainerBuilder()
    {
        $container = $this->getContainerFromFile('config.yml');
        $this->verifyContainerStructure($container);
    }

    public function testContainerImports()
    {
        $container = $this->getContainerFromFile('configimport.yml');
        $this->verifyContainerStructure($container);
    }

    public function testRecursiveContainerImports()
    {
        $container = $this->getContainerFromFile('configrecursiveimport.yml');
        $this->verifyContainerStructure($container);
    }

    private function verifyContainerStructure(Container $container)
    {


        $this->assertInstanceOf(DummyClass::class, $container->get('test.hello.world'));
        $this->assertInstanceOf(DummyClass::class, $dummyClassService = $container->get('test.argument.with.service'));
        $this->assertInstanceOf(DummyClass::class, $dummyClassService->getParameter());

        $this->assertInstanceOf(DummyClass::class, $dummyClassSlimService = $container->get('test.argument.with.slim.service'));
        $this->assertInstanceOf(Collection::class, $dummyClassSlimService->getParameter());
    }

}
