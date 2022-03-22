<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Collection;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Collection\ArrayCollection;
use SmartAssert\YamlFile\Collection\ProviderInterface;
use SmartAssert\YamlFile\Collection\Serializer;
use SmartAssert\YamlFile\FileHashes\Serializer as FileHashesSerializer;
use SmartAssert\YamlFile\YamlFile;
use Symfony\Component\Yaml\Dumper;

class SerializerTest extends TestCase
{
    private Serializer $serializer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serializer = new Serializer(
            new FileHashesSerializer(
                new Dumper()
            )
        );
    }

    /**
     * @dataProvider serializeSuccessDataProvider
     */
    public function testSerializeSuccess(ProviderInterface $provider, string $expected): void
    {
        self::assertSame($expected, $this->serializer->serialize($provider));
    }

    /**
     * @return array<mixed>
     */
    public function serializeSuccessDataProvider(): array
    {
        $filenames = [
            'file1.yaml',
            'file2.yaml',
            'file3.yaml',
            'empty.yaml',
            'duplicate-empty.yaml',
            'duplicate-file1.yaml',
        ];

        $content = [
            '- file1line1',
            '- file2line1' . "\n" . '- file2line2',
            '- file3line1' . "\n" . '- file3line2',
            '', // intentionally empty
            '', // intentionally empty
            '- file1line1',
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
                'provider' => new ArrayCollection([]),
                'expected' => '',
            ],
            'single non-empty yaml file, single line' => [
                'provider' => new ArrayCollection([$yamlFiles[0]]),
                'expected' => <<< EOF
                ---
                {$hashes[0]}:
                    - {$filenames[0]}
                ...
                ---
                {$content[0]}
                ...
                EOF,
            ],
            'single empty yaml file' => [
                'provider' => new ArrayCollection([$yamlFiles[3]]),
                'expected' => <<< EOF
                ---
                {$hashes[3]}:
                    - {$filenames[3]}
                ...
                ---
                ...
                EOF,
            ],
            'single non-empty multiline yaml file' => [
                'provider' => new ArrayCollection([$yamlFiles[1]]),
                'expected' => <<< EOF
                ---
                {$hashes[1]}:
                    - {$filenames[1]}
                ...
                ---
                {$content[1]}
                ...
                EOF,
            ],
            'all' => [
                'provider' => new ArrayCollection($yamlFiles),
                'expected' => <<< EOF
                ---
                {$hashes[0]}:
                    - {$filenames[0]}
                    - {$filenames[5]}
                {$hashes[1]}:
                    - {$filenames[1]}
                {$hashes[2]}:
                    - {$filenames[2]}
                {$hashes[3]}:
                    - {$filenames[3]}
                    - {$filenames[4]}
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
            ],
        ];
    }
}
