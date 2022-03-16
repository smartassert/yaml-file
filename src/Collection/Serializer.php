<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\Exception\ProvisionException;
use SmartAssert\YamlFile\SerializedYamlFile;
use SmartAssert\YamlFile\YamlFile;

class Serializer
{
    private const DOCUMENT_TEMPLATE = '---' . "\n" . '%s' . "\n" . '...';

    /**
     * @throws ProvisionException
     */
    public function serialize(ProviderInterface $provider): string
    {
        $documents = [];

        /** @var YamlFile $yamlFile */
        foreach ($provider->getYamlFiles() as $yamlFile) {
            $documents[] = sprintf(
                self::DOCUMENT_TEMPLATE,
                (string) new SerializedYamlFile($yamlFile)
            );
        }

        return implode("\n", $documents);
    }
}
