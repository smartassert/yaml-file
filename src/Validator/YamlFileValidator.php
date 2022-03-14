<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Validator;

use SmartAssert\YamlFile\Model\Validation\Validation;
use SmartAssert\YamlFile\Model\Validation\ValidationInterface;
use SmartAssert\YamlFile\Model\Validation\YamlFileContext;
use SmartAssert\YamlFile\Model\YamlFile;

class YamlFileValidator
{
    public function __construct(
        private readonly YamlFilenameValidator $yamlFilenameValidator,
        private readonly ContentValidator $contentValidator,
    ) {
    }

    public function validate(YamlFile $yamlFile): ValidationInterface
    {
        $filenameValidation = $this->yamlFilenameValidator->validate($yamlFile->name);
        if (false === $filenameValidation->isValid()) {
            return Validation::createInvalid(YamlFileContext::FILENAME, null, $filenameValidation);
        }

        $contentValidation = $this->contentValidator->validate($yamlFile->content);
        if (false === $contentValidation->isValid()) {
            return Validation::createInvalid(YamlFileContext::CONTENT, null, $contentValidation);
        }

        return Validation::createValid();
    }
}
