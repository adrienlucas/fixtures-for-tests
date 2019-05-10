<?php

declare(strict_types=1);

namespace Adrien\Tests;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DummyFixture implements FixtureInterface
{
    /** @var int */
    public static $totalCalls = 0;

    public function load(ObjectManager $manager)
    {
        static::$calls++;
        DummyFixture::$totalCalls++;
    }
}
