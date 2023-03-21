<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\Exception\Collection\SerializeException;
use SmartAssert\YamlFile\Exception\ProvisionException;
use SmartAssert\YamlFile\FileHashes;
use SmartAssert\YamlFile\FileHashes\Serializer as FileHashesSerializer;

class Serializer
{
    private const DOCUMENT_START = '---';
    private const DOCUMENT_END = '...';

    public function __construct(
        private readonly FileHashesSerializer $fileHashesSerializer,
    ) {
    }

    /**
     * @throws SerializeException
     */
    public function serialize(ProviderInterface $provider): string
    {
        $fileHashes = new FileHashes();
        $documents = [];

        try {
            foreach ($provider->getYamlFiles() as $yamlFile) {
                $fileHashes->add((string) $yamlFile->name, md5($yamlFile->content));
                $documentContent = $this->createDocument($yamlFile->content);

                if (false === in_array($documentContent, $documents)) {
                    $documents[] = $documentContent;
                }
            }
        } catch (ProvisionException $e) {
            throw new SerializeException($e);
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
