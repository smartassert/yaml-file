<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\Exception\ProvisionException;
use SmartAssert\YamlFile\YamlFile;

interface UnreliableProviderInterface extends ProviderInterface
{
    /**
     * @throws ProvisionException
     *
     * @return \Generator<YamlFile>
     */
    public function getYamlFiles(): \Generator;
}
