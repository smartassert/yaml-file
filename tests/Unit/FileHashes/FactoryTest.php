<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\FileHashes;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Collection\ArrayCollection;
use SmartAssert\YamlFile\FileHashes;
use SmartAssert\YamlFile\FileHashes\Factory;
use SmartAssert\YamlFile\YamlFile;

class FactoryTest extends TestCase
{
    private Factory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new Factory();
    }

    public function testCreateFromProvider(): void
    {
        /**
         * @var YamlFile[] $yamlFiles
         */
        $yamlFiles = [
            YamlFile::create('Test/test1.yml', 'content 001'),
            YamlFile::create('Page/page1.yml', 'content 002'),
            YamlFile::create('DataProvider/provider1.yml', 'content 003'),
        ];

        $provider = new ArrayCollection($yamlFiles);
        $fileHashes = $this->factory->createFromProvider($provider);

        self::assertEquals(
            (new FileHashes())
                ->add((string) $yamlFiles[0]->name, md5($yamlFiles[0]->content))
                ->add((string) $yamlFiles[1]->name, md5($yamlFiles[1]->content))
                ->add((string) $yamlFiles[2]->name, md5($yamlFiles[2]->content)),
            $fileHashes
        );
    }
}
