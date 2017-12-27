<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\StaticFactory;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

interface StaticFactoryInterface
{
    /**
     * Create a service for $key.
     *
     * @param string                  $key
     * @param ServiceLocatorInterface $serviceLocator
     * @param array|null              $extra
     * @return object
     * @throws StaticFactoryResolverException
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object;
}
