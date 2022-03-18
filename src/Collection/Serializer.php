<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\Exception\ProvisionException;
use SmartAssert\YamlFile\FileHashes;
use SmartAssert\YamlFile\FileHashes\Serializer as FileHashesSerializer;
use SmartAssert\YamlFile\YamlFile;

class Serializer
{
    private const DOCUMENT_TEMPLATE = '---' . "\n" . '%s' . "\n" . '...';

    public function __construct(
        private FileHashesSerializer $fileHashesSerializer,
    ) {
    }

    /**
     * @throws ProvisionException
     */
    public function serialize(ProviderInterface $provider): string
    {
        $fileHashes = new FileHashes();
        $documents = [];

        /** @var YamlFile $yamlFile */
        foreach ($provider->getYamlFiles() as $yamlFile) {
            $fileHashes->add((string) $yamlFile->name, md5($yamlFile->content));

            $documents[] = sprintf(
                self::DOCUMENT_TEMPLATE,
                $yamlFile->content
            );
        }

        $fileHashItems = $fileHashes->getItems();
        if ([] !== $fileHashItems) {
            array_unshift($documents, sprintf(
                self::DOCUMENT_TEMPLATE,
                $this->fileHashesSerializer->serialize($fileHashes)
            ));
        }

        return implode("\n", $documents);
    }
}
