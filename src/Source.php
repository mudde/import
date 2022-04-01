<?php

namespace Mudde\Import;

use Iterator;
use Mudde\Import\Core\SourceAbstract;
use Mudde\Import\Helper\ObjectHelper;

class Source implements Iterator
{
    private SourceAbstract $source;

    public function __construct($config)
    {
        $this->source = ObjectHelper::getObject($config, 'Mudde\Import\Source\\');
    }

    public function init(){
        $this->source->init();
    }

    public function toArray():array{
        return $this->source->toArray();
    }

    public function current(): mixed
    {
        return $this->source->current();
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
        return $this->source->valid();
    }

    public function rewind(): void
    {
        $this->source->rewind();
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
