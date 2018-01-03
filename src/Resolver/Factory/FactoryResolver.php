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
        return array_key_exists($key, $this->getFactories()) === true;
    }

    /**
     * When the factory is a string, a new instance will be created and replaces the string.
     *
     * @inheritDoc
     */
    public function getService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $factory = $this->getFactories()[$key];
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
     * @inheritDoc
     */
    public static function factory(array $services): ResolverInterface
    {
        $resolver = new static();
        foreach ($services as $key => $factory) {
            $resolver->addFactory($key, $factory);
        }

        return $resolver;
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
        if (is_subclass_of($factory, ServiceFactoryInterface::class, true) === false) {
            throw new InvalidFactoryType($factory);
        }

        $this->factories[$key] = $factory;

        return $this;
    }

    /**
     * Get factories.
     *
     * @return ServiceFactoryInterface[]
     */
    protected function getFactories(): array
    {
        return $this->factories;
    }
}
