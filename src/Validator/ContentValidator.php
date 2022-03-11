<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Validator;

use SmartAssert\YamlFile\Model\Validation\ContentContext;
use SmartAssert\YamlFile\Model\Validation\ContentValidation;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

class ContentValidator
{
    public function __construct(
        private Parser $yamlParser,
    ) {
    }

    public function validate(string $content): ContentValidation
    {
        if ('' === trim($content)) {
            return ContentValidation::createInvalid(ContentContext::NOT_EMPTY);
        }

        try {
            $this->yamlParser->parse($content);
        } catch (ParseException $e) {
            return ContentValidation::createInvalid(ContentContext::IS_YAML, $e->getMessage());
        }

        return ContentValidation::createValid();
    }
}
