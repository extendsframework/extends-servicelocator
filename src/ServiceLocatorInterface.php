<?php

namespace ExtendsFramework\ServiceLocator;

use ExtendsFramework\ServiceLocator\Resolver\ResolverException;

interface ServiceLocatorInterface
{
    /**
     * Check if the service locator has a service with the name $key.
     *
     * An exception will be thrown when $key is invalid.
     *
     * @param string $key
     * @return bool
     * @throws ServiceLocatorException
     */
    public function has($key);

    /**
     * Get a service with the name $key.
     *
     * An exception will be thrown when $key is invalid, no service is found for $key or the returned service is not
     * a object.
     *
     * @param string $key
     * @return mixed
     * @throws ServiceLocatorException
     * @throws ResolverException
     */
    public function get($key);
}
