<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\Exception\ProvisionException;
use SmartAssert\YamlFile\FileHashes;
use SmartAssert\YamlFile\FileHashes\Serializer as FileHashesSerializer;
use SmartAssert\YamlFile\YamlFile;

class Serializer
{
    private const DOCUMENT_START = '---';
    private const DOCUMENT_END = '...';

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
            $documentContent = $this->createDocument($yamlFile->content);

            if (false === in_array($documentContent, $documents)) {
                $documents[] = $documentContent;
            }
        }

        $fileHashItems = $fileHashes->getItems();
        if ([] !== $fileHashItems) {
            array_unshift($documents, $this->createDocument($this->fileHashesSerializer->serialize($fileHashes)));
        }

        return implode("\n", $documents);
    }

    private function createDocument(string $content): string
    {
        $documentContent = self::DOCUMENT_START . "\n";

        if ('' !== $content) {
            $documentContent .= $content . "\n";
        }

        return $documentContent . self::DOCUMENT_END;
    }
}
