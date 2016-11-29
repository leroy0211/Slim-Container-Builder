<?php

namespace Flexsounds\Slim\ContainerBuilder;


class Dummy
{

    private $test;

    public function __construct($string = "string")
    {
        $this->test = $string;
    }


    public function test()
    {
        return $this->test;
    }

}