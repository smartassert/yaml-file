<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Model\Validation;

class YamlFilenameValidation
{
    public function __construct(
        public readonly bool $isValid,
        public readonly FilenameContext $context = FilenameContext::NONE,
        public readonly string $errorMessage = '',
    ) {
    }

    public static function createValid(): self
    {
        return new YamlFilenameValidation(true);
    }

    public static function createInvalid(FilenameContext $context, string $errorMessage = ''): self
    {
        return new YamlFilenameValidation(false, $context, $errorMessage);
    }
}
