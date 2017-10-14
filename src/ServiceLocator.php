<?php
declare(strict_types=1);

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
    public function getService(string $key)
    {
        $service = $this->getCachedService($key);
        if ($service === null) {
            $resolver = $this->getResolver($key);
            if ($resolver instanceof ResolverInterface) {
                $this->services[$key] = $service = $resolver->getService($key, $this);
            } else {
                throw new ServiceNotFound($key);
            }
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
    public function addResolver(ResolverInterface $resolver, string $key): ServiceLocator
    {
        $this->resolvers[$key] = $resolver;

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
    protected function getResolver(string $key): ?ResolverInterface
    {
        foreach ($this->resolvers as $resolver) {
            if ($resolver->hasService($key) === true) {
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
    protected function getCachedService(string $key)
    {
        if (array_key_exists($key, $this->services) === true) {
            return $this->services[$key];
        }

        return null;
    }
}
