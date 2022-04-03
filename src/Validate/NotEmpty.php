<?php

namespace Mudde\Import\Validate;

use Mudde\Import\Core\ValidationAbstract;

class NotEmpty extends ValidationAbstract
{
    public function isValid($data)
    {
        return !empty($data);
    }

    public function getError()
    {
        return 'Value is not empty';
    }
}
