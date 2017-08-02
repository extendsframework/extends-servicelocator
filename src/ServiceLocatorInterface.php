<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator;

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
    public function has(string $key): bool;

    /**
     * Get a service with the name $key.
     *
     * An exception will be thrown when $key is invalid or no service is found for $key.
     *
     * @param string $key
     * @return mixed
     * @throws ServiceLocatorException
     */
    public function get(string $key);
}
