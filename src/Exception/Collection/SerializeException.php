<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Exception\Collection;

class SerializeException extends \Exception
{
    public function __construct(\Throwable $previous)
    {
        parent::__construct('Collection serialization failed: ' . $previous->getMessage(), 0, $previous);
    }
}
