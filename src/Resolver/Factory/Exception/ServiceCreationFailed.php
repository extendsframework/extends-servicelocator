<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Factory\Exception;

use ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolverException;
use Throwable;

class ServiceCreationFailed extends FactoryResolverException
{
    /**
     * When service creation for $key fails with $exception.
     *
     * @param string    $key
     * @param Throwable $exception
     */
    public function __construct(string $key, Throwable $exception)
    {
        parent::__construct(sprintf(
            'Failed to create service for key "%s".',
            $key
        ), 0, $exception);
    }
}
