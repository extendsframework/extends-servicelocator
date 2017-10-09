<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Factory\Exception;

use Exception;
use ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolverException;
use Throwable;

class ServiceCreateFailed extends Exception implements FactoryResolverException
{
    /**
     * When service create for $key fails with $exception.
     *
     * @param string    $key
     * @param Throwable $exception
     */
    public function __construct(string $key, Throwable $exception)
    {
        parent::__construct(sprintf(
            'Failed to create service for key "%s". See previous exception for more details.',
            $key
        ), 0, $exception);
    }
}
