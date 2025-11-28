<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Model;

use SmartAssert\YamlFile\Collection\ProviderInterface;

class SerializationDataSet
{
    public function __construct(
        public readonly ProviderInterface $provider,
        public readonly string $serialized
    ) {}
}
