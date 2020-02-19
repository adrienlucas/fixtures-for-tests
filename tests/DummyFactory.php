<?php

declare(strict_types=1);

namespace Adrien\FixturesForTests\Tests;

use Adrien\FixturesForTests\FixtureLoaderTrait;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class DummyFactory
{
    public static function createEntityManager(): EntityManager
    {
        return EntityManager::create(
            [
                'driver' => 'pdo_sqlite',
                'path' => __DIR__.'/../db.sqlite',
            ],
            Setup::createAnnotationMetadataConfiguration([__DIR__])
        );
    }

    public static function createTraitUser($objectManager, FixtureInterface...$fixtures): object
    {
        return new class($objectManager, ...$fixtures) {
            use FixtureLoaderTrait;
            public function __construct($manager, FixtureInterface...$fixtures)
            {
                $this->loadFixture($manager, ...$fixtures);
            }
            public function getFixtureRepository(): ?ReferenceRepository
            {
                return $this->fixtureRepository;
            }
        };
    }
}
