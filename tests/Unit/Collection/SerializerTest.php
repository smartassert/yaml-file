<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Collection;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Collection\Serializer;
use SmartAssert\YamlFile\Model\YamlFile;
use SmartAssert\YamlFile\Provider\ArrayProvider;
use SmartAssert\YamlFile\Provider\ProviderInterface;

class SerializerTest extends TestCase
{
    /**
     * @dataProvider serializeDataProvider
     */
    public function testSerialize(ProviderInterface $provider, string $expected): void
    {
        self::assertSame($expected, (new Serializer())->serialize($provider));
    }

    /**
     * @return array<mixed>
     */
    public function serializeDataProvider(): array
    {
        $singleLineFile = YamlFile::create('filename1.yaml', '- file1line1');
        $multiLineFile1 = YamlFile::create('filename2.yaml', '- file2line1' . "\n" . '- file2line2');
        $multilineFile2 = YamlFile::create('filename3.yaml', '- file3line1' . "\n" . '- file3line2');

        $expectedSingleLineFile = <<< 'EOF'
        "filename1.yaml": |
          - file1line1
        EOF;

        $expectedMultiLineFile1 = <<< 'EOF'
        "filename2.yaml": |
          - file2line1
          - file2line2
        EOF;

        $expectedMultiLineFile2 = <<< 'EOF'
        "filename3.yaml": |
          - file3line1
          - file3line2
        EOF;

        return [
            'empty' => [
                'provider' => new ArrayProvider([]),
                'expected' => '',
            ],
            'single yaml file, single line' => [
                'provider' => new ArrayProvider([$singleLineFile]),
                'expected' => $expectedSingleLineFile,
            ],
            'single multiline yaml file' => [
                'provider' => new ArrayProvider([$multiLineFile1]),
                'expected' => $expectedMultiLineFile1
            ],
            'multiple multiline yaml files' => [
                'provider' => new ArrayProvider([$multiLineFile1, $multilineFile2]),
                'expected' => $expectedMultiLineFile1 . "\n\n" . $expectedMultiLineFile2,
            ],
        ];
    }
}
