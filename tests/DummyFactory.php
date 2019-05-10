<?php

declare(strict_types=1);

namespace Adrien\Tests;


use Adrien\FixtureLoaderTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class DummyFactory
{
    public static function createEntityManager()
    {
        return EntityManager::create(
            [
                'driver' => 'pdo_sqlite',
                'path' => __DIR__ . '/../db.sqlite'
            ],
            Setup::createXMLMetadataConfiguration([])
        );
    }

    public static function createTraitUser($objectManager, $firstRequestedFixture, $secondRequestedFixture = null)
    {
        new class($objectManager, $firstRequestedFixture, $secondRequestedFixture) {
            use FixtureLoaderTrait;
            public function __construct($manager, ...$fixtures)
            {
                $this->loadFixture($manager, ...$fixtures);
            }
        };
    }

    public static function createFixture()
    {
        $f = new class extends DummyFixture {};
        return $f;
    }
}
