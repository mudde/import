<?php

namespace Mudde\Import\Core;

use Iterator;

abstract class DatasetAbstract extends ConfigurableAbstract implements Iterator {
    
    private string $id;
    private ContentTypeAbstract $contentType;
    private string $selector;
    private string $host;
    private array $mapping;
    private array $validate;
    private array $children;

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->configuring();
    }

    function getDefaultConfig(): array
    {
        return [
            'id' => 'PHPHelper::GUID()',
            'contentType' => null,
            'selector' => null,
            'host' => null,
            'mapping' => [],
            'validate' => [],
            'children' => [],
        ];
    }

    public function configureContentType(array $value): void
    {
        $namespace = '\\Mudde\\Import\\ContentType\\';
        $classname = $namespace . $value['@type'];

        $this->contentType = new $classname($value);
    }

    public function configureMapping(array $value): void
    {
        $this->mapping = $mapping = [];
        $namespace = '\\Mudde\\Import\\Mapping\\';

        foreach ($value as $config) {
            $classname = $namespace . $config['@type'];
            $mapping[] = new $classname($config);
        }
    }

    public function configureValidate(array $value): void
    {
        $this->validate = $validate = [];
        $namespace = '\\Mudde\\Import\\Validation\\';

        foreach ($value as $config) {
            $classname = $namespace . $config['@type'];
            $validate[] = new $classname($config);
        }
    }

    public function configureChildren(array $value): void
    {
        $this->children = $children = [];
        $classname = self::class;

        foreach ($value as $config) {
            $children[] = new $classname($config);
        }
    }

    public function current():mixed
    {
        // TODO: Implement current() method.
    }

    public function next(): void
    {
        // TODO: Implement next() method.
    }

    public function key():mixed
    {
        // TODO: Implement key() method.
    }

    public function valid(): bool
    {
        // TODO: Implement valid() method.
        return true;
    }

    public function rewind():void
    {
        // TODO: Implement rewind() method.
    }

    public function setId(string $id): DatasetAbstract
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setContentType(ContentTypeAbstract $contentType): DatasetAbstract
    {
        $this->contentType = $contentType;

        return $this;
    }

    public function getContentType(): ContentTypeAbstract
    {
        return $this->contentType;
    }

    public function setSelector(string $selector): DatasetAbstract
    {
        $this->selector = $selector;

        return $this;
    }

    public function getSelector(): string
    {
        return $this->selector;
    }

    public function setHost(string $host): DatasetAbstract
    {
        $this->host = $host;

        return $this;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function setMapping(array $mapping): DatasetAbstract
    {
        $this->mapping = $mapping;

        return $this;
    }

    public function addMapping(MappingAbstract $mapping): DatasetAbstract
    {
        $this->mapping = $this->mapping ? [...$this->mapping, $mapping] : [$mapping];

        return $this;
    }

    public function getMapping(): array
    {
        return $this->mapping;
    }

    public function setValidate(array $validate): DatasetAbstract
    {
        $this->validate = $validate;

        return $this;
    }

    public function addValidate(ValidationAbstract $validate): DatasetAbstract
    {
        $this->validate = $this->validate ? [...$this->validate, $validate] : [$validate];

        return $this;
    }

    public function getValidate(): array
    {
        return $this->validate;
    }

    public function setChildren(array $children): DatasetAbstract
    {
        $this->children = $children;

        return $this;
    }

    public function addChild(DatasetAbstract $child): DatasetAbstract
    {
        $this->children = $this->children ? [...$this->children, $child] : [$child];

        return $this;
    }

    public function getChildren(): array
    {
        return $this->children;
    }
    
}