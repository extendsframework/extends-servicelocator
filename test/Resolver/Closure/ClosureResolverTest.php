<?php
declare(strict_types = 1);

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
                $service = new stdClass();

                return $service;
            })
            ->get('foo', $serviceLocator);

        $this->assertInstanceOf(stdClass::class, $service);
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
                $service = new stdClass();

                return $service;
            })
            ->unregister('foo')
            ->get('foo', $serviceLocator);

        $this->assertNull($service);
    }
}
