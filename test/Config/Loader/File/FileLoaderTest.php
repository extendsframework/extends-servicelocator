<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Config\Loader\File;

use PHPUnit\Framework\TestCase;

class FileLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that added path will be loaded and array is returned.
     *
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\File\FileLoader::__construct()
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\File\FileLoader::addPath()
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\File\FileLoader::load()
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\File\FileLoader::getPaths()
     */
    public function testLoad(): void
    {
        $loader = new FileLoader(__DIR__ . '/config/{,*.}{global,local}.php');
        $loaded = $loader->load();

        $this->assertSame([
            [
                'global' => [
                    'baz' => 'baz',
                ],
            ],
            [
                'local' => [
                    'qux' => 'qux',
                ],
                'global' => [
                    'foo' => 'bar',
                ],
            ],
        ], $loaded);
    }
}
