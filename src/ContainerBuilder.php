<?php

namespace Flexsounds\Slim\ContainerBuilder;


use Flexsounds\Slim\ContainerBuilder\Loader\LoaderInterface;
use Interop\Container\ContainerInterface;
use Slim\Container;

class ContainerBuilder
{
    /** @var ContainerInterface|Container  */
    private $container;
    private $booted = false;

    /** @var LoaderInterface */
    private $loader;

    public function __construct(ContainerInterface $container = null)
    {
        if(null == $container){
            $container = new Container();
        }

        $this->container = $container;
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
        $config = $this->loader->load(null, $this->container->settings);

        if($this->booted){
            return $this->container;
        }

        $this->container['services'] = array();

        $this->parseDefinitions($config);

        $this->build();

        $this->booted = true;

        return $this->container;
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

        $this->container['services'] = $services;
    }


    /**
     * Try to build the container
     */
    private function build()
    {
        if($this->container->has('services')){
            foreach ($this->container->get('services') as $serviceName => $serviceConf) {
                $serviceCallback = function () use ($serviceConf) {
                    $class  = new \ReflectionClass($serviceConf->getClass());
                    $params = [];
                    foreach ((array)$serviceConf->getArguments() as $argument) {
                        $params[] = $this->decodeArgument($argument);
                    }
                    return $class->newInstanceArgs($params);
                };

                if ($serviceConf->isFactory()) {
                    $this->container[$serviceName] = $this->container->factory($serviceCallback);
                } else {
                    $this->container[$serviceName] = $serviceCallback;
                }
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
                $value = $this->container[substr($value, 1)];
            } elseif (0 === strpos($value, '%')) {
                $value = $this->container[substr($value, 1, -1)];
            }
        }
        return $value;
    }

}