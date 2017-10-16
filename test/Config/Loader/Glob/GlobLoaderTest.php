<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Config\Loader\Glob;

use PHPUnit\Framework\TestCase;

class GlobLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that added path will be loaded and array is returned.
     *
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\Glob\GlobLoader::addPath()
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\Glob\GlobLoader::load()
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\Glob\GlobLoader::getContent()
     */
    public function testLoad(): void
    {
        $loader = new GlobLoader();
        $loaded = $loader
            ->addPath(__DIR__ . '/config/{,*.}{global,local}.php')
            ->load();

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

    /**
     * Load failed.
     *
     * Test that loading a non existing path will throw an exception.
     *
     * @covers                         \ExtendsFramework\ServiceLocator\Config\Loader\Glob\GlobLoader::addPath()
     * @covers                         \ExtendsFramework\ServiceLocator\Config\Loader\Glob\GlobLoader::load()
     * @covers                         \ExtendsFramework\ServiceLocator\Config\Loader\Glob\GlobLoader::getContent()
     * @covers                         \ExtendsFramework\ServiceLocator\Config\Loader\Glob\Exception\RequiredFileFailed::__construct()
     * @expectedException              \ExtendsFramework\ServiceLocator\Config\Loader\Glob\Exception\RequiredFileFailed
     * @expectedExceptionMessageRegExp /^Glob loader failed to require file "(.*)"./
     */
    public function testLoadFailed(): void
    {
        $loader = new GlobLoader();
        $loader
            ->addPath(__DIR__ . '/config/.')
            ->load();
    }
}
