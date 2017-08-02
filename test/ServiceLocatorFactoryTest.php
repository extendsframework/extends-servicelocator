<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator;

use ExtendsFramework\ServiceLocator\Resolver\Alias\AliasResolver;
use ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver;
use ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsFramework\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver;
use ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use PHPUnit\Framework\TestCase;
use stdClass;

class ServiceLocatorFactoryTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocatorFactory::create()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocatorFactory::createResolver()
     */
    public function testCanCreateServiceLocatorForResolvers(): void
    {
        $factory = new ServiceLocatorFactory();
        $serviceLocator = $factory->create([
            AliasResolver::class => [
                'foo' => 'bar'
            ],
            ClosureResolver::class => [
                'foo' => function () {
                }
            ],
            FactoryResolver::class => [
                'foo' => $this->createMock(ServiceFactoryInterface::class)
            ],
            InvokableResolver::class => [
                'foo' => stdClass::class
            ],
            ReflectionResolver::class => [
                'foo' => stdClass::class
            ]
        ]);

        static::assertInstanceOf(ServiceLocatorInterface::class, $serviceLocator);
    }

    /**
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocatorFactory::create()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocatorFactory::createResolver()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocatorException::forInvalidResolverType()
     * @expectedException        \ExtendsFramework\ServiceLocator\ServiceLocatorException
     * @expectedExceptionMessage Resolver MUST be instance or subclass of ResolverInterface, got "A".
     */
    public function testCanNotCreateWithUnknownResolver(): void
    {
        $factory = new ServiceLocatorFactory();
        $factory->create([
            'A' => []
        ]);
    }
}
