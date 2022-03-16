<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\SerializedYamlFile;
use SmartAssert\YamlFile\YamlFile;

class SerializedYamlFileTest extends TestCase
{
    /**
     * @dataProvider toStringDataProvider
     */
    public function testToString(SerializedYamlFile $file, string $expected): void
    {
        self::assertSame($expected, (string) $file);
    }

    /**
     * @return array<mixed>
     */
    public function toStringDataProvider(): array
    {
        return [
            'single line file' => [
                'file' => new SerializedYamlFile(
                    YamlFile::create('filename1.yaml', 'single line of content'),
                ),
                'expected' => <<< 'EOF'
                "path": "filename1.yaml"
                "content": |
                  single line of content
                EOF
            ],
            'multiline file' => [
                'file' => new SerializedYamlFile(
                    YamlFile::create('filename2.yaml', 'line one' . "\n" . 'line two' . "\n" . 'line three'),
                ),
                'expected' => <<< 'EOF'
                "path": "filename2.yaml"
                "content": |
                  line one
                  line two
                  line three
                EOF
            ],
        ];
    }
}
