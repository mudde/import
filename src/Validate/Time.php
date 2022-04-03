<?php

namespace Mudde\Import\Validate;

use Mudde\Import\Core\ValidationAbstract;

class Time extends ValidationAbstract
{

    public function isValid($data)
    {
        return strtotime($data) !== false;
    }

    public function getError()
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
