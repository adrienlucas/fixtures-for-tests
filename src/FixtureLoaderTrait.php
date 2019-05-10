<?php

namespace Adrien;

use Adrien\Tests\DummyFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\Persistence\ObjectManager;

trait FixtureLoaderTrait
{
    public function loadFixture(ObjectManager $manager, FixtureInterface ...$fixtures)
    {
        $loader = new Loader();

        /** @uses Loader::addFixture() */
        array_map([$loader, 'addFixture'], $fixtures);

        $executor = FixtureExecutorFactory::createManagerExecutor($manager);
        $orderedFixtures = $loader->getFixtures();

        $executor->execute($orderedFixtures);
    }
}
