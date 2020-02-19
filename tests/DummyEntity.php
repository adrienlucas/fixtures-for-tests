<?php

declare(strict_types=1);

namespace Adrien\FixturesForTests\Tests;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;

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
