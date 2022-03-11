<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Model\Validation;

class YamlFileValidation
{
    public function __construct(
        public readonly bool $isValid,
        public readonly YamlFileContext $context = YamlFileContext::NONE,
        public readonly string $errorMessage = '',
    ) {
    }

    public static function createValid(): self
    {
        return new YamlFileValidation(true);
    }

    public static function createInvalid(YamlFileContext $context, string $errorMessage = ''): self
    {
        return new YamlFileValidation(false, $context, $errorMessage);
    }
}
