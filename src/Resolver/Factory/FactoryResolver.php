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
    private $factories = [];

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
     * @inheritDoc
     */
    public function hasService(string $key): bool
    {
        return isset($this->factories[$key]);
    }

    /**
     * When the factory is a string, a new instance will be created and replaces the string.
     *
     * An exception will be thrown when $factory is a string and not an subclass of ServiceFactoryInterface.
     *
     * @inheritDoc
     */
    public function getService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $factory = $this->factories[$key];

        if (is_string($factory)) {
            if (!is_subclass_of($factory, ServiceFactoryInterface::class, true)) {
                throw new InvalidFactoryType($factory);
            }

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
     * @param string $key
     * @param string $factory
     *
     * @return FactoryResolver
     */
    public function addFactory(string $key, string $factory): FactoryResolver
    {
        $this->factories[$key] = $factory;

        return $this;
    }
}
