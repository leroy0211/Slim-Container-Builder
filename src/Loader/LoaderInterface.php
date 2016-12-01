<?php


namespace Flexsounds\Slim\ContainerBuilder\Loader;


interface LoaderInterface
{

    public function load($resource, &$parameters);

}