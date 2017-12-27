<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Reflection;

use ExtendsFramework\ServiceLocator\Resolver\Reflection\Exception\InvalidParameter;
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
    public function getService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $class = $this->classes[$key];
        $values = $this->values($class, $serviceLocator);

        return new $class(...$values);
    }

    /**
     * @inheritDoc
     */
    public static function factory(array $services): ResolverInterface
    {
        $resolver = new static();
        foreach ($services as $key => $class) {
            $resolver->addReflection($key, $class);
        }

        return $resolver;
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
        $constructor = (new ReflectionClass($class))->getConstructor();

        $values = [];
        if (($constructor instanceof ReflectionMethod) === true) {
            foreach ($constructor->getParameters() as $parameter) {
                $reflection = $parameter->getClass();
                if (($reflection instanceof ReflectionClass) === false) {
                    throw new InvalidParameter($parameter);
                }

                $name = $reflection->getName();
                if ($name === ServiceLocatorInterface::class) {
                    $values[] = $serviceLocator;
                } else {
                    $values[] = $serviceLocator->getService($reflection->getName());
                }
            }
        }

        return $values;
    }
}
