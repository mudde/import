<?php

namespace Mudde\Import\ContentType;

use Mudde\Import\Core\ContentTypeAbstract;

class ApplicationXml extends ContentTypeAbstract
{

    public function process($content): array
    {
        $xml = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOCDATA);
        $output = (array) array_walk_recursive($xml, function ($item) {
            return (array) $item;
        });

        return $output;
    }
}
