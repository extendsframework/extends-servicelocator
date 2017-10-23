<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator;

use ExtendsFramework\ServiceLocator\Exception\UnknownResolverType;
use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;

class ServiceLocatorFactory implements ServiceLocatorFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createService(array $config): ServiceLocatorInterface
    {
        $serviceLocator = new ServiceLocator($config);
        foreach ($config['service_locator'] ?? [] as $fqcn => $services) {
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
     * @param ResolverInterface|string $fqcn
     * @param iterable                 $services
     * @return ResolverInterface
     * @throws UnknownResolverType
     */
    protected function getResolver(string $fqcn, iterable $services): ResolverInterface
    {
        if (is_subclass_of($fqcn, ResolverInterface::class, true) === false) {
            throw new UnknownResolverType($fqcn);
        }

        return $fqcn::factory($services);
    }
}
