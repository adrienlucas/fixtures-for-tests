<?php

declare(strict_types=1);

namespace Adrien\Tests;

use Adrien\FixtureLoaderTrait;
use PHPUnit\Framework\TestCase;

class FixtureLoaderTraitTest extends TestCase
{
    public function testItLoadsTheRequestedFixture()
    {
        $objectManager = DummyFactory::createEntityManager();

        $requestedFixture = new class extends DummyFixture {
            static public $calls = 0;
        };

        new class($objectManager, $requestedFixture) {
            use FixtureLoaderTrait;
            public function __construct($manager, $fixture)
            {
                $this->loadFixture($manager, $fixture);
            }
        };

        static::assertFixtureLoadCalls($requestedFixture);
    }

    public function testItLoadsSeveralRequestedFixtures()
    {
        $objectManager = DummyFactory::createEntityManager();

        $firstRequestedFixture = new class extends DummyFixture {
            static public $calls = 0;
        };
        $secondRequestedFixture = new class extends DummyFixture {
            static public $calls = 0;
        };

        new class($objectManager, $firstRequestedFixture, $secondRequestedFixture) {
            use FixtureLoaderTrait;
            public function __construct($manager, $firstRequestedFixture, $secondRequestedFixture)
            {
                $this->loadFixture($manager, $firstRequestedFixture, $secondRequestedFixture);
            }
        };

        static::assertFixtureLoadCalls($firstRequestedFixture);
        static::assertFixtureLoadCalls($secondRequestedFixture);
    }

    public function testItLoadsTheRequestedOrderedFixtures()
    {
        $objectManager = DummyFactory::createEntityManager();

        $firstOrderedFixture = new class(0) extends DummyOrderedFixture {
            static public $calls = 0;
        };
        $secondOrderedFixture = new class(1) extends DummyOrderedFixture {
            static public $calls = 0;
        };

        new class($objectManager, $firstOrderedFixture, $secondOrderedFixture) {
            use FixtureLoaderTrait;
            public function __construct($manager, $firstOrderedFixture, $secondOrderedFixture)
            {
                $this->loadFixture($manager, $secondOrderedFixture, $firstOrderedFixture);
            }
        };

        static::assertSame(2, DummyFixture::$totalCalls);
        static::assertFixtureLoadCalls($firstOrderedFixture);
        static::assertFixtureLoadCalls($secondOrderedFixture);
    }

    public function testItLoadsTheRequestedDependentFixtures()
    {
        $objectManager = DummyFactory::createEntityManager();

        $mainFixture = new class extends DummyFixture {
            static public $calls = 0;
        };

        $dependentFixture = new class(1, get_class($mainFixture)) extends DummyDependentFixture {
            static public $calls = 0;
        };

        new class($objectManager, $dependentFixture) {
            use FixtureLoaderTrait;
            public function __construct($manager, $dependentFixture)
            {
                $this->loadFixture($manager, $dependentFixture);
            }
        };

        static::assertSame(2, DummyFixture::$totalCalls);
        static::assertFixtureLoadCalls($mainFixture);
        static::assertFixtureLoadCalls($dependentFixture);
    }

    public function setUp(): void
    {
        DummyFixture::$totalCalls = 0;
    }

    private static function assertFixtureLoadCalls(DummyFixture $fixture, int $expectedNumberOfCalls = 1): void
    {
        $actualNumberOfCalls = $fixture::$calls;
        static::assertSame(
            $expectedNumberOfCalls,
            $actualNumberOfCalls,
            sprintf('The fixture "load" method has been called %d times.', $actualNumberOfCalls)
        );
    }
}
