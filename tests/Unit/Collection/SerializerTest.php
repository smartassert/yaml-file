<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Collection;

use PHPUnit\Framework\TestCase;
use SmartAssert\Tests\YamlFile\Services\SerializationDataSetFactory;
use SmartAssert\YamlFile\Collection\ProviderInterface;
use SmartAssert\YamlFile\Collection\Serializer;
use SmartAssert\YamlFile\Exception\Collection\SerializeException;
use SmartAssert\YamlFile\Exception\ProvisionException;
use SmartAssert\YamlFile\FileHashes\Serializer as FileHashesSerializer;
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

    public function testSerializeThrowsException(): void
    {
        $provisionException = new ProvisionException(
            new \Exception()
        );

        $provider = \Mockery::mock(ProviderInterface::class);
        $provider
            ->shouldReceive('getYamlFiles')
            ->andThrow($provisionException)
        ;

        try {
            $this->serializer->serialize($provider);
            self::fail(SerializeException::class . ' not thrown');
        } catch (SerializeException $e) {
            self::assertSame($provisionException, $e->getPrevious());
        }
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
        $serializationDataSetFactory = new SerializationDataSetFactory();
        $emptyDataSet = $serializationDataSetFactory->createEmpty();
        $singleFileWithSingleLineDataSet = $serializationDataSetFactory->createSingleFileWithSingleLine();
        $singleEmptyFileDataSet = $serializationDataSetFactory->createSingleEmptyFile();
        $singleFileWithMultipleLinesDataSet = $serializationDataSetFactory->createSingleFileWithMultipleLines();
        $allDataSet = $serializationDataSetFactory->createAll();

        return [
            'empty' => [
                'provider' => $emptyDataSet->provider,
                'expected' => $emptyDataSet->serialized,
            ],
            'single non-empty yaml file, single line' => [
                'provider' => $singleFileWithSingleLineDataSet->provider,
                'expected' => $singleFileWithSingleLineDataSet->serialized,
            ],
            'single empty yaml file' => [
                'provider' => $singleEmptyFileDataSet->provider,
                'expected' => $singleEmptyFileDataSet->serialized,
            ],
            'single non-empty multiline yaml file' => [
                'provider' => $singleFileWithMultipleLinesDataSet->provider,
                'expected' => $singleFileWithMultipleLinesDataSet->serialized,
            ],
            'all' => [
                'provider' => $allDataSet->provider,
                'expected' => $allDataSet->serialized,
            ],
        ];
    }
}
