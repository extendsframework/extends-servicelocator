<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Factory\Exception;

use Exception;
use ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolverException;

class InvalidFactoryType extends Exception implements FactoryResolverException
{
    /**
     * Invalid type for $factory.
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        parent::__construct(sprintf(
            'Factory must be a subclass of ServiceFactoryInterface, got "%s".',
            $type
        ));
    }
}
