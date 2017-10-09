<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Reflection;

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
     */
    public function testHas(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ReflectionResolver();

        $this->assertFalse($resolver->hasService('foo'));
        $this->assertNull($resolver->getService('foo', $serviceLocator));
    }

    /**
     * Reflection failed.
     *
     * Test that reflection will fail for non existing class.
     *
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::addReflection()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::getService()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::hasService()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::values()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\Exception\ReflectionFailed::__construct()
     * @expectedException        \ExtendsFramework\ServiceLocator\Resolver\Reflection\Exception\ReflectionFailed
     * @expectedExceptionMessage Failed to reflect class "A", got exception "Class A does not exist".
     */
    public function testReflectionFailed(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ReflectionResolver();
        $resolver
            ->addReflection(ClassC::class, 'A')
            ->getService(ClassC::class, $serviceLocator);
    }

    /**
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::addReflection()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::getService()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::hasService()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::values()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\Exception\InvalidParameter::__construct()
     * @expectedException        \ExtendsFramework\ServiceLocator\Resolver\Reflection\Exception\InvalidParameter
     * @expectedExceptionMessage Reflection parameter "name" must be a class, got type "string".
     */
    public function testCanNotCreateClassWithNonObjectParameter(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ReflectionResolver();
        $resolver
            ->addReflection(ClassC::class, ClassC::class)
            ->getService(ClassC::class, $serviceLocator);
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
    public function __construct(string $name)
    {
    }
}
