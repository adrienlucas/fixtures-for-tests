<?php

declare(strict_types=1);

namespace Adrien\FixturesForTests;

use LogicException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

trait FixtureAttachedTrait
{
    use FixtureLoaderTrait;

    public function setUp(): void
    {
        if (!$this instanceof KernelTestCase) {
            throw new LogicException('The "FixtureAttachedTrait" should only be used on objects extending the symfony/framework-bundle KernelTestCase.');
        }

        self::bootKernel();

        if (!self::$container->has(ManagerRegistry::class)) {
            throw new LogicException('No Doctrine ManagerRegistry service has been found in the service container. Please provide an implementation.');
        }

        /** @var ManagerRegistry $registry */
        $registry = self::$container->get(ManagerRegistry::class);
        $fixtureName = static::getFixtureNameForTestCase(get_class($this));
        $this->loadFixture(
            $registry->getManager(),
            new $fixtureName()
        );
    }

    private static function getFixtureNameForTestCase(string $testCaseName)
    {
        return $testCaseName.'Fixture';
    }
}
