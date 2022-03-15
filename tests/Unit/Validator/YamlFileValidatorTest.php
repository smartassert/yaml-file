<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Validator;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Model\Filename;
use SmartAssert\YamlFile\Model\YamlFile;
use SmartAssert\YamlFile\Validation\ContentContext;
use SmartAssert\YamlFile\Validation\FilenameContext;
use SmartAssert\YamlFile\Validation\Validation;
use SmartAssert\YamlFile\Validation\ValidationInterface;
use SmartAssert\YamlFile\Validation\YamlFileContext;
use SmartAssert\YamlFile\Validator\ContentValidator;
use SmartAssert\YamlFile\Validator\YamlFilenameValidator;
use SmartAssert\YamlFile\Validator\YamlFileValidator;
use Symfony\Component\Yaml\Parser;

class YamlFileValidatorTest extends TestCase
{
    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate(YamlFile $yamlFile, ValidationInterface $expected): void
    {
        $validator = new YamlFileValidator(
            new YamlFilenameValidator(),
            new ContentValidator(
                new Parser()
            )
        );

        self::assertEquals($expected, $validator->validate($yamlFile));
    }

    /**
     * @return array<mixed>
     */
    public function validateDataProvider(): array
    {
        return [
            'invalid filename is invalid' => [
                'yamlFile' => new YamlFile(
                    new Filename('', ' ', 'yaml'),
                    '- valid content'
                ),
                'expected' => Validation::createInvalid(
                    YamlFileContext::FILENAME,
                    null,
                    Validation::createInvalid(
                        FilenameContext::NAME
                    )
                ),
            ],
            'invalid content is invalid' => [
                'yamlFile' => new YamlFile(
                    new Filename('', 'filename', 'yaml'),
                    '  invalid' . "\n" . 'yaml'
                ),
                'expected' => Validation::createInvalid(
                    YamlFileContext::CONTENT,
                    null,
                    Validation::createInvalid(
                        ContentContext::IS_YAML,
                        'Unable to parse at line 1 (near "  invalid").'
                    ),
                ),
            ],
            'valid' => [
                'yamlFile' => new YamlFile(
                    new Filename('', 'filename', 'yaml'),
                    '- valid content'
                ),
                'expected' => Validation::createValid(),
            ],
        ];
    }
}
