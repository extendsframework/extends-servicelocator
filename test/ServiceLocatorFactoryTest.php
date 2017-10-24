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
     * Create.
     *
     * Test that a ServiceLocatorInterface will be created from a config.
     *
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocatorFactory::createService()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocatorFactory::getResolver()
     */
    public function testCreate(): void
    {
        $factory = new ServiceLocatorFactory();
        $serviceLocator = $factory->createService([
            'service_locator' => [
                AliasResolver::class => [
                    'foo' => 'bar'
                ],
                ClosureResolver::class => [
                    'foo' => function () {
                    }
                ],
                FactoryResolver::class => [
                    'foo' => FactoryStub::class
                ],
                InvokableResolver::class => [
                    'foo' => stdClass::class
                ],
                ReflectionResolver::class => [
                    'foo' => stdClass::class
                ]
            ]
        ]);

        $this->assertInstanceOf(ServiceLocatorInterface::class, $serviceLocator);
    }

    /**
     * Unknown resolver.
     *
     * Test that a unknown resolver can not be found.
     *
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocatorFactory::createService()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocatorFactory::getResolver()
     * @covers                   \ExtendsFramework\ServiceLocator\Exception\UnknownResolverType::__construct()
     * @expectedException        \ExtendsFramework\ServiceLocator\Exception\UnknownResolverType
     * @expectedExceptionMessage Resolver must be instance or subclass of ResolverInterface, got "A".
     */
    public function testUnknownResolver(): void
    {
        $factory = new ServiceLocatorFactory();
        $factory->createService([
            ServiceLocatorInterface::class => [
                'A' => []
            ]
        ]);
    }
}

class FactoryStub implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null)
    {
    }
}
