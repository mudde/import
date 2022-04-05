<?php

namespace Mudde\Import\Validate;

use Mudde\Import\Core\ValidationAbstract;

class NotEmpty extends ValidationAbstract
{
    public function isValid(mixed $data):bool
    {
        return !empty($data);
    }

    public function getError():string
    {
        return 'Value is not empty';
    }
}
