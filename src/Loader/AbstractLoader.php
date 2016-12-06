<?php

namespace Flexsounds\Slim\ContainerBuilder\Loader;


/**
 * Class AbstractLoader
 * @package Flexsounds\Slim\ContainerBuilder\Loader
 */
abstract class AbstractLoader implements LoaderInterface
{

    /**
     * Parse parameters from the content
     *
     * @param $content
     * @param $parameters
     */
    protected function parseParameters($content, &$parameters)
    {
        if (isset($content['parameters'])) {
            foreach ($content['parameters'] as $param) {
                foreach ($param as $key => $value) {
                    $parameters[$key] = $value;
                }
            }
        }
    }

}