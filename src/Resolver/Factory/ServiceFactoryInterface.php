<?php

namespace ExtendsFramework\ServiceLocator\Resolver\Factory;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

interface ServiceFactoryInterface
{
    /**
     * Create an service object for $key.
     *
     * @param string                  $key
     * @param ServiceLocatorInterface $serviceLocator
     * @return object
     */
    public function create($key, ServiceLocatorInterface $serviceLocator);
}
