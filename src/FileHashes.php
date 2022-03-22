<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile;

class FileHashes
{
    /**
     * @var array<string, array<int, string>>
     */
    private array $items = [];

    public function add(string $path, string $hash): self
    {
        if (false === array_key_exists($hash, $this->items)) {
            $this->items[$hash] = [];
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

    /**
     * @return string[]
     */
    public function getFilenames(string $hash): array
    {
        return $this->items[$hash] ?? [];
    }
}
