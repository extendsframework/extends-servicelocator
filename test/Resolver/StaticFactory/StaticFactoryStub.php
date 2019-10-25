<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\StaticFactory;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use stdClass;

class StaticFactoryStub implements StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $service = new stdClass();
        $service->key = $key;
        $service->serviceLocator = $serviceLocator;
        $service->extra = $extra;

        return $service;
    }
}
