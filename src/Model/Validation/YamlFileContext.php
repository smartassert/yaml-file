<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Model\Validation;

enum YamlFileContext
{
    case NONE;
    case FILENAME;
    case CONTENT;
}
