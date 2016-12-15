<?php

namespace Flexsounds\Slim\ContainerBuilder;


use Flexsounds\Slim\ContainerBuilder\Bridge\ContainerBridgeInterface;
use Flexsounds\Slim\ContainerBuilder\Loader\LoaderInterface;
use Interop\Container\ContainerInterface;
use Slim\Container;

/**
 * Class ContainerBuilder
 *
 * @package Flexsounds\Slim\ContainerBuilder
 */
class ContainerBuilder
{

    /** @var ContainerBridgeInterface */
    private $containerBridge;

    /** @var bool */
    private $booted = false;

    /** @var LoaderInterface */
    private $loader;

    /**
     * ContainerBuilder constructor.
     *
     * @param ContainerBridgeInterface $containerBridge
     */
    public function __construct(ContainerBridgeInterface $containerBridge)
    {
        $this->containerBridge = $containerBridge;
    }

    /**
     * @param LoaderInterface $loader
     * @return $this
     */
    public function setLoader(LoaderInterface $loader)
    {
        $this->loader = $loader;
        return $this;
    }

    /**
     * Load the container with generated services
     *
     * @return ContainerInterface|Container
     * @throws \Exception
     */
    public function getContainer()
    {
        if($this->booted){
            return $this->containerBridge->getContainer();
        }

        if($this->loader instanceof LoaderInterface){
            $containerParameters = $this->containerBridge->getParameters();
            $config = $this->loader->load(null, $containerParameters);

            $this->containerBridge->set('services', array());

            $this->parseDefinitions($config);
        }

        $this->build();

        $this->booted = true;

        return $this->containerBridge->getContainer();
    }


    /**
     * Parse the service definitions from the configuration
     * @param $config
     */
    private function parseDefinitions($config)
    {
        if(!isset($config['services'])){
            return;
        }

        $services = array();

        foreach($config['services'] as $id => $serviceConfiguration){
            $services[$id] = Definition::createDefinition($serviceConfiguration);
        }

        $this->containerBridge->set('services', $services);
    }


    /**
     * Try to build the container
     */
    private function build()
    {
        if($this->containerBridge->has('services')){
            /** @var Definition $serviceConf */
            foreach ($this->containerBridge->get('services') as $serviceName => $serviceConf) {
                $serviceCallback = function () use ($serviceConf) {
                    $class  = new \ReflectionClass($serviceConf->getClass());
                    $params = [];
                    foreach ((array)$serviceConf->getArguments() as $argument) {
                        $params[] = $this->decodeArgument($argument);
                    }
                    return $class->newInstanceArgs($params);
                };

                if(!$serviceConf->isShared()){
                    $serviceCallback = $this->containerBridge->createNonShared($serviceCallback);
                }

                $this->containerBridge->set($serviceName, $serviceCallback);
            }
        }
    }

    /**
     * @param $value
     * @return mixed
     */
    private function decodeArgument($value)
    {
        if (is_string($value)) {
            if (0 === strpos($value, '@')) {
                $value = $this->containerBridge->get(substr($value, 1));
            } elseif (0 === strpos($value, '%')) {
                $value = $this->containerBridge->get(substr($value, 1, -1));
            }
        }
        if(is_array($value)){
            foreach($value as $k => $v){
                $value[$k] = $this->decodeArgument($v);
            }
        }
        return $value;
    }

}
