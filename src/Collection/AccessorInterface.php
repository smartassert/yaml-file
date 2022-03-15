<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\Model\YamlFile;

interface AccessorInterface
{
    public function get(string $path): ?YamlFile;
}
