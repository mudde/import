<?php

namespace Mudde\Import\ContentType;

use Mudde\Import\Core\ContentTypeAbstract;

class TextCsv extends ContentTypeAbstract
{

    public function process($content): array
    {
        $csv = explode(PHP_EOL, $content);
        $header = null;
        $output = [];

        foreach ($csv as $value) {
            if (!$header) {
                $header = str_getcsv($value);
                continue;
            }
            $data = $this->processItem($value);
            $output[] = array_combine($header, $data);
        }

        return $output;
    }

    private function processItem($value)
    {
        return array_map(function ($item) {
            return strtolower($item) == 'true'
                ?  true
                : (strtolower($item) == 'false'
                    ? false
                    : (is_numeric($item) && is_int(0 + $item)
                        ? (int) $item
                        : (is_numeric($item)
                            ? (float) $item
                            : $item)));
        }, str_getcsv($value));
    }
}
