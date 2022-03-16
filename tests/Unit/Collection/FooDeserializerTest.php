<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Collection;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Collection\ArrayCollection;
use SmartAssert\YamlFile\Collection\FooDeserializer;
use SmartAssert\YamlFile\Collection\ProviderInterface;
use SmartAssert\YamlFile\YamlFile;
use webignition\YamlDocumentSetParser\Parser;

class FooDeserializerTest extends TestCase
{
    private FooDeserializer $deserializer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->deserializer = new FooDeserializer(
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

        $encodedContent = [];
        foreach ($content as $item) {
            $encodedContent[] = str_replace("\n", "\n  ", $item);
        }

        $yamlFiles = [];
        foreach ($filenames as $index => $filename) {
            $yamlFiles[] = YamlFile::create($filename, $content[$index]);
        }

        return [
            'empty' => [
                'serialized' => '',
                'expected' => new ArrayCollection([]),
            ],
            'single yaml file, single line' => [
                'serialized' => <<< EOF
                ---
                "path": "{$filenames[0]}"
                "content": |
                  {$encodedContent[0]}
                ...
                EOF,
                'expected' => new ArrayCollection([$yamlFiles[0]]),
            ],
            'single multiline yaml file' => [
                'serialized' => <<< EOF
                ---
                "path": "{$filenames[1]}"
                "content": |
                  {$encodedContent[1]}
                ...
                EOF,
                'expected' => new ArrayCollection([$yamlFiles[1]]),
            ],
            'multiple yaml files' => [
                'serialized' => <<< EOF
                ---
                "path": "{$filenames[0]}"
                "content": |
                  {$encodedContent[0]}
                ...
                ---
                "path": "{$filenames[1]}"
                "content": |
                  {$encodedContent[1]}
                ...
                ---
                "path": "{$filenames[2]}"
                "content": |
                  {$encodedContent[2]}
                ...
                EOF,
                'expected' => new ArrayCollection($yamlFiles),
            ],
        ];
    }
}
