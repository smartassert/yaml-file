<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Exception\FileHashesDeserializer;

use Symfony\Component\Yaml\Exception\ParseException;

class DecodeException extends AbstractFileHashesDeserializerException
{
    public function __construct(
        string $encodedContent,
        private ParseException $parseException,
    ) {
        parent::__construct(
            $encodedContent,
            'Error decoding encoded content: invalid yaml',
            $parseException
        );
    }

    public function getParseException(): ParseException
    {
        return $this->parseException;
    }
}
