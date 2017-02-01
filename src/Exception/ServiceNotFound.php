<?php

namespace ExtendsFramework\ServiceLocator\Exception;

use InvalidArgumentException;

class ServiceNotFound extends InvalidArgumentException
{
    /**
     * Returns a new instance when service with $key can not be found.
     *
     * @param string $key
     * @return static
     */
    public static function forService($key)
    {
        $exception = new static(sprintf('Service with key "%s" MUST exist.', $key));
        return $exception;
    }
}
