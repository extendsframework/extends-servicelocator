<?php

namespace ExtendsFramework\ServiceLocator;

use ExtendsFramework\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use PHPUnit_Framework_TestCase;
use stdClass;

class ServiceLocatorFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocatorFactory::create()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocatorFactory::resolver()
     */
    public function testCanCreateServiceLocatorForResolvers()
    {
        $factory = new ServiceLocatorFactory();
        $factory->create([
            'aliases' => [
                'A' => 'A',
            ],
            'closures' => [
                'A' => function () {
                },
            ],
            'factories' => [
                'A' => $this->createMock(ServiceFactoryInterface::class),
            ],
            'invokables' => [
                'A' => stdClass::class,
            ],
            'reflections' => [
                'A' => stdClass::class,
            ],
        ]);
    }

    /**
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocatorFactory::create()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocatorFactory::resolver()
     * @covers                   \ExtendsFramework\ServiceLocator\Exception\ResolverNotFound::forName()
     * @expectedException        \ExtendsFramework\ServiceLocator\Exception\ResolverNotFound
     * @expectedExceptionMessage Resolver MUST be registered with the factory, got "A".
     */
    public function testCanNotCreateWithUnknownResolver()
    {
        $factory = new ServiceLocatorFactory();
        $factory->create([
            'A' => [],
        ]);
    }
}
