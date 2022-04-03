<?php

namespace Mudde\Import\Validate;

use Mudde\Import\Core\ValidationAbstract;

class Range extends ValidationAbstract
{
    public int $min;
    public int $max;

    public function getDefaultConfig(): array
    {
        return [
            'min' => 0,
            'max' => PHP_INT_MAX,
        ];
    }

    public function isValid($data)
    {
        return (int) $data >= $this->min && (int) $data <= $this->max;
    }

    public function getError()
    {
        return 'Value is not in range';
    }

    public function getMin()
    {
        return $this->min;
    }

    public function setMin(int $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function getMax(): int
    {
        return $this->max;
    }

    public function setMax(int $max): self
    {
        $this->max = $max;

        return $this;
    }
}
