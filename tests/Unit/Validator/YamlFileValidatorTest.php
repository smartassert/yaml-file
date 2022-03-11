<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Validator;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Model\Filename;
use SmartAssert\YamlFile\Model\Validation\YamlFileContext;
use SmartAssert\YamlFile\Model\Validation\YamlFileValidation;
use SmartAssert\YamlFile\Model\YamlFile;
use SmartAssert\YamlFile\Validator\ContentValidator;
use SmartAssert\YamlFile\Validator\YamlFilenameValidator;
use SmartAssert\YamlFile\Validator\YamlFileValidator;
use Symfony\Component\Yaml\Parser;

class YamlFileValidatorTest extends TestCase
{
    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate(YamlFile $yamlFile, YamlFileValidation $expected): void
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
                'expected' => YamlFileValidation::createInvalid(YamlFileContext::FILENAME),
            ],
            'invalid content is invalid' => [
                'yamlFile' => new YamlFile(
                    new Filename('', 'filename', 'yaml'),
                    '  invalid' . "\n" . 'yaml'
                ),
                'expected' => YamlFileValidation::createInvalid(
                    YamlFileContext::CONTENT,
                    'Unable to parse at line 1 (near "  invalid").'
                ),
            ],
            'valid' => [
                'yamlFile' => new YamlFile(
                    new Filename('', 'filename', 'yaml'),
                    '- valid content'
                ),
                'expected' => YamlFileValidation::createValid(),
            ],
        ];
    }
}
