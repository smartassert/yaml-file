<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Validator;

use SmartAssert\YamlFile\Model\Validation\YamlFileContext;
use SmartAssert\YamlFile\Model\Validation\YamlFileValidation;
use SmartAssert\YamlFile\Model\YamlFile;

class YamlFileValidator
{
    public function __construct(
        private readonly YamlFilenameValidator $yamlFilenameValidator,
        private readonly ContentValidator $contentValidator,
    ) {
    }

    public function validate(YamlFile $yamlFile): YamlFileValidation
    {
        $filenameValidation = $this->yamlFilenameValidator->validate($yamlFile->name);
        if (false === $filenameValidation->isValid) {
            return YamlFileValidation::createInvalid(YamlFileContext::FILENAME, $filenameValidation->errorMessage);
        }

        $contentValidation = $this->contentValidator->validate($yamlFile->content);
        if (false === $contentValidation->isValid) {
            return YamlFileValidation::createInvalid(YamlFileContext::CONTENT, $contentValidation->errorMessage);
        }

        return YamlFileValidation::createValid();
    }
}
