<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator;

interface ServiceLocatorInterface
{
    /**
     * Get a service with the name $key.
     *
     * A shared service will be created when $extra is null. If not, a managed service will be created. An exception
     * will be thrown when no service is found for $key or service is an non object.
     *
     * @param string $key
     * @param array  $extra
     * @return object
     * @throws ServiceLocatorException
     */
    public function getService(string $key, array $extra = null);

    /**
     * Get global config.
     *
     * @return array
     */
    public function getConfig(): array;
}
