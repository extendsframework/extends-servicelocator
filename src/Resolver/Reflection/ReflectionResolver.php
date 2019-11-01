<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Reflection;

use ExtendsFramework\ServiceLocator\Resolver\Reflection\Exception\InvalidParameter;
use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;
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
    private $classes = [];

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
     * @inheritDoc
     */
    public function hasService(string $key): bool
    {
        return isset($this->classes[$key]);
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function getService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $class = $this->classes[$key];
        $constructor = (new ReflectionClass($class))->getConstructor();

        $values = [];
        if ($constructor instanceof ReflectionMethod) {
            foreach ($constructor->getParameters() as $parameter) {
                $reflection = $parameter->getClass();
                if (!$reflection instanceof ReflectionClass) {
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
}
