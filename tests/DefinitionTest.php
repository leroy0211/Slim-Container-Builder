<?php

namespace Flexsounds\Slim\ContainerBuilder\Tests;

use Flexsounds\Slim\ContainerBuilder\Definition;

class DefinitionTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateDefinition()
    {
        $definition = Definition::createDefinition(array());
        $this->assertEquals(null, $definition->getClass());
    }

    public function testCreateInstance()
    {
        $definition = new Definition();
        $this->assertEquals($definition, $definition->setClass('\Location\To\A\Class'));
        $this->assertEquals('\Location\To\A\Class', $definition->getClass());

        $this->assertEquals($definition, $definition->setArguments(array('John' => 'Doe')));
        $this->assertEquals(array(
            'John' => 'Doe'
        ), $definition->getArguments());

        $this->assertEquals($definition, $definition->setFactory('1'));
        $this->assertEquals(true, $definition->isFactory());
    }

}