<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\Model\SerializableYamlFile;
use SmartAssert\YamlFile\Model\YamlFile;
use SmartAssert\YamlFile\Provider\ProviderInterface;

class Serializer
{
    public function serialize(ProviderInterface $provider): string
    {
        $serializedFiles = [];

        /** @var YamlFile $yamlFile */
        foreach ($provider->provide() as $yamlFile) {
            $serializedFiles[] = (string) new SerializableYamlFile($yamlFile);
        }

        return implode("\n\n", $serializedFiles);
    }
}
