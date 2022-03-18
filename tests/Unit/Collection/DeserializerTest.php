<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Collection;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Collection\ArrayCollection;
use SmartAssert\YamlFile\Collection\Deserializer;
use SmartAssert\YamlFile\Collection\ProviderInterface;
use SmartAssert\YamlFile\Exception\CollectionDeserializer\FilePathNotFoundException;
use SmartAssert\YamlFile\Exception\FileHashesDeserializer\InvalidPathException;
use SmartAssert\YamlFile\FileHashes;
use SmartAssert\YamlFile\FileHashes\Deserializer as FileHashesDeserializer;
use SmartAssert\YamlFile\YamlFile;
use Symfony\Component\Yaml\Parser as YamlParser;
use webignition\YamlDocumentSetParser\Parser as DocumentSetParser;

class DeserializerTest extends TestCase
{
    private Deserializer $deserializer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->deserializer = new Deserializer(
            new DocumentSetParser(),
            new FileHashesDeserializer(
                new YamlParser()
            )
        );
    }

    public function testDeserializeInvalidFileHashes(): void
    {
        $content = <<< 'EOF'
            ---
            hash1: true
            ...
            ---
            content for path1.yaml
            ...
            EOF;

        $this->expectExceptionObject(new InvalidPathException($content, 'hash1', 0));

        $this->deserializer->deserialize($content);
    }

    public function testDeserializeFilePathNotFound(): void
    {
        $content = <<< 'EOF'
            ---
            hash1: path1hash
            ...
            ---
            content for path1.yaml
            ...
            EOF;

        $this->expectExceptionObject(new FilePathNotFoundException(
            'e1810158fa43e20800abda5f82bb7baa',
            (new FileHashes())
        ));

        $this->deserializer->deserialize($content);
    }

    /**
     * @dataProvider deserializeSuccessDataProvider
     */
    public function testDeserializeSuccess(string $serialized, ProviderInterface $expected): void
    {
        self::assertEquals($expected, $this->deserializer->deserialize($serialized));
    }

    /**
     * @return array<mixed>
     */
    public function deserializeSuccessDataProvider(): array
    {
        $filenames = ['file1.yaml', 'file2.yaml', 'file3.yaml', 'empty.yaml'];

        $content = [
            '- file1line1',
            '- file2line1' . "\n" . '- file2line2',
            '- file3line1' . "\n" . '- file3line2',
            '', // intentionally empty
        ];

        $yamlFiles = [];
        foreach ($filenames as $index => $filename) {
            $yamlFiles[] = YamlFile::create($filename, $content[$index]);
        }

        $hashes = [];
        foreach ($content as $item) {
            $hashes[] = md5($item);
        }

        return [
            'empty' => [
                'serialized' => '',
                'expected' => new ArrayCollection([]),
            ],
            'single yaml file, single line' => [
                'serialized' => <<< EOF
                ---
                {$hashes[0]}: {$filenames[0]}
                ...
                ---
                {$content[0]}
                ...
                EOF,
                'expected' => new ArrayCollection([$yamlFiles[0]]),
            ],
            'single multiline yaml file' => [
                'serialized' => <<< EOF
                ---
                {$hashes[1]}: {$filenames[1]}
                ...
                ---
                {$content[1]}
                ...
                EOF,
                'expected' => new ArrayCollection([$yamlFiles[1]]),
            ],
            'multiple yaml files' => [
                'serialized' => <<< EOF
                ---
                {$hashes[0]}: {$filenames[0]}
                {$hashes[1]}: {$filenames[1]}
                {$hashes[2]}: {$filenames[2]}
                {$hashes[3]}: {$filenames[3]}
                ...
                ---
                {$content[0]}
                ...
                ---
                {$content[1]}
                ...
                ---
                {$content[2]}
                ...
                ---
                ...
                EOF,
                'expected' => new ArrayCollection($yamlFiles),
            ],
        ];
    }
}
