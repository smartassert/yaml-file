<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile;

class FileHashes
{
    /**
     * @var array<string, int>
     */
    private array $accessPointers = [];

    /**
     * @var array<string, array<int, string>>
     */
    private array $items = [];

    public function add(string $path, string $hash): self
    {
        if (false === array_key_exists($hash, $this->items)) {
            $this->items[$hash] = [];
            $this->accessPointers[$hash] = 0;
        }

        $this->items[$hash][] = $path;

        return $this;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getFilename(string $hash): ?string
    {
        if (false === array_key_exists($hash, $this->accessPointers)) {
            return null;
        }

        $filename = $this->items[$hash][$this->accessPointers[$hash]];
        ++$this->accessPointers[$hash];

        return $filename;
    }
}
