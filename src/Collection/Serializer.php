<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\Model\SerializableYamlFile;
use SmartAssert\YamlFile\Model\YamlFile;
use SmartAssert\YamlFile\Provider\ProviderInterface;

class Serializer
{
//    private const FOO_TEMPLATE = '"%s": |' . "\n" . '%s';

    public function serialize(ProviderInterface $provider): string
    {
        $serializedFiles = [];

        /** @var YamlFile $yamlFile */
        foreach ($provider->provide() as $yamlFile) {
            $serializedFiles[] = (string) new SerializableYamlFile($yamlFile);
        }

//            $content = $yamlFile->content;
//
//            $filePath = $this->removePathPrefix($directoryPath, $file);
//
//            $serializedFiles[] = sprintf(
//                self::FOO_TEMPLATE,
//                addcslashes($filePath, '"'),
//                $this->createFileContentPayload($content)
//            );

        return implode("\n\n", $serializedFiles);
    }
}
