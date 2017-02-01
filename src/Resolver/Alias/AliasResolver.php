<?php

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
    public function has($key)
    {
        $exists = isset($this->aliases[$key]);
        return $exists;
    }

    /**
     * If resolver has an alias for $key, the alias will be used to get the service from the service locator. A
     * infinite loop between aliases and services will not be detected.
     *
     * @inheritDoc
     */
    public function get($key, ServiceLocatorInterface $serviceLocator)
    {
        if (!$this->has($key)) {
            return null;
        }

        $name = $this->aliases[$key];
        $service = $serviceLocator->get($name);
        return $service;
    }

    /**
     * Register $alias for $key.
     *
     * @param string $key
     * @param string $alias
     * @return $this
     */
    public function register($key, $alias)
    {
        $this->aliases[$key] = (string)$alias;
        return $this;
    }

    /**
     * Unregister alias for $key.
     *
     * @param string $key
     * @return $this
     */
    public function unregister($key)
    {
        unset($this->aliases[$key]);
        return $this;
    }
}
