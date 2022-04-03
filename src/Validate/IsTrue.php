<?php

namespace Mudde\Import\Validate;

use Mudde\Import\Core\ValidationAbstract;

class IsTrue extends ValidationAbstract
{
    public function isValid($data)
    {
        return $data == true;
    }

    public function getError()
    {
        return 'Value is not true';
    }
}
