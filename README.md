[![Build Status](https://travis-ci.org/leroy0211/Slim-Container-Builder.svg?branch=master)](https://travis-ci.org/leroy0211/Slim-Container-Builder)

# Slim-Container-Builder
A tool to build a container from a config file. 


# Run example

```
php -S localhost:8080 -t . index.php
```

And go in your webbrowser to `localhost:8080`


# How to use

```php

$containerBuilder = new \Flexsounds\Slim\ContainerBuilder\ContainerBuilder();
$containerBuilder->setLoader(new \Flexsounds\Slim\ContainerBuilder\Loader\FileLoader('./config.yml'));

$slim = new Slim\App($containerBuilder->getContainer());

```
