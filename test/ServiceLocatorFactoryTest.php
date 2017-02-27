<?php
declare(strict_types = 1);

namespace ExtendsFramework\ServiceLocator;

use ExtendsFramework\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class ServiceLocatorFactoryTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocatorFactory::create()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocatorFactory::resolver()
     */
    public function testCanCreateServiceLocatorForResolvers(): void
    {
        $factory = new ServiceLocatorFactory();
        $serviceLocator = $factory->create([
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

        $this->assertInstanceOf(ServiceLocatorInterface::class, $serviceLocator);
    }

    /**
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocatorFactory::create()
     * @covers                   \ExtendsFramework\ServiceLocator\ServiceLocatorFactory::resolver()
     * @covers                   \ExtendsFramework\ServiceLocator\Exception\ResolverNotFound::forName()
     * @expectedException        \ExtendsFramework\ServiceLocator\Exception\ResolverNotFound
     * @expectedExceptionMessage Resolver MUST be registered with the factory, got "A".
     */
    public function testCanNotCreateWithUnknownResolver(): void
    {
        $factory = new ServiceLocatorFactory();
        $factory->create([
            'A' => [],
        ]);
    }
}
