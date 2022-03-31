<?php

namespace Mudde\Import\Core;

use ArrayObject;
use Iterator;
use JetBrains\PhpStorm\ArrayShape;
use Mudde\Import\Helper\ObjectHelper;
use Mudde\Import\Helper\TemplateHelper;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

abstract class SourceAbstract extends ConfigurableAbstract implements Iterator
{

    private string $id;
    private ArrayObject $children;
    private ContentTypeAbstract $contentType;
    protected $data;
    protected Iterator $dataIterator;
    private string $host;
    private ArrayObject $mapping;
    private string $selector;
    private ArrayObject $validate;

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

    abstract function _init(ArrayObject $data): array | string;

    function init(): void
    {
        $data = new ArrayObject();
        $data2 = $this->_init($data);

        if (is_array($data2)) {
            $xmlEncoder = new XmlEncoder(['xml_root_node_name' => 'root']);
            $xml = $xmlEncoder->encode($data2, 'xml');
        } else {
            $xml = $data2;
        }

        $crawler = new Crawler($xml);
        $crawledData = $crawler->filterXPath(TemplateHelper::render($this->selector, $data));

        $this->data = $crawledData;
        $this->dataIterator = $crawledData->getIterator();

        foreach ($this->children as $child) {
            $child->init();
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
        $namespace = '';
        $className = $this::class;

        foreach ($value as $config) {
            $children[] = ObjectHelper::getObject($config, $namespace, $className);
        }
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
