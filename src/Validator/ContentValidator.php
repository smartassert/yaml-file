<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Validator;

use SmartAssert\YamlFile\Exception\UnexpectedSubjectTypeException;
use SmartAssert\YamlFile\Model\Validation\ContentContext;
use SmartAssert\YamlFile\Model\Validation\Validation;
use SmartAssert\YamlFile\Model\Validation\ValidationInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

class ContentValidator implements ValidatorInterface
{
    public function __construct(
        private Parser $yamlParser,
    ) {
    }

    public function validate(string|object $subject): ValidationInterface
    {
        if (false === is_string($subject)) {
            throw UnexpectedSubjectTypeException::create('string', $subject);
        }

        if ('' === trim($subject)) {
            return Validation::createInvalid(ContentContext::NOT_EMPTY);
        }

        try {
            $this->yamlParser->parse($subject);
        } catch (ParseException $e) {
            return Validation::createInvalid(ContentContext::IS_YAML, $e->getMessage());
        }

        return Validation::createValid();
    }
}
