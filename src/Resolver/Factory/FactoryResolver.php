<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Factory;

use ExtendsFramework\ServiceLocator\Resolver\ResolverException;
use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class FactoryResolver implements ResolverInterface
{
    /**
     * An associative array which holds the factories.
     *
     * @var array
     */
    protected $factories = [];

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return isset($this->factories[$key]);
    }

    /**
     * When the factory is a string, a new instance will be created and replaces the string.
     *
     * @inheritDoc
     */
    public function get(string $key, ServiceLocatorInterface $serviceLocator)
    {
        if (!$this->has($key)) {
            return null;
        }

        $factory = $this->factories[$key];
        if (is_string($factory)) {
            $factory = new $factory();
            $this->factories[$key] = $factory;
        }

        return $factory->create($key, $serviceLocator);
    }

    /**
     * @inheritDoc
     */
    public static function create(array $services): ResolverInterface
    {
        $resolver = new static;

        foreach ($services as $key => $factory) {
            $resolver->register($key, $factory);
        }

        return $resolver;
    }

    /**
     * Register $factory for $key.
     *
     * The $factory can be a string or object. When string or object are not an subclass or instance of
     * ServiceFactoryInterface, an exception will be thrown.
     *
     * @param string                         $key
     * @param ServiceFactoryInterface|string $factory
     * @return FactoryResolver
     * @throws ResolverException
     */
    public function register(string $key, $factory): FactoryResolver
    {
        if (is_string($factory) && !is_subclass_of($factory, ServiceFactoryInterface::class, true)) {
            throw FactoryResolverException::forUnknownStringType($factory);
        }
        if (!is_string($factory) && !$factory instanceof ServiceFactoryInterface) {
            throw FactoryResolverException::forUnknownStringObject($factory);
        }

        $this->factories[$key] = $factory;

        return $this;
    }

    /**
     * Unregister factory for $key.
     *
     * @param string $key
     * @return FactoryResolver
     */
    public function unregister(string $key): FactoryResolver
    {
        unset($this->factories[$key]);

        return $this;
    }
}
