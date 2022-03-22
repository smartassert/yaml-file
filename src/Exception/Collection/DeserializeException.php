<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Exception\Collection;

class DeserializeException extends \Exception
{
    public function __construct(\Throwable $previous)
    {
        parent::__construct('Collection deserialization failed: ' . $previous->getMessage(), 0, $previous);
    }
}
