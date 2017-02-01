<?php

namespace ExtendsFramework\ServiceLocator;

use ExtendsFramework\ServiceLocator\Exception\InvalidServiceType;
use ExtendsFramework\ServiceLocator\Exception\ServiceNotFound;
use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;

class ServiceLocator implements ServiceLocatorInterface
{
    /**
     * An associative array with all the registered resolvers.
     *
     * @var ResolverInterface[]
     */
    protected $resolvers = [];

    /**
     * An associative array with all the cached services.
     *
     * @var array
     */
    protected $services = [];

    /**
     * @inheritDoc
     */
    public function has($key)
    {
        if (isset($this->services[$key])) {
            return true;
        }

        $resolver = $this->resolver($key);
        return boolval($resolver);
    }

    /**
     * @inheritDoc
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            throw ServiceNotFound::forService($key);
        }

        $service = $this->service($key);
        if (!$service) {
            $resolver = $this->resolver($key);
            $service = $resolver->get($key, $this);
            if (!is_object($service)) {
                throw InvalidServiceType::forNonObject($key, $service);
            }

            $this->services[$key] = $service;
        }
        return $service;
    }

    /**
     * Register a new $resolver for $key.
     *
     * When a resolver is already registered for $key, it will be overwritten.
     *
     * @param ResolverInterface $resolver
     * @param string            $key
     * @return $this
     */
    public function register(ResolverInterface $resolver, $key)
    {
        $this->resolvers[$key] = $resolver;
        return $this;
    }

    /**
     * Unregister the resolver for $key.
     *
     * @param string $key
     * @return $this
     */
    public function unregister($key)
    {
        unset($this->resolvers[$key]);
        return $this;
    }

    /**
     * Get cached service for $key.
     *
     * If no service is cached, null will be returned.
     *
     * @param string $key
     * @return object|null
     */
    protected function service($key)
    {
        if (isset($this->services[$key])) {
            return $this->services[$key];
        }
        return null;
    }

    /**
     * Get resolver for $key.
     *
     * All resolvers will be checked if it can resolve service for $key. First resolver which can will be returned.
     *
     * @param string $key
     * @return ResolverInterface|null
     */
    protected function resolver($key)
    {
        foreach ($this->resolvers as $resolver) {
            if ($resolver->has($key)) {
                return $resolver;
            }
        }
        return null;
    }
}
