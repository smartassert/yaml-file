<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\YamlFile;

interface ArrayProviderInterface
{
    /**
     * @return array<YamlFile>
     */
    public function getYamlFilesAsArray(): array;
}
