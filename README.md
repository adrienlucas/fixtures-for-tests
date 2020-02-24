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
### Exemple using PHPUnit + Symfony's KernelTestCase :

```php
<?php

namespace SomeNamespace\Test;

use Adrien\FixturesForTests\FixtureAttachedTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SomeFeatureTest extends KernelTestCase
{
    use FixtureAttachedTrait;

    public function testItDoesWhatIsExpected(): void
    {
        $dummy = $this->fixtureRepository->getReference('my_dummy');
        // ...
    }
}
```

```php
<?php

namespace SomeNamespace\Test;

use App\Entity\SomeEntity;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class SomeFeatureFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $dummy = new SomeEntity();
        $dummy->setSomething('something');

        $manager->persist($dummy);
        $manager->flush();

        $this->referenceRepository->addReference('my_dummy', $dummy);
    }
}
```

### Exemple using Behat (with PHPCR) :

```php
<?php

namespace SomeNamespace\Behat;

use Adrien\FixturesForTests\FixtureLoaderTrait;
use Behat\Behat\Context\Context;
use Doctrine\ODM\PHPCR\DocumentManager;

class FeatureContext implements Context
{
    use FixtureLoaderTrait;

    /** @BeforeScenario */
    public function prepareScenarioFixtures()
    {
        $persistenceManager = new DocumentManager(/*...*/);
        $this->loadFixture($persistenceManager, new SomeScenarioFixture());
    }
}
```

```php
<?php

namespace SomeNamespace\Behat;

use App\Entity\SomeEntity;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SomeFeatureFixture implements FixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $dummy = new SomeEntity();
        $dummy->setSomething('something');
        
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
