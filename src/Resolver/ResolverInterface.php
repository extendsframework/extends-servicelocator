<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver;

use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

interface ResolverInterface
{
    /**
     * Check if resolver can resolve service for $key.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Get service for $key.
     *
     * @param string                  $key
     * @param ServiceLocatorInterface $serviceLocator
     * @throws ResolverException
     * @throws ServiceLocatorException
     * @return mixed
     */
    public function get(string $key, ServiceLocatorInterface $serviceLocator);

    /**
     * Create a new resolver.
     *
     * Throws an exception when creation fails.
     *
     * @param array $services
     * @return ResolverInterface
     * @throws ResolverException
     */
    public static function create(array $services): ResolverInterface;
}
