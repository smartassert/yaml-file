<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Exception\FileHashesDeserializer;

class InvalidPathException extends AbstractInvalidIndexItemException
{
    public function __construct(
        string $encodedContent,
        private string $hash,
        private int $index,
    ) {
        parent::__construct(
            $encodedContent,
            sprintf('Expected path for hash "%s" at index %d is not a string', $this->hash, $index),
            $this->index
        );
    }

    public function getHash(): string
    {
        return $this->hash;
    }
}
