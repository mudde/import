<?php

namespace Mudde\Import;

use ArrayObject;
use Iterator;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Mudde\Import\Core\ConfigurableAbstract;
use Mudde\Import\Exception\ImportException;
use Mudde\Import\Helper\ObjectHelper;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Import extends ConfigurableAbstract implements Iterator
{
    private string $id;
    private ArrayObject $filter;
    private bool $haltOnErrors;
    private Mapping $mapping;
    private Dataset $source;
    private ArrayObject $validate;
    private LoggerInterface $log;
    private ArrayObject $lastErrors;

    function getDefaultConfig(): array
    {
        return [
            'id' => uniqid(),
            'filter' => new ArrayObject(),
            'halt-on-errors' => false,
            'mapping' => '',
            'source' => null,
            'validate' => [],
            'log' => new NullLogger(),
        ];
    }

    public function configureLog(LoggerInterface | NULL $config): void
    {
        $this->log = $config;
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
        $this->log->info('Starting import @ ' . date('Y-m-d H:i:s', time()));
        $this->source->init();
    }

    public function stop(): void
    {
        $this->log->info('Ending import @ ' . date('Y-m-d H:i:s', time()));
        if(file_exists(__DIR__ . '/../log/my_app.log'))
        echo (file_get_contents(__DIR__ . '/../log/my_app.log'));
    }

    public function toArray(): ArrayObject
    {
        $data = $this->source->toArray();
        $mapped = ['_mapped' => $this->map($data)];
        $output = new ArrayObject([...$data, ...$mapped]);

        return $output;
    }

    public function map(): ArrayObject
    {
        $data = $this->source->toArray();
        $output = $this->mapping->map($data);

        return $output;
    }

    public function validate(): bool
    {
        $data = $this->source->toArray(true);
        $errors = null;
        $output = $this->mapping->map($data);
        $validators = $this->validate;

        foreach ($output as $key => $value) {
            $keyValidators = $validators[$key] ?? new ArrayObject();
            foreach ($keyValidators as $validate) {
                $valid = $validate->validate($value);

                if ($valid !== true) {
                    $errors = $errors ?? $this->lastErrors = new ArrayObject();
                    $errors[$key] = $errors[$key] ?? new ArrayObject();
                    $errors[$key][] = $valid;
                }
            }
        }

        if ($errors) {
            $this->log->error('Validation errors: ' . json_encode([...['errors'=>$errors], ...$data]));
            $this->haltOnErrors();
        }

        return $errors ? false : true;
    }

    public function haltOnErrors(): void
    {
        if ($this->haltOnErrors) {
            $this->log->info('Halting import @ ' . date('Y-m-d H:i:s', time()));
            $this->stop();
            throw new ImportException('Import halted on errors');
        }
    }

    public function current(): mixed
    {
        return $this->toArray();
    }

    public function next(): void
    {
        $source = $this->source;
        

        while ($source->valid()) {
            $source->next();
            if ($source->valid()) {
                if ($this->validate()) {
                    break;
                }
            }
        };
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

    public function getLog(): LoggerInterface
    {
        return $this->log;
    }

    public function setLog(LoggerInterface $log): self
    {
        $this->log = $log;

        return $this;
    }
}
