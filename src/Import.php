<?php

namespace Mudde\Import;

use ArrayObject;
use Iterator;
use Mudde\Import\Core\ConfigurableAbstract;
use Mudde\Import\Helper\ObjectHelper;

class Import extends ConfigurableAbstract implements Iterator
{
    private string $id;
    private ArrayObject $filter;
    private bool $haltOnErrors;
    private Mapping $mapping;
    private Dataset $source;
    private ArrayObject $validate;

    function getDefaultConfig(): array
    {
        return [
            'id' => uniqid(),
            'filter' => new ArrayObject(),
            'halt-on-errors' => false,
            'mapping' => '',
            'source' => null,
            'validate' => [],
        ];
    }

    public function configureSource(ArrayObject|array $config): void
    {
        $this->source = new Dataset($config);
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

    public function configureValidate(ArrayObject|array $value)
    {
        $this->validate = new ArrayObject();

        foreach ($value as $key => $validations) {
            foreach ($validations as $config) {
                $this->validate[$key][] = new Validate($config);
            }
        }
    }

    public function configureMapping(ArrayObject|array $value): void
    {
        $mapping = new ArrayObject($value);

        $this->mapping = new Mapping($mapping);
    }

    public function init(): void
    {
        $this->source->init();
    }

    public function toArray(): ArrayObject
    {
        $data = $this->source->toArray();
        $mapped = ['_mapped' => [...$this->map($data), ...$data['_mapped']]];
        $output = new ArrayObject([...$data, ...$mapped]);

        return $output;
    }

    public function map(): ArrayObject
    {
        $data = $this->source->toArray();
        $output = $this->mapping->map($data);
        foreach ($output as $key => $value) {
            $val = $this->validate[$key];
            if ($val) {
                foreach ($val as $validate) {
                    $errors = $validate->validate($value);
                    if ($errors->count() > 0) {
                        $output['_errors'] = $errors;
                    }
                }
            }
        }

        return $output;
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

    public function setSource(Dataset $source): Import
    {
        $this->source = $source;

        return $this;
    }

    public function getSource(): Dataset
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

    public function setMapping(Mapping $mapping): Import
    {
        $this->mapping = $mapping;

        return $this;
    }

    public function getMapping(): Mapping
    {
        return $this->mapping;
    }

    public function current(): mixed
    {
        return $this->toArray();
    }

    public function next(): void
    {
        $this->source->next();
    }

    public function key(): mixed
    {
        return $this->source->key();
    }

    public function valid(): bool
    {
        return  $this->source->valid();
    }

    public function rewind(): void
    {
        $this->source->rewind();
    }
}
