<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\YamlFile;
use Symfony\Component\Yaml\Exception\ParseException;
use webignition\YamlDocumentSetParser\Parser;

class FooDeserializer
{
    public function __construct(
        private Parser $documentSetParser,
    ) {
    }

    /**
     * @throws ParseException
     */
    public function deserialize(string $content): ProviderInterface
    {
        $yamlFiles = [];

        $documents = $this->documentSetParser->parse($content);

        foreach ($documents as $document) {
            if (is_array($document)) {
                $filename = $document['filename'] ?? '';
                $content = $document['content'] ?? '';

                if ('' !== $filename && '' !== $content) {
                    $yamlFiles[] = YamlFile::create($filename, $content);
                }
            }
        }

        return new ArrayCollection($yamlFiles);
    }
}
