<?php
declare(strict_types = 1);

namespace ExtendsFramework\ServiceLocator;

use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class ServiceLocatorTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::register()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::get()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::has()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::service()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::resolver()
     */
    public function testCanRegisterResolverAndGetServiceForKey(): void
    {
        $resolver = $this->createMock(ResolverInterface::class);
        $resolver
            ->expects($this->once())
            ->method('get')
            ->with('A')
            ->willReturn(new stdClass());

        $resolver
            ->expects($this->exactly(2))
            ->method('has')
            ->with('A')
            ->willReturn(true);

        /**
         * @var ResolverInterface $resolver
         */
        $serviceLocator = new ServiceLocator();
        $service = $serviceLocator
            ->register($resolver, 'invokables')
            ->get('A');

        $this->assertInstanceOf(stdClass::class, $service);
    }

    /**
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::register()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::get()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::has()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::service()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::resolver()
     */
    public function testCanDirectlyReturnCachedServiceForKey(): void
    {
        $resolver = $this->createMock(ResolverInterface::class);
        $resolver
            ->expects($this->once())
            ->method('get')
            ->with('A')
            ->willReturn(new stdClass());

        $resolver
            ->expects($this->exactly(2))
            ->method('has')
            ->with('A')
            ->willReturn(true);

        /**
         * @var ResolverInterface $resolver
         */
        $serviceLocator = new ServiceLocator();
        $serviceLocator
            ->register($resolver, 'invokables')
            ->get('A');

        $service = $serviceLocator
            ->unregister('invokables')
            ->get('A');

        $this->assertInstanceOf(stdClass::class, $service);
    }

    /**
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::register()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::unregister()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::get()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::has()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::service()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::resolver()
     * @covers                   \ExtendsFramework\ServiceLocator\Exception\ServiceNotFound::forService()
     * @expectedException        \ExtendsFramework\ServiceLocator\Exception\ServiceNotFound
     * @expectedExceptionMessage Service with key "A" MUST exist.
     */
    public function testCanUnRegisterResolverAndCanNotGetServiceForKey(): void
    {
        $resolver = $this->createMock(ResolverInterface::class);

        /**
         * @var ResolverInterface $resolver
         */
        $serviceLocator = new ServiceLocator();
        $service = $serviceLocator
            ->register($resolver, 'invokables')
            ->unregister('invokables')
            ->get('A');

        $this->assertInstanceOf(stdClass::class, $service);
    }
}
