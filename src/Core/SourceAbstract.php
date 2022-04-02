<?php

namespace Mudde\Import\Core;

use ArrayObject;
use Exception;
use Iterator;
use Mudde\Import\Helper\ObjectHelper;
use Mudde\Import\Helper\TemplateHelper;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

abstract class SourceAbstract extends ConfigurableAbstract implements Iterator
{

    private string $id;
    private ArrayObject $children;
    private ContentTypeAbstract $contentType;
    protected ArrayObject $data;
    protected Iterator $dataIterator;
    private string $host;
    private ArrayObject $mapping;
    private string $selector;
    private ArrayObject $validate;
    private Crawler $crawler;
    private bool $isRoot;

    function getDefaultConfig(): array
    {
        return [
            'id' => uniqid(),
            'children' => new ArrayObject(),
            'mapping' => new ArrayObject(),
            'selector' => '//**/*',
            'validate' => new ArrayObject(),
            'contentType' => 'application/Json',
            'host' => 'http://localhost:8080'
        ];
    }

    public function __construct(array $config, bool $isRoot = true)
    {
        parent::__construct($config);

        $this->isRoot = $isRoot;
    }

    abstract function _init(): array | string;

    function init(ArrayObject $data = new ArrayObject()): void
    {
        $sourceXML = $this->_init();

        if (is_array($sourceXML)) {
            $xmlEncoder = new XmlEncoder(['xml_root_node_name' => 'root']);
            $xml = $xmlEncoder->encode($sourceXML, 'xml');
        } else {
            $xml = $sourceXML;
        }

        $crawler = new Crawler($xml);
        $xpathCrawler = $this->crawler = $crawler->filterXPath(TemplateHelper::render($this->selector, $data));

        $this->crawler = $xpathCrawler;
        $this->dataIterator = $xpathCrawler->getIterator();
        $data->exchangeArray([...(array)$data, ...$this->toArray(true)]);

        foreach ($this->children as $child) {
            $child->init($data);
        };
    }

    public function configureContentType(string|array $config): void
    {
        $namespace = '\\Mudde\\Import\\ContentType\\';
        $isString = is_string($config);
        $className = $isString ? $config : $config['_type'];
        $config = $isString ? [] : $config;

        $this->contentType = ObjectHelper::getObject($config, $namespace, $className);
    }

    public function configureChildren(ArrayObject|array $value): void
    {
        $this->children = $children = new ArrayObject();
        $className = $this::class;

        foreach ($value as $config) {
            $children[] = new $className($config, false);
        }
    }

    public function toArray(Bool $rootOnly = false): array
    {

        $data = [];
        $id = $this->id;
        $current = $this->dataIterator->current();
        $data[$id] = $current ? (array) simplexml_load_string($current->C14N()) : [];

        if (!$rootOnly) {
            foreach ($this->children as $child) {
                $data = [...$data, ...$child->toArray()];
            }
        }

        return $data;
    }

    public function setContentType(ContentTypeAbstract $contentType): self
    {
        $this->contentType = $contentType;

        return $this;
    }

    public function getContentType(): ContentTypeAbstract
    {
        return $this->contentType;
    }


    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getDataIterator(): Iterator
    {
        return $this->dataIterator;
    }

    public function setDataIterator(Iterator $dataIterator): self
    {
        $this->dataIterator = $dataIterator;

        return $this;
    }

    public function getChildren(): ArrayObject
    {
        return $this->children;
    }

    public function setChildren(ArrayObject | array $children): self
    {
        $this->children = is_array($children) ? new ArrayObject($children) : $children;

        return $this;
    }

    public function getMapping(): ArrayObject
    {
        return $this->mapping;
    }

    public function setMapping(ArrayObject|array $mapping): self
    {
        $this->mapping = is_array($mapping) ? new ArrayObject($mapping) : $mapping;

        return $this;
    }

    public function getValidate(): ArrayObject
    {
        return $this->validate;
    }

    public function setValidate(ArrayObject|array $validate): self
    {
        $this->validate = is_array($validate) ? new ArrayObject($validate) : $validate;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    private function childrenInit(){
        foreach ($this->children as $child) {
            $data = new ArrayObject($this->toArray());
            $child->init($data);
        }
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
        $this->childrenInit();
    }

    public function key(): mixed
    {
        return $this->dataIterator->key();
    }

    public function valid(): bool
    {
        return  $this->dataIterator->valid();
    }

    public function rewind(): void
    {
        $this->dataIterator->rewind();
        $this->childrenInit();
    }

    public function getSelector(): string
    {
        return $this->selector;
    }

    public function setSelector(string $selector): self
    {
        $this->selector = $selector;

        return $this;
    }
}
