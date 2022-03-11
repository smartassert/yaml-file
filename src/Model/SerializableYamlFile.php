<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Model;

class SerializableYamlFile implements \Stringable
{
    private const PAYLOAD_INDENT = '  ';
    private const TEMPLATE = '"%s": |' . "\n" . self::PAYLOAD_INDENT . '%s';

    public function __construct(
        private readonly YamlFile $yamlFile,
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            self::TEMPLATE,
            addcslashes((string) $this->yamlFile->name, '"'),
            $this->createFileContentPayload($this->yamlFile->content)
        );
    }

    private function createFileContentPayload(string $content): string
    {
        return str_replace("\n", "\n" . self::PAYLOAD_INDENT, $content);
    }
}
