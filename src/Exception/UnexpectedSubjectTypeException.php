<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Exception;

class UnexpectedSubjectTypeException extends \Exception
{
    public function __construct(
        public readonly string $expected,
        public readonly string $actual
    ) {
        parent::__construct(sprintf(
            'Unexpected subject type. Expected "%s", got "%s"',
            $expected,
            $actual
        ));
    }

    public static function create(string $expected, string|object $actual): self
    {
        return new UnexpectedSubjectTypeException(
            $expected,
            is_string($actual) ? $actual : $actual::class
        );
    }
}
