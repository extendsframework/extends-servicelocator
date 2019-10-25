<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Reflection;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class ClassA
{
    /**
     * ClassA constructor.
     *
     * @param ClassB                  $b
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ClassB $b, ServiceLocatorInterface $serviceLocator)
    {
    }
}
