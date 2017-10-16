<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Config\Merger\Recursive;

use PHPUnit\Framework\TestCase;
use stdClass;

class RecursiveMergerTest extends TestCase
{
    /**
     * Merge.
     *
     * Test that two configs ($left and $right) can be merged and merged config ($merged) will be returned.
     *
     * @covers \ExtendsFramework\ServiceLocator\Config\Merger\Recursive\RecursiveMerger::merge()
     * @covers \ExtendsFramework\ServiceLocator\Config\Merger\Recursive\RecursiveMerger::isAssoc()
     */
    public function testMerge(): void
    {
        $left = [
            1,
            'a' => 'a',
            'b' => 1,
            'c' => null,
            'd' => [
                'a',
                'b',
                new stdClass(),
            ],
            'e' => null,
            'f',
            function () {
                echo 'test';
            },
            'x' => [
                'y' => 'z',
            ],
        ];

        $right = [
            2,
            'a' => null,
            'b' => [
                'a',
                'b',
            ],
            'c' => 'd',
            'd' => [
                'b',
                'c',
                new stdClass(),
            ],
            3,
            'g' => [
                'a',
                'b',
            ],
            function () {
                echo 'test';
            },
            'x' => [
                'z' => 'y',
            ],
        ];

        $expected = [
            0 => 2,
            'a' => null,
            'b' => [
                0 => 'a',
                1 => 'b',
            ],
            'c' => 'd',
            'd' => [
                0 => 'a',
                1 => 'b',
                2 => new stdClass(),
                4 => 'c',
            ],
            'e' => null,
            1 => 3,
            'g' => [
                0 => 'a',
                1 => 'b',
            ],
            2 => function () {
                echo 'test';
            },
            'x' => [
                'y' => 'z',
                'z' => 'y',
            ],
        ];

        $merger = new RecursiveMerger();
        $merged = $merger->merge($left, $right);

        $this->assertEquals($expected, $merged);
    }
}
