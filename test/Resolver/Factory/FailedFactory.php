<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Factory;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use RuntimeException;

class FailedFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        throw new class extends RuntimeException implements ServiceFactoryException
        {
        };
    }
}
