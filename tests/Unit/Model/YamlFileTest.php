<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit\Model;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Model\Filename;
use SmartAssert\YamlFile\Model\YamlFile;

class YamlFileTest extends TestCase
{
    public function testCreate(): void
    {
        $filenameAsString = 'filename.yaml';
        $filename = Filename::parse($filenameAsString);
        $content = ' - content';

        self::assertEquals(
            new YamlFile($filename, $content),
            YamlFile::create($filenameAsString, $content)
        );
    }
}
