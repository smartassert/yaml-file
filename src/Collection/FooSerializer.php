<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\Exception\ProvisionException;
use SmartAssert\YamlFile\YamlFile;
use Symfony\Component\Yaml\Dumper;

class FooSerializer
{
    private const DOCUMENT_TEMPLATE = '---' . "\n" . '%s' . "\n" . '...';

    public function __construct(
        private Dumper $yamlDumper,
    ) {
    }

    /**
     * @throws ProvisionException
     */
    public function serialize(ProviderInterface $provider): string
    {
        $metadata = [];
        $documents = [];

        $serializedFiles = [];

        /** @var YamlFile $yamlFile */
        foreach ($provider->getYamlFiles() as $yamlFile) {
            $metadata[(string) $yamlFile->name] = md5($yamlFile->content);
            $documents[] = sprintf(self::DOCUMENT_TEMPLATE, $yamlFile->content);
        }

        if (0 === count($documents)) {
            return '';
        }

        $metadataDocument = sprintf(
            self::DOCUMENT_TEMPLATE,
            trim($this->yamlDumper->dump(['metadata' => $metadata], 2))
        );

        array_unshift($documents, $metadataDocument);

        return implode("\n", $documents);
    }
}
