<?php

namespace ExtendsFramework\ServiceLocator\Resolver\Invokable;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit_Framework_TestCase;
use stdClass;

class InvokableResolverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::register()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::get()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::has()
     */
    public function testCanRegisterInvokableAndGetServiceForKey()
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new InvokableResolver();
        $service = $resolver
            ->register('foo', stdClass::class)
            ->get('foo', $serviceLocator);

        $this->assertInstanceOf(stdClass::class, $service);
    }

    /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::register()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::unregister()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::get()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::has()
     */
    public function testCanUnregisterInvokableAndNotGetServiceForKey()
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new InvokableResolver();
        $service = $resolver
            ->register('foo', stdClass::class)
            ->unregister('foo')
            ->get('foo', $serviceLocator);

        $this->assertNull($service);
    }

    /**
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::register()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Invokable\Exception\UnknownInvokableType::forNonExistingClass()
     * @expectedException        \ExtendsFramework\ServiceLocator\Resolver\Invokable\Exception\UnknownInvokableType
     * @expectedExceptionMessage Invokable MUST be a valid class, got "bar".
     */
    public function testCanNotRegisterUnknownFactoryString()
    {
        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new InvokableResolver();
        $resolver->register('foo', 'bar');
    }
}
