<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Validation;

enum YamlFileContext implements ContextInterface
{
    case NONE;
    case FILENAME;
    case CONTENT;
}
