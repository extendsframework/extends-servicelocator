<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator;

use ExtendsFramework\ServiceLocator\Exception\UnknownResolverType;
use ExtendsFramework\ServiceLocator\Resolver\Alias\AliasResolver;
use ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver;
use ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver;
use ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use PHPUnit\Framework\TestCase;
use stdClass;

class ServiceLocatorFactoryTest extends TestCase
{
    /**
     * Create.
     *
     * Test that a ServiceLocatorInterface will be created from a config.
     *
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocatorFactory::create()
     */
    public function testCreate(): void
    {
        $factory = new ServiceLocatorFactory();
        $serviceLocator = $factory->create([
            ServiceLocatorInterface::class => [
                AliasResolver::class => [
                    'foo' => 'bar',
                ],
                ClosureResolver::class => [
                    'foo' => static function () {
                    },
                ],
                FactoryResolver::class => [
                    'foo' => FactoryStub::class,
                ],
                InvokableResolver::class => [
                    'foo' => stdClass::class,
                ],
                ReflectionResolver::class => [
                    'foo' => stdClass::class,
                ],
            ],
        ]);

        $this->assertIsObject($serviceLocator);
    }

    /**
     * Unknown resolver.
     *
     * Test that a unknown resolver can not be found.
     *
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocatorFactory::create()
     * @covers \ExtendsFramework\ServiceLocator\Exception\UnknownResolverType::__construct()
     */
    public function testUnknownResolver(): void
    {
        $this->expectException(UnknownResolverType::class);
        $this->expectExceptionMessage('Resolver must be instance or subclass of ResolverInterface, got "A".');

        $factory = new ServiceLocatorFactory();
        $factory->create([
            ServiceLocatorInterface::class => [
                'A' => [],
            ],
        ]);
    }
}
