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
            if (!is_subclass_of($fqcn, ResolverInterface::class, true)) {
                throw new UnknownResolverType($fqcn);
            }

            /** @noinspection PhpUndefinedMethodInspection */
            $serviceLocator->addResolver($fqcn::factory($services), $fqcn);
        }

        return $serviceLocator;
    }
}
