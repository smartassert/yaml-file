<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\YamlFile;

interface MutableProviderInterface
{
    /**
     * Extract an item from a collection by path.
     *
     * If present, the specified item is removed from the collection and returned.
     */
    public function extract(string $path): ?YamlFile;
}
