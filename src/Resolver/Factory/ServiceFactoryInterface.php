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
     * @return mixed
     */
    public function create($key, ServiceLocatorInterface $serviceLocator);
}
