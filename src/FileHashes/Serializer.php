<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\FileHashes;

use SmartAssert\YamlFile\FileHashes;
use Symfony\Component\Yaml\Dumper;

class Serializer
{
    public function __construct(
        private Dumper $yamlDumper,
    ) {}

    public function serialize(FileHashes $fileHashes): string
    {
        $items = $fileHashes->getItems();
        if ([] === $items) {
            return '';
        }

        return trim($this->yamlDumper->dump($fileHashes->getItems(), 2));
    }
}
