<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Exception;

use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use RuntimeException;

class NonObjectService extends RuntimeException implements ServiceLocatorException
{
    /**
     * ServiceNotAnObject constructor.
     *
     * @param string $key
     * @param mixed  $service
     */
    public function __construct(string $key, $service)
    {
        parent::__construct(sprintf(
            'Service for key "%s" must an object, got type "%s".',
            $key,
            gettype($service)
        ));
    }
}
