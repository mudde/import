<?php

namespace Mudde\Import\Validate;

use Mudde\Import\Core\ValidationAbstract;

class PositiveOrZero extends ValidationAbstract
{
    public function isValid($data)
    {
        return (int) $data >= 0;
    }

    public function getError()
    {
        return 'Value is not positive or zero';
    }
}
