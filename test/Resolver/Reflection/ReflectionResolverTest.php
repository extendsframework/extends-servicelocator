<?php
declare(strict_types=1);

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
            ->expects(static::once())
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

        static::assertInstanceOf(ClassA::class, $service);
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

        static::assertNull($service);
    }

    /**
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::register()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::get()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::has()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::values()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolverException::forFailedReflection()
     * @expectedException        \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolverException
     * @expectedExceptionMessage Failed to reflect class "A".
     */
    public function testCanNotCreateClassWithNonExistingClass(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ReflectionResolver();
        $resolver
            ->register(ClassC::class, 'A')
            ->get(ClassC::class, $serviceLocator);
    }

    /**
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::register()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::get()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::has()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::values()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolverException::forInvalidParameter()
     * @expectedException        \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolverException
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

    /**
     * /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver::create()
     */
    public function testCanCreateReflectionResolver(): void
    {
        $resolver = ReflectionResolver::create([
            'foo' => 'bar'
        ]);

        static::assertInstanceOf(ReflectionResolver::class, $resolver);
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
