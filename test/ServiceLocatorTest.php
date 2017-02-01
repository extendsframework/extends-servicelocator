<?php

namespace ExtendsFramework\ServiceLocator;

use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;
use PHPUnit_Framework_TestCase;
use stdClass;

class ServiceLocatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::register()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::get()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::has()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::service()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::resolver()
     */
    public function testCanRegisterResolverAndGetServiceForKey()
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
    public function testCanDirectlyReturnCachedServiceForKey()
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
    public function testCanUnRegisterResolverAndCanNotGetServiceForKey()
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

    /**
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::register()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::get()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::has()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::service()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::resolver()
     * @covers                   \ExtendsFramework\ServiceLocator\Exception\InvalidServiceType::forNonObject()
     * @expectedException        \ExtendsFramework\ServiceLocator\Exception\InvalidServiceType
     * @expectedExceptionMessage Service for key "A" MUST be an object, got "array".
     */
    public function testCanNotHandleInvalidResolverReturnType()
    {
        $resolver = $this->createMock(ResolverInterface::class);
        $resolver
            ->expects($this->once())
            ->method('get')
            ->with('A')
            ->willReturn([]);

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
    }
}
