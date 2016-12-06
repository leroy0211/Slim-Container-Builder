<?php

namespace Flexsounds\Slim\ContainerBuilder\Loader;

use Noodlehaus\Config;

class FileLoader extends AbstractLoader
{
    private $file;
    private $resourcePath;

    public function __construct($resourcePath)
    {
        $this->resourcePath = realpath($resourcePath);
    }

    /**
     * Add a file to load
     *
     * @param $file
     * @return $this
     */
    public function addFile($file)
    {
        $realFilePath = realpath($this->resourcePath . '/' . $file);
        if (!file_exists($realFilePath)) {
            throw new \InvalidArgumentException(sprintf("The file %s is not found in %s", $file, $this->resourcePath));
        }

        $this->file = $file;
        return $this;
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

        $file = realpath($this->resourcePath . '/' . $resource);
        $content = Config::load($file)->all();

        $this->parseImports($content, $parameters);
        $this->parseParameters($content, $parameters);

        array_walk_recursive($content, function (&$val, $key) use ($parameters) {
            $matches = null;
            preg_match('/\%(.*?)\%/', $val, $matches);
            $param = isset($matches[1]) ? $matches[1] : false;
            if ($param) {
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
        if (!isset($content['imports'])) {
            return $content;
        }

        foreach ($content['imports'] as $import) {
            if ($importedContent = $this->load($import['resource'], $parameters)) {
                $content = array_replace_recursive($importedContent, $content);
            }
        }
    }

}