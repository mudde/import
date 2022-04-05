<?php

namespace Mudde\Import\Validate;

use Exception;
use Mudde\Import\Core\ValidationAbstract;

class All extends ValidationAbstract
{
    public function isValid(mixed $data):bool
    {
        throw new Exception('Not implemented');
    }

    public function getError():string
    {
        throw new Exception('Not implemented');
    }
}