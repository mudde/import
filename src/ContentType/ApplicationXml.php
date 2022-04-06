<?php

namespace Mudde\Import\ContentType;

use ArrayObject;
use Mudde\Import\Core\ContentTypeAbstract;

class ApplicationXml extends ContentTypeAbstract
{

    public function process($content): ArrayObject
    {
        $output = new ArrayObject();

        $xml = simplexml_load_string($content);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        $output = new ArrayObject($array);

        return $output;
    }
}
