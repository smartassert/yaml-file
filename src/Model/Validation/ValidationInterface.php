<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Model\Validation;

interface ValidationInterface
{
    public function isValid(): bool;

    public function getContext(): ?ContextInterface;

    public function getErrorMessage(): ?string;

    public function getPrevious(): ?ValidationInterface;
}
