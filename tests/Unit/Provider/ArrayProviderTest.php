<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Provider;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Model\YamlFile;
use SmartAssert\YamlFile\Provider\AccessorInterface;
use SmartAssert\YamlFile\Provider\ArrayProvider;

class ArrayProviderTest extends TestCase
{
    public function testProvide(): void
    {
        $yamlFiles = [
            YamlFile::create('file1.yaml', '- content1'),
            YamlFile::create('file2.yaml', '- content2'),
            YamlFile::create('file3.yaml', '- content3'),
        ];

        $provider = new ArrayProvider($yamlFiles);

        $providedYamlFiles = [];

        foreach ($provider->provide() as $yamlFile) {
            $providedYamlFiles[] = $yamlFile;
        }

        self::assertSame($yamlFiles, $providedYamlFiles);
    }

    /**
     * @dataProvider getDataProvider
     */
    public function testGet(AccessorInterface $accessor, string $path, ?YamlFile $expected): void
    {
        self::assertSame($expected, $accessor->get($path));
    }

    /**
     * @return array<mixed>
     */
    public function getDataProvider(): array
    {
        $yamlFiles = [
            YamlFile::create('file1.yaml', '- file1 content'),
            YamlFile::create('directory/file2.yaml', '- file2 content'),
        ];

        return [
            'empty' => [
                'accessor' => new ArrayProvider([]),
                'path' => 'not relevant',
                'expected' => null,
            ],
            'not found' => [
                'accessor' => new ArrayProvider($yamlFiles),
                'path' => 'not-found.yaml',
                'expected' => null,
            ],
            'find file1.yaml' => [
                'accessor' => new ArrayProvider($yamlFiles),
                'path' => 'file1.yaml',
                'expected' => $yamlFiles[0],
            ],
            'find file2.yaml' => [
                'accessor' => new ArrayProvider($yamlFiles),
                'path' => 'directory/file2.yaml',
                'expected' => $yamlFiles[1],
            ],
        ];
    }
}
