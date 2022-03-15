<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Provider;

use SmartAssert\YamlFile\Exception\ProvisionException;
use SmartAssert\YamlFile\Model\YamlFile;

interface ProviderInterface
{
    /**
     * @throws ProvisionException
     *
     * @return \Generator<YamlFile>
     */
    public function getYamlFiles(): \Generator;
}
