<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Config\Merger;

interface MergerInterface
{
    /**
     * Merge $left and $right into a new array.
     *
     * @param array $left
     * @param array $right
     * @return array
     * @throws MergerException
     */
    public function merge(array $left, array $right): array;
}
