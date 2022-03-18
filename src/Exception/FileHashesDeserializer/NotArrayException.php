<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Exception\FileHashesDeserializer;

class NotArrayException extends AbstractFileHashesDeserializerException
{
    public function __construct(
        string $encodedContent,
    ) {
        parent::__construct($encodedContent, 'Decoded data is not an array');
    }
}
