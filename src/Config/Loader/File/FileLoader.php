<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Config\Loader\File;

use ExtendsFramework\ServiceLocator\Config\Loader\LoaderInterface;

class FileLoader implements LoaderInterface
{
    /**
     * Paths for glob() to locate configs.
     *
     * @var string[]
     */
    private $paths = [];

    /**
     * GlobLoader constructor.
     *
     * @param string ...$paths
     */
    public function __construct(string ...$paths)
    {
        foreach ($paths as $path) {
            $this->addPath($path);
        }
    }

    /**
     * @inheritDoc
     */
    public function load(): array
    {
        $loaded = [];
        foreach ($this->paths as $path) {
            foreach (glob($path, GLOB_BRACE) as $file) {
                /** @noinspection PhpIncludeInspection */
                $loaded[] = require $file;
            }
        }

        return $loaded;
    }

    /**
     * Add glob $path.
     *
     * @param string $path
     * @return FileLoader
     */
    public function addPath(string $path): FileLoader
    {
        $this->paths[] = $path;

        return $this;
    }
}
