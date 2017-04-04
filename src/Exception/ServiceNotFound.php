<?php
declare(strict_types = 1);

namespace ExtendsFramework\ServiceLocator\Exception;

use ExtendsFramework\ServiceLocator\ServiceLocatorException;

class ServiceNotFound extends ServiceLocatorException
{
    /**
     * Returns a new instance when service with $key can not be found.
     *
     * @param string $key
     * @return ServiceLocatorException
     */
    public static function forService(string $key): ServiceLocatorException
    {
        return new static(sprintf(
            'Service with key "%s" MUST exist.',
            $key
        ));
    }
}
