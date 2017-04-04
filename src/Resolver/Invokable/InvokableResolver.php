<?php
declare(strict_types = 1);

namespace ExtendsFramework\ServiceLocator\Resolver\Invokable;

use ExtendsFramework\ServiceLocator\Resolver\Invokable\Exception\UnknownInvokableType;
use ExtendsFramework\ServiceLocator\Resolver\ResolverException;
use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class InvokableResolver implements ResolverInterface
{
    /**
     * An associative array which holds the invokables.
     *
     * @var array
     */
    protected $invokables = [];

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return isset($this->invokables[$key]);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, ServiceLocatorInterface $serviceLocator)
    {
        if (!$this->has($key)) {
            return null;
        }

        $invokable = $this->invokables[$key];

        return new $invokable();
    }

    /**
     * Register $invokable for $key.
     *
     * An exception will be thrown when $invokable is not a existing class.
     *
     * @param string $key
     * @param string $invokable
     * @return InvokableResolver
     * @throws ResolverException
     */
    public function register($key, $invokable): InvokableResolver
    {
        if (!class_exists($invokable)) {
            throw UnknownInvokableType::forNonExistingClass($invokable);
        }

        $this->invokables[$key] = (string)$invokable;

        return $this;
    }

    /**
     * Unregister invokable for $key.
     *
     * @param string $key
     * @return InvokableResolver
     */
    public function unregister(string $key): InvokableResolver
    {
        unset($this->invokables[$key]);

        return $this;
    }
}
