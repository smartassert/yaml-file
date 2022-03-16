<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Collection;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Collection\ArrayCollection;
use SmartAssert\YamlFile\Collection\FooSerializer;
use SmartAssert\YamlFile\Collection\ProviderInterface;
use SmartAssert\YamlFile\SerializedYamlFile;
use SmartAssert\YamlFile\YamlFile;

class FooSerializerTest extends TestCase
{
    private FooSerializer $serializer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serializer = new FooSerializer();
    }

    /**
     * @dataProvider serializeDataProvider
     */
    public function testSerialize(ProviderInterface $provider, string $expected): void
    {
        self::assertSame($expected, $this->serializer->serialize($provider));
    }

    /**
     * @return array<mixed>
     */
    public function serializeDataProvider(): array
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
                'provider' => new ArrayCollection([]),
                'expected' => '',
            ],
            'single yaml file, single line' => [
                'provider' => new ArrayCollection([$yamlFiles[0]]),
                'expected' => <<< EOF
                ---
                {$serializedFiles[0]}
                ...
                EOF,
            ],
            'single multiline yaml file' => [
                'provider' => new ArrayCollection([$yamlFiles[1]]),
                'expected' => <<< EOF
                ---
                {$serializedFiles[1]}
                ...
                EOF
            ],
            'multiple yaml files' => [
                'provider' => new ArrayCollection($yamlFiles),
                'expected' => <<< EOF
                ---
                {$serializedFiles[0]}
                ...
                ---
                {$serializedFiles[1]}
                ...
                ---
                {$serializedFiles[2]}
                ...
                EOF
            ],
        ];
    }
}
