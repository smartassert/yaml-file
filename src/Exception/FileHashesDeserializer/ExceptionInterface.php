<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Exception\FileHashesDeserializer;

interface ExceptionInterface extends \Throwable
{
    public function getEncodedContent(): string;
}
