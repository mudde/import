<?php

namespace Mudde\Import\ContentType;

use Mudde\Import\Core\ContentTypeAbstract;

class ApplicationJson extends ContentTypeAbstract
{

    public function process($content): array
    {
        $output = json_decode($content, true);

        return $output;
    }
}
