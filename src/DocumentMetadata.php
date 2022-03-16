<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile;

class DocumentMetadata
{
    public function __construct(
        public readonly string $path,
        public readonly string $hash,
    ) {
    }

    public static function create(string $path, string $content): self
    {
        return new DocumentMetadata($path, md5($content));
    }
}
