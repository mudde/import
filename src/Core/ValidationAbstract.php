<?php

namespace Mudde\Import\Core;

abstract class ValidationAbstract extends ConfigurableAbstract
{
    abstract function isValid(mixed $data): bool;
    abstract function getError(): string;

    public function getDefaultConfig(): array
    {
        return [];
    }
}
