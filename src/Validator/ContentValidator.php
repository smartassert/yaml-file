<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Validator;

use SmartAssert\YamlFile\Validation\ContentContext;
use SmartAssert\YamlFile\Validation\Validation;
use SmartAssert\YamlFile\Validation\ValidationInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

readonly class ContentValidator
{
    public function __construct(
        private Parser $yamlParser,
    ) {}

    public function validate(string $content): ValidationInterface
    {
        try {
            $this->yamlParser->parse($content);
        } catch (ParseException $e) {
            return Validation::createInvalid(ContentContext::IS_YAML, $e->getMessage());
        }

        return Validation::createValid();
    }
}
