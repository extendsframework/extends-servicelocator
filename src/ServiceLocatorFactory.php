<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator;

use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;

class ServiceLocatorFactory implements ServiceLocatorFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(iterable $resolvers): ServiceLocatorInterface
    {
        $serviceLocator = new ServiceLocator();
        foreach ($resolvers as $name => $services) {
            $resolver = $this->createResolver($name, $services);
            $serviceLocator->register($resolver, $name);
        }

        return $serviceLocator;
    }

    /**
     * Get resolver with $name and register $services.
     *
     * An exception will be thrown when a resolver for $name can not be found.
     *
     * @param string   $resolver
     * @param iterable $services
     * @return ResolverInterface
     * @throws ServiceLocatorException
     */
    protected function createResolver(string $resolver, iterable $services): ResolverInterface
    {
        if (is_subclass_of($resolver, ResolverInterface::class, true) === false) {
            throw ServiceLocatorException::forInvalidResolverType($resolver);
        }

        /** @var ResolverInterface $resolver */
        return $resolver::create($services);
    }
}
