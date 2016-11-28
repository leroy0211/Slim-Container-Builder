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
    private $container;
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
    }




    public function getContainer()
    {




        return $this->container;
    }




    private function loadYaml($env, &$parameters = array())
    {







        $env = pathinfo($env, PATHINFO_FILENAME);

        try{
            $file = realpath(__DIR__. "/copydb/".$env.".yml");
            $content = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($file));

            if(isset($content['imports'])){
                foreach($content['imports'] as $import){
                    if($extraContent = $this->loadYaml($import['resource'], $parameters)){
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

        }catch(\Symfony\Component\Yaml\Exception\ParseException $e){
            $this->displayError(sprintf("Unable to parse the YAML string: %s", $e->getMessage()));
            return array();
        }
    }

}
