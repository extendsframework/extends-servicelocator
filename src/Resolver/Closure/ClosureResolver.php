<?php

namespace ExtendsFramework\ServiceLocator\Resolver\Closure;

use Closure;
use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class ClosureResolver implements ResolverInterface
{
    /**
     * A associative array which holds the closures.
     *
     * @var array
     */
    protected $closures = [];

    /**
     * @inheritDoc
     */
    public function has($key)
    {
        return isset($this->closures[$key]);
    }

    /**
     * The closure will be called with the parameters $key and $serviceLocator in specified order.
     *
     * @inheritDoc
     */
    public function get($key, ServiceLocatorInterface $serviceLocator)
    {
        if (!$this->has($key)) {
            return null;
        }

        return $this->closures[$key]($key, $serviceLocator);
    }

    /**
     * Register $closure for $key.
     *
     * @param string  $key
     * @param Closure $closure
     * @return $this
     */
    public function register($key, Closure $closure)
    {
        $this->closures[$key] = $closure;

        return $this;
    }

    /**
     * Unregister closure for $key.
     *
     * @param string $key
     * @return $this
     */
    public function unregister($key)
    {
        unset($this->closures[$key]);

        return $this;
    }
}
