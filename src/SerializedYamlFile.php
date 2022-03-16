<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile;

class SerializedYamlFile implements \Stringable
{
    public const KEY_PATH = 'path';
    public const KEY_CONTENT = 'content';

    public function __construct(
        private YamlFile $yamlFile
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            <<< 'EOF'
            "%s": "%s"
            "%s": |
              %s
            EOF,
            self::KEY_PATH,
            $this->yamlFile->name,
            self::KEY_CONTENT,
            str_replace("\n", "\n  ", $this->yamlFile->content)
        );
    }
}
