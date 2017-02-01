<?php

namespace ExtendsFramework\ServiceLocator\Resolver\Factory;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit_Framework_TestCase;
use stdClass;

class FactoryResolverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::register()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::get()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::has()
     */
    public function testCanRegisterFactoryClassAndGetServiceForKey()
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $factory = $this->createMock(ServiceFactoryInterface::class);
        $factory
            ->expects($this->once())
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

        $this->assertInstanceOf(stdClass::class, $service);
    }

    /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::register()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::get()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::has()
     */
    public function testCanRegisterFactoryStringAndGetServiceForKey()
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $factory = $this
            ->getMockBuilder(ServiceFactoryInterface::class)
            ->setMethods([
                '__construct',
                'create',
            ])
            ->getMock();

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new FactoryResolver();
        $resolver
            ->register('foo', get_class($factory))
            ->get('foo', $serviceLocator);
    }

    /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::register()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::unregister()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::get()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::has()
     */
    public function testCanUnregisterAndNotGetServiceForKey()
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

        $this->assertNull($service);
    }

    /**
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::register()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Factory\Exception\UnknownServiceFactoryType::forString()
     * @expectedException        \ExtendsFramework\ServiceLocator\Resolver\Factory\Exception\UnknownServiceFactoryType
     * @expectedExceptionMessage Factory MUST be a FQCN to an instance of Factory, got "bar".
     */
    public function testCanNotRegisterUnknownFactoryString()
    {
        $resolver = new FactoryResolver();
        $resolver->register('foo', 'bar');
    }

    /**
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver::register()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Factory\Exception\UnknownServiceFactoryType::forObject()
     * @expectedException        \ExtendsFramework\ServiceLocator\Resolver\Factory\Exception\UnknownServiceFactoryType
     * @expectedExceptionMessage Factory MUST be object and instance of Factory, got "stdClass".
     */
    public function testCanNotRegisterUnknownFactoryClass()
    {
        $resolver = new FactoryResolver();
        $resolver->register('foo', new stdClass());
    }
}
