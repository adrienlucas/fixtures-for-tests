<?php

namespace Adrien\FixturesForTests;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Common\Persistence\ObjectManager;

trait FixtureLoaderTrait
{
    /** @var ReferenceRepository */
    protected $fixtureRepository;

    private function loadFixture(ObjectManager $manager, FixtureInterface ...$fixtures): void
    {
        $executor = FixtureExecutorFactory::createManagerExecutor($manager);
        $this->fixtureRepository = $executor->getReferenceRepository();

        $loader = new Loader();
        array_map([$loader, 'addFixture'], $fixtures);

        $executor->execute($loader->getFixtures());
    }
}
