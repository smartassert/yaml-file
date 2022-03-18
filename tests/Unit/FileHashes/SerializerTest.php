<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\FileHashes;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\FileHashes;
use SmartAssert\YamlFile\FileHashes\Serializer;
use Symfony\Component\Yaml\Dumper;

class SerializerTest extends TestCase
{
    private Serializer $serializer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serializer = new Serializer(new Dumper());
    }

    /**
     * @dataProvider serializeDataProvider
     */
    public function testSerialize(FileHashes $fileHashes, string $expected): void
    {
        self::assertSame($expected, $this->serializer->serialize($fileHashes));
    }

    /**
     * @return array<mixed>
     */
    public function serializeDataProvider(): array
    {
        return [
            'empty' => [
                'fileHashes' => new FileHashes(),
                'expected' => '',
            ],
            'single' => [
                'fileHashes' => (new FileHashes())
                    ->add('path1.extension', 'hash1'),
                'expected' => 'hash1: path1.extension',
            ],
            'multiple' => [
                'fileHashes' => (new FileHashes())
                    ->add('path1.extension', 'hash1')
                    ->add('path2.extension', 'hash2')
                    ->add('path3.extension', 'hash3'),
                'expected' => <<< 'EOT'
                hash1: path1.extension
                hash2: path2.extension
                hash3: path3.extension
                EOT,
            ],
        ];
    }
}