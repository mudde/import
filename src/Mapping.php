<?php

namespace Mudde\Import;

use ArrayObject;
use Mudde\Import\Core\MappingAbstract;
use Mudde\Import\Helper\TemplateHelper;

class MApping
{
    private ArrayObject $mapping;

    public function __construct(ArrayObject $mapping)
    {
        $this->mapping = $mapping;
    }

    public function getMapping(): ArrayObject
    {
        return $this->mapping;
    }

    public function map(ArrayObject $templateData): ArrayObject
    {
        $output = new ArrayObject();

        foreach ($this->mapping as $key => $map) {
            $output[$key] = TemplateHelper::render($map, $templateData);
        }

        return $output;
    }
}
