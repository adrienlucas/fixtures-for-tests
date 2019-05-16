<?php

declare(strict_types=1);

namespace Adrien\FixturesForTests;

use LogicException;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

trait FixtureAttachedTrait
{
    use FixtureLoaderTrait;

    public function setUp(): void
    {
        if (!$this instanceof KernelTestCase) {
            throw new LogicException('The "FixtureAttachedTrait" should only be used on objects extending the symfony/framework-bundle KernelTestCase.');
        }

        $container = (self::bootKernel())->getContainer();

        if (!$container->has(ObjectManager::class)) {
            throw new LogicException('No doctrine ObjectManager service has been found in the service container. Please provide an implementation.');
        }

        /** @var ObjectManager $manager */
        $manager = $container->get(ObjectManager::class);
        $fixtureName = static::getFixtureNameForTestCase(get_class($this));
        $this->loadFixture(
            $manager,
            new $fixtureName()
        );
    }

    public static function getFixtureNameForTestCase(string $testCaseName)
    {
        return $testCaseName.'Fixture';
    }
}
