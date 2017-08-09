<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Factory;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use Throwable;

interface ServiceFactoryInterface
{
    /**
     * Create an service object for $key.
     *
     * @param string                  $key
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     * @throws Throwable
     */
    public function create(string $key, ServiceLocatorInterface $serviceLocator);
}
