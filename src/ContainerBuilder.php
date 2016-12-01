<?php
/**
 * Created by PhpStorm.
 * User: leroy
 * Date: 28/11/2016
 * Time: 13:07
 */

namespace Flexsounds\Slim\ContainerBuilder;


use Interop\Container\ContainerInterface;
use Noodlehaus\Config;
use Slim\Container;

class ContainerBuilder
{
    /** @var ContainerInterface|Container  */
    private $container;
    private $booted = false;
    private $configFiles = array();

    public function __construct(ContainerInterface $container = null)
    {
        if(null == $container){
            $container = new Container();
        }

        $this->container = $container;
    }

    public function loadFiles($files)
    {
        if(!is_array($files)){
            $files = array($files);
        }

        ksort($files);

        $this->configFiles = $files;
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
        $config = $this->loadConfig($this->configFiles);

        if($this->booted){
            return $this->container;
        }

        $this->container['services'] = array();

        $this->parseDefinitions($config);

        $this->build();

        $this->booted = true;

        return $this->container;
    }


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


    /**
     * Dynamically load a configuration file,
     * parse parameters (reusable variables)
     * parse imports (load extra configuration from inside a configuration)
     *
     * @param       $file
     * @param array $parameters
     * @return array|null
     * @throws \Exception
     */
    private function loadConfig($file, &$parameters = array())
    {
        if(is_array($file)){
            $file = reset($file);
        }

        try{
            $file = realpath($file);
            $content = Config::load($file)->all();

            if(isset($content['imports'])){
                foreach($content['imports'] as $import){
                    if($extraContent = $this->loadConfig($import['resource'], $parameters)){
                        $content = array_replace_recursive($extraContent, $content);
                    }
                }
            }
            if(isset($content['parameters'])){
                foreach($content['parameters'] as $param){
                    foreach($param as $key => $value){
                        $parameters[$key] = $value;
                    }
                }
            }

            array_walk_recursive($content, function(&$val, $key) use ($parameters){
                $matches = null;
                preg_match('/\%(.*?)\%/', $val, $matches);
                $param = isset($matches[1]) ? $matches[1] : false;
                if($param){
                    if (isset($parameters[$param])) {
                        $val = str_replace("%$param%", $parameters[$param], $val);
                    }
                }
            });

            return $content;

        }catch(\Exception $e){

            throw $e;

            return array();
        }
    }

}
