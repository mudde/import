<?php

namespace Mudde\Import\Source;

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
            'timeout' => 2.0,
            'user' => '',
            'pass' => '',
            ...parent::getDefaultConfig()
        ];
    }

    function init()
    {
        $client = new Client([
            'timeout'  => $this->timeout,
        ]);
        $res = $client->request('GET', $this->getHost(), ['auth' => ['user' => $this->getUser(), 'pass' => $this->getPass()]]);
        $body = $res->getBody();
        $content = $body->getContents();

        var_dump($this->getContentType()->process($content));
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
