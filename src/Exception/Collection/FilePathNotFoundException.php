<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Exception\Collection;

use SmartAssert\YamlFile\FileHashes;

class FilePathNotFoundException extends \Exception
{
    public function __construct(
        private string $hash,
        private FileHashes $fileHashes,
    ) {
        parent::__construct(sprintf('File path for hash "%s" not found', $hash));
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getFileHashes(): FileHashes
    {
        return $this->fileHashes;
    }
}
