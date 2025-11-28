<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Filename;

class FilenameTest extends TestCase
{
    #[DataProvider('filenameDataProvider')]
    public function testToString(
        string $value,
        string $expectedPath,
        string $expectedName,
        string $expectedExtension
    ): void {
        self::assertSame($value, (string) Filename::parse($value));
    }

    #[DataProvider('filenameDataProvider')]
    public function testGetParts(
        string $value,
        string $expectedPath,
        string $expectedName,
        string $expectedExtension
    ): void {
        $filename = Filename::parse($value);

        self::assertSame($expectedPath, $filename->path);
        self::assertSame($expectedName, $filename->name);
        self::assertSame($expectedExtension, $filename->extension);
    }

    /**
     * @return array<mixed>
     */
    public static function filenameDataProvider(): array
    {
        return [
            'empty' => [
                'value' => '',
                'expectedPath' => '',
                'expectedName' => '',
                'expectedExtension' => '',
            ],
            'name only' => [
                'value' => 'value',
                'expectedPath' => '',
                'expectedName' => 'value',
                'expectedExtension' => '',
            ],
            'extension only' => [
                'value' => '.yaml',
                'expectedPath' => '',
                'expectedName' => '',
                'expectedExtension' => 'yaml',
            ],
            'name and extension' => [
                'value' => 'filename.yaml',
                'expectedPath' => '',
                'expectedName' => 'filename',
                'expectedExtension' => 'yaml',
            ],
            'has path' => [
                'value' => 'path/to/filename.yml',
                'expectedPath' => 'path/to',
                'expectedName' => 'filename',
                'expectedExtension' => 'yml',
            ],
        ];
    }
}
