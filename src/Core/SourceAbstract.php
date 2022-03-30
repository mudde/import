<?php

namespace Mudde\Import\Core;

use ArrayObject;
use Mudde\Import\Helper\ObjectHelper;

abstract class SourceAbstract extends ConfigurableAbstract
{

    private ContentTypeAbstract $contentType;
    private string $host;
    protected $data;

    function getDefaultConfig(): array
    {
        return [
            'contentType' => 'application/Json',
            'host' => 'http://localhost:8080'
        ];
    }

    abstract function init(ArrayObject $data) : array;

    public function configureContentType(string|array $config): void
    {
        $namespace = '\\Mudde\\Import\\ContentType\\';
        $isString = is_string($config);
        $className = $isString ? $config : $config['_type'];
        $config = $isString ? [] : $config;

        $this->contentType = ObjectHelper::getObject($config, $namespace, $className);
    }


    public function setContentType(ContentTypeAbstract $contentType): SourceAbstract
    {
        $this->contentType = $contentType;

        return $this;
    }

    public function getContentType(): ContentTypeAbstract
    {
        return $this->contentType;
    }


    public function setHost(string $host): SourceAbstract
    {
        $this->host = $host;

        return $this;
    }

    public function getHost(): string
    {
        return $this->host;
    }
}
