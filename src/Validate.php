<?php

namespace Mudde\Import;

use ArrayObject;
use Mudde\Import\Core\ValidationAbstract;
use Mudde\Import\Helper\ObjectHelper;

class Validate
{

    private ValidationAbstract $validation;

    public function __construct($config)
    {
        $this->validation = ObjectHelper::getObject($config, 'Mudde\\Import\\Validate\\');
    }

    public function getValidations():ValidationAbstract
    {
        return $this->validations;
    }

    public function validate(mixed $data): bool | string
    {
        $output = true;
        $validation = $this->validation;
        $validation->isValid($data) || $output = $validation->getError();

        return $output;
    }
}
