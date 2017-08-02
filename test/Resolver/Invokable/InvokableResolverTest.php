<?php
declare(strict_types=1);

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

        static::assertInstanceOf(stdClass::class, $service);
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

        static::assertNull($service);
    }

    /**
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::register()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolverException::forNonExistingClass()
     * @expectedException        \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolverException
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


    /**
     * /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::create()
     */
    public function testCanCreateInvokableResolver(): void
    {
        $resolver = InvokableResolver::create([
            'foo' => stdClass::class
        ]);

        static::assertInstanceOf(InvokableResolver::class, $resolver);
    }
}
