<?php

declare(strict_types=1);

namespace Adrien\FixturesForTests\Tests;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DummyFixture implements FixtureInterface
{
    /** @var int */
    public static $totalCalls = 0;

    public function load(ObjectManager $manager)
    {
        static::$calls++;
        ++DummyFixture::$totalCalls;
    }
}
