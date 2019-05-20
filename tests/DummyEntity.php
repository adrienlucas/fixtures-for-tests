<?php

declare(strict_types=1);

namespace Adrien\FixturesForTests\Tests;

use Doctrine\ODM\MongoDB\Mapping\Annotations\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

/**
 * @Entity()
 */
class DummyEntity
{
    /**
     * @Id()
     * @Column()
     */
    private $id;
}
