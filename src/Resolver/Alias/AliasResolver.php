<?php
declare(strict_types = 1);

namespace ExtendsFramework\ServiceLocator\Resolver\Alias;

use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class AliasResolver implements ResolverInterface
{
    /**
     * An associative array which holds the aliases.
     *
     * @var array
     */
    protected $aliases = [];

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return isset($this->aliases[$key]);
    }

    /**
     * If resolver has an alias for $key, the alias will be used to get the service from the service locator. A
     * infinite loop between aliases and services will not be detected.
     *
     * @inheritDoc
     */
    public function get(string $key, ServiceLocatorInterface $serviceLocator)
    {
        if (!$this->has($key)) {
            return null;
        }

        return $serviceLocator->get($this->aliases[$key]);
    }

    /**
     * Register $alias for $key.
     *
     * @param string $key
     * @param string $alias
     * @return AliasResolver
     */
    public function register(string $key, string $alias): AliasResolver
    {
        $this->aliases[$key] = (string)$alias;

        return $this;
    }

    /**
     * Unregister alias for $key.
     *
     * @param string $key
     * @return AliasResolver
     */
    public function unregister(string $key): AliasResolver
    {
        unset($this->aliases[$key]);

        return $this;
    }
}
