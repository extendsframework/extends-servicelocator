<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Config\Loader\File;

use ExtendsFramework\ServiceLocator\Config\Loader\LoaderInterface;

class FileLoader implements LoaderInterface
{
    /**
     * Glob pattern.
     *
     * @var string
     */
    protected $pattern;

    /**
     * GlobLoader constructor.
     *
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @inheritDoc
     */
    public function load(): array
    {
        $loaded = [];
        foreach (glob($this->pattern, GLOB_BRACE) as $file) {
            $loaded[] = require $file;
        }

        return $loaded;
    }
}
