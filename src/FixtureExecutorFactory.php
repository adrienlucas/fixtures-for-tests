<?php

declare(strict_types=1);

namespace Adrien\FixturesForTests;

use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Doctrine\Common\DataFixtures\Executor\MongoDBExecutor;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Executor\PHPCRExecutor;
use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Purger\PHPCRPurger;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ODM\PHPCR\DocumentManagerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use LogicException;

class FixtureExecutorFactory
{
    public static function createManagerExecutor(ObjectManager $manager): AbstractExecutor
    {
        if ($manager instanceof EntityManagerInterface) {
            return new ORMExecutor($manager, new ORMPurger($manager));
        }

        if ($manager instanceof DocumentManagerInterface) {
            return new PHPCRExecutor($manager, new PHPCRPurger($manager));
        }

        if ($manager instanceof DocumentManager) {
            return new MongoDBExecutor($manager, new MongoDBPurger($manager));
        }

        throw new LogicException(sprintf('No fixture executor found for "%s".', get_class($manager)));
    }
}
