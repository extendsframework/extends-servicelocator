<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Config;

use ExtendsFramework\ServiceLocator\Config\Loader\Glob\GlobLoader;
use ExtendsFramework\ServiceLocator\Config\Merger\Recursive\RecursiveMerger;

class ConfigFactory implements ConfigFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(string ...$paths): array
    {
        $loader = new GlobLoader();
        foreach ($paths as $path) {
            $loader->addPath($path);
        }

        $loaded = $loader->load();

        $merged = [];
        $merger = new RecursiveMerger();
        foreach ($loaded as $content) {
            $merged = $merger->merge($merged, $content);
        }

        return $merged;
    }
}
