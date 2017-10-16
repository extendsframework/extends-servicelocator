<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Config;

interface ConfigFactoryInterface
{
    /**
     * Load files for $pattern and create config.
     *
     * @param string[] $paths
     * @return array
     * @throws ConfigException
     */
    public function create(string ...$paths): array;
}
