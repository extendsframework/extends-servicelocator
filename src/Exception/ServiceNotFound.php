<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Exception;

use Exception;
use ExtendsFramework\ServiceLocator\ServiceLocatorException;

class ServiceNotFound extends Exception implements ServiceLocatorException
{
    /**
     * Service with $key not found.
     *
     * @param string $key
     */
    public function __construct(string $key)
    {
        parent::__construct(sprintf(
            'No service found for key "%s".',
            $key
        ));
    }
}
