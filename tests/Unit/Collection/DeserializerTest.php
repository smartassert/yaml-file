<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Collection;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Collection\ArrayCollection;
use SmartAssert\YamlFile\Collection\Deserializer;
use SmartAssert\YamlFile\Collection\ProviderInterface;
use SmartAssert\YamlFile\SerializedYamlFile;
use SmartAssert\YamlFile\YamlFile;
use webignition\YamlDocumentSetParser\Parser;

class DeserializerTest extends TestCase
{
    private Deserializer $deserializer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->deserializer = new Deserializer(
            new Parser()
        );
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
        $filenames = ['file1.yaml', 'file2.yaml', 'file3.yaml'];

        $content = ['- file1line1', '- file2line1' . "\n" . '- file2line2', '- file3line1' . "\n" . '- file3line2'];

        $yamlFiles = [];
        foreach ($filenames as $index => $filename) {
            $yamlFiles[] = YamlFile::create($filename, $content[$index]);
        }

        $serializedFiles = [];
        foreach ($yamlFiles as $yamlFile) {
            $serializedFiles[] = (string) new SerializedYamlFile($yamlFile);
        }

        return [
            'empty' => [
                'serialized' => '',
                'expected' => new ArrayCollection([]),
            ],
            'single yaml file, single line' => [
                'serialized' => <<< EOF
                ---
                {$serializedFiles[0]}
                ...
                EOF,
                'expected' => new ArrayCollection([$yamlFiles[0]]),
            ],
            'single multiline yaml file' => [
                'serialized' => <<< EOF
                ---
                {$serializedFiles[1]}
                ...
                EOF,
                'expected' => new ArrayCollection([$yamlFiles[1]]),
            ],
            'multiple yaml files' => [
                'serialized' => <<< EOF
                ---
                {$serializedFiles[0]}
                ...
                ---
                {$serializedFiles[1]}
                ...
                ---
                {$serializedFiles[2]}
                ...
                EOF,
                'expected' => new ArrayCollection($yamlFiles),
            ],
        ];
    }
}
