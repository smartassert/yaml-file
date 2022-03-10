<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Model;

class YamlFile
{
    public function __construct(
        public readonly Filename $name,
        public readonly string $content,
    ) {
    }

    public static function create(string $name, string $content): YamlFile
    {
        return new YamlFile(Filename::parse($name), $content);
    }
}
