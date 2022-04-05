<?php

namespace Mudde\Import\Validate;

use Mudde\Import\Core\ValidationAbstract;

class Regex extends ValidationAbstract
{
    public string $regex;

    public function getDefaultConfig():array
    {
        return [
            'regex' => '',
        ];
    }

    public function isValid(mixed $data):bool

    {
        return preg_match($this->regex, $data);
    }

    public function getError():string
    {
        return 'Value is not matching the regex';
    }

    public function getRegex():string 
    {
        return $this->regex;
    }

    public function setRegex(string $regex):self
    {
        $this->regex = $regex;

        return $this;
    }
}
