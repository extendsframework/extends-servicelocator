<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Exception;

use Exception;

class ServiceNotFound extends Exception
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
