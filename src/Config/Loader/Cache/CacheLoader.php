<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Config\Loader\Cache;

use ExtendsFramework\ServiceLocator\Config\Loader\LoaderInterface;

class CacheLoader implements LoaderInterface
{
    /**
     * Cache file location
     *
     * @var string
     */
    private $filename;

    /**
     * CacheLoader constructor.
     *
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @inheritDoc
     */
    public function load(): array
    {
        $filename = $this->getFilename();
        if (is_file($filename)) {
            /** @noinspection PhpIncludeInspection */
            return require $filename;
        }

        return [];
    }

    /**
     * Save $config to file.
     *
     * @param array $config
     * @return CacheLoader
     */
    public function save(array $config): CacheLoader
    {
        file_put_contents($this->getFilename(), sprintf(
            '<?php return %s;',
            var_export($config, true)
        ));

        return $this;
    }

    /**
     * Get filename.
     *
     * @return string
     */
    private function getFilename(): string
    {
        return $this->filename;
    }
}
