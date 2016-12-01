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
    private $class;
    private $configuration;
    private $parameters = array();

    function __invoke()
    {
        return $this->createService();
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
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     */
    public function setClass($class)
    {
        $this->class = $class;
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
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param mixed $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }


    /**
     * @param $container
     * @param $name
     * @param $configuration
     * @return static
     * @throws \Exception
     */
    public static function createDefinition($container, $name, $configuration)
    {
        $definition = new static();
        $definition->setName($name);
        if(!isset($configuration['class'])){
            throw new \Exception("Cannot create a service without a class definition");
        }
        $definition->setClass($configuration['class']);
        $definition->setConfiguration($configuration);
        $definition->setContainer($container);
        if(isset($configuration['arguments'])){
            $definition->setParameters($configuration['arguments']);
        }
        return $definition;
    }

    /**
     * Create a single service based on some array configuration
     *
     * @param $serviceConfiguration
     * @return object
     * @throws \Exception
     */
    private function createService()
    {
        $objectReflection = new \ReflectionClass($this->getClass());

        $object = null === $objectReflection->getConstructor() ? $objectReflection->newInstance() : $objectReflection->newInstanceArgs($this->getParameters());

//        if(isset($serviceConfiguration['arguments'])){
//
//            $arguments = $serviceConfiguration['arguments'];
//
//            var_dump($arguments);
//            die;
//
//            foreach($arguments as $argumentKey => $argument){
//                if(preg_match('/\@(.*?)/', $argument, $matches)){
//                    if($this->container->has($matches[1])){
//                        $arguments[$argumentKey] = $this->container->get($matches[1]);
//                    }
//                }
//            }
//
//            $object = $objectReflection->newInstanceArgs($arguments ?: null);
//        }else{
//            $object = $objectReflection->newInstance();
//        }

        return $object;
    }

}