<?php

require_once("vendor/autoload.php");

$containerBuilder = new \Flexsounds\Slim\ContainerBuilder\ContainerBuilder();
$containerBuilder->setLoader(new \Flexsounds\Slim\ContainerBuilder\Loader\FileLoader('./config.yml'));

$slim = new Slim\App($containerBuilder->getContainer());


$slim->get('/', function($request, $response){

    $fiets = $this->get('john');
    echo '<pre>';
    var_dump($fiets);
});

$slim->run();