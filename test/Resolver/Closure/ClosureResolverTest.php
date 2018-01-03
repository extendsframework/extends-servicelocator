<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Closure;

use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class ClosureResolverTest extends TestCase
{
    /**
     * Register.
     *
     * Test that a new closure can be registered.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver::addClosure()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver::getService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver::hasService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver::getClosures()
     */
    public function testRegister(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ClosureResolver();
        $service = $resolver
            ->addClosure('foo', function (string $key, ServiceLocatorInterface $serviceLocator, array $extra = null) {
                $service = new stdClass();
                $service->key = $key;
                $service->serviceLocator = $serviceLocator;
                $service->extra = $extra;

                return $service;
            })
            ->getService('foo', $serviceLocator, ['foo' => 'bar']);

        $this->assertInstanceOf(stdClass::class, $service);
        $this->assertSame('foo', $service->key);
        $this->assertSame($serviceLocator, $service->serviceLocator);
        $this->assertSame(['foo' => 'bar'], $service->extra);
    }

    /**
     * Has.
     *
     * Test that resolver can check for service existence.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver::getService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver::hasService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver::getClosures()
     */
    public function testHasService(): void
    {
        $resolver = new ClosureResolver();

        $this->assertFalse($resolver->hasService('foo'));
    }

    /**
     * Create.
     *
     * Test that static factory will return resolver interface.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver::factory()
     */
    public function testCreate(): void
    {
        $resolver = ClosureResolver::factory([
            'A' => function () {
            },
        ]);

        $this->assertInstanceOf(ResolverInterface::class, $resolver);
    }
}
