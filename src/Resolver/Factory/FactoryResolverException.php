<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Factory;

use ExtendsFramework\ServiceLocator\Resolver\ResolverException;

class FactoryResolverException extends ResolverException
{
    /**
     * Returns an new instance when $factory string is not a valid subclass of.
     *
     * @param string $factory
     * @return ResolverException
     */
    public static function forUnknownStringType(string $factory): ResolverException
    {
        return new static(sprintf(
            'Factory MUST be a FQCN to an instance of Factory, got "%s".',
            $factory
        ));
    }

    /**
     * Returns an new instance when $factory is not a valid instance of.
     *
     * @param $factory
     * @return ResolverException
     */
    public static function forUnknownStringObject($factory): ResolverException
    {
        return new static(sprintf(
            'Factory MUST be object and instance of Factory, got "%s".',
            is_object($factory) ? get_class($factory) : gettype($factory)
        ));
    }
}
