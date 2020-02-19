<?php

declare(strict_types=1);

namespace Adrien\FixturesForTests\Tests;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;

class FixtureLoaderTraitTest extends TestCase
{
    public function testItLoadsTheRequestedFixture()
    {
        $objectManager = DummyFactory::createEntityManager();

        $requestedFixture = new class() extends DummyFixture {
            public static $calls = 0;
        };

        DummyFactory::createTraitUser($objectManager, $requestedFixture);

        static::assertFixtureLoadCalls($requestedFixture);
    }

    public function testItLoadsSeveralRequestedFixtures()
    {
        $objectManager = DummyFactory::createEntityManager();

        $firstRequestedFixture = new class() extends DummyFixture {
            public static $calls = 0;
        };
        $secondRequestedFixture = new class() extends DummyFixture {
            public static $calls = 0;
        };

        DummyFactory::createTraitUser($objectManager, $firstRequestedFixture, $secondRequestedFixture);

        static::assertFixtureLoadCalls($firstRequestedFixture);
        static::assertFixtureLoadCalls($secondRequestedFixture);
    }

    public function testItLoadsTheRequestedOrderedFixtures()
    {
        $objectManager = DummyFactory::createEntityManager();

        $firstOrderedFixture = new class(0) extends DummyOrderedFixture {
            public static $calls = 0;
        };
        $secondOrderedFixture = new class(1) extends DummyOrderedFixture {
            public static $calls = 0;
        };
        $thirdOrderedFixture = new class(2) extends DummyOrderedFixture {
            public static $calls = 0;
        };

        DummyFactory::createTraitUser($objectManager, $secondOrderedFixture, $thirdOrderedFixture, $firstOrderedFixture);

        static::assertSame(3, DummyFixture::$totalCalls);
        static::assertFixtureLoadCalls($firstOrderedFixture);
        static::assertFixtureLoadCalls($secondOrderedFixture);
        static::assertFixtureLoadCalls($thirdOrderedFixture);
    }

    public function testItLoadsTheRequestedDependentFixtures()
    {
        $objectManager = DummyFactory::createEntityManager();

        $mainFixture = new class() extends DummyFixture {
            public static $calls = 0;
        };

        $dependentFixture = new class(1, get_class($mainFixture)) extends DummyDependentFixture {
            public static $calls = 0;
        };

        DummyFactory::createTraitUser($objectManager, $dependentFixture);

        static::assertSame(2, DummyFixture::$totalCalls);
        static::assertFixtureLoadCalls($mainFixture);
        static::assertFixtureLoadCalls($dependentFixture);
    }

    public function testItLoadsTheRequestedSharedFixtures()
    {
        $objectManager = DummyFactory::createEntityManager();

        $fixture = new class() extends AbstractFixture {
            public const REFERENCE = 'ref';
            public function load(ObjectManager $manager)
            {
                $this->addReference(self::REFERENCE, new DummyEntity());
            }
        };

        $testCase = DummyFactory::createTraitUser($objectManager, $fixture);
        static::assertInstanceOf(DummyEntity::class, $testCase->getFixtureRepository()->getReference($fixture::REFERENCE));
    }

//    public function testItPurgesTheDatabaseBeforeLoadingFixtures()
//    {
//        $objectManager = DummyFactory::createEntityManager();
//
//        $mainFixture = new class() extends DummyFixture {
//            public static $calls = 0;
//            public function load(ObjectManager $manager)
//            {
//                static::$calls++;
//                ++DummyFixture::$totalCalls;
//
//                $manager->persist();
//            }
//        };
//    }

    public function setUp(): void
    {
        DummyFixture::$totalCalls = 0;
    }

    private static function assertFixtureLoadCalls(DummyFixture $fixture, int $expectedNumberOfCalls = 1): void
    {
        $actualNumberOfCalls = $fixture::$calls;
        static::assertSame($expectedNumberOfCalls, $actualNumberOfCalls,
            sprintf('The fixture "load" method has been called %d times.', $actualNumberOfCalls)
        );
    }
}
