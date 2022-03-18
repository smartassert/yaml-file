<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Exception\FileHashesDeserializer;

class AbstractFileHashesDeserializerException extends \Exception implements ExceptionInterface
{
    public function __construct(
        private string $encodedContent,
        string $message,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function getEncodedContent(): string
    {
        return $this->encodedContent;
    }
}
