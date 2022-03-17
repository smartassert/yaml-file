<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\SerializedYamlFile;
use SmartAssert\YamlFile\YamlFile;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser as YamlParser;
use webignition\YamlDocumentSetParser\Parser as DocumentSetParser;

class Deserializer
{
    public function __construct(
        private DocumentSetParser $documentSetParser,
        private YamlParser $yamlParser,
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
            $data = $this->yamlParser->parse($document);

            if (is_array($data)) {
                $filename = $data[SerializedYamlFile::KEY_PATH] ?? '';
                $content = $data[SerializedYamlFile::KEY_CONTENT] ?? '';

                if ('' !== $filename) {
                    $yamlFiles[] = YamlFile::create($filename, $content);
                }
            }
        }

        return new ArrayCollection($yamlFiles);
    }
}
