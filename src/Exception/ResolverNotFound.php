<?php
declare(strict_types = 1);

namespace ExtendsFramework\ServiceLocator\Exception;

use ExtendsFramework\ServiceLocator\ServiceLocatorException;

class ResolverNotFound extends ServiceLocatorException
{
    /**
     * Returns a new instance when no resolver can be found for $name.
     *
     * @param string $name
     * @return ServiceLocatorException
     */
    public static function forName(string $name): ServiceLocatorException
    {
        return new static(\sprintf(
            'Resolver MUST be registered with the factory, got "%s".',
            $name
        ));
    }
}
