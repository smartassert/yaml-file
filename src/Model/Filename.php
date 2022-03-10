<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Model;

class Filename implements \Stringable
{
    public function __construct(
        public readonly string $name,
        public readonly string $extension,
    ) {
    }

    public function __toString(): string
    {
        if ('' === $this->name && '' === $this->extension) {
            return '';
        }

        if ('' !== $this->name && '' === $this->extension) {
            return $this->name;
        }

        return implode('.', [$this->name, $this->extension]);
    }

    public static function parse(string $value): Filename
    {
        $lastDotPosition = strrpos($value, '.');

        $name = false === $lastDotPosition ? $value : substr($value, 0, $lastDotPosition);
        $extension = false === $lastDotPosition ? '' : substr($value, ($lastDotPosition) + 1);

        return new Filename($name, $extension);
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
