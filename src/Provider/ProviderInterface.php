<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Provider;

use SmartAssert\YamlFile\Model\YamlFile;

interface ProviderInterface
{
    /**
     * @return \Generator<YamlFile>
     */
    public function provide(): \Generator;
}
