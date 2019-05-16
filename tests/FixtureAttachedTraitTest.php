<?php

declare(strict_types=1);

namespace Adrien\FixturesForTests\Tests;

use Adrien\FixturesForTests\FixtureAttachedTrait;
use Doctrine\Common\Persistence\ObjectManager;
use LogicException;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class FixtureAttachedTraitTest extends TestCase
{
    public function testItThrowsAnExceptionWhenTheUsingClassIsNotAKernelTestCase()
    {
        $notValidUser = new class() {
            use FixtureAttachedTrait;
        };

        static::expectException(LogicException::class);
        $notValidUser->setUp();
    }

    public function testItLoadsFixturesWhenTheSetupMethodIsCalled()
    {
        $objectManager = DummyFactory::createEntityManager();

        $mockContainer = static::createMock(ContainerInterface::class);
//        $mockContainer->method('has')->withConsecutive([static::equalTo(ObjectManager::class)], [static::equalTo('test.service_container')])
//            ->willReturnOnConsecutiveCalls([false, true]);
        $mockContainer->method('has')->will(static::onConsecutiveCalls(false, true));
        $mockContainer->method('get')->with(ObjectManager::class)
            ->willReturnReference($objectManager);

        $mockKernel = static::createMock(KernelInterface::class);
        $mockKernel->method('getContainer')->willReturnReference($mockContainer);

        DummyTestCase::$preparedKernel = $mockKernel;
        $dummyTestCase = new DummyTestCase();
        $dummyTestCase->setUp();

        static::assertFixtureLoadCalls(DummyTestCaseFixture::class);
        static::assertTrue($dummyTestCase->setupCalled, 'The test case setup method has not been called');
    }

    private static function assertFixtureLoadCalls(string $fixtureName, int $expectedNumberOfCalls = 1): void
    {
        if (!is_subclass_of($fixtureName, DummyFixture::class)) {
            throw new LogicException('A DummyFixture child class should have been provided.');
        }

        $actualNumberOfCalls = $fixtureName::$calls;
        static::assertSame(
            $expectedNumberOfCalls,
            $actualNumberOfCalls,
            sprintf('The fixture "load" method has been called %d times.', $actualNumberOfCalls)
        );
    }
}

class DummyTestCase extends KernelTestCase
{
    use FixtureAttachedTrait { setUp as loadFixtures; }

    /** @var KernelInterface */
    public static $preparedKernel;

    /** @var bool */
    public $setupCalled = false;

    public function setUp(): void
    {
        $this->loadFixtures();

        $this->setupCalled = true;
        parent::setUp();
    }

    protected static function createKernel(array $options = [])
    {
        return clone self::$preparedKernel;
    }
}

class DummyTestCaseFixture extends DummyFixture
{
    public static $calls = 0;
}
