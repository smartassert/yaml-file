<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Collection;

use SmartAssert\YamlFile\Model\YamlFile;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

class Deserializer
{
    public function __construct(
        private Parser $yamlParser,
    ) {
    }

    /**
     * @throws ParseException
     */
    public function deserialize(string $content): ProviderInterface
    {
        $yamlFiles = [];

        $parsed = $this->yamlParser->parse($content);

        if (is_array($parsed)) {
            foreach ($parsed as $path => $content) {
                $yamlFiles[] = YamlFile::create($path, rtrim($content));
            }
        }

        return new ArrayCollection($yamlFiles);
    }
}
