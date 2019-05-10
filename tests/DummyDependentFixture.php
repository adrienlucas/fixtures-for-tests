<?php

declare(strict_types=1);

namespace Adrien\Tests;


use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
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

    public function load(ObjectManager $manager)
    {
        if (DummyFixture::$totalCalls !== $this->order) {
            throw new AssertionFailedError(sprintf('The fixture has not been called at the right time (calls : %d; order : %d).', DummyFixture::$totalCalls, $this->order));
        }

        parent::load($manager);
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [$this->dependency];
    }
}
