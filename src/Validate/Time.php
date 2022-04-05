<?php

namespace Mudde\Import\Validate;

use Mudde\Import\Core\ValidationAbstract;

class Time extends ValidationAbstract
{

    private string $regex;

    public function getDefaultConfig():array
    {
        return [
            parent::getDefaultConfig(),
            'regex' => '',
        ];
    }

    public function isValid(mixed $data):bool

    {
        return strtotime($data) !== false;
    }

    public function getError():string
    {
        return 'Value is not a valid time';
    }

    public function getRegex(): string
    {
        return $this->regex;
    }

    public function setRegex(string $regex): self
    {
        $this->regex = $regex;

        return $this;
    }
}
