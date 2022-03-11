<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Model;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Model\SerializableYamlFile;
use SmartAssert\YamlFile\Model\YamlFile;

class SerializableYamlFileTest extends TestCase
{
    /**
     * @dataProvider toStringDataProvider
     */
    public function testToString(SerializableYamlFile $yamlFile, string $expected): void
    {
        self::assertSame($expected, (string) $yamlFile);
    }

    /**
     * @return array<mixed>
     */
    public function toStringDataProvider(): array
    {
        return [
            'empty' => [
                'yamlFile' => new SerializableYamlFile(
                    YamlFile::create('filename.yaml', '')
                ),
                'expected' => <<< 'EOF'
                "filename.yaml": |
                  
                EOF,
            ],
            'non-empty, single line' => [
                'yamlFile' => new SerializableYamlFile(
                    YamlFile::create('filename.yaml', '- line1')
                ),
                'expected' => <<< 'EOF'
                "filename.yaml": |
                  - line1
                EOF,
            ],
            'non-empty, multiple lines' => [
                'yamlFile' => new SerializableYamlFile(
                    YamlFile::create('filename.yaml', '- line1' . "\n" . '- line2' . "\n" . '- line3')
                ),
                'expected' => <<< 'EOF'
                "filename.yaml": |
                  - line1
                  - line2
                  - line3
                EOF,
            ],
        ];
    }
}
