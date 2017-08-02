<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator;

use Exception;

class ServiceLocatorException extends Exception
{
    /**
     * Returns a new instance when service with $key can not be found.
     *
     * @param string $key
     * @return ServiceLocatorException
     */
    public static function forServiceNotFound(string $key): ServiceLocatorException
    {
        return new static(sprintf(
            'Service with key "%s" MUST exist.',
            $key
        ));
    }

    /**
     * Returns a new instance when no resolver can be found for $name.
     *
     * @param string $name
     * @return ServiceLocatorException
     */
    public static function forInvalidResolverType(string $name): ServiceLocatorException
    {
        return new static(sprintf(
            'Resolver MUST be instance or subclass of ResolverInterface, got "%s".',
            $name
        ));
    }
}
