<?php

namespace ExtendsFramework\ServiceLocator;

use ExtendsFramework\ServiceLocator\Exception\InvalidServiceType;
use ExtendsFramework\ServiceLocator\Exception\ServiceNotFound;

interface ServiceLocatorInterface
{
    /**
     * Check if the service locator has a service with the name $key.
     *
     * An exception will be thrown when $key is invalid.
     *
     * @param string $key
     * @throws ServiceNotFound
     * @return bool
     */
    public function has($key);

    /**
     * Get a service with the name $key.
     *
     * An exception will be thrown when $key is invalid, no service is found for $key or the returned service is not
     * a object.
     *
     * @param string $key
     * @return object
     * @throws ServiceNotFound
     * @throws InvalidServiceType
     */
    public function get($key);
}
