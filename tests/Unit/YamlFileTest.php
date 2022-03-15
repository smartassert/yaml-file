<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Unit;

use PHPUnit\Framework\TestCase;
use SmartAssert\YamlFile\Filename;
use SmartAssert\YamlFile\YamlFile;

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
