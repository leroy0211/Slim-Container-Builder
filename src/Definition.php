<?php

namespace Flexsounds\Slim\ContainerBuilder;

/**
 * This class holds the definition of a service
 *
 * Class Definition
 * @package Flexsounds\Slim\ContainerBuilder
 */
class Definition
{
    /**
     * @var string
     */
    private $class;
    /**
     * @var array
     */
    protected $arguments;
    /**
     * @var bool
     */
    protected $factory = false;


    /**
     * Create a new definition based on configuration
     *
     * @param array $serviceConfiguration
     * @return static
     */
    public static function createDefinition(array $serviceConfiguration)
    {
        $definition = new static();

        if (isset($serviceConfiguration['class'])) {
            $definition->setClass($serviceConfiguration['class']);
        }

        if (isset($serviceConfiguration['arguments'])) {
            $definition->setArguments($serviceConfiguration['arguments']);
        }

        if (isset($serviceConfiguration['factory'])) {
            $definition->setFactory($serviceConfiguration['factory']);
        }

        return $definition;
    }

    /**
     * @param $class
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param array $arguments
     * @return $this
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param $factory
     * @return $this
     */
    public function setFactory($factory)
    {
        $this->factory = (bool)$factory;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFactory()
    {
        return $this->factory;
    }
}
