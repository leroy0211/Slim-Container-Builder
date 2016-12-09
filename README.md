[![Build Status](https://travis-ci.org/leroy0211/Slim-Container-Builder.svg?branch=master)](https://travis-ci.org/leroy0211/Slim-Container-Builder)
[![Packagist](https://img.shields.io/packagist/dt/flexsounds/slim-container-builder.svg)](https://packagist.org/packages/flexsounds/slim-container-builder)
[![license](https://img.shields.io/github/license/leroy0211/Slim-Container-Builder.svg)]()
[![Coverage Status](https://coveralls.io/repos/github/leroy0211/Slim-Container-Builder/badge.svg?branch=master)](https://coveralls.io/github/leroy0211/Slim-Container-Builder?branch=master)

# Slim-Container-Builder
A tool to build your Slim Framework 3 container. Instead of having to define your container in php, you just define it from a configuration file.

This will not replace the `pimple` container, but will build one for you.

# Installation

User composer to install

```
composer require flexsounds/slim-container-builder
```

Install `symfony/yaml` to use yaml files!

# Default usage

This will create a slim framework container with your services defined in a configuration file.

```php
$containerBuilder = new \Flexsounds\Slim\ContainerBuilder\ContainerBuilder();
$containerBuilder->setLoader($loader = new \Flexsounds\Slim\ContainerBuilder\Loader\FileLoader('./config'));
$loader->addFile("config.yml");
$app = new Slim\App($containerBuilder->getContainer());

// define your routes here.

$app->run();
```

# Configuration file support
We use [hassankhan/config](https://github.com/hassankhan/config) to load the configuration, which has support for YML, XML, JSON and some more.

You can import other configuration files from a configuration file, independent of what file format.

```yml
# config.yml
imports:
  - { Resource: otherconfig.xml }
```

All examples are created in yaml notation!

# Creating your services

The service configuration has almost the same notation as `Symfony` does.
 Which means you could copy your service configuration you built to a Symfony application (keep in mind, symfony has many more features than `Slim Container Builder`)

All your services must be defined under the `services` key and are shared throughout your entire application.
The default services like `settings`, `request`, `response`, `router` etc. from Slim Framework are also available as service

## Basic example

Creating a service without any arguments is very simple.

```yml

services:
  my.custom.service:
    class: 'App\MyBasicService'

```

In your code you can use

```php

$app->get('/', function($request, $response){
    $myCustomService = $this->get('my.custom.service');
    // $myCustomService is now an instance of 'App\MyBasicService'
});

```

## Non-Shared services

All services are shared by default. Which means each time you retrieve the service, you get the same instance.
When you want a new instance each time you call the service, use the `shared` key

```yml
services:
    my.nonshared.service:
        class: 'App\MyNonSharedService'
        shared: false

```

## Dependency Injection

Some of your services might need some dependency injection, this could be done with the `arguments` key.
You can inject anyting you want, even other services.

```yml
services:
  mailer:
    class: 'Location\To\Mailer'


  newslettermanager:
    class: 'App\Newsletter\NewsletterManager'
    arguments:
      - '@mailer'

```

```php
namespace App\Newsletter;

use Location\To\Mailer;

class NewsletterManager {

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
}

```


# Contributing

Read the [CONTRIBUTING](CONTRIBUTING.md) for details








