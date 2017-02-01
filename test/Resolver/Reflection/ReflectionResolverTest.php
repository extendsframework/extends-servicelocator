<?php

namespace ExtendsFramework\ServiceLocator\Resolver\Reflection;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit_Framework_TestCase;

class ReflectionResolverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::register()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::get()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::has()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::values()
     */
    public function testCanRegisterReflectionClassAndGetServiceForKey()
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('get')
            ->with(B::class)
            ->willReturn(new B());

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ReflectionResolver();
        $service = $resolver
            ->register(A::class, A::class)
            ->get(A::class, $serviceLocator);

        $this->assertInstanceOf(A::class, $service);
    }

    /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::register()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::unregister()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::get()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::has()
     */
    public function testCanUnregisterReflectionClassAndNotGetServiceForKey()
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ReflectionResolver();
        $service = $resolver
            ->register(A::class, A::class)
            ->unregister(A::class)
            ->get(A::class, $serviceLocator);

        $this->assertNull($service);
    }

    /**
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::register()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::get()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::has()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::values()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\Exception\InvalidConstructorParameter::forName
     * @expectedException        \ExtendsFramework\ServiceLocator\Resolver\Reflection\Exception\InvalidConstructorParameter
     * @expectedExceptionMessage Parameter "name" MUST be a class.
     */
    public function testCanNotCreateClassWithNonObjectParameter()
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ReflectionResolver();
        $resolver
            ->register(C::class, C::class)
            ->get(C::class, $serviceLocator);
    }
}

class A
{
    public function __construct(B $b)
    {
    }
}

class B
{
}

class C
{
    public function __construct($name)
    {
    }
}
