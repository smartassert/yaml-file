<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Collection;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Collection\Serializer;
use SmartAssert\YamlFile\Model\SerializableYamlFile;
use SmartAssert\YamlFile\Model\YamlFile;
use SmartAssert\YamlFile\Provider\ArrayProvider;
use SmartAssert\YamlFile\Provider\ProviderInterface;

class SerializerTest extends TestCase
{
    /**
     * @dataProvider serializeDataProvider
     */
    public function testSerialize(ProviderInterface $provider, string $expected): void
    {
        self::assertSame($expected, (new Serializer())->serialize($provider));
    }

    /**
     * @return array<mixed>
     */
    public function serializeDataProvider(): array
    {
        $file1 = YamlFile::create('filename1.yaml', '- line1' . "\n" . '- line2' . "\n" . '- line3');
        $file2 = YamlFile::create('filename2.yaml', '- line1' . "\n" . '- line2' . "\n" . '- line3');

        return [
            'empty' => [
                'provider' => new ArrayProvider([]),
                'expected' => '',
            ],
            'single yaml file' => [
                'provider' => new ArrayProvider([$file1]),
                'expected' => (string) new SerializableYamlFile($file1),
            ],
            'multiple yaml files' => [
                'provider' => new ArrayProvider([$file1, $file2]),
                'expected' => new SerializableYamlFile($file1) . "\n\n" . new SerializableYamlFile($file2),
            ],
        ];
    }
}
