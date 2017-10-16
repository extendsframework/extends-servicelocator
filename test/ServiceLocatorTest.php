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
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::__construct()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::addResolver()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getService()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getSharedService()
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
            ->expects($this->once())
            ->method('hasService')
            ->with('A')
            ->willReturn(true);

        /**
         * @var ResolverInterface $resolver
         */
        $serviceLocator = new ServiceLocator([]);
        $service = $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A');

        $this->assertInstanceOf(stdClass::class, $service);
    }

    /**
     * Shared service.
     *
     * Test that a shared service will be returned and cached by the service locator.
     *
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::__construct()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::addResolver()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getService()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getSharedService()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getResolver()
     */
    public function testSharedService(): void
    {
        $resolver = $this->createMock(ResolverInterface::class);
        $resolver
            ->expects($this->once())
            ->method('getService')
            ->with('A')
            ->willReturn(new stdClass());

        $resolver
            ->expects($this->once())
            ->method('hasService')
            ->with('A')
            ->willReturn(true);

        /**
         * @var ResolverInterface $resolver
         */
        $serviceLocator = new ServiceLocator([]);
        $service1 = $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A');

        $service2 = $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A');

        $this->assertSame($service1, $service2);
    }

    /**
     * Managed service.
     *
     * Test that a managed service will be returned and not cached by the service locator.
     *
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::__construct()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::addResolver()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getService()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getSharedService()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getResolver()
     */
    public function testManagedService(): void
    {
        $resolver = $this->createMock(ResolverInterface::class);
        $resolver
            ->expects($this->exactly(2))
            ->method('getService')
            ->with(
                'A',
                $this->isInstanceOf(ServiceLocatorInterface::class),
                ['foo' => 'bar']
            )
            ->willReturnOnConsecutiveCalls(
                new stdClass(),
                new stdClass()
            );

        $resolver
            ->expects($this->exactly(2))
            ->method('hasService')
            ->with('A')
            ->willReturn(true);

        /**
         * @var ResolverInterface $resolver
         */
        $serviceLocator = new ServiceLocator([]);
        $service1 = $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A', ['foo' => 'bar']);

        $service2 = $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A', ['foo' => 'bar']);

        $this->assertNotSame($service1, $service2);
    }

    /**
     * Get config.
     *
     * Test that config is returned.
     *
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::__construct()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getConfig()
     */
    public function testGetConfig(): void
    {
        $serviceLocator = new ServiceLocator([
            'foo' => 'bar',
        ]);

        $this->assertSame(['foo' => 'bar'], $serviceLocator->getConfig());
    }

    /**
     * Non object.
     *
     * Test that a non object service is not allowed and will throw an exception.
     *
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::__construct()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::getService()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::getSharedService()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::getResolver()
     * @covers                   \ExtendsFramework\ServiceLocator\Exception\NonObjectService::__construct()
     * @expectedException        \ExtendsFramework\ServiceLocator\Exception\NonObjectService
     * @expectedExceptionMessage Service for key "A" must an object, got type "array".
     */
    public function testNonObject(): void
    {
        $resolver = $this->createMock(ResolverInterface::class);
        $resolver
            ->expects($this->once())
            ->method('getService')
            ->with('A')
            ->willReturn([]);

        $resolver
            ->expects($this->once())
            ->method('hasService')
            ->with('A')
            ->willReturn(true);

        /**
         * @var ResolverInterface $resolver
         */
        $serviceLocator = new ServiceLocator([]);
        $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A');
    }

    /**
     * Service not found.
     *
     * Test that a service can not be located and an exception will be thrown.
     *
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::__construct()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::getService()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::getSharedService()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocator::getResolver()
     * @covers                   \ExtendsFramework\ServiceLocator\Exception\ServiceNotFound::__construct()
     * @expectedException        \ExtendsFramework\ServiceLocator\Exception\ServiceNotFound
     * @expectedExceptionMessage No service found for key "foo".
     */
    public function testServiceNotFound(): void
    {
        $serviceLocator = new ServiceLocator([]);
        $serviceLocator->getService('foo');
    }
}
