<?php

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
    public function has($key);

    /**
     * Get service for $key.
     *
     * @param string                  $key
     * @param ServiceLocatorInterface $serviceLocator
     * @throws ResolverException
     * @throws ServiceLocatorException
     * @return mixed
     */
    public function get($key, ServiceLocatorInterface $serviceLocator);
}
