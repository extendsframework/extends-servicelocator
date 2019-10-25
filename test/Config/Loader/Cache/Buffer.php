<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Config\Loader\Cache;

class Buffer
{
    /**
     * Filename.
     *
     * @var string
     */
    protected static $filename;

    /**
     * File data.
     *
     * @var string
     */
    protected static $data;

    /**
     * Get filename.
     *
     * @return string|null
     */
    public static function getFilename(): ?string
    {
        return static::$filename;
    }

    /**
     * Get file data.
     *
     * @return string|null
     */
    public static function getData(): ?string
    {
        return static::$data;
    }

    /**
     * Set $data for $filename.
     *
     * @param string $filename
     * @param string $data
     */
    public static function set(string $filename, string $data): void
    {
        static::$filename = $filename;
        static::$data = $data;
    }

    /**
     * Reset buffer.
     *
     * @return void
     */
    public static function reset(): void
    {
        static::$filename = null;
        static::$data = null;
    }
}
