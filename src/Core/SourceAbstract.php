<?php

namespace Mudde\Import\Core;

abstract class SourceAbstract extends ConfigurableAbstract
{

    private ContentTypeAbstract $contentType;
    private string $host;

    function getDefaultConfig(): array
    {
        return [
            'contentType' => ['@type' => 'application/Json'],
            'host' => 'localhost:8080'
        ];
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
