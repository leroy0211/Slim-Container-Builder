<?php

namespace Flexsounds\Slim\ContainerBuilder\Bridge;


use Slim\Container;

/**
 * Class SlimFrameworkContainerBridge
 *
 * @package Flexsounds\Slim\ContainerBuilder\Bridge
 * @method Container getContainer()
 */
class SlimFrameworkContainerBridge extends PimpleContainerBridge
{

    /**
     * SlimFrameworkContainerBridge constructor.
     *
     * @param \Pimple\Container|null $container
     */
    public function __construct(\Pimple\Container $container = null)
    {
        if (null === $container) {
            $container = new Container();
        }

        parent::__construct($container);
    }


    /**
     * @inheritDoc
     */
    public function getParameters()
    {
        $container = $this->getContainer();
        return $container['settings'];
    }

}
