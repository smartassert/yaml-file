<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\Exception\ProvisionException;
use SmartAssert\YamlFile\YamlFile;

class Serializer
{
    private const PAYLOAD_INDENT = '  ';
    private const TEMPLATE = '"%s": |' . "\n" . self::PAYLOAD_INDENT . '%s';

    /**
     * @throws ProvisionException
     */
    public function serialize(ProviderInterface $provider): string
    {
        $serializedFiles = [];

        /** @var YamlFile $yamlFile */
        foreach ($provider->getYamlFiles() as $yamlFile) {
            $serializedFiles[] = $this->serializeYamlFile($yamlFile);
        }

        return implode("\n\n", $serializedFiles);
    }

    private function serializeYamlFile(YamlFile $yamlFile): string
    {
        return sprintf(
            self::TEMPLATE,
            addcslashes((string) $yamlFile->name, '"'),
            $this->createFileContentPayload($yamlFile->content)
        );
    }

    private function createFileContentPayload(string $content): string
    {
        return str_replace("\n", "\n" . self::PAYLOAD_INDENT, $content);
    }
}
