<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Reflection\Exception;

use Exception;
use ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolverException;
use ReflectionException;

class ReflectionFailed extends Exception implements ReflectionResolverException
{
    /**
     * Reflection failed for $class with $exception.s
     *
     * @param ReflectionException $exception
     * @param string              $class
     */
    public function __construct(ReflectionException $exception, string $class)
    {
        parent::__construct(sprintf(
            'Failed to reflect class "%s", got exception "%s".',
            $class,
            $exception->getMessage()
        ), 0, $exception);
    }
}
