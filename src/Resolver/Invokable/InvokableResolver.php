<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Invokable;

use ExtendsFramework\ServiceLocator\Resolver\Invokable\Exception\NonExistingClass;
use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class InvokableResolver implements ResolverInterface
{
    /**
     * An associative array which holds the invokables.
     *
     * @var array
     */
    private $invokables = [];

    /**
     * @inheritDoc
     * @throws InvokableResolverException
     */
    public static function factory(array $services): ResolverInterface
    {
        $resolver = new static();
        foreach ($services as $key => $invokable) {
            $resolver->addInvokable($key, $invokable);
        }

        return $resolver;
    }

    /**
     * @inheritDoc
     */
    public function hasService(string $key): bool
    {
        return isset($this->invokables[$key]);
    }

    /**
     * @inheritDoc
     */
    public function getService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
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
     * @throws InvokableResolverException
     */
    public function addInvokable($key, $invokable): InvokableResolver
    {
        if (!class_exists($invokable)) {
            throw new NonExistingClass($invokable);
        }

        $this->invokables[$key] = (string)$invokable;

        return $this;
    }
}
