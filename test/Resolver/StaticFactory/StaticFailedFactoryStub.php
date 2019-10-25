<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\StaticFactory;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use RuntimeException;

class StaticFailedFactoryStub implements StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        throw new class extends RuntimeException implements StaticFactoryResolverException
        {
        };
    }
}
