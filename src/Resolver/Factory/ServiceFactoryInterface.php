<?php
declare(strict_types = 1);

namespace ExtendsFramework\ServiceLocator\Resolver\Factory;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

interface ServiceFactoryInterface
{
    /**
     * Create an service object for $key.
     *
     * @param string                  $key
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function create(string $key, ServiceLocatorInterface $serviceLocator);
}
