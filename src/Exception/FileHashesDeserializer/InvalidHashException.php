<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Exception\FileHashesDeserializer;

class InvalidHashException extends AbstractInvalidIndexItemException
{
    public function __construct(
        string $encodedContent,
        int $index,
    ) {
        parent::__construct(
            $encodedContent,
            sprintf('Expected hash at index %d is not a string', $index),
            $index
        );
    }
}
