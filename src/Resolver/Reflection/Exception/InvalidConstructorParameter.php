<?php

namespace ExtendsFramework\ServiceLocator\Resolver\Reflection\Exception;

use ExtendsFramework\ServiceLocator\Resolver\ResolverException;

class InvalidConstructorParameter extends ResolverException
{
    /**
     * Returns an new instance when the reflection parameter $name is not an object.
     *
     * @param string $name
     * @return static
     */
    public static function forName($name)
    {
        return new static(sprintf(
            'Parameter "%s" MUST be a class.',
            $name
        ));
    }
}
