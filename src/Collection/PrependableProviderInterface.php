<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\YamlFile;

interface PrependableProviderInterface
{
    /**
     * Prepend an existing collection with a new file.
     *
     * The implementation must return a new collection with the new file as the first item.
     */
    public function prepend(YamlFile $file): PrependableProviderInterface;
}
