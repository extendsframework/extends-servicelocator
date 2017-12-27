<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\StaticFactory;

use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;
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

        $this->assertInstanceOf(ResolverInterface::class, $resolver);
    }

    /**
     * Invalid static factory.
     *
     * Test that adding a service without StaticFactoryInterface will throw an exception.
     *
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver::addStaticFactory()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\Exception\InvalidStaticFactory::__construct()
     * @expectedException        \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\Exception\InvalidStaticFactory
     * @expectedExceptionMessage Factory must be a subclass of StaticFactoryInterface, got "stdClass".
     */
    public function testInvalidStaticFactory()
    {
        $resolver = new StaticFactoryResolver();
        $resolver->addStaticFactory(stdClass::class, stdClass::class);
    }

    /**
     * Service create failed.
     *
     * Test that exception thrown by service is caught and rethrow as wrapped exception.
     *
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver::addStaticFactory()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver::getService()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver::hasService()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\Exception\ServiceCreateFailed::__construct()
     * @expectedException        \ExtendsFramework\ServiceLocator\Resolver\StaticFactory\Exception\ServiceCreateFailed
     * @expectedExceptionMessage Failed to create service for key "A". See previous exception for more details.
     */
    public function testServiceCreateFailed()
    {
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

class StaticFactoryStub implements StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): stdClass
    {
        $service = new stdClass();
        $service->key = $key;
        $service->serviceLocator = $serviceLocator;
        $service->extra = $extra;

        return $service;
    }
}

class StaticFailedFactoryStub implements StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null)
    {
        throw new class extends RuntimeException implements StaticFactoryResolverException
        {
        };
    }
}
