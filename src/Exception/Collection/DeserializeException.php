<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Exception\Collection;

use SmartAssert\YamlFile\Exception\AbstractHasPreviousExceptionException;

class DeserializeException extends AbstractHasPreviousExceptionException
{
    public function __construct(\Throwable $previous)
    {
        parent::__construct($previous, 'Collection deserialization failed: ' . $previous->getMessage());
    }
}
