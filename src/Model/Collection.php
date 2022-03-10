<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Model;

use Doctrine\Common\Collections\ArrayCollection;

class Collection
{
    /**
     * @var ArrayCollection<int, YamlFile>
     */
    private ArrayCollection $sources;

    /**
     * @param array<mixed> $yamlFiles
     */
    public function __construct(array $yamlFiles = [])
    {
        $this->sources = new ArrayCollection();

        foreach ($yamlFiles as $yamlFile) {
            if ($yamlFile instanceof YamlFile) {
                $this->add($yamlFile);
            }
        }
    }

    public function add(YamlFile $yamlFile): void
    {
        $this->sources->add($yamlFile);
    }

    /**
     * @return YamlFile[]
     */
    public function getAll(): array
    {
        return $this->sources->toArray();
    }
}
