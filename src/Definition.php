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
    protected $shared = true;


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

        if (isset($serviceConfiguration['shared'])) {
            $definition->setShared($serviceConfiguration['shared']);
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
     * @return boolean
     */
    public function isShared()
    {
        return $this->shared;
    }

    /**
     * @param boolean $shared
     * @return $this
     */
    public function setShared($shared)
    {
        $this->shared = $shared;
        return $this;
    }


}
