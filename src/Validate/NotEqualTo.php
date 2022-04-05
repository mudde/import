<?php

namespace Mudde\Import\Validate;

use Mudde\Import\Core\ValidationAbstract;

class NotEqualTo extends ValidationAbstract
{
    private mixed $value;

    public function getDefaultConfig():array
    {
        return [
            parent::getDefaultConfig(),
            'value' => null,
        ];
    }

    public function isValid(mixed $data):bool
    {
        return $this->value == $data;
    }

    public function getError():string
    {
        return 'Value is not empty';
    }

    public function getValue():mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value):self
    {
        $this->value = $value;

        return $this;
    }
}
