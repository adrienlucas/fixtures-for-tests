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
        // Clean-up previous remaining database
        if(file_exists($dbFile = __DIR__.'/../db.sqlite')) {
            unlink($dbFile);
        }

        $entityManager = EntityManager::create(
            [
                'driver' => 'pdo_sqlite',
                'path' => $dbFile,
            ],
            Setup::createAnnotationMetadataConfiguration([__DIR__])
        );

        // Prepare schema for \Adrien\FixturesForTests\Tests\DummyEntity
        $entityManager->getConnection()->query('CREATE TABLE DummyEntity (id varchar)');

        return $entityManager;
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
