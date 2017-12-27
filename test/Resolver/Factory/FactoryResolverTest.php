<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Factory;

use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;

class FactoryResolverTest extends TestCase
{
    /**
     * Register.
     *
     * Test that a new factory fqcn can be registered.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::addFactory()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::getService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::hasService()
     */
    public function testRegister(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new FactoryResolver();
        $service = $resolver
            ->addFactory('foo', FactoryStub::class)
            ->getService('foo', $serviceLocator, ['foo' => 'bar']);

        $this->assertInstanceOf(stdClass::class, $service);
        $this->assertSame('foo', $service->key);
        $this->assertSame($serviceLocator, $service->serviceLocator);
        $this->assertSame(['foo' => 'bar'], $service->extra);
    }

    /**
     * Has.
     *
     * Test that resolver can check for service existence.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::getService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::hasService()
     */
    public function testHasService(): void
    {
        $resolver = new FactoryResolver();

        $this->assertFalse($resolver->hasService('foo'));
    }

    /**
     * Register.
     *
     * Test that a invalid factory fqcn can not be registered.
     *
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::addFactory()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Factory\Exception\InvalidFactoryType::__construct()
     * @expectedException        \ExtendsFramework\ServiceLocator\Resolver\Factory\Exception\InvalidFactoryType
     * @expectedExceptionMessage Factory must be a subclass of ServiceFactoryInterface, got "bar".
     */
    public function testRegisterInvalidFqcn(): void
    {
        $resolver = new FactoryResolver();
        $resolver->addFactory('foo', 'bar');
    }

    /**
     * Service exception.
     *
     * Test that exception from factory can be caught.
     *
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::addFactory()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::getService()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Factory\Exception\ServiceCreateFailed::__construct()
     * @expectedException        \ExtendsFramework\ServiceLocator\Resolver\Factory\Exception\ServiceCreateFailed
     * @expectedExceptionMessage Failed to create service for key "foo". See previous exception for more details.
     */
    public function testServiceException(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new FactoryResolver();
        $resolver
            ->addFactory('foo', FailedFactory::class)
            ->getService('foo', $serviceLocator);
    }

    /**
     * Create.
     *
     * Test that static factory will return resolver interface.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::factory()
     */
    public function testCreate(): void
    {
        $resolver = FactoryResolver::factory([
            'A' => FactoryStub::class,
        ]);

        $this->assertInstanceOf(ResolverInterface::class, $resolver);
    }
}

class FactoryStub implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $service = new stdClass();
        $service->key = $key;
        $service->serviceLocator = $serviceLocator;
        $service->extra = $extra;

        return $service;
    }
}

class FailedFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        throw new class extends RuntimeException implements ServiceFactoryException
        {
        };
    }
}
