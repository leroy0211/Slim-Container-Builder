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
    /** @var Definition[] */
    private $definitions = array();

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

        $this->generateServices($config);


//        var_dump($this->container->has('fiets'));
        die;



        $this->booted = true;

        return $this->container;
    }

    /**
     * Generate a set of services based on configuration
     *
     * @param $config
     * @throws \Exception
     */
    private function generateServices(array $config)
    {
        if(isset($config['services'])){

            foreach($config['services'] as $servicename => $serviceConfiguration){

                if($this->container->has($servicename)){
                    throw new \Exception(sprintf("Service with servicename '%s' already exists", $servicename));
                }
//                $this->container[$servicename] = function() use ($serviceConfiguration){
//                    return call_user_func(array($this, 'createService'), $serviceConfiguration);
//                };
                $this->container[$servicename] = Definition::createDefinition($this->container, $servicename, $serviceConfiguration);
            }
        }
    }




    /**
     * Dynamically load a configuration file,
     * parse parameters (reusable variables)
     * parse imports (load extra configuration from inside a configuration)
     *
     * @param $file
     * @param array $parameters
     * @return array|null
     */
    private function loadConfig($file, &$parameters = array())
    {
        if(is_array($file)){
            $file = reset($file);
        }

        try{
            $file = realpath($file);
            $content = Config::load($file)->all();

            var_dump($content);
            die;

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
