<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Invokable;

use ExtendsFramework\ServiceLocator\Resolver\Invokable\Exception\NonExistingClass;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class InvokableResolverTest extends TestCase
{
    /**
     * Register.
     *
     * Test that a invokable can be registered.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::addInvokable()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::getService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::hasService()
     */
    public function testRegister(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new InvokableResolver();
        $service = $resolver
            ->addInvokable('foo', InvokableStub::class)
            ->getService('foo', $serviceLocator, ['foo' => 'bar']);

        $this->assertInstanceOf(InvokableStub::class, $service);
    }

    /**
     * Has.
     *
     * Test that resolver can check for service existence.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::getService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::hasService()
     */
    public function testHasService(): void
    {
        $resolver = new InvokableResolver();

        $this->assertFalse($resolver->hasService('foo'));
    }

    /**
     * Non existing class.
     *
     * Test that a non existing class can no be registered.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::addInvokable()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::getService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\Exception\NonExistingClass::__construct()
     */
    public function testNonExistingClass(): void
    {
        $this->expectException(NonExistingClass::class);
        $this->expectExceptionMessage('Invokable "bar" must be a existing class.');

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new InvokableResolver();
        $resolver
            ->addInvokable('foo', 'bar')
            ->getService('foo', $serviceLocator);
    }

    /**
     * Create.
     *
     * Test that static factory will return resolver interface.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::factory()
     */
    public function testCreate(): void
    {
        $resolver = InvokableResolver::factory([
            'A' => InvokableStub::class,
        ]);

        $this->assertIsObject($resolver);
    }
}
