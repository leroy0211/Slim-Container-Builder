<?php

namespace Flexsounds\Slim\ContainerBuilder;

class Definition
{
    /**
     * @var
     */
    private $class;
    /**
     * @var
     */
    protected $arguments;
    /**
     * @var bool
     */
    protected $factory = false;


    public static function createDefinition($serviceConfiguration)
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
        $this->factory = (bool) $factory;
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