<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Factory;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class FactoryResolverTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::register()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::get()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::has()
     */
    public function testCanRegisterFactoryClassAndGetServiceForKey(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $factory = $this->createMock(ServiceFactoryInterface::class);
        $factory
            ->expects(static::once())
            ->method('create')
            ->with('foo', $serviceLocator)
            ->willReturn(new stdClass());

        /**
         * @var ServiceLocatorInterface $serviceLocator
         * @var ServiceFactoryInterface $factory
         */
        $resolver = new FactoryResolver();
        $service = $resolver
            ->register('foo', $factory)
            ->get('foo', $serviceLocator);

        static::assertInstanceOf(stdClass::class, $service);
    }

    /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::register()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::get()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::has()
     */
    public function testCanRegisterFactoryStringAndGetServiceForKey(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new FactoryResolver();
        $service = $resolver
            ->register('foo', Factory::class)
            ->get('foo', $serviceLocator);

        static::assertInstanceOf(stdClass::class, $service);
    }

    /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::register()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::unregister()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::get()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::has()
     */
    public function testCanUnregisterAndNotGetServiceForKey(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $factory = $this->createMock(ServiceFactoryInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         * @var ServiceFactoryInterface $factory
         */
        $resolver = new FactoryResolver();
        $service = $resolver
            ->register('foo', $factory)
            ->unregister('foo')
            ->get('foo', $serviceLocator);

        static::assertNull($service);
    }

    /**
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::register()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolverException::forUnknownStringType()
     * @expectedException        \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolverException
     * @expectedExceptionMessage Factory MUST be a FQCN to an instance of Factory, got "bar".
     */
    public function testCanNotRegisterUnknownFactoryString(): void
    {
        $resolver = new FactoryResolver();
        $resolver->register('foo', 'bar');
    }

    /**
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::register()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolverException::forUnknownStringObject()
     * @expectedException        \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolverException
     * @expectedExceptionMessage Factory MUST be object and instance of Factory, got "stdClass".
     */
    public function testCanNotRegisterUnknownFactoryClass(): void
    {
        $resolver = new FactoryResolver();
        $resolver->register('foo', new stdClass());
    }

    /**
     * /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::create()
     */
    public function testCanCreateFactoryResolver(): void
    {
        $factory = $this->createMock(ServiceFactoryInterface::class);

        /**
         * @var ServiceFactoryInterface $factory
         */
        $resolver = FactoryResolver::create([
            'foo' => $factory
        ]);

        static::assertInstanceOf(FactoryResolver::class, $resolver);
    }
}

class Factory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(string $key, ServiceLocatorInterface $serviceLocator)
    {
        return new stdClass();
    }
}
