<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile;

class Filename implements \Stringable
{
    private const EXTENSION_SEPARATOR = '.';
    private const PATH_SEPARATOR = '/';

    public function __construct(
        public readonly string $path,
        public readonly string $name,
        public readonly string $extension,
    ) {}

    public function __toString(): string
    {
        $value = '';
        if ('' !== $this->path) {
            $value .= $this->path . self::PATH_SEPARATOR;
        }

        if ('' === $this->name && '' === $this->extension) {
            return $value;
        }

        if ('' !== $this->name && '' === $this->extension) {
            return $value . $this->name;
        }

        return $value . implode(self::EXTENSION_SEPARATOR, [$this->name, $this->extension]);
    }

    public static function parse(string $value): Filename
    {
        $lastPathSeparatorPosition = strrpos($value, self::PATH_SEPARATOR);

        $path = '';
        $nameAndExtension = $value;

        if (is_int($lastPathSeparatorPosition)) {
            $path = substr($value, 0, $lastPathSeparatorPosition);
            $nameAndExtension = substr($value, $lastPathSeparatorPosition + 1);
        }

        $lastDotPosition = strrpos($nameAndExtension, self::EXTENSION_SEPARATOR);

        $name = false === $lastDotPosition ? $value : substr($nameAndExtension, 0, $lastDotPosition);
        $extension = false === $lastDotPosition ? '' : substr($nameAndExtension, $lastDotPosition + 1);

        return new Filename($path, $name, $extension);
    }
}
