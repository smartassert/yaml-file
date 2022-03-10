<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Model;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Model\Filename;

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
     * @return array<mixed>
     */
    public function filenameDataProvider(): array
    {
        return [
            'empty' => [
                'filename' => '',
            ],
            'name only' => [
                'filename' => 'filename',
            ],
            'extension only' => [
                'filename' => '.yaml',
            ],
            'name and extension' => [
                'filename' => 'filename.yaml',
            ],
            'has path' => [
                'filename' => 'path/to/filename.yml',
            ],
        ];
    }

    /**
     * @dataProvider getExtensionDataProvider
     */
    public function testGetExtension(Filename $filename, string $expected): void
    {
        self::assertSame($expected, $filename->getExtension());
    }

    /**
     * @return array<mixed>
     */
    public function getExtensionDataProvider(): array
    {
        return [
            'empty' => [
                'filename' => Filename::parse(''),
                'expected' => '',
            ],
            'no extension' => [
                'filename' => Filename::parse('filename'),
                'expected' => '',
            ],
            'empty extension' => [
                'filename' => Filename::parse('filename.'),
                'expected' => '',
            ],
            'single dot' => [
                'filename' => Filename::parse('filename.yaml'),
                'expected' => 'yaml',
            ],
            'multiple dots' => [
                'filename' => Filename::parse('multiple.filename.yml'),
                'expected' => 'yml',
            ],
        ];
    }

    /**
     * @dataProvider getNameDataProvider
     */
    public function testGetName(Filename $filename, string $expected): void
    {
        self::assertSame($expected, $filename->getName());
    }

    /**
     * @return array<mixed>
     */
    public function getNameDataProvider(): array
    {
        return [
            'empty' => [
                'filename' => Filename::parse(''),
                'expected' => '',
            ],
            'no extension' => [
                'filename' => Filename::parse('filename'),
                'expected' => 'filename',
            ],
            'empty extension' => [
                'filename' => Filename::parse('filename.'),
                'expected' => 'filename',
            ],
            'single dot' => [
                'filename' => Filename::parse('filename.yaml'),
                'expected' => 'filename',
            ],
            'multiple dots' => [
                'filename' => Filename::parse('multiple.filename.yml'),
                'expected' => 'multiple.filename',
            ],
        ];
    }
}
