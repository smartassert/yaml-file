<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Exception\FileHashesDeserializer;

class PathCollectionNotArrayException extends AbstractFileHashesDeserializerException
{
    public function __construct(
        string $encodedContent,
        private string $hash,
        private mixed $pathCollection,
    ) {
        parent::__construct(
            $encodedContent,
            sprintf('Path for hash "%s" is not an array', $this->hash)
        );
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getPathCollection(): mixed
    {
        return $this->pathCollection;
    }
}
