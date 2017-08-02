<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Closure;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class ClosureResolverTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver::register()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver::get()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver::has()
     */
    public function testCanRegisterClosureAndGetServiceForKey(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ClosureResolver();
        $service = $resolver
            ->register('foo', function () {
                return new stdClass();
            })
            ->get('foo', $serviceLocator);

        static::assertInstanceOf(stdClass::class, $service);
    }

    /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver::register()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver::unregister()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver::get()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver::has()
     */
    public function testCanUnregisterClosureAndNotGetServiceForKey(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ClosureResolver();
        $service = $resolver
            ->register('foo', function () {
                return new stdClass();
            })
            ->unregister('foo')
            ->get('foo', $serviceLocator);

        static::assertNull($service);
    }

    /**
     * /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver::create()
     */
    public function testCanCreateClosureResolver(): void
    {
        $resolver = ClosureResolver::create([
            'foo' => function () {
            }
        ]);

        static::assertInstanceOf(ClosureResolver::class, $resolver);
    }
}
