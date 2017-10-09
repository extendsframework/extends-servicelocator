<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Closure;

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
     */
    public function testRegister(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ClosureResolver();
        $service = $resolver
            ->addClosure('foo', function () {
                return new stdClass();
            })
            ->getService('foo', $serviceLocator);

        $this->assertInstanceOf(stdClass::class, $service);
    }

    /**
     * Has.
     *
     * Test that resolver can check for service existence.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver::getService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver::hasService()
     */
    public function testHas(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ClosureResolver();

        $this->assertFalse($resolver->hasService('foo'));
        $this->assertNull($resolver->getService('foo', $serviceLocator));
    }
}
