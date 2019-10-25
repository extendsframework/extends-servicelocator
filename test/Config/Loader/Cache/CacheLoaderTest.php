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
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\Cache\CacheLoader::getFilename()
     */
    public function testLoad(): void
    {
        $loader = new CacheLoader(__DIR__ . '/config/cache.php');

        $this->assertSame([
            'global' => [
                'baz' => 'baz',
            ],
        ], $loader->load());
    }

    /**
     * Load not cached.
     *
     * Test that load will return an empty array when no cache is available.
     *
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\Cache\CacheLoader::__construct()
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\Cache\CacheLoader::load()
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\Cache\CacheLoader::getFilename()
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
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\Cache\CacheLoader::getFilename()
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

/**
 * Override native PHP function.
 *
 * @param $filename
 * @param $data
 */
function file_put_contents($filename, $data): void
{
    Buffer::set($filename, $data);
}
