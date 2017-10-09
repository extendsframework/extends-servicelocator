<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator;

use ExtendsFramework\ServiceLocator\Exception\UnknownResolverType;
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
    public function createService(array $resolvers): ServiceLocatorInterface
    {
        $serviceLocator = new ServiceLocator();
        foreach ($resolvers as $fqcn => $services) {
            $resolver = $this->getResolver($fqcn, $services);
            $serviceLocator->addResolver($resolver, $fqcn);
        }

        return $serviceLocator;
    }

    /**
     * Get resolver with $name and register $services.
     *
     * An exception will be thrown when a resolver for $name can not be found.
     *
     * @param string   $fqcn
     * @param iterable $services
     * @return ResolverInterface
     * @throws ServiceLocatorException
     */
    protected function getResolver(string $fqcn, iterable $services): ResolverInterface
    {
        switch ($fqcn) {
            case AliasResolver::class:
                $resolver = new AliasResolver();
                foreach ($services as $key => $service) {
                    $resolver->addAlias($key, $service);
                }

                break;
            case ClosureResolver::class:
                $resolver = new ClosureResolver();
                foreach ($services as $key => $service) {
                    $resolver->addClosure($key, $service);
                }

                break;
            case FactoryResolver::class:
                $resolver = new FactoryResolver();
                foreach ($services as $key => $service) {
                    $resolver->addFactory($key, $service);
                }

                break;
            case InvokableResolver::class:
                $resolver = new InvokableResolver();
                foreach ($services as $key => $service) {
                    $resolver->addInvokable($key, $service);
                }

                break;
            case ReflectionResolver::class:
                $resolver = new ReflectionResolver();
                foreach ($services as $key => $service) {
                    $resolver->addReflection($key, $service);
                }

                break;
            default:
                throw new UnknownResolverType($fqcn);
        }

        return $resolver;
    }
}
