<?php 

require_once("vendor/autoload.php");

$containerBuilder = new \Flexsounds\Slim\ContainerBuilder\ContainerBuilder();
$containerBuilder->

$slim = new Slim\App($containerBuilder->getContainer());


$slim->get('/', function($request, $response){

    $a = $this->has('fiets');

    var_dump($a);

});

$slim->run();