<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Exception\FileHashesDeserializer;

abstract class AbstractInvalidIndexItemException extends AbstractFileHashesDeserializerException
{
    public function __construct(
        string $encodedContent,
        string $message,
        private int $index,
    ) {
        parent::__construct($encodedContent, $message);
    }

    public function getIndex(): int
    {
        return $this->index;
    }
}
