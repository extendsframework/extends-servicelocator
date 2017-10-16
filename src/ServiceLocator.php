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
     * An associative array with all the shared services.
     *
     * @var array
     */
    protected $shared = [];

    /**
     * Service locator config.
     *
     * @var array
     */
    protected $config;

    /**
     * ServiceLocator constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function getService(string $key, array $extra = null)
    {
        $service = $this->getSharedService($key);
        if ($service === null) {
            $resolver = $this->getResolver($key);
            if ($resolver instanceof ResolverInterface) {
                $service = $resolver->getService($key, $this, $extra);

                if ($extra === null) {
                    $this->shared[$key] = $service;
                }
            } else {
                throw new ServiceNotFound($key);
            }
        }

        return $service;
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): array
    {
        return $this->config;
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
     * Get shared service for $key.
     *
     * If no service is shared, null will be returned.
     *
     * @param string $key
     * @return mixed
     */
    protected function getSharedService(string $key)
    {
        if (array_key_exists($key, $this->shared) === true) {
            return $this->shared[$key];
        }

        return null;
    }
}
