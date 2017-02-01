<?php

namespace ExtendsFramework\ServiceLocator\Exception;

use RuntimeException;

class InvalidServiceType extends RuntimeException
{
    /**
     * Returns an new instance when $service is not an object.
     *
     * @param string $key
     * @param mixed  $service
     * @return static
     */
    public static function forNonObject($key, $service)
    {
        $exception = new static(sprintf('Service for key "%s" MUST be an object, got "%s".', $key, gettype($service)));
        return $exception;
    }
}
