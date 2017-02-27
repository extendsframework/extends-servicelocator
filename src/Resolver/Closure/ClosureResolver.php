<?php
declare(strict_types = 1);

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
    public function has(string $key): bool
    {
        return isset($this->closures[$key]);
    }

    /**
     * The closure will be called with the parameters $key and $serviceLocator in specified order.
     *
     * @inheritDoc
     */
    public function get(string $key, ServiceLocatorInterface $serviceLocator)
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
     * @return ClosureResolver
     */
    public function register(string $key, Closure $closure): ClosureResolver
    {
        $this->closures[$key] = $closure;

        return $this;
    }

    /**
     * Unregister closure for $key.
     *
     * @param string $key
     * @return ClosureResolver
     */
    public function unregister(string $key): ClosureResolver
    {
        unset($this->closures[$key]);

        return $this;
    }
}
