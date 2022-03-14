<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Validator;

use SmartAssert\YamlFile\Exception\UnexpectedSubjectTypeException;
use SmartAssert\YamlFile\Model\Validation\Validation;
use SmartAssert\YamlFile\Model\Validation\ValidationInterface;
use SmartAssert\YamlFile\Model\Validation\YamlFileContext;
use SmartAssert\YamlFile\Model\YamlFile;

class YamlFileValidator implements ValidatorInterface
{
    public function __construct(
        private readonly YamlFilenameValidator $yamlFilenameValidator,
        private readonly ContentValidator $contentValidator,
    ) {
    }

    public function validate(string|object $subject): ValidationInterface
    {
        if (!$subject instanceof YamlFile) {
            throw UnexpectedSubjectTypeException::create(YamlFile::class, $subject);
        }

        $filenameValidation = $this->yamlFilenameValidator->validate($subject->name);
        if (false === $filenameValidation->isValid()) {
            return Validation::createInvalid(YamlFileContext::FILENAME, null, $filenameValidation);
        }

        $contentValidation = $this->contentValidator->validate($subject->content);
        if (false === $contentValidation->isValid()) {
            return Validation::createInvalid(YamlFileContext::CONTENT, null, $contentValidation);
        }

        return Validation::createValid();
    }
}
