<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Exception;

interface HasPreviousExceptionInterface extends \Throwable
{
    public function getPreviousException(): \Throwable;
}
