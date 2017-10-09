<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Reflection;

use ExtendsFramework\ServiceLocator\Resolver\Reflection\Exception\InvalidParameter;
use ExtendsFramework\ServiceLocator\Resolver\Reflection\Exception\ReflectionFailed;
use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class ReflectionResolver implements ResolverInterface
{
    /**
     * An associative array which holds the classes.
     *
     * @var array
     */
    protected $classes = [];

    /**
     * @inheritDoc
     */
    public function hasService(string $key): bool
    {
        return array_key_exists($key, $this->classes) === true;
    }

    /**
     * @inheritDoc
     */
    public function getService(string $key, ServiceLocatorInterface $serviceLocator)
    {
        if ($this->hasService($key) === false) {
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
    public function addReflection(string $key, string $class): ReflectionResolver
    {
        $this->classes[$key] = $class;

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
     * @return iterable
     * @throws ReflectionResolverException
     * @throws ServiceLocatorException
     */
    protected function values(string $class, ServiceLocatorInterface $serviceLocator): iterable
    {
        try {
            new ReflectionClass($class);
            $constructor = (new ReflectionClass($class))->getConstructor();
        } catch (ReflectionException $exception) {
            throw new ReflectionFailed($exception, $class);
        }

        $values = [];
        if (($constructor instanceof ReflectionMethod) === true) {
            foreach ($constructor->getParameters() as $parameter) {
                $reflection = $parameter->getClass();
                if (($reflection instanceof ReflectionClass) === false) {
                    throw new InvalidParameter($parameter);
                }

                $values[] = $serviceLocator->getService($reflection->getName());
            }
        }

        return $values;
    }
}
