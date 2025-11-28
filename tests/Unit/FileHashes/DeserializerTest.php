<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\FileHashes;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Exception\FileHashesDeserializer\DecodeException;
use SmartAssert\YamlFile\Exception\FileHashesDeserializer\InvalidHashException;
use SmartAssert\YamlFile\Exception\FileHashesDeserializer\InvalidPathException;
use SmartAssert\YamlFile\Exception\FileHashesDeserializer\NotArrayException;
use SmartAssert\YamlFile\Exception\FileHashesDeserializer\PathCollectionNotArrayException;
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
        } catch (DecodeException $exception) {
            self::assertSame($encodedContent, $exception->getEncodedContent());
            self::assertSame(
                'Unable to parse at line 1 (near "  invalid").',
                $exception->getParseException()->getMessage()
            );
        }
    }

    public function testDeserializeDataIsNotAnArray(): void
    {
        $encodedContent = 'true';

        try {
            $this->deserializer->deserialize($encodedContent);
            self::fail(NotArrayException::class . ' not thrown');
        } catch (NotArrayException $exception) {
            self::assertSame($encodedContent, $exception->getEncodedContent());
        }
    }

    public function testDeserializeInvalidHashException(): void
    {
        $encodedContent = <<< 'EOT'
        hash1:
            - path1.extension
        123:
            - path2.extension
        hash3:
            - path3.extension
        EOT;

        try {
            $this->deserializer->deserialize($encodedContent);
            self::fail(InvalidHashException::class . ' not thrown');
        } catch (InvalidHashException $exception) {
            self::assertSame($encodedContent, $exception->getEncodedContent());
            self::assertSame(1, $exception->getIndex());
        }
    }

    public function testDeserializePathCollectionNotAnArray(): void
    {
        $encodedContent = <<< 'EOT'
        hash1:
            - path1.extension
        hash2: 123
        EOT;

        try {
            $this->deserializer->deserialize($encodedContent);
            self::fail(PathCollectionNotArrayException::class . ' not thrown');
        } catch (PathCollectionNotArrayException $exception) {
            self::assertSame($encodedContent, $exception->getEncodedContent());
            self::assertSame('hash2', $exception->getHash());
            self::assertSame(123, $exception->getPathCollection());
        }
    }

    public function testDeserializeInvalidPathException(): void
    {
        $encodedContent = <<< 'EOT'
        hash1:
            - path1.extension
        hash2:
            - path2.extension
        hash3:
            - true
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

    #[DataProvider('deserializeSuccessDataProvider')]
    public function testDeserializeSuccess(string $content, FileHashes $expected): void
    {
        self::assertEquals($expected, $this->deserializer->deserialize($content));
    }

    /**
     * @return array<mixed>
     */
    public static function deserializeSuccessDataProvider(): array
    {
        return [
            'empty' => [
                'content' => '',
                'expected' => new FileHashes(),
            ],
            'single' => [
                'content' => <<< 'EOT'
                hash1:
                    - path1.extension
                EOT,
                'expected' => (new FileHashes())
                    ->add('path1.extension', 'hash1'),
            ],
            'multiple hashes mapping to one file each' => [
                'content' => <<< 'EOT'
                hash1:
                    - path1.extension
                hash2:
                    - path2.extension
                hash3:
                    - path3.extension
                EOT,
                'expected' => (new FileHashes())
                    ->add('path1.extension', 'hash1')
                    ->add('path2.extension', 'hash2')
                    ->add('path3.extension', 'hash3'),
            ],
            'multiple hashes each mapping to multiple files' => [
                'content' => <<< 'EOT'
                hash1:
                    - path1.extension
                    - path2.extension
                hash2:
                    - path3.extension
                    - path4.extension
                hash3:
                    - path5.extension
                    - path6.extension
                    - path7.extension
                EOT,
                'expected' => (new FileHashes())
                    ->add('path1.extension', 'hash1')
                    ->add('path2.extension', 'hash1')
                    ->add('path3.extension', 'hash2')
                    ->add('path4.extension', 'hash2')
                    ->add('path5.extension', 'hash3')
                    ->add('path6.extension', 'hash3')
                    ->add('path7.extension', 'hash3'),
            ],
        ];
    }
}
