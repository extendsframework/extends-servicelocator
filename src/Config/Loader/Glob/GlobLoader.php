<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Config\Loader\Glob;

use ExtendsFramework\ServiceLocator\Config\Loader\Glob\Exception\RequiredFileFailed;
use ExtendsFramework\ServiceLocator\Config\Loader\LoaderException;
use ExtendsFramework\ServiceLocator\Config\Loader\LoaderInterface;

class GlobLoader implements LoaderInterface
{
    /**
     * Paths for glob() to locate configs.
     *
     * @var string[]
     */
    protected $paths = [];

    /**
     * @inheritDoc
     */
    public function load(): array
    {
        $loaded = [];
        foreach ($this->paths as $path) {
            foreach (glob($path, GLOB_BRACE) as $files) {
                $loaded[] = $this->getContent($files);
            }
        }

        return $loaded;
    }

    /**
     * Add glob $path.
     *
     * @param string $path
     * @return GlobLoader
     */
    public function addPath(string $path): GlobLoader
    {
        $this->paths[] = $path;

        return $this;
    }

    /**
     * Get file content for $path.
     *
     * When $path is not a valid file, an exception will be thrown.
     *
     * @param string $file
     * @return array
     * @throws LoaderException
     */
    protected function getContent(string $file): array
    {
        if (is_file($file) === false) {
            throw new RequiredFileFailed($file);
        }

        return require $file;
    }
}
