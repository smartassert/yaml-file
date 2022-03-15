<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Validator;

use SmartAssert\YamlFile\Filename;
use SmartAssert\YamlFile\Validation\FilenameContext;
use SmartAssert\YamlFile\Validation\Validation;
use SmartAssert\YamlFile\Validation\ValidationInterface;

class YamlFilenameValidator
{
    public const VALID_EXTENSIONS = ['yml', 'yaml'];

    public function validate(Filename $filename): ValidationInterface
    {
        if (false === $this->isPathValid($filename->path)) {
            return Validation::createInvalid(FilenameContext::PATH);
        }

        if (false === $this->isPartValid($filename->name)) {
            return Validation::createInvalid(FilenameContext::NAME);
        }

        if (false === $this->isExtensionValid($filename->extension)) {
            return Validation::createInvalid(FilenameContext::EXTENSION);
        }

        return Validation::createValid();
    }

    private function isPathValid(string $path): bool
    {
        if ('' === $path) {
            return true;
        }

        foreach (explode('/', $path) as $part) {
            if (false === $this->isPartValid($part)) {
                return false;
            }
        }

        return true;
    }

    private function isPartValid(string $name): bool
    {
        return !(
            '' === $name
            || str_contains($name, '\\')
            || str_contains($name, chr(0))
            || str_contains($name, ' ')
        );
    }

    private function isExtensionValid(string $extension): bool
    {
        return in_array($extension, self::VALID_EXTENSIONS);
    }
}
