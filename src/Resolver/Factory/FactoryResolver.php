<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Factory;

use ExtendsFramework\ServiceLocator\Resolver\Factory\Exception\InvalidFactoryType;
use ExtendsFramework\ServiceLocator\Resolver\Factory\Exception\ServiceCreateFailed;
use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use Throwable;

class FactoryResolver implements ResolverInterface
{
    /**
     * An associative array which holds the factories.
     *
     * @var ServiceFactoryInterface[]
     */
    protected $factories = [];

    /**
     * @inheritDoc
     */
    public function hasService(string $key): bool
    {
        return array_key_exists($key, $this->factories) === true;
    }

    /**
     * When the factory is a string, a new instance will be created and replaces the string.
     *
     * @inheritDoc
     */
    public function getService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null)
    {
        if ($this->hasService($key) === false) {
            return null;
        }

        $factory = $this->factories[$key];
        if (is_string($factory) === true) {
            $factory = new $factory();
            $this->factories[$key] = $factory;
        }

        try {
            return $factory->createService($key, $serviceLocator, $extra);
        } catch (Throwable $exception) {
            throw new ServiceCreateFailed($key, $exception);
        }
    }

    /**
     * Register $factory for $key.
     *
     * An exception will be thrown when $factory is not an subclass of ServiceFactoryInterface.
     *
     * @param string $key
     * @param string $factory
     * @return FactoryResolver
     * @throws FactoryResolverException
     */
    public function addFactory(string $key, string $factory): FactoryResolver
    {
        if (is_string($factory) === false || is_subclass_of($factory, ServiceFactoryInterface::class, true) === false) {
            throw new InvalidFactoryType($factory);
        }

        $this->factories[$key] = $factory;

        return $this;
    }
}
