<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Invokable;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

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
            ->addInvokable('foo', stdClass::class)
            ->getService('foo', $serviceLocator);

        $this->assertInstanceOf(stdClass::class, $service);
    }

    /**
     * Has.
     *
     * Test that resolver can check for service existence.
     *
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::getService()
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::hasService()
     */
    public function testHas(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new InvokableResolver();

        $this->assertFalse($resolver->hasService('foo'));
        $this->assertNull($resolver->getService('foo', $serviceLocator));
    }

    /**
     * Non existing class.
     *
     * Test that a non existing class can no be registered.
     *
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver::addInvokable()
     * @covers                   \ExtendsFramework\ServiceLocator\Resolver\Invokable\Exception\NonExistingClass::__construct()
     * @expectedException        \ExtendsFramework\ServiceLocator\Resolver\Invokable\Exception\NonExistingClass
     * @expectedExceptionMessage Invokable "bar" must be a existing class.
     */
    public function testNonExistingClass(): void
    {
        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new InvokableResolver();
        $resolver->addInvokable('foo', 'bar');
    }
}
