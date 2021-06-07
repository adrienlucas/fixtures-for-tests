<?php

declare(strict_types=1);

namespace Adrien\FixturesForTests\Tests;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\AssertionFailedError;

class DummyDependentFixture extends DummyFixture implements DependentFixtureInterface
{
    private $dependency;
    private $order;

    public function __construct(int $order = 0, string $dependency = null)
    {
        $this->order = $order;
        $this->dependency = $dependency;
    }

    public function load(ObjectManager $manager): void
    {
        if (DummyFixture::$totalCalls !== $this->order) {
            throw new AssertionFailedError(sprintf('The fixture has not been called at the right time (calls : %d; order : %d).', DummyFixture::$totalCalls, $this->order));
        }

        parent::load($manager);
    }

    public function getDependencies(): array
    {
        return [$this->dependency];
    }
}
