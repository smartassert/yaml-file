<?php

declare(strict_types=1);

namespace SmartAssert\Tests\YamlFile\Services;

use SmartAssert\Tests\YamlFile\Model\SerializationDataSet;
use SmartAssert\YamlFile\Collection\ArrayCollection;
use SmartAssert\YamlFile\YamlFile;

class SerializationDataSetFactory
{
    /**
     * @var string[]
     */
    private array $filenames;

    /**
     * @var string[]
     */
    private array $content;

    /**
     * @var YamlFile[]
     */
    private array $yamlFiles;

    /**
     * @var string[]
     */
    private array $hashes;

    public function __construct()
    {
        $this->filenames = [
            'single-line.yaml',
            'multi-line.yaml',
            'empty.yaml',
            'duplicate-empty.yaml',
            'duplicate-single-line.yaml',
        ];

        $this->content = [
            '- single-line-content',
            '- multi-line-content-line-1' . "\n" . '- multi-line-content-line-2',
            '', // intentionally empty
            '', // intentionally empty
            '- single-line-content',
        ];

        $this->yamlFiles = [];
        foreach ($this->filenames as $index => $filename) {
            $this->yamlFiles[] = YamlFile::create($filename, $this->content[$index]);
        }

        $this->hashes = [];
        foreach ($this->content as $item) {
            $this->hashes[] = md5($item);
        }
    }

    public function createEmpty(): SerializationDataSet
    {
        return new SerializationDataSet(
            new ArrayCollection([]),
            ''
        );
    }

    public function createSingleFileWithSingleLine(): SerializationDataSet
    {
        return new SerializationDataSet(
            new ArrayCollection([$this->yamlFiles[0]]),
            <<< EOF
                ---
                {$this->hashes[0]}:
                    - {$this->filenames[0]}
                ...
                ---
                {$this->content[0]}
                ...
                EOF
        );
    }

    public function createSingleEmptyFile(): SerializationDataSet
    {
        return new SerializationDataSet(
            new ArrayCollection([$this->yamlFiles[2]]),
            <<< EOF
                ---
                {$this->hashes[2]}:
                    - {$this->filenames[2]}
                ...
                ---
                ...
                EOF
        );
    }

    public function createSingleFileWithMultipleLines(): SerializationDataSet
    {
        return new SerializationDataSet(
            new ArrayCollection([$this->yamlFiles[1]]),
            <<< EOF
                ---
                {$this->hashes[1]}:
                    - {$this->filenames[1]}
                ...
                ---
                {$this->content[1]}
                ...
                EOF
        );
    }

    public function createAll(): SerializationDataSet
    {
        return new SerializationDataSet(
            new ArrayCollection($this->yamlFiles),
            <<< EOF
                ---
                {$this->hashes[0]}:
                    - {$this->filenames[0]}
                    - {$this->filenames[4]}
                {$this->hashes[1]}:
                    - {$this->filenames[1]}
                {$this->hashes[2]}:
                    - {$this->filenames[2]}
                    - {$this->filenames[3]}
                ...
                ---
                {$this->content[0]}
                ...
                ---
                {$this->content[1]}
                ...
                ---
                ...
                EOF
        );
    }
}
