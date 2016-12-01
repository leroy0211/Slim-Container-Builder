<?php

namespace Flexsounds\Slim\ContainerBuilder\Tests;


use Flexsounds\Slim\ContainerBuilder\Definition;

class DefinitionTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateDefinition()
    {
        try{
            Definition::createDefinition();
            $this->fail("Cannot create a definition without options");
        }catch (\Exception $e){
            // Exception thrown, so all is OK!
        }
    }

    public function testCreateInstance()
    {
        $definition = new Definition();

        $definition->setClass('\Location\To\A\Class');

        $this->assertEquals('\Location\To\A\Class', $definition->getClass());
    }


}