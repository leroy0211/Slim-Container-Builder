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

    private function getContainerFromFile()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->setLoader(new FileLoader(__DIR__. '/fixtures/config.yml'));

        $container = $containerBuilder->getContainer();
        return $container;
    }


    public function testContainerBuilderToLoadServiceFromFile()
    {
        $container = $this->getContainerFromFile();

        $this->assertInstanceOf(DummyClass::class, $container->get('test.hello.world'));
    }

    public function testContainerBuilderToLoadCustomServiceInArgument()
    {
        $container = $this->getContainerFromFile();

        $this->assertInstanceOf(DummyClass::class, $dummyClassService = $container->get('test.argument.with.service'));
        $this->assertInstanceOf(DummyClass::class, $dummyClassService->getParameter());
    }

    public function testContainerBuilderToLoadSlimServiceInArgument()
    {
        $container = $this->getContainerFromFile();

        $this->assertInstanceOf(DummyClass::class, $dummyClassSlimService = $container->get('test.argument.with.slim.service'));
        $this->assertInstanceOf(Collection::class, $dummyClassSlimService->getParameter());
    }

}
