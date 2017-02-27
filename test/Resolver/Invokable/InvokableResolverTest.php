<?php
declare(strict_types = 1);

namespace ExtendsFramework\ServiceLocator\Resolver\Invokable;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class InvokableResolverTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::register()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::get()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::has()
     */
    public function testCanRegisterInvokableAndGetServiceForKey(): void
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
    public function testCanUnregisterInvokableAndNotGetServiceForKey(): void
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
    public function testCanNotRegisterUnknownFactoryString(): void
    {
        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new InvokableResolver();
        $resolver->register('foo', 'bar');
    }
}
