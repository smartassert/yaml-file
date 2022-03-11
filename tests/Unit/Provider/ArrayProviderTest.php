<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Provider;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Model\YamlFile;
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
}
