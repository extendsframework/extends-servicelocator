<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Config;

use PHPUnit\Framework\TestCase;

class ConfigFactoryTest extends TestCase
{
    /**
     * Create.
     *
     * Test that config can be created from paths and array will be returned.
     *
     * @covers \ExtendsFramework\ServiceLocator\Config\ConfigFactory::create()
     */
    public function testCreate(): void
    {
        $factory = new ConfigFactory();
        $config = $factory->create(__DIR__ . '/Loader/Glob/config/{,*.}{global,local}.php');

        $this->assertInternalType('array', $config);
    }
}
