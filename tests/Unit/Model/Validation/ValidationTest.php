<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Model\Validation;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Model\Validation\ContentContext;
use SmartAssert\YamlFile\Model\Validation\FilenameContext;
use SmartAssert\YamlFile\Model\Validation\Validation;
use SmartAssert\YamlFile\Model\Validation\ValidationInterface;
use SmartAssert\YamlFile\Model\Validation\YamlFileContext;

class ValidationTest extends TestCase
{
    /**
     * @dataProvider getErrorMessageDataProvider
     */
    public function testGetErrorMessage(ValidationInterface $validation, ?string $expected): void
    {
        self::assertSame($expected, $validation->getErrorMessage());
    }

    /**
     * @return array<mixed>
     */
    public function getErrorMessageDataProvider(): array
    {
        return [
            'no error message, no previous' => [
                'validation' => Validation::createValid(),
                'expected' => null,
            ],
            'has error message, no previous' => [
                'validation' => Validation::createInvalid(
                    ContentContext::NOT_EMPTY,
                    'content cannot be empty'
                ),
                'expected' => 'content cannot be empty',
            ],
            'has error message, has previous' => [
                'validation' => Validation::createInvalid(
                    YamlFileContext::FILENAME,
                    'yaml file - filename invalid',
                    Validation::createInvalid(
                        FilenameContext::EXTENSION,
                        'filename - extension invalid',
                    ),
                ),
                'expected' => 'yaml file - filename invalid',
            ],
            'no error message, has previous' => [
                'validation' => Validation::createInvalid(
                    YamlFileContext::FILENAME,
                    null,
                    Validation::createInvalid(
                        FilenameContext::EXTENSION,
                        'filename - extension invalid',
                    ),
                ),
                'expected' => 'filename - extension invalid',
            ],
        ];
    }
}
