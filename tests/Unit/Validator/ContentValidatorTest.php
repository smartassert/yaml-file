<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Validator;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Validation\ContentContext;
use SmartAssert\YamlFile\Validation\Validation;
use SmartAssert\YamlFile\Validation\ValidationInterface;
use SmartAssert\YamlFile\Validator\ContentValidator;
use Symfony\Component\Yaml\Parser;

class ContentValidatorTest extends TestCase
{
    #[DataProvider('validateDataProvider')]
    public function testValidate(string $content, ValidationInterface $expected): void
    {
        $validator = new ContentValidator(new Parser());

        self::assertEquals($expected, $validator->validate($content));
    }

    /**
     * @return array<mixed>
     */
    public static function validateDataProvider(): array
    {
        return [
            'non-parseable yaml is invalid' => [
                'content' => '  invalid' . "\n" . 'yaml',
                'expected' => Validation::createInvalid(
                    ContentContext::IS_YAML,
                    'Unable to parse at line 1 (near "  invalid").'
                ),
            ],
            'valid' => [
                'content' => '- content',
                'expected' => Validation::createValid(),
            ],
        ];
    }
}
