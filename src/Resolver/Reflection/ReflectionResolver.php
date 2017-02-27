<?php
declare(strict_types = 1);

namespace ExtendsFramework\ServiceLocator\Resolver\Reflection;

use ExtendsFramework\ServiceLocator\Resolver\Reflection\Exception\InvalidConstructorParameter;
use ExtendsFramework\ServiceLocator\Resolver\ResolverException;
use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ReflectionClass;
use ReflectionMethod;

class ReflectionResolver implements ResolverInterface
{
    /**
     * An associative array which holds the classes.
     *
     * @var array
     */
    protected $classes;

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return isset($this->classes[$key]);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, ServiceLocatorInterface $serviceLocator)
    {
        if (!$this->has($key)) {
            return null;
        }

        $class = $this->classes[$key];
        $values = $this->values($class, $serviceLocator);

        return new $class(...$values);
    }

    /**
     * Register $class for $key.
     *
     * @param string $key
     * @param string $class
     * @return ReflectionResolver
     */
    public function register(string $key, string $class): ReflectionResolver
    {
        $this->classes[$key] = $class;

        return $this;
    }

    /**
     * Unregister class for $key.
     *
     * @param string $key
     * @return ReflectionResolver
     */
    public function unregister(string $key): ReflectionResolver
    {
        unset($this->classes[$key]);

        return $this;
    }

    /**
     * Get construct parameters for $class.
     *
     * Parameters will be obtained by reflection. A parameter must be a class which can be resolved by the service
     * locator. When the parameter is not a class, or can not be resolved by the service locator, an exception will
     * be thrown.
     *
     * @param string                  $class
     * @param ServiceLocatorInterface $serviceLocator
     * @return array
     * @throws ResolverException
     * @throws ServiceLocatorException
     */
    protected function values(string $class, ServiceLocatorInterface $serviceLocator): array
    {
        $values = [];
        $constructor = (new ReflectionClass($class))->getConstructor();
        if ($constructor instanceof ReflectionMethod) {
            foreach ($constructor->getParameters() as $parameter) {
                $reflection = $parameter->getClass();
                if (!$reflection instanceof ReflectionClass) {
                    throw InvalidConstructorParameter::forName($parameter->getName());
                }

                $values[] = $serviceLocator->get($reflection->getName());
            }
        }

        return $values;
    }
}
