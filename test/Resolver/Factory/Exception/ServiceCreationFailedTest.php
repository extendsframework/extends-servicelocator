<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Factory\Exception;

use PHPUnit\Framework\TestCase;
use RuntimeException;

class ServiceCreationFailedTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\ServiceLocator\Resolver\Factory\Exception\ServiceCreationFailed::__construct()
     */
    public function testCanConstructWithKeyAndPreviousException(): void
    {
        $previous = new RuntimeException('Hello world!');
        $exception = new ServiceCreationFailed('foo', $previous);

        $this->assertSame('Failed to create service for key "foo".', $exception->getMessage());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
