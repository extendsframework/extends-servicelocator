<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Reflection;

use ExtendsFramework\ServiceLocator\Resolver\ResolverException;
use ReflectionException;
use ReflectionParameter;

class ReflectionResolverException extends ResolverException
{
    /**
     * Returns a new instance when reflection fails.
     *
     * @param ReflectionException $exception
     * @param string              $class
     * @return ResolverException
     */
    public static function forFailedReflection(ReflectionException $exception, string $class): ResolverException
    {
        return new static(sprintf(
            'Failed to reflect class "%s".',
            $class
        ), 0, $exception);

    }

    /**
     * Returns an new instance when the reflection parameter $name is not an object.
     *
     * @param ReflectionParameter $parameter
     * @return ResolverException
     */
    public static function forInvalidParameter(ReflectionParameter $parameter): ResolverException
    {
        return new static(sprintf(
            'Parameter "%s" MUST be a class.',
            $parameter->getName()
        ));
    }
}
