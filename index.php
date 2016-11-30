<?php

require_once("vendor/autoload.php");

$containerBuilder = new \Flexsounds\Slim\ContainerBuilder\ContainerBuilder();

$containerBuilder->loadFiles(__DIR__. "/config.yml");
;

$slim = new Slim\App($containerBuilder->getContainer());


$slim->get('/', function($request, $response){

    $fiets = $this->get('fiets');

    var_dump($fiets);

//    echo $fiets->test();

//    $john = $this->get('john');
//    echo $john->test();

});

$slim->run();