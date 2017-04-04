<?php
declare(strict_types = 1);

namespace ExtendsFramework\ServiceLocator\Resolver\Factory\Exception;

use ExtendsFramework\ServiceLocator\Resolver\ResolverException;

class UnknownServiceFactoryType extends ResolverException
{
    /**
     * Returns an new instance when $factory string is not a valid subclass of.
     *
     * @param string $factory
     * @return ResolverException
     */
    public static function forString(string $factory): ResolverException
    {
        return new static(sprintf(
            'Factory MUST be a FQCN to an instance of Factory, got "%s".',
            $factory
        ));
    }

    /**
     * Returns an new instance when $factory is not a valid instance of.
     *
     * @param mixed $factory
     * @return ResolverException
     */
    public static function forObject($factory): ResolverException
    {
        return new static(sprintf(
            'Factory MUST be object and instance of Factory, got "%s".',
            is_object($factory) ? get_class($factory) : gettype($factory)
        ));
    }
}
