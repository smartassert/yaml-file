<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Collection;

use PHPUnit\Framework\TestCase;
use SmartAssert\Tests\YamlFile\Services\SerializationDataSetFactory;
use SmartAssert\YamlFile\Collection\Deserializer;
use SmartAssert\YamlFile\Collection\ProviderInterface;
use SmartAssert\YamlFile\Exception\CollectionDeserializer\FilePathNotFoundException;
use SmartAssert\YamlFile\Exception\FileHashesDeserializer\InvalidPathException;
use SmartAssert\YamlFile\FileHashes;
use SmartAssert\YamlFile\FileHashes\Deserializer as FileHashesDeserializer;
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

    public function testDeserializeFileHashesException(): void
    {
        $content = <<< 'EOF'
            ---
            hash1:
                - true
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
            hash1:
                - path1hash
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
        $serializationDataSetFactory = new SerializationDataSetFactory();
        $emptyDataSet = $serializationDataSetFactory->createEmpty();
        $singleFileWithSingleLineDataSet = $serializationDataSetFactory->createSingleFileWithSingleLine();
        $singleEmptyFileDataSet = $serializationDataSetFactory->createSingleEmptyFile();
        $singleFileWithMultipleLinesDataSet = $serializationDataSetFactory->createSingleFileWithMultipleLines();
        $allDataSet = $serializationDataSetFactory->createAll();

        return [
            'empty' => [
                'serialized' => $emptyDataSet->serialized,
                'expected' => $emptyDataSet->provider,
            ],
            'single yaml file, single line' => [
                'serialized' => $singleFileWithSingleLineDataSet->serialized,
                'expected' => $singleFileWithSingleLineDataSet->provider,
            ],
            'single empty yaml file' => [
                'serialized' => $singleEmptyFileDataSet->serialized,
                'expected' => $singleEmptyFileDataSet->provider,
            ],
            'single multiline yaml file' => [
                'serialized' => $singleFileWithMultipleLinesDataSet->serialized,
                'expected' => $singleFileWithMultipleLinesDataSet->provider,
            ],
            'all' => [
                'serialized' => $allDataSet->serialized,
                'expected' => $allDataSet->provider,
            ],
        ];
    }
}
