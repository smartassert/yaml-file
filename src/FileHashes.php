<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile;

class FileHashes
{
    /**
     * @var array<string, string>
     */
    private array $items = [];

    public function add(string $path, string $hash): self
    {
        $this->items[$hash] = $path;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getFilename(string $hash): ?string
    {
        return $this->items[$hash] ?? null;
    }
}
