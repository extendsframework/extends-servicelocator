<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Alias;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class AliasResolverTest extends TestCase
{
    /**
     * Register.
     *
     * Test that a new alias can be registered.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Alias\AliasResolver::addAlias()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Alias\AliasResolver::getService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Alias\AliasResolver::hasService()
     */
    public function testRegister(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with('bar')
            ->willReturn(new stdClass());

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new AliasResolver();
        $service = $resolver
            ->addAlias('foo', 'bar')
            ->getService('foo', $serviceLocator);

        $this->assertInstanceOf(stdClass::class, $service);
    }

    /**
     * Has.
     *
     * Test that resolver can check for service existence.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Alias\AliasResolver::getService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Alias\AliasResolver::hasService()
     */
    public function testHas(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new AliasResolver();

        $this->assertFalse($resolver->hasService('foo'));
        $this->assertNull($resolver->getService('foo', $serviceLocator));
    }
}
