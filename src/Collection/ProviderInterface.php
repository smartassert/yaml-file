<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\Exception\ProvisionException;
use SmartAssert\YamlFile\YamlFile;

interface ProviderInterface
{
    /**
     * @return \Generator<YamlFile>
     *
     * @throws ProvisionException
     */
    public function getYamlFiles(): \Generator;
}
