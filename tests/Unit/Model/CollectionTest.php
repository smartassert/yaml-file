<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Model;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Model\Collection;
use SmartAssert\YamlFile\Model\YamlFile;

class CollectionTest extends TestCase
{
    public function testGetAll(): void
    {
        $files = [
            YamlFile::create('file1.yaml', ' - file1 content'),
            YamlFile::create('file2.yaml', ' - file2 content'),
            YamlFile::create('file3.yaml', ' - file3 content'),
        ];

        $collection = new Collection($files);

        self::assertEquals($files, $collection->getAll());
    }
}
