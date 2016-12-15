<?php


namespace Flexsounds\Slim\ContainerBuilder\Bridge;


/**
 * Interface ContainerBridgeInterface
 *
 * @package Flexsounds\Slim\ContainerBuilder\Bridge
 */
interface ContainerBridgeInterface
{

    /**
     * Return the container
     *
     * @return mixed
     */
    public function getContainer();

    /**
     * Return an array of parameters
     *
     * @return array
     */
    public function getParameters();

    /**
     * Create a service in the container
     *
     * @param $name
     * @param $callable
     * @return mixed
     */
    public function set($name, $callable);

    /**
     * Create a non-shared service
     *
     * @param $callable
     * @return mixed
     */
    public function createNonShared($callable);

    /**
     * Validates if a service already exists
     *
     * @param $name
     * @return mixed
     */
    public function has($name);

    /**
     * Returns a service by it's name
     *
     * @param $name
     * @return mixed
     */
    public function get($name);

}
