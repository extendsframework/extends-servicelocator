<?php
declare(strict_types = 1);

namespace ExtendsFramework\ServiceLocator\Resolver\Reflection;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class ReflectionResolverTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::register()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::get()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::has()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::values()
     */
    public function testCanRegisterReflectionClassAndGetServiceForKey(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('get')
            ->with(ClassB::class)
            ->willReturn(new ClassB());

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ReflectionResolver();
        $service = $resolver
            ->register(ClassA::class, ClassA::class)
            ->get(ClassA::class, $serviceLocator);

        $this->assertInstanceOf(ClassA::class, $service);
    }

    /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::register()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::unregister()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::get()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::has()
     */
    public function testCanUnregisterReflectionClassAndNotGetServiceForKey(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ReflectionResolver();
        $service = $resolver
            ->register(ClassA::class, ClassA::class)
            ->unregister(ClassA::class)
            ->get(ClassA::class, $serviceLocator);

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
    public function testCanNotCreateClassWithNonObjectParameter(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ReflectionResolver();
        $resolver
            ->register(ClassC::class, ClassC::class)
            ->get(ClassC::class, $serviceLocator);
    }
}

class ClassA
{
    public function __construct(ClassB $b)
    {
    }
}

class ClassB
{
}

class ClassC
{
    public function __construct($name)
    {
    }
}
