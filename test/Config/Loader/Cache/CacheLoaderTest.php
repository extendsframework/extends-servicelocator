<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Config\Loader\Cache;

use PHPUnit\Framework\TestCase;

class CacheLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that load will return correct config values.
     *
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\Cache\CacheLoader::__construct()
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\Cache\CacheLoader::load()
     */
    public function testLoad(): void
    {
        $loader = new CacheLoader(__DIR__ . '/config/cache.php');

        $this->assertSame([
            'global' => [
                'baz' => 'baz'
            ]
        ], $loader->load());
    }

    /**
     * Load not cached.
     *
     * Test that load will return an empty array when no cache is available.
     *
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\Cache\CacheLoader::__construct()
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\Cache\CacheLoader::load()
     */
    public function testLoadNotCached(): void
    {
        $loader = new CacheLoader(__DIR__ . '/config/cache_not_exists.php');

        $this->assertSame([], $loader->load());
    }

    /**
     * Save.
     *
     * Test that correct cached config is saved.
     *
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\Cache\CacheLoader::__construct()
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\Cache\CacheLoader::load()
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\Cache\CacheLoader::save()
     */
    public function testSave(): void
    {
        Buffer::reset();

        $loader = new CacheLoader(__DIR__ . '/config/cache.php');
        $loaded = $loader->load();
        $loaded['foo'] = 'bar';

        $loader->save($loaded);

        $this->assertSame(__DIR__ . '/config/cache.php', Buffer::getFilename());
        $this->assertSame(sprintf(
            '<?php return %s;',
            var_export($loaded, true)
        ), Buffer::getData());
    }
}

class Buffer
{
    protected static $filename;

    protected static $data;

    public static function getFilename(): ?string
    {
        return static::$filename;
    }

    public static function getData(): ?string
    {
        return static::$data;
    }

    public static function set(string $filename, string $data): void
    {
        static::$filename = $filename;
        static::$data = $data;
    }

    public static function reset(): void
    {
        static::$filename = null;
        static::$data = null;
    }
}

function file_put_contents($filename, $data): void
{
    Buffer::set($filename, $data);
}

