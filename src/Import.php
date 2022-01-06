<?php

namespace Mudde\Import;

use Mudde\Import\Core\ConfigurableAbstract;
use Mudde\Import\Core\DatasetAbstract;

class Import extends ConfigurableAbstract {

    private string $id;
    private bool $haltOnErrors;
    private DatasetAbstract $dataset;
    private array $filter;
    private array $mapping;

    function getDefaultConfig(): array{
        return [
            'id' => 'guid-id',
            'halt-on-errors' => false,
            'dataset' => [],
            'filter' => [],
            'mapping' => []
        ];
    }

    public function configureDataset($value): void
    {
        $namespace = 'Mudde\\Import\\DataSet\\';
        $classname = $namespace . ucfirst($value['@type']);

        $this->dataset = new $classname($value);
    }

    public function configureFilter($value): void
    {
        $this->filter = $filter = [];
        $namespace = 'Mudde\\Import\\Filter\\';

        foreach ($value as $item) {
            $classname = $namespace . ucfirst($item['@type']);
            $filter[] = new $classname($item);
        }
    }

    public function configureMapping($value): void
    {
        $this->mapping = $mapping = [];

        foreach ($value as $name => $template) {
            $filter[$name] = $template;
        }
    }

    public function init(): void
    {
        $config = $this->getConfig();

        $this->configuring($config);
    }

    public function run(): void
    {
        $this->dataset->next();

        var_dump($this->dataset->current());
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

    public function isHaltOnErrors(): bool
    {
        return $this->haltOnErrors;
    }

    public function setDataset(DatasetAbstract $dataset): Import
    {
        $this->dataset = $dataset;

        return $this;
    }

    public function getDataset(): DatasetAbstract
    {
        return $this->dataset;
    }

    public function setFilter(array $filter): Import
    {
        $this->filter = $filter;

        return $this;
    }

    public function getFilter(): array
    {
        return $this->filter;
    }

    public function setMapping(array $mapping): Import
    {
        $this->mapping = $mapping;

        return $this;
    }

    public function getMapping(): array
    {
        return $this->mapping;
    }
}