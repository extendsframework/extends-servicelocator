<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Invokable;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class InvokableStub
{
    /**
     * InvokableSub constructor.
     *
     * @param string                  $key
     * @param ServiceLocatorInterface $serviceLocator
     * @param array|null              $extra
     */
    public function __construct(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null)
    {
    }
}
