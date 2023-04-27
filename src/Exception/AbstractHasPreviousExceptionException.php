<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Exception;

abstract class AbstractHasPreviousExceptionException extends \Exception implements HasPreviousExceptionInterface
{
    public function __construct(
        private readonly \Throwable $previous,
        string $message = '',
        int $code = 0,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getPreviousException(): \Throwable
    {
        return $this->previous;
    }
}
