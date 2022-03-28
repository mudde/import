<?php

namespace Mudde\Import;

use Mudde\Import\Core\DatasetAbstract;

class Dataset extends DatasetAbstract
{
    function getDefaultConfig(): array
    {
        return [
            ...parent::getDefaultConfig()
        ];
    }
}
