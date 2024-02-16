<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Validator;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Filename;
use SmartAssert\YamlFile\Validation\FilenameContext;
use SmartAssert\YamlFile\Validation\Validation;
use SmartAssert\YamlFile\Validation\ValidationInterface;
use SmartAssert\YamlFile\Validator\YamlFilenameValidator;

class YamlFilenameValidatorTest extends TestCase
{
    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate(Filename $filename, ValidationInterface $expected): void
    {
        $validator = new YamlFilenameValidator();

        self::assertEquals($expected, $validator->validate($filename));
    }

    /**
     * @return array<mixed>
     */
    public static function validateDataProvider(): array
    {
        return [
            'path invalid, part contains back slash' => [
                'filename' => new Filename('path/to/\\invalid', 'filename', 'yaml'),
                'expected' => Validation::createInvalid(FilenameContext::PATH),
            ],
            'path invalid, part contains null byte' => [
                'filename' => new Filename('path/to/' . chr(0) . 'invalid', 'filename', 'yaml'),
                'expected' => Validation::createInvalid(FilenameContext::PATH),
            ],
            'path invalid, part contains space' => [
                'filename' => new Filename('path/to/ invalid', 'filename', 'yaml'),
                'expected' => Validation::createInvalid(FilenameContext::PATH),
            ],
            'name invalid, is empty' => [
                'filename' => new Filename('path', '', 'yaml'),
                'expected' => Validation::createInvalid(FilenameContext::NAME),
            ],
            'name invalid, contains back slash' => [
                'filename' => new Filename('path', 'contains\\backslash', 'yaml'),
                'expected' => Validation::createInvalid(FilenameContext::NAME),
            ],
            'name invalid, contains null byte' => [
                'filename' => new Filename('path', 'contains' . chr(0) . 'null-byte', 'yaml'),
                'expected' => Validation::createInvalid(FilenameContext::NAME),
            ],
            'name invalid, contains space' => [
                'filename' => new Filename('path', 'contains space', 'yaml'),
                'expected' => Validation::createInvalid(FilenameContext::NAME),
            ],
            'extension invalid, not yml, yaml' => [
                'filename' => new Filename('path', 'filename', 'txt'),
                'expected' => Validation::createInvalid(FilenameContext::EXTENSION),
            ],
            'valid, path empty, name non-empty, extension yml' => [
                'filename' => new Filename('', 'filename', 'yml'),
                'expected' => Validation::createValid(),
            ],
            'valid, path empty, name non-empty, extension yaml' => [
                'filename' => new Filename('', 'filename', 'yaml'),
                'expected' => Validation::createValid(),
            ],
            'valid, path non-empty, name non-empty, extension yml' => [
                'filename' => new Filename('path/not/empty', 'filename', 'yml'),
                'expected' => Validation::createValid(),
            ],
            'valid, path non-empty, name non-empty, extension yaml' => [
                'filename' => new Filename('path/not/empty', 'filename', 'yaml'),
                'expected' => Validation::createValid(),
            ],
        ];
    }
}
