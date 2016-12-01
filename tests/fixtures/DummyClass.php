<?php

namespace Flexsounds\Slim\ContainerBuilder\Tests\fixtures;

class DummyClass
{
    private $parameter;

    function __construct($parameter = null)
    {
        $this->parameter = $parameter;
    }

    public function getParameter()
    {
        return $this->parameter;
    }

    public function test()
    {
        return 'hello world';
    }

}
