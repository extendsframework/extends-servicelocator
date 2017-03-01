<?php
declare(strict_types = 1);

namespace ExtendsFramework\ServiceLocator;

use ExtendsFramework\ServiceLocator\Exception\ResolverNotFound;
use ExtendsFramework\ServiceLocator\Resolver\Alias\AliasResolver;
use ExtendsFramework\ServiceLocator\Resolver\Closure\ClosureResolver;
use ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver;
use ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;

class ServiceLocatorFactory implements ServiceLocatorFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(array $resolvers): ServiceLocatorInterface
    {
        $serviceLocator = new ServiceLocator();
        foreach ($resolvers as $name => $services) {
            $resolver = $this->resolver($name, $services);
            $serviceLocator->register($resolver, $name);
        }

        return $serviceLocator;
    }

    /**
     * Get resolver with $name and register $services.
     *
     * An exception will be thrown when a resolver for $name can not be found.
     *
     * @param string $name
     * @param array  $services
     * @return ResolverInterface
     * @throws ServiceLocatorException
     */
    protected function resolver(string $name, array $services): ResolverInterface
    {
        switch ($name) {
            case 'aliases':
                $resolver = new AliasResolver();
                break;
            case 'closures':
                $resolver = new ClosureResolver();
                break;
            case 'factories':
                $resolver = new FactoryResolver();
                break;
            case 'invokables':
                $resolver = new InvokableResolver();
                break;
            case 'reflections':
                $resolver = new ReflectionResolver();
                break;
            default:
                throw ResolverNotFound::forName($name);
        }

        foreach ($services as $key => $value) {
            $resolver->register($key, $value);
        }

        return $resolver;
    }
}
