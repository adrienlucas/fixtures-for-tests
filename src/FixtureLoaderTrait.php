<?php

namespace Adrien\FixturesForTests;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\Persistence\ObjectManager;

trait FixtureLoaderTrait
{
    public function loadFixture(ObjectManager $manager, FixtureInterface ...$fixtures)
    {
        $loader = new Loader();
        array_map([$loader, 'addFixture'], $fixtures);

        $executor = FixtureExecutorFactory::createManagerExecutor($manager);
        $orderedFixtures = $loader->getFixtures();

        $executor->execute($orderedFixtures);
    }
}
