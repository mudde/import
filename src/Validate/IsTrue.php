<?php

namespace Mudde\Import\Validate;

use Mudde\Import\Core\ValidationAbstract;

class IsTrue extends ValidationAbstract
{
    public function isValid(mixed $data):bool
    {
        return (bool) $data == true;
    }

    public function getError():string
    {
        return 'Value is not true';
    }
}
