<?php

namespace Mudde\Import;

use ArrayObject;
use Mudde\Import\Core\ConfigurableAbstract;
use Mudde\Import\Helper\ObjectHelper;

class Import extends ConfigurableAbstract
{
    private string $id;
    private ArrayObject $filter;
    private bool $haltOnErrors;
    private ArrayObject $mapping;
    private Source $source;

    function getDefaultConfig(): array
    {
        return [
            'id' => uniqid(),
            'filter' => new ArrayObject(),
            'halt-on-errors' => false,
            'mapping' => new ArrayObject(),
            'source' => null
        ];
    }

    public function configureSource(ArrayObject|array $config): void
    {
        $this->source = new Source($config);
    }

    public function configureFilter(ArrayObject|array $value): void
    {
        $this->filter = $filter = new ArrayObject();
        $namespace = 'Mudde\\Import\\Validate\\';

        foreach ($value as $key => $configList) {
            $filter[$key] = new ArrayObject();
            foreach ($configList as $config) {
                $filter[$key][] = ObjectHelper::getObject($config, $namespace);
            }
        }
    }

    public function configureMapping(ArrayObject|array $value): void
    {
        $this->mapping = $mapping = new ArrayObject();

        foreach ($value as $name => $template) {
            $mapping[$name] = $template;
        }
    }

    public function init(): void
    {
        $this->source->init();
    }

    public function run(): void
    {
        var_dump($this->source->toArray());
        $this->source->next();

        var_dump($this->source->toArray());
        $this->source->next();

        var_dump($this->source->toArray());
    }

    public function stop(): void
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Import
    {
        $this->id = $id;

        return $this;
    }

    public function setHaltOnErrors(bool $haltOnErrors): Import
    {
        $this->haltOnErrors = $haltOnErrors;
        return $this;
    }

    public function isHaltedOnErrors(): bool
    {
        return $this->haltOnErrors;
    }

    public function setSource(Source $source): Import
    {
        $this->source = $source;

        return $this;
    }

    public function getSource(): Source
    {
        return $this->source;
    }

    public function setFilter(array $filter): Import
    {
        $this->filter = $filter;

        return $this;
    }

    public function getFilter(): ArrayObject
    {
        return $this->filter;
    }

    public function setMapping(array $mapping): Import
    {
        $this->mapping = $mapping;

        return $this;
    }

    public function getMapping(): ArrayObject
    {
        return $this->mapping;
    }
}
