<?php
declare(strict_types=1);

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
    private $aliases = [];

    /**
     * @inheritDoc
     */
    public static function factory(array $services): ResolverInterface
    {
        $resolver = new static();
        foreach ($services as $key => $alias) {
            $resolver->addAlias($key, $alias);
        }

        return $resolver;
    }

    /**
     * @inheritDoc
     */
    public function hasService(string $key): bool
    {
        return isset($this->aliases[$key]);
    }

    /**
     * If resolver has an alias for $key, the alias will be used to get the service from the service locator. A
     * infinite loop between aliases and services will not be detected.
     *
     * @inheritDoc
     */
    public function getService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return $serviceLocator->getService($this->aliases[$key], $extra);
    }

    /**
     * Register $alias for $key.
     *
     * @param string $key
     * @param string $alias
     * @return AliasResolver
     */
    public function addAlias(string $key, string $alias): AliasResolver
    {
        $this->aliases[$key] = $alias;

        return $this;
    }
}
