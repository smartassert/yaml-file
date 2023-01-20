<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\Exception\Collection\DeserializeException;
use SmartAssert\YamlFile\Exception\Collection\FilePathNotFoundException;
use SmartAssert\YamlFile\Exception\FileHashesDeserializer\ExceptionInterface;
use SmartAssert\YamlFile\FileHashes\Deserializer as FileHashesDeserializer;
use SmartAssert\YamlFile\YamlFile;
use webignition\YamlDocumentSetParser\Parser as DocumentSetParser;

class Deserializer
{
    public function __construct(
        private readonly DocumentSetParser $documentSetParser,
        private readonly FileHashesDeserializer $fileHashesDeserializer,
    ) {
    }

    /**
     * @throws DeserializeException
     */
    public function deserialize(string $content): ProviderInterface&MutableProviderInterface
    {
        $yamlFiles = [];
        $documents = $this->documentSetParser->parse($content);

        $fileHashesDocument = array_shift($documents);
        $fileHashesDocument = is_string($fileHashesDocument) ? $fileHashesDocument : '';

        try {
            $fileHashes = $this->fileHashesDeserializer->deserialize($fileHashesDocument);
        } catch (ExceptionInterface $e) {
            throw new DeserializeException($e);
        }

        foreach ($documents as $document) {
            $documentHash = md5($document);
            $filenames = $fileHashes->getFilenames($documentHash);

            if ([] === $filenames) {
                throw new DeserializeException(
                    new FilePathNotFoundException($documentHash, $fileHashes)
                );
            }

            foreach ($filenames as $filename) {
                $yamlFiles[] = YamlFile::create($filename, $document);
            }
        }

        return new ArrayCollection($yamlFiles);
    }
}
