<?php

namespace Mudde\Import\Core;

use Mudde\Import\Helper\ObjectHelper;

abstract class SourceAbstract extends ConfigurableAbstract
{

    private ContentTypeAbstract $contentType;
    private string $host;

    function getDefaultConfig(): array
    {
        return [
            'contentType' => ['_type' => 'application/Json'],
            'host' => 'localhost:8080'
        ];
    }

    public function configureContentType(array $config): void
    {
        $namespace = '\\Mudde\\Import\\ContentType\\';

        $this->contentType = ObjectHelper::getObject($config, $namespace);
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
