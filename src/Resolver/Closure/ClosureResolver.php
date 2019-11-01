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
    private $closures = [];

    /**
     * @inheritDoc
     */
    public static function factory(array $services): ResolverInterface
    {
        $resolver = new static();
        foreach ($services as $key => $closure) {
            $resolver->addClosure($key, $closure);
        }

        return $resolver;
    }

    /**
     * @inheritDoc
     */
    public function hasService(string $key): bool
    {
        return isset($this->closures[$key]);
    }

    /**
     * The closure will be called with the parameters $key and $serviceLocator in specified order.
     *
     * @inheritDoc
     */
    public function getService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return $this->closures[$key]($key, $serviceLocator, $extra);
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
