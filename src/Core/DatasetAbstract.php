<?php

namespace Mudde\Import\Core;

use ArrayObject;
use GuzzleHttp\Psr7\Response;
use Iterator;
use Mudde\Import\Helper\ObjectHelper;
use SimpleXMLElement;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

abstract class DatasetAbstract extends ConfigurableAbstract implements Iterator
{

    private string $id;
    private ArrayObject $children;
    private ArrayObject $mapping;
    private string $selector;
    private SourceAbstract $source;
    private ArrayObject $validate;
    private ArrayObject $data;
    private Iterator $dataIterator;

    function getDefaultConfig(): array
    {
        return [
            'id' => uniqid(),
            'children' => new ArrayObject(),
            'mapping' => new ArrayObject(),
            'selector' => '//**/*',
            'source' => ['_type' => 'local', 'host' => '/var/www/html/github/mudde/import/example/data/data-root.csv'],
            'validate' => new ArrayObject(),
        ];
    }

    public function configureMapping(ArrayObject|array $value): void
    {
        $this->mapping = $mapping = new ArrayObject();

        foreach ($value as $key => $config) {
            $mapping[$key] = $config;
        }
    }

    public function configureValidate(ArrayObject|array $value): void
    {
        $this->validate = $validate = new ArrayObject();
        // $namespace = '\\Mudde\\Import\\Validation\\';

        foreach ($value as $config) {
            $validate[] = $config; //ObjectHelper::getObject($config, $namespace);
        }
    }

    public function configureChildren(ArrayObject|array $value): void
    {
        $this->children = $children = new ArrayObject();
        $namespace = '';
        $className = $this::class;

        foreach ($value as $config) {
            $children[] = ObjectHelper::getObject($config, $namespace, $className);
        }
    }

    public function configureSource(ArrayObject|array $config): void
    {
        $namespace = '\\Mudde\\Import\\Source\\';

        $this->source = ObjectHelper::getObject($config, $namespace);
    }

    public function init($data = null)
    {
        $id = $this->id;
        $data = $this->data = $data ?? new ArrayObject();
        $data[$id] = $this->source->init($data);

        foreach ($this->children as $child) {
            $child->init($data);
        }

        $xmlEncoder = new XmlEncoder(['xml_root_node_name' => 'root']);
        $xml = $xmlEncoder->encode((array) $data[$id], 'xml');
        $crawler = new Crawler($xml);
        $crawledData = $crawler->filterXPath($this->selector);
        
        $this->dataIterator = $crawledData->getIterator();
    }

    public function current(): mixed
    {
        $output = new ArrayObject();
        $output[$this->id] = $this->dataIterator->current();

        foreach ($this->children as $child) {
            $output = [...$output, ...$child->current()];
        }

        return $output;
    }

    public function next(): void
    {
        $this->dataIterator->next();

        foreach ($this->children as $child) {
            $child->next();
        }
    }

    public function key(): mixed
    {
        $output = new ArrayObject();
        $output[$this->id] = $this->dataIterator->key();

        foreach ($this->children as $child) {
            $output = [...$output, ...$child->key()];
        }

        return $output;
    }

    public function valid(): bool
    {
        $output = $this->dataIterator->valid();

        if ($output === true) {
            foreach ($this->children as $child) {
                if ($child->valid() === false) {
                    $output = false;
                    break;
                }
            }
        }

        return $output;
    }

    public function rewind(): void
    {
        $this->dataIterator->rewind();

        foreach ($this->children as $child) {
            $child->rewind();
        }
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

    public function setSelector(string $selector): DatasetAbstract
    {
        $this->selector = $selector;

        return $this;
    }

    public function getSelector(): string
    {
        return $this->selector;
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

    public function getMapping(): ArrayObject
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

    public function getValidate(): ArrayObject
    {
        return $this->validate;
    }

    public function setChildren(ArrayObject $children): DatasetAbstract
    {
        $this->children = $children;

        return $this;
    }

    public function addChild(DatasetAbstract $child): DatasetAbstract
    {
        $this->children = $this->children ? new ArrayObject([...$this->children, $child]) : new ArrayObject([$child]);

        return $this;
    }

    public function getChildren(): ArrayObject
    {
        return $this->children;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }
}
