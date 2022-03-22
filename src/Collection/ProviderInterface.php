<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\YamlFile;

interface ProviderInterface
{
    /**
     * @return \Generator<YamlFile>
     */
    public function getYamlFiles(): \Generator;
}
