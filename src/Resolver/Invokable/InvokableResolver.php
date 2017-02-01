<?php

namespace ExtendsFramework\ServiceLocator\Resolver\Invokable;

use ExtendsFramework\ServiceLocator\Resolver\Invokable\Exception\UnknownInvokableType;
use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class InvokableResolver implements ResolverInterface
{
    /**
     * An associative array which holds the invokables.
     *
     * @var
     */
    protected $invokables = [];

    /**
     * @inheritDoc
     */
    public function has($key)
    {
        $exists = isset($this->invokables[$key]);
        return $exists;
    }

    /**
     * @inheritDoc
     */
    public function get($key, ServiceLocatorInterface $serviceLocator)
    {
        if (!$this->has($key)) {
            return null;
        }

        $invokable = $this->invokables[$key];
        $service = new $invokable();
        return $service;
    }

    /**
     * Register $invokable for $key.
     *
     * An exception will be thrown when $invokable is not a existing class.
     *
     * @param string $key
     * @param string $invokable
     * @return $this
     * @throws UnknownInvokableType
     */
    public function register($key, $invokable)
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
     * @return $this
     */
    public function unregister($key)
    {
        unset($this->invokables[$key]);
        return $this;
    }
}
