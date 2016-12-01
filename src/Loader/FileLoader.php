<?php

namespace Flexsounds\Slim\ContainerBuilder\Loader;

use Noodlehaus\Config;

class FileLoader implements LoaderInterface
{
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }


    /**
     * Dynamically load a configuration file,
     * parse parameters (reusable variables)
     * parse imports (load extra configuration from inside a configuration)
     *
     * @param       $resource
     * @param array $parameters
     * @return array|null
     */
    public function load($resource, &$parameters = array())
    {
        $resource = $resource ?: $this->file;

        $file = realpath($resource);
        $content = Config::load($file)->all();

        $this->parseImports($content, $parameters);

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

    }


    /**
     * @param $content
     * @param $parameters
     * @return array
     */
    protected function parseImports(&$content, &$parameters)
    {
        if(!isset($content['imports'])){
            return $content;
        }

        foreach($content['imports'] as $import){
            if($importedContent = $this->load($import['resource'], $parameters)){
                $content = array_replace_recursive($importedContent, $content);
            }
        }
    }

}