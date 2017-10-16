<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Config\Loader;

interface LoaderInterface
{
    /**
     * Load multiple configs and return them all in one indexed array.
     *
     * @return array
     * @throws LoaderException
     */
    public function load(): array;
}
