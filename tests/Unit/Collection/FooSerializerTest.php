<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Collection;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Collection\ArrayCollection;
use SmartAssert\YamlFile\Collection\FooSerializer;
use SmartAssert\YamlFile\Collection\ProviderInterface;
use SmartAssert\YamlFile\YamlFile;
use Symfony\Component\Yaml\Dumper;

class FooSerializerTest extends TestCase
{
    private FooSerializer $serializer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serializer = new FooSerializer(
            new Dumper(),
        );
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

        $hashes = [];
        foreach ($content as $item) {
            $hashes[] = md5($item);
        }

        $yamlFiles = [];
        foreach ($filenames as $index => $filename) {
            $yamlFiles[] = YamlFile::create($filename, $content[$index]);
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
                metadata:
                    {$filenames[0]}: {$hashes[0]}
                ...
                ---
                {$content[0]}
                ...
                EOF,
            ],
            'single multiline yaml file' => [
                'provider' => new ArrayCollection([$yamlFiles[1]]),
                'expected' => <<< EOF
                ---
                metadata:
                    {$filenames[1]}: {$hashes[1]}
                ...
                ---
                {$content[1]}
                ...
                EOF
            ],
            'multiple yaml files' => [
                'provider' => new ArrayCollection($yamlFiles),
                'expected' => <<< EOF
                ---
                metadata:
                    {$filenames[0]}: {$hashes[0]}
                    {$filenames[1]}: {$hashes[1]}
                    {$filenames[2]}: {$hashes[2]}
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
                EOF
            ],
        ];
    }
}
