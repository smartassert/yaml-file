<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\Exception\CollectionDeserializer\FilePathNotFoundException;
use SmartAssert\YamlFile\Exception\FileHashesDeserializer\ExceptionInterface;
use SmartAssert\YamlFile\FileHashes\Deserializer as FileHashesDeserializer;
use SmartAssert\YamlFile\YamlFile;
use webignition\YamlDocumentSetParser\Parser as DocumentSetParser;

class Deserializer
{
    public function __construct(
        private DocumentSetParser $documentSetParser,
        private FileHashesDeserializer $fileHashesDeserializer,
    ) {
    }

    /**
     * @throws ExceptionInterface
     * @throws FilePathNotFoundException
     */
    public function deserialize(string $content): ProviderInterface
    {
        $yamlFiles = [];
        $documents = $this->documentSetParser->parse($content);

        $fileHashesDocument = array_shift($documents);
        $fileHashesDocument = is_string($fileHashesDocument) ? $fileHashesDocument : '';

        $fileHashes = $this->fileHashesDeserializer->deserialize($fileHashesDocument);

        foreach ($documents as $document) {
            $documentHash = md5($document);
            $filename = $fileHashes->getFilename($documentHash);

            if (null === $filename) {
                throw new FilePathNotFoundException($documentHash, $fileHashes);
            }

            $yamlFiles[] = YamlFile::create($filename, $document);
        }

        return new ArrayCollection($yamlFiles);
    }
}
