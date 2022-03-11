<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Model\Validation;

class ContentValidation
{
    public function __construct(
        public readonly bool $isValid,
        public readonly ContentContext $context = ContentContext::NONE,
        public readonly string $errorMessage = '',
    ) {
    }

    public static function createValid(): self
    {
        return new ContentValidation(true);
    }

    public static function createInvalid(ContentContext $context, string $errorMessage = ''): self
    {
        return new ContentValidation(false, $context, $errorMessage);
    }
}
