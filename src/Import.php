<?php

namespace Mudde\Import;

use ArrayObject;
use Mudde\Import\Core\ConfigurableAbstract;
use Mudde\Import\Core\DatasetAbstract;
use Mudde\Import\Helper\ObjectHelper;

class Import extends ConfigurableAbstract
{

    private string $id;
    private bool $haltOnErrors;
    private DatasetAbstract $dataset;
    private ArrayObject $filter;
    private ArrayObject $mapping;

    function getDefaultConfig(): array
    {
        return [
            'id' => uniqid(),
            'halt-on-errors' => false,
            'dataset' => new ArrayObject(),
            'filter' => new ArrayObject(),
            'mapping' => new ArrayObject()
        ];
    }

    public function configureDataset(ArrayObject|array $config): void
    {
        $namespace = 'Mudde\\Import\\';

        $this->dataset = ObjectHelper::getObject($config, $namespace, 'Dataset');
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
        $this->dataset->init();
    }

    public function run(): void
    {
        var_dump($this->dataset->current());
        $this->dataset->next();
        var_dump($this->dataset->current());
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
