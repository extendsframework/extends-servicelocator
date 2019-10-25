<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Reflection;

use ExtendsFramework\ServiceLocator\Resolver\Reflection\Exception\InvalidParameter;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class ReflectionResolverTest extends TestCase
{
    /**
     * Register.
     *
     * Test that a invokable can be registered.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::addReflection()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::getService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::hasService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::getClasses()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::values()
     */
    public function testRegister(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(ClassB::class)
            ->willReturn(new ClassB());

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ReflectionResolver();
        $service = $resolver
            ->addReflection(ClassA::class, ClassA::class)
            ->getService(ClassA::class, $serviceLocator);

        $this->assertInstanceOf(ClassA::class, $service);
    }

    /**
     * Has.
     *
     * Test that resolver can check for service existence.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::getService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::hasService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::getClasses()
     */
    public function testHasService(): void
    {
        $resolver = new ReflectionResolver();

        $this->assertFalse($resolver->hasService('foo'));
    }

    /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::addReflection()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::getService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::hasService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::getClasses()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::values()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\Exception\InvalidParameter::__construct()
     */
    public function testCanNotCreateClassWithNonObjectParameter(): void
    {
        $this->expectException(InvalidParameter::class);
        $this->expectExceptionMessage('Reflection parameter "name" must be a class, got type "string".');

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ReflectionResolver();
        $resolver
            ->addReflection(ClassC::class, ClassC::class)
            ->getService(ClassC::class, $serviceLocator);
    }

    /**
     * Create.
     *
     * Test that static factory will return resolver interface.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::factory()
     */
    public function testCreate(): void
    {
        $resolver = ReflectionResolver::factory([
            'A' => ClassA::class,
        ]);

        $this->assertIsObject($resolver);
    }
}
