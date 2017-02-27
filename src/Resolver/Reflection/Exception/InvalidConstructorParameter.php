<?php
declare(strict_types = 1);

namespace ExtendsFramework\ServiceLocator\Resolver\Reflection\Exception;

use ExtendsFramework\ServiceLocator\Resolver\ResolverException;

class InvalidConstructorParameter extends ResolverException
{
    /**
     * Returns an new instance when the reflection parameter $name is not an object.
     *
     * @param string $name
     * @return ResolverException
     */
    public static function forName(string $name): ResolverException
    {
        return new static(\sprintf(
            'Parameter "%s" MUST be a class.',
            $name
        ));
    }
}
