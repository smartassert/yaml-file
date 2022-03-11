<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Provider;

use SmartAssert\YamlFile\Model\YamlFile;

class ArrayProvider implements ProviderInterface
{
    /**
     * @var array<int, YamlFile>
     */
    private array $yamlFiles = [];

    /**
     * @param array<mixed> $yamlFiles
     */
    public function __construct(array $yamlFiles)
    {
        foreach ($yamlFiles as $yamlFile) {
            if ($yamlFile instanceof YamlFile) {
                $this->yamlFiles[] = $yamlFile;
            }
        }
    }

    public function provide(): \Generator
    {
        foreach ($this->yamlFiles as $yamlFile) {
            yield $yamlFile;
        }
    }
}
