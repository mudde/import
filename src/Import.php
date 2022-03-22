<?php

namespace Mudde\Import;

use Mudde\Import\Core\ConfigurableAbstract;
use Mudde\Import\Core\DatasetAbstract;
use Mudde\Import\Helper\ObjectHelper;

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

    public function configureDataset($config): void
    {
        $namespace = 'Mudde\\Import\\Dataset\\';

        $this->dataset = ObjectHelper::getObject($config, $namespace);
    }

    public function configureFilter($value): void
    {
        $this->filter = $filter = [];
        $namespace = 'Mudde\\Import\\Filter\\';

        foreach ($value as $config) {
            $filter[] = ObjectHelper::getObject($config, $namespace);
        }
    }

    public function configureMapping($value): void
    {
        $this->mapping = $mapping = [];

        foreach ($value as $name => $template) {
            $mapping[$name] = $template;
        }
    }

    public function init(): void
    {
        echo '<pre>';
        var_dump($this);
    }

    public function run(): void
    {
        $this->dataset->next();

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