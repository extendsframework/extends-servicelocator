<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\StaticFactory;

use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\Exception\InvalidStaticFactory;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\Exception\ServiceCreateFailed;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use Throwable;

class StaticFactoryResolver implements ResolverInterface
{
    /**
     * Classes with static methods.
     *
     * @var StaticFactoryInterface[]
     */
    protected $factories = [];

    /**
     * @inheritDoc
     */
    public function hasService(string $key): bool
    {
        return array_key_exists($key, $this->getFactories()) === true;
    }

    /**
     * @inheritDoc
     */
    public function getService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $service = $this->getFactories()[$key];

        try {
            return $service::factory($key, $serviceLocator, $extra);
        } catch (Throwable $exception) {
            throw new ServiceCreateFailed($key, $exception);
        }
    }

    /**
     * @inheritDoc
     */
    public static function factory(array $services): ResolverInterface
    {
        $resolver = new static();
        foreach ($services as $key => $service) {
            $resolver->addStaticFactory($key, $service);
        }

        return $resolver;
    }

    /**
     * Add static $factory for $key.
     *
     * @param string $key
     * @param string $factory
     * @return StaticFactoryResolver
     * @throws InvalidStaticFactory
     */
    public function addStaticFactory(string $key, string $factory): StaticFactoryResolver
    {
        if (is_subclass_of($factory, StaticFactoryInterface::class, true) === false) {
            throw new InvalidStaticFactory($factory);
        }

        $this->factories[$key] = $factory;

        return $this;
    }

    /**
     * Get factories.
     *
     * @return StaticFactoryInterface[]
     */
    protected function getFactories(): array
    {
        return $this->factories;
    }
}
