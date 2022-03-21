<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\FileHashes;

use SmartAssert\YamlFile\Exception\FileHashesDeserializer\DecodeException;
use SmartAssert\YamlFile\Exception\FileHashesDeserializer\ExceptionInterface;
use SmartAssert\YamlFile\Exception\FileHashesDeserializer\InvalidHashException;
use SmartAssert\YamlFile\Exception\FileHashesDeserializer\InvalidPathException;
use SmartAssert\YamlFile\Exception\FileHashesDeserializer\NotArrayException;
use SmartAssert\YamlFile\Exception\FileHashesDeserializer\PathCollectionNotArrayException;
use SmartAssert\YamlFile\FileHashes;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

class Deserializer
{
    public function __construct(
        private Parser $yamlParser,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function deserialize(string $content): FileHashes
    {
        $fileHashes = new FileHashes();

        if ('' === $content) {
            return $fileHashes;
        }

        try {
            $data = $this->yamlParser->parse($content);
        } catch (ParseException $parseException) {
            throw new DecodeException($content, $parseException);
        }

        if (!is_array($data)) {
            throw new NotArrayException($content);
        }

        $index = 0;
        foreach ($data as $hash => $paths) {
            if (!is_string($hash)) {
                throw new InvalidHashException($content, $index);
            }

            if (!is_array($paths)) {
                throw new PathCollectionNotArrayException($content, $hash, $paths);
            }

            foreach ($paths as $path) {
                if (!is_string($path)) {
                    throw new InvalidPathException($content, $hash, $index);
                }

                $fileHashes->add($path, $hash);
            }

            ++$index;
        }

        return $fileHashes;
    }
}
