<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\FileHashes;

use SmartAssert\YamlFile\Collection\ProviderInterface;
use SmartAssert\YamlFile\FileHashes;

class Factory
{
    public function createFromProvider(ProviderInterface $provider): FileHashes
    {
        $fileHashes = new FileHashes();
        foreach ($provider->getYamlFiles() as $source) {
            $fileHashes->add((string) $source->name, md5($source->content));
        }

        return $fileHashes;
    }
}
