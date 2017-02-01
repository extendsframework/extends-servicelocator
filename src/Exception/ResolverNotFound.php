<?php

namespace ExtendsFramework\ServiceLocator\Exception;

use InvalidArgumentException;

class ResolverNotFound extends InvalidArgumentException
{
    /**
     * Returns a new instance when no resolver can be found for $name.
     *
     * @param string $name
     * @return static
     */
    public static function forName($name)
    {
        $exception = new static(sprintf('Resolver MUST be registered with the factory, got "%s".', $name));
        return $exception;
    }
}
