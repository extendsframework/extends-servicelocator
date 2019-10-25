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
    public function create(array $config): ServiceLocatorInterface
    {
        $serviceLocator = new ServiceLocator($config);
        foreach ($config[ServiceLocatorInterface::class] ?? [] as $fqcn => $services) {
            $serviceLocator->addResolver(
                $this->getResolver($fqcn, $services),
                $fqcn
            );
        }

        return $serviceLocator;
    }

    /**
     * Get resolver with $name and register $services.
     *
     * An exception will be thrown when a resolver for $name can not be found.
     *
     * @param ResolverInterface|string $fqcn
     * @param array                    $services
     * @return ResolverInterface
     * @throws UnknownResolverType
     */
    protected function getResolver(string $fqcn, array $services): ResolverInterface
    {
        if (is_subclass_of($fqcn, ResolverInterface::class, true) === false) {
            throw new UnknownResolverType($fqcn);
        }

        return $fqcn::factory($services);
    }
}
