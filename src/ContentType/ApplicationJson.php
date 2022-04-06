<?php

namespace Mudde\Import\ContentType;

use ArrayObject;
use Mudde\Import\Core\ContentTypeAbstract;

class ApplicationJson extends ContentTypeAbstract
{

    public function process($content): ArrayObject
    {
        $output = new ArrayObject(json_decode($content, true, 512, JSON_OBJECT_AS_ARRAY));

        return $output;
    }
}
