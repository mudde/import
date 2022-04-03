<?php

namespace Mudde\Import\Core;

abstract class ValidationAbstract extends ConfigurableAbstract
{
    abstract function isValid(mixed $data);
    abstract function getError();

    public function getDefaultConfig(): array
    {
        return [];
    }
}
