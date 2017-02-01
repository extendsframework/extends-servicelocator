<?php

namespace ExtendsFramework\ServiceLocator\Resolver\Reflection\Exception;

use InvalidArgumentException;

class InvalidConstructorParameter extends InvalidArgumentException
{
    /**
     * Returns an new instance when the reflection parameter $name is not an object.
     *
     * @param string $name
     * @return static
     */
    public static function forName($name)
    {
        $exception = new static(sprintf('Parameter "%s" MUST be a class.', $name));
        return $exception;
    }

}
