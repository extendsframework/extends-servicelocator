<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Invokable\Exception;

use Exception;
use ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolverException;

class NonExistingClass extends Exception implements InvokableResolverException
{
    /**
     * Invokable $class must be an existing class.
     *
     * @param string $class
     */
    public function __construct(string $class)
    {
        parent::__construct(sprintf(
            'Invokable "%s" must be a existing class.',
            $class
        ));
    }
}
