<?php

namespace Mudde\Import\Source;

use ArrayObject;
use \GuzzleHttp\Client;
use Mudde\Import\Core\SourceAbstract;

class Guzzle extends SourceAbstract
{

    private float $timeout;
    private string $user;
    private string $pass;

    function getDefaultConfig(): array
    {
        return [
            ...parent::getDefaultConfig(),
            'timeout' => 20.0,
            'user' => '',
            'pass' => ''
        ];
    }

    function _init(): ArrayObject
    {
        $client = new Client([
            'timeout'  => $this->timeout,
        ]);
        $options = $this->getUser() ? ['auth' => ['user' => $this->getUser(), 'pass' => $this->getPass()]] : [];
        $host = $this->getHost();
        $res = $client->request('GET', $host, $options);
        $body = $res->getBody();
        $content = $body->getContents();

        return $this->getContentType()->process($content);
    }

    public function getTimeout(): float
    {
        return $this->timeout;
    }

    public function setTimeout(float $timeout): self
    {
        $this->timeout = $timeout;

        return $this;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPass(): string
    {
        return $this->pass;
    }

    public function setPass($pass): self
    {
        $this->pass = $pass;

        return $this;
    }
}
