<?php
declare(strict_types = 1);

namespace ExtendsFramework\ServiceLocator\Resolver\Invokable\Exception;

use ExtendsFramework\ServiceLocator\Resolver\ResolverException;

class UnknownInvokableType extends ResolverException
{
    /**
     * Returns an new instance when $invokable is not a existing class.
     *
     * @param string $invokable
     * @return ResolverException
     */
    public static function forNonExistingClass(string $invokable): ResolverException
    {
        return new static(\sprintf(
            'Invokable MUST be a valid class, got "%s".',
            $invokable
        ));
    }
}
