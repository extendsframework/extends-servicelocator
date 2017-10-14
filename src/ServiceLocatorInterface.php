<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator;

interface ServiceLocatorInterface
{
    /**
     * Get a service with the name $key.
     *
     * An exception will be thrown when $key is invalid or no service is found for $key.
     *
     * @param string $key
     * @return mixed
     * @throws ServiceLocatorException
     */
    public function getService(string $key);
}
