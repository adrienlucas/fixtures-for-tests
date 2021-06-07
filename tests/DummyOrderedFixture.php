<?php

declare(strict_types=1);

namespace Adrien\FixturesForTests\Tests;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\AssertionFailedError;

class DummyOrderedFixture extends DummyFixture implements OrderedFixtureInterface
{
    private $order;

    public function __construct($order = 0)
    {
        $this->order = $order;
    }

    public function load(ObjectManager $manager)
    {
        if (DummyFixture::$totalCalls !== $this->order) {
            throw new AssertionFailedError(sprintf('The fixture has not been called at the right time (calls: %d, order : %d).', DummyFixture::$totalCalls, $this->order));
        }
        parent::load($manager);
    }

    public function getOrder()
    {
        return $this->order;
    }
}
