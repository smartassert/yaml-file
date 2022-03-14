<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Model\Validation;

enum ContentContext implements ContextInterface
{
    case NONE;
    case NOT_EMPTY;
    case IS_YAML;
}
