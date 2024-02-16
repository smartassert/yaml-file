<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Filename;

class FilenameTest extends TestCase
{
    /**
     * @dataProvider filenameDataProvider
     */
    public function testToString(string $filename): void
    {
        self::assertSame($filename, (string) Filename::parse($filename));
    }

    /**
     * @dataProvider filenameDataProvider
     */
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
                'filename' => '',
                'expectedPath' => '',
                'expectedName' => '',
                'expectedExtension' => '',
            ],
            'name only' => [
                'filename' => 'filename',
                'expectedPath' => '',
                'expectedName' => 'filename',
                'expectedExtension' => '',
            ],
            'extension only' => [
                'filename' => '.yaml',
                'expectedPath' => '',
                'expectedName' => '',
                'expectedExtension' => 'yaml',
            ],
            'name and extension' => [
                'filename' => 'filename.yaml',
                'expectedPath' => '',
                'expectedName' => 'filename',
                'expectedExtension' => 'yaml',
            ],
            'has path' => [
                'filename' => 'path/to/filename.yml',
                'expectedPath' => 'path/to',
                'expectedName' => 'filename',
                'expectedExtension' => 'yml',
            ],
        ];
    }
}
