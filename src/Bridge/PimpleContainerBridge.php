<?php

namespace Flexsounds\Slim\ContainerBuilder\Bridge;


use Pimple\Container;

/**
 * Class PimpleContainerBridge
 *
 * @package Flexsounds\Slim\ContainerBuilder\Bridge
 */
class PimpleContainerBridge implements ContainerBridgeInterface
{
    /** @var Container */
    private $container;

    /**
     * PimpleContainerBridge constructor.
     *
     * @param Container|null $container
     */
    public function __construct(Container $container = null)
    {
        if (null === $container) {
            $container = new Container();
        }
        $this->container = $container;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function getParameters()
    {
        return array();
    }

    /**
     * @inheritDoc
     */
    public function set($name, $callable)
    {
        $this->container[$name] = $callable;
    }

    /**
     * @inheritDoc
     */
    public function createNonShared($callable)
    {
        return $this->container->factory($callable);
    }

    /**
     * @inheritDoc
     */
    public function has($name)
    {
        return $this->container->offsetExists($name);
    }

    /**
     * @inheritDoc
     */
    public function get($name)
    {
        return $this->container->offsetGet($name);
    }

}
