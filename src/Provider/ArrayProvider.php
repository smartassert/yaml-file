<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Provider;

use SmartAssert\YamlFile\Model\YamlFile;

class ArrayProvider implements AccessorInterface, ProviderInterface
{
    /**
     * @var array<string, YamlFile>
     */
    private array $yamlFiles = [];

    /**
     * @param array<mixed> $yamlFiles
     */
    public function __construct(array $yamlFiles)
    {
        foreach ($yamlFiles as $yamlFile) {
            if ($yamlFile instanceof YamlFile) {
                $this->yamlFiles[(string) $yamlFile->name] = $yamlFile;
            }
        }
    }

    public function get(string $path): ?YamlFile
    {
        return $this->yamlFiles[$path] ?? null;
    }

    public function getYamlFiles(): \Generator
    {
        foreach ($this->yamlFiles as $yamlFile) {
            yield $yamlFile;
        }
    }
}
