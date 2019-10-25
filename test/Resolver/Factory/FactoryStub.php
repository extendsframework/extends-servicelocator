<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Factory;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use stdClass;

class FactoryStub implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $service = new stdClass();
        $service->key = $key;
        $service->serviceLocator = $serviceLocator;
        $service->extra = $extra;

        return $service;
    }
}
