<?php

namespace ExtendsFramework\ServiceLocator\Exception;

use ExtendsFramework\ServiceLocator\ServiceLocatorException;

class ServiceNotFound extends ServiceLocatorException
{
    /**
     * Returns a new instance when service with $key can not be found.
     *
     * @param string $key
     * @return static
     */
    public static function forService($key)
    {
        return new static(sprintf(
            'Service with key "%s" MUST exist.',
            $key
        ));
    }
}
