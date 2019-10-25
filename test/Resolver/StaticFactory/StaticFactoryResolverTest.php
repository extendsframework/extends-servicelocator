<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\StaticFactory;

use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\Exception\InvalidStaticFactory;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\Exception\ServiceCreateFailed;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class StaticFactoryResolverTest extends TestCase
{
    /**
     * Get service.
     *
     * Test that resolver returns a new service for key.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver::addStaticFactory()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver::getService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver::hasService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver::getFactories()
     */
    public function testGetService(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new StaticFactoryResolver();
        $service = $resolver
            ->addStaticFactory(StaticFactoryStub::class, StaticFactoryStub::class)
            ->getService(StaticFactoryStub::class, $serviceLocator, [
                'foo' => 'bar',
            ]);

        $this->assertInstanceOf(stdClass::class, $service);
        $this->assertSame(StaticFactoryStub::class, $service->key);
        $this->assertSame($serviceLocator, $service->serviceLocator);
        $this->assertSame(['foo' => 'bar'], $service->extra);
    }

    /**
     * Has service.
     *
     * Test that method will return true for known service and false for unknown service.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver::addStaticFactory()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver::hasService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver::getService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver::getFactories()
     */
    public function testHasService(): void
    {
        $resolver = new StaticFactoryResolver();
        $resolver->addStaticFactory(StaticFactoryStub::class, StaticFactoryStub::class);

        $this->assertTrue($resolver->hasService(StaticFactoryStub::class));
        $this->assertFalse($resolver->hasService(stdClass::class));
    }

    /**
     * Create.
     *
     * Test that static factory returns an instance of ResolverInterface.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver::factory()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver::addStaticFactory()
     */
    public function testCreate(): void
    {
        $resolver = StaticFactoryResolver::factory([
            StaticFactoryStub::class => StaticFactoryStub::class,
        ]);

        $this->assertIsObject($resolver);
    }

    /**
     * Invalid static factory.
     *
     * Test that adding a service without StaticFactoryInterface will throw an exception.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver::addStaticFactory()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\Exception\InvalidStaticFactory::__construct()
     */
    public function testInvalidStaticFactory(): void
    {
        $this->expectException(InvalidStaticFactory::class);
        $this->expectExceptionMessage('Factory must be a subclass of StaticFactoryInterface, got "stdClass".');

        $resolver = new StaticFactoryResolver();
        $resolver->addStaticFactory(stdClass::class, stdClass::class);
    }

    /**
     * Service create failed.
     *
     * Test that exception thrown by service is caught and rethrow as wrapped exception.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver::addStaticFactory()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver::getService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver::hasService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\Exception\ServiceCreateFailed::__construct()
     */
    public function testServiceCreateFailed(): void
    {
        $this->expectException(ServiceCreateFailed::class);
        $this->expectExceptionMessage('Failed to create service for key "A". See previous exception for more details.');

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new StaticFactoryResolver();
        $resolver
            ->addStaticFactory('A', StaticFailedFactoryStub::class)
            ->getService('A', $serviceLocator, []);
    }
}
