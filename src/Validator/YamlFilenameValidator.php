<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Validator;

use SmartAssert\YamlFile\Exception\UnexpectedSubjectTypeException;
use SmartAssert\YamlFile\Model\Filename;
use SmartAssert\YamlFile\Model\Validation\FilenameContext;
use SmartAssert\YamlFile\Model\Validation\Validation;
use SmartAssert\YamlFile\Model\Validation\ValidationInterface;

class YamlFilenameValidator implements ValidatorInterface
{
    public const VALID_EXTENSIONS = ['yml', 'yaml'];

    public function validate(string|object $subject): ValidationInterface
    {
        if (!$subject instanceof Filename) {
            throw UnexpectedSubjectTypeException::create(Filename::class, $subject);
        }

        if (false === $this->isPathValid($subject->path)) {
            return Validation::createInvalid(FilenameContext::PATH);
        }

        if (false === $this->isPartValid($subject->name)) {
            return Validation::createInvalid(FilenameContext::NAME);
        }

        if (false === $this->isExtensionValid($subject->extension)) {
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
