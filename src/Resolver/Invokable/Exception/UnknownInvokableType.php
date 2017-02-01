<?php

namespace ExtendsFramework\ServiceLocator\Resolver\Invokable\Exception;

use InvalidArgumentException;

class UnknownInvokableType extends InvalidArgumentException
{
    /**
     * Returns an new instance when $invokable is not a existing class.
     *
     * @param string $invokable
     * @return static
     */
    public static function forNonExistingClass($invokable)
    {
        $exception = new static(sprintf('Invokable MUST be a valid class, got "%s".', $invokable));
        return $exception;
    }
}
