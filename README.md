# Fixtures For Tests

[![Latest Version](https://img.shields.io/packagist/v/adrien/fixtures-for-tests.svg?style=flat-square)](https://github.com/adrienlucas/fixtures-for-tests/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/adrienlucas/fixtures-for-tests/master.svg?style=flat-square)](https://travis-ci.org/adrienlucas/fixtures-for-tests)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/adrienlucas/fixtures-for-tests/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/adrienlucas/fixtures-for-tests)
[![Quality Score](https://img.shields.io/scrutinizer/g/adrienlucas/fixtures-for-tests/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/adrienlucas/fixtures-for-tests)
<!--
[![Total Downloads](https://img.shields.io/packagist/dt/adrien/fixtures-for-tests.svg?style=flat-square)](https://packagist.org/packages/adrien/fixtures-for-tests)
-->

**A set of traits to be used in test cases**


## Install

Via Composer

``` bash
$ composer require adrien/fixtures-for-tests
```

## Usage
 
 - Use the `FixtureLoaderTrait` to add a fixture loading shortcut method.
 - Use the `FixtureAttachedTrait` within a `KernelTestCase` extending class to have fixture loaded automatically before each tests.
 
### Exemple :
```php
<?php

namespace SomeNamespace\Test;

class SomeFeatureTest extends TestCase
{
    use FixtureAttachedTrait;

    public function testItDoesWhatIsExpected(): void
    {
        
    }
}
```

```php
<?php

namespace SomeNamespace\Test;

class SomeFeatureFixture implements FixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $dummy = new SomeEntity();
        $dummy->setSomething('something');
        
        $
        $manager->persist($dummy);
        $manager->flush();
    }
}
```

## Contributing and testing

``` bash
$ composer update --prefer-lowest --prefer-source
$ ./vendor/bin/phpunit
```

**Please maintain the test suite : if you add a feature, prove the new behavior; if you fix a bug, ensure the non-regression.**
