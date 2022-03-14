<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Validator;

use SmartAssert\YamlFile\Exception\UnexpectedSubjectTypeException;
use SmartAssert\YamlFile\Model\Validation\ValidationInterface;

interface ValidatorInterface
{
    /**
     * @throws UnexpectedSubjectTypeException
     */
    public function validate(string|object $subject): ValidationInterface;
}
