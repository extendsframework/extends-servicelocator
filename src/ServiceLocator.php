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
    private $resolvers = [];

    /**
     * An associative array with all the shared services.
     *
     * @var array
     */
    private $shared = [];

    /**
     * Service locator config.
     *
     * @var array
     */
    private $config;

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
    public function getService(string $key, array $extra = null): object
    {
        if (!isset($this->shared[$key])) {
            $service = null;
            foreach ($this->resolvers as $resolver) {
                if ($resolver->hasService($key)) {
                    $service = $resolver->getService($key, $this, $extra);

                    if (!$extra) {
                        $this->shared[$key] = $service;
                    }

                    break 1;
                }
            }

            if (!$service) {
                throw new ServiceNotFound($key);
            }
        }

        return $service ?? $this->shared[$key];
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
}
