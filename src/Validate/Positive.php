<?php

namespace Mudde\Import\Validate;

use Mudde\Import\Core\ValidationAbstract;

class Positive extends ValidationAbstract
{
    public function isValid(mixed $data):bool
    {
        return (int) $data > 0;
    }

    public function getError():string
    {
        return 'Value is not positive';
    }
}
