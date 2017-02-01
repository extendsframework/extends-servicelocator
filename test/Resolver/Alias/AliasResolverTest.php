<?php

namespace ExtendsFramework\ServiceLocator\Resolver\Alias;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit_Framework_TestCase;
use stdClass;

class AliasResolverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Alias\AliasResolver::register()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Alias\AliasResolver::get()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Alias\AliasResolver::has()
     */
    public function testCanRegisterAliasAndGetServiceForKey()
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('get')
            ->with('bar')
            ->willReturn(new stdClass());

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new AliasResolver();
        $service = $resolver
            ->register('foo', 'bar')
            ->get('foo', $serviceLocator);

        $this->assertInstanceOf(stdClass::class, $service);
    }

    /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Alias\AliasResolver::register()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Alias\AliasResolver::unregister()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Alias\AliasResolver::get()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Alias\AliasResolver::has()
     */
    public function testCanUnregisterAliasAndNotGetServiceForKey()
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new AliasResolver();
        $service = $resolver
            ->register('foo', 'bar')
            ->unregister('foo')
            ->get('foo', $serviceLocator);

        $this->assertNull($service);
    }
}
