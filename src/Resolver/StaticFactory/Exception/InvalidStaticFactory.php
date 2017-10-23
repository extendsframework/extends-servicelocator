<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\StaticFactory\Exception;

use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolverException;
use RuntimeException;

class InvalidStaticFactory extends RuntimeException implements StaticFactoryResolverException
{
    /**
     * InvalidStaticFactory constructor.
     *
     * @param string $factory
     */
    public function __construct($factory)
    {
        parent::__construct(sprintf(
            'Factory must be a subclass of StaticFactoryInterface, got "%s".',
            $factory
        ));
    }
}
