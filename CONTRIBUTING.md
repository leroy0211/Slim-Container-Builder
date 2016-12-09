# Contributing to Slim Container Builder

## Opening issues

If you find a bug, or thinking of a new feature, feel free to [open an issue](https://github.com/leroy0211/Slim-Container-Builder/issues)

## Pull Requests

1. Fork the Slim Container Builder repository
2. Create a new branch for each feature or improvement
3. Send a pull request from your feature branch to the `develop` branch

## Style guide

Slim Container Builder used the PSR-2 standard.
If anything you find is not PSR-2, create a pull request!

## Unit Testing

All pull requests must be accompanied with Unit Tests. Slim Container Builder uses PHPUnit for testing.

PHPUnit is allready loaded in our `composer.json` file, so just run phpunit from the projects root directory

```
vendor/bin/phpunit
```

All Pull Requests are built automatically with [Travis](https://travis-ci.org/leroy0211/Slim-Container-Builder).

Code Coverage is generated on [Coveralls](https://coveralls.io/github/leroy0211/Slim-Container-Builder?branch=master) and [CodeClimate](https://codeclimate.com/github/leroy0211/Slim-Container-Builder)