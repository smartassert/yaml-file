<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Validation;

enum FilenameContext implements ContextInterface
{
    case NONE;
    case PATH;
    case NAME;
    case EXTENSION;
}
