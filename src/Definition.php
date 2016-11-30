<?php
/**
 * Created by PhpStorm.
 * User: leroy
 * Date: 30/11/2016
 * Time: 13:40
 */

namespace Flexsounds\Slim\ContainerBuilder;


use Slim\Container;

class Definition
{

    private $name;
    /** @var  Container */
    private $container;
    private $configuration;

    function __invoke()
    {
        return $this->createService($this->configuration);
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param mixed $configuration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param mixed $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }


    /**
     * @param $name
     * @param $configuration
     * @return static
     */
    public static function createDefinition($container, $name, $configuration)
    {
        $definition = new static();
        $definition->setName($name);
        $definition->setConfiguration($configuration);
        $definition->setContainer($container);
        return $definition;
    }

    /**
     * Create a single service based on some array configuration
     *
     * @param $serviceConfiguration
     * @return object
     * @throws \Exception
     */
    private function createService($serviceConfiguration)
    {
        if(!isset($serviceConfiguration['class'])){
            throw new \Exception("Cannot create a service without a class definition");
        }

        $objectReflection = new \ReflectionClass($serviceConfiguration['class']);
        if(isset($serviceConfiguration['arguments'])){

            $arguments = $serviceConfiguration['arguments'];

            var_dump($arguments);
            die;

            foreach($arguments as $argumentKey => $argument){
                if(preg_match('/\@(.*?)/', $argument, $matches)){
                    if($this->container->has($matches[1])){
                        $arguments[$argumentKey] = $this->container->get($matches[1]);
                    }
                }
            }

            $object = $objectReflection->newInstanceArgs($arguments ?: null);
        }else{
            $object = $objectReflection->newInstance();
        }

        return $object;
    }

}