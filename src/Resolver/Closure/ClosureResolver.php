<?php
declare(strict_types=1);

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
    public function hasService(string $key): bool
    {
        return array_key_exists($key, $this->closures) === true;
    }

    /**
     * The closure will be called with the parameters $key and $serviceLocator in specified order.
     *
     * @inheritDoc
     */
    public function getService(string $key, ServiceLocatorInterface $serviceLocator)
    {
        if ($this->hasService($key) === false) {
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
    public function addClosure(string $key, Closure $closure): ClosureResolver
    {
        $this->closures[$key] = $closure;

        return $this;
    }
}
