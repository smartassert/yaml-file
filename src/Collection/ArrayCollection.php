<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\Collection\AccessorInterface as Accessor;
use SmartAssert\YamlFile\Collection\ArrayProviderInterface as ArrayProvider;
use SmartAssert\YamlFile\Collection\PrependableProviderInterface as PrependableProvider;
use SmartAssert\YamlFile\Collection\ProviderInterface as Provider;
use SmartAssert\YamlFile\YamlFile;

class ArrayCollection implements Accessor, Provider, ArrayProvider, PrependableProvider, MutableProviderInterface
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

    public function getYamlFilesAsArray(): array
    {
        return $this->yamlFiles;
    }

    public function prepend(YamlFile $file): ArrayCollection
    {
        $newYamlFiles = $this->yamlFiles;
        array_unshift($newYamlFiles, $file);

        return new ArrayCollection($newYamlFiles);
    }

    public function extract(string $path): ?YamlFile
    {
        $yamlFile = $this->get($path);
        if ($yamlFile instanceof YamlFile) {
            unset($this->yamlFiles[$path]);
        }

        return $yamlFile;
    }
}
