<?php
declare(strict_types = 1);

namespace ExtendsFramework\ServiceLocator;

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
    public function has(string $key): bool
    {
        if (isset($this->services[$key])) {
            return true;
        }

        return $this->resolver($key) instanceof ResolverInterface;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key)
    {
        if (!$this->has($key)) {
            throw ServiceNotFound::forService($key);
        }

        $service = $this->service($key);
        if (!$service) {
            $resolver = $this->resolver($key);
            $service = $resolver->get($key, $this);
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
     * @return ServiceLocator
     */
    public function register(ResolverInterface $resolver, string $key): ServiceLocator
    {
        $this->resolvers[$key] = $resolver;

        return $this;
    }

    /**
     * Unregister the resolver for $key.
     *
     * @param string $key
     * @return ServiceLocator
     */
    public function unregister(string $key): ServiceLocator
    {
        unset($this->resolvers[$key]);

        return $this;
    }

    /**
     * Get resolver for $key.
     *
     * All resolvers will be checked if it can resolve service for $key. First resolver which can will be returned.
     *
     * @param string $key
     * @return ResolverInterface
     */
    protected function resolver(string $key): ?ResolverInterface
    {
        foreach ($this->resolvers as $resolver) {
            if ($resolver->has($key)) {
                return $resolver;
            }
        }

        return null;
    }

    /**
     * Get cached service for $key.
     *
     * If no service is cached, null will be returned.
     *
     * @param string $key
     * @return mixed
     */
    protected function service(string $key)
    {
        if (isset($this->services[$key])) {
            return $this->services[$key];
        }

        return null;
    }
}
