<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\FileHashes;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Exception\FileHashesDeserializer\DecodeException;
use SmartAssert\YamlFile\Exception\FileHashesDeserializer\InvalidHashException;
use SmartAssert\YamlFile\Exception\FileHashesDeserializer\InvalidPathException;
use SmartAssert\YamlFile\Exception\FileHashesDeserializer\NotArrayException;
use SmartAssert\YamlFile\FileHashes;
use SmartAssert\YamlFile\FileHashes\Deserializer;
use Symfony\Component\Yaml\Parser;

class DeserializerTest extends TestCase
{
    private Deserializer $deserializer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->deserializer = new Deserializer(new Parser());
    }

    public function testDeserializeThrowsYamlParseException(): void
    {
        $encodedContent = '  invalid' . "\n" . 'yaml';

        try {
            $this->deserializer->deserialize($encodedContent);
            self::fail(DecodeException::class . ' not thrown');
        } catch (DecodeException $decodeException) {
            self::assertSame($encodedContent, $decodeException->getEncodedContent());
            self::assertSame(
                'Unable to parse at line 1 (near "  invalid").',
                $decodeException->getParseException()->getMessage()
            );
        }
    }

    public function testDeserializeDataIsNotAnArray(): void
    {
        $encodedContent = 'true';

        try {
            $this->deserializer->deserialize($encodedContent);
            self::fail(NotArrayException::class . ' not thrown');
        } catch (NotArrayException $notArrayException) {
            self::assertSame($encodedContent, $notArrayException->getEncodedContent());
        }
    }

    public function testDeserializeInvalidHashException(): void
    {
        $encodedContent = <<< 'EOT'
        hash1: path1.extension
        123: path2.extension
        hash3: path3.extension
        EOT;

        try {
            $this->deserializer->deserialize($encodedContent);
            self::fail(InvalidHashException::class . ' not thrown');
        } catch (InvalidHashException $invalidHashException) {
            self::assertSame($encodedContent, $invalidHashException->getEncodedContent());
            self::assertSame(1, $invalidHashException->getIndex());
        }
    }

    public function testDeserializeInvalidPathException(): void
    {
        $encodedContent = <<< 'EOT'
        hash1: path1.extension
        hash2: path2.extension
        hash3: true
        EOT;

        try {
            $this->deserializer->deserialize($encodedContent);
            self::fail(InvalidPathException::class . ' not thrown');
        } catch (InvalidPathException $invalidPathException) {
            self::assertSame($encodedContent, $invalidPathException->getEncodedContent());
            self::assertSame(2, $invalidPathException->getIndex());
            self::assertSame('hash3', $invalidPathException->getHash());
        }
    }

    /**
     * @dataProvider deserializeDataProvider
     */
    public function testDeserialize(string $content, FileHashes $expected): void
    {
        self::assertEquals($expected, $this->deserializer->deserialize($content));
    }

    /**
     * @return array<mixed>
     */
    public function deserializeDataProvider(): array
    {
        return [
            'empty' => [
                'content' => '',
                'expected' => new FileHashes(),
            ],
            'single' => [
                'content' => 'hash1: path1.extension',
                'expected' => (new FileHashes())
                    ->add('path1.extension', 'hash1'),
            ],
            'multiple' => [
                'content' => <<< 'EOT'
                hash1: path1.extension
                hash2: path2.extension
                hash3: path3.extension
                EOT,
                'expected' => (new FileHashes())
                    ->add('path1.extension', 'hash1')
                    ->add('path2.extension', 'hash2')
                    ->add('path3.extension', 'hash3'),
            ],
        ];
    }
}
