<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator;

use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class ServiceLocatorTest extends TestCase
{
    /**
     * Register.
     *
     * Test that a resolver can be registered.
     *
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::addResolver()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getService()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::hasService()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getCachedService()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getResolver()
     */
    public function testRegister(): void
    {
        $resolver = $this->createMock(ResolverInterface::class);
        $resolver
            ->expects($this->once())
            ->method('getService')
            ->with('A')
            ->willReturn(new stdClass());

        $resolver
            ->expects($this->exactly(2))
            ->method('hasService')
            ->with('A')
            ->willReturn(true);

        /**
         * @var ResolverInterface $resolver
         */
        $serviceLocator = new ServiceLocator();
        $service = $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A');

        $this->assertInstanceOf(stdClass::class, $service);
    }

    /**
     * Has.
     *
     * Test that service locator can check for service existence.
     *
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getService()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::hasService()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getCachedService()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getResolver()
     */
    public function testHas(): void
    {
        $serviceLocator = new ServiceLocator();

        $this->assertFalse($serviceLocator->hasService('foo'));
    }

    /**
     * Cached service.
     *
     * Test that a cached service will be returned.
     *
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::addResolver()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getService()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::hasService()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getCachedService()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getResolver()
     */
    public function testCachedService(): void
    {
        $resolver = $this->createMock(ResolverInterface::class);
        $resolver
            ->expects($this->once())
            ->method('getService')
            ->with('A')
            ->willReturn(new stdClass());

        $resolver
            ->expects($this->exactly(2))
            ->method('hasService')
            ->with('A')
            ->willReturn(true);

        /**
         * @var ResolverInterface $resolver
         */
        $serviceLocator = new ServiceLocator();
        $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A');

        $service = $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A');

        $this->assertInstanceOf(stdClass::class, $service);
    }

    /**
     * Service not found.
     *
     * Test that a service can not be located and an exception will be thrown.
     *
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::getService()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::hasService()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::getCachedService()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::getResolver()
     * @covers                   \ExtendsFramework\ServiceLocator\Exception\ServiceNotFound::__construct()
     * @expectedException        \ExtendsFramework\ServiceLocator\Exception\ServiceNotFound
     * @expectedExceptionMessage No service found for key "foo".
     */
    public function testServiceNotFound(): void
    {
        $serviceLocator = new ServiceLocator();
        $serviceLocator->getService('foo');
    }
}
