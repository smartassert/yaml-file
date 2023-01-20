<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Collection;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Collection\AccessorInterface;
use SmartAssert\YamlFile\Collection\ArrayCollection;
use SmartAssert\YamlFile\YamlFile;

class ArrayCollectionTest extends TestCase
{
    public function testGetYamlFiles(): void
    {
        $yamlFiles = [
            YamlFile::create('file1.yaml', '- content1'),
            YamlFile::create('file2.yaml', '- content2'),
            YamlFile::create('file3.yaml', '- content3'),
        ];

        $provider = new ArrayCollection($yamlFiles);

        $providedYamlFiles = [];

        foreach ($provider->getYamlFiles() as $yamlFile) {
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
                'accessor' => new ArrayCollection([]),
                'path' => 'not relevant',
                'expected' => null,
            ],
            'not found' => [
                'accessor' => new ArrayCollection($yamlFiles),
                'path' => 'not-found.yaml',
                'expected' => null,
            ],
            'find file1.yaml' => [
                'accessor' => new ArrayCollection($yamlFiles),
                'path' => 'file1.yaml',
                'expected' => $yamlFiles[0],
            ],
            'find file2.yaml' => [
                'accessor' => new ArrayCollection($yamlFiles),
                'path' => 'directory/file2.yaml',
                'expected' => $yamlFiles[1],
            ],
        ];
    }

    public function testIsPrependable(): void
    {
        $initialItems = [
            'file1' => YamlFile::create('file1', 'file 1 content'),
            'file2' => YamlFile::create('file2', 'file 2 content'),
            'file3' => YamlFile::create('file3', 'file 3 content'),
        ];

        $collection = new ArrayCollection($initialItems);

        self::assertSame($initialItems, $collection->getYamlFilesAsArray());

        $newItem = YamlFile::create('file0', 'file 0 content');

        $prependedCollection = $collection->prepend($newItem);

        self::assertSame(
            array_merge(['file0' => $newItem], $initialItems),
            $prependedCollection->getYamlFilesAsArray()
        );
    }
}
