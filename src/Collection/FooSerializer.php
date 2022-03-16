<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\Exception\ProvisionException;
use SmartAssert\YamlFile\YamlFile;

class FooSerializer
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
                $this->createDocumentContent((string) $yamlFile->name, $yamlFile->content)
            );
        }

        return implode("\n", $documents);
    }

    private function createDocumentContent(string $filename, string $content): string
    {
        return sprintf(
            <<< 'EOF'
            "filename": "%s"
            "content": |
              %s
            EOF,
            $filename,
            str_replace("\n", "\n  ", $content)
        );
    }
}
