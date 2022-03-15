<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Collection;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Collection\ArrayProvider;
use SmartAssert\YamlFile\Collection\Deserializer;
use SmartAssert\YamlFile\Collection\ProviderInterface;
use SmartAssert\YamlFile\Model\YamlFile;
use Symfony\Component\Yaml\Parser;

class DeserializerTest extends TestCase
{
    private Deserializer $deserializer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->deserializer = new Deserializer(new Parser());
    }

    /**
     * @dataProvider deserializeDataProvider
     */
    public function testDeserialize(string $serialized, ProviderInterface $expected): void
    {
        self::assertEquals($expected, $this->deserializer->deserialize($serialized));
    }

    /**
     * @return array<mixed>
     */
    public function deserializeDataProvider(): array
    {
        $singleLineFile = YamlFile::create('filename1.yaml', '- file1line1');
        $multiLineFile1 = YamlFile::create('filename2.yaml', '- file2line1' . "\n" . '- file2line2');
        $multilineFile2 = YamlFile::create('filename3.yaml', '- file3line1' . "\n" . '- file3line2');

        $serializedSingleLineFile = <<< 'EOF'
        "filename1.yaml": |
          - file1line1
        EOF;

        $serializedMultiLineFile1 = <<< 'EOF'
        "filename2.yaml": |
          - file2line1
          - file2line2
        EOF;

        $serializedMultiLineFile2 = <<< 'EOF'
        "filename3.yaml": |
          - file3line1
          - file3line2
        EOF;

        return [
            'empty' => [
                'serialized' => '',
                'expected' => new ArrayProvider([]),
            ],
            'single yaml file, single line' => [
                'serialized' => $serializedSingleLineFile,
                'expected' => new ArrayProvider([$singleLineFile]),
            ],
            'single multiline yaml file' => [
                'serialized' => $serializedMultiLineFile1,
                'expected' => new ArrayProvider([$multiLineFile1]),
            ],
            'multiple multiline yaml files' => [
                'serialized' => $serializedMultiLineFile1 . "\n\n" . $serializedMultiLineFile2,
                'expected' => new ArrayProvider([$multiLineFile1, $multilineFile2]),
            ],
        ];
    }
}
