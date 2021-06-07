<?php

declare(strict_types=1);

namespace Adrien\FixturesForTests\Tests;

use Adrien\FixturesForTests\FixtureExecutorFactory;
use Doctrine\Common\DataFixtures\Executor\MongoDBExecutor;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Executor\PHPCRExecutor;
use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Purger\PHPCRPurger;
use Doctrine\Common\EventManager;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager as MongoDBDocumentManager;
use Doctrine\ODM\PHPCR\DocumentManager as PHPCRDocumentManager;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use PHPUnit\Framework\TestCase;

class FixtureExecutorFactoryTest extends TestCase
{
    /**
     * @dataProvider provideManagersWithExpectedExecutor
     */
    public function testItReturnsTheRightExecutorForTheGivenManager($givenManager, $expectedExecutor, $expectedPurger)
    {
        $executor = FixtureExecutorFactory::createManagerExecutor($givenManager);

        static::assertInstanceOf(
            $expectedExecutor,
            $executor
        );

        static::assertInstanceOf(
            $expectedPurger,
            $executor->getPurger()
        );
    }

    public function provideManagersWithExpectedExecutor()
    {
        yield 'orm' => [
            static::createConfiguredMock(EntityManagerInterface::class, ['getEventManager' => new EventManager()]),
            ORMExecutor::class,
            ORMPurger::class,
        ];

        yield 'phpcr' => [
            static::createMock(PHPCRDocumentManager::class),
            PHPCRExecutor::class,
            PHPCRPurger::class,
        ];

        yield 'mongodb' => [
            static::createConfiguredMock(MongoDBDocumentManager::class, ['getEventManager' => new EventManager()]),
            MongoDBExecutor::class,
            MongoDBPurger::class,
        ];
    }

    public function testItThrowsAnExceptionWhenNoExecutorFound()
    {
        static::expectException(LogicException::class);
        FixtureExecutorFactory::createManagerExecutor(static::createMock(ObjectManager::class));
    }
}
