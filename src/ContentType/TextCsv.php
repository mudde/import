<?php

namespace Mudde\Import\ContentType;

use Mudde\Import\Core\ContentTypeAbstract;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
            return in_array($item, ['true', 'false'])
                ? (bool) $item
                : (is_numeric($item) && is_int(0 + $item)
                    ? (int) $item
                    : (is_numeric($item)
                        ? (float) $item
                        : $item));
        }, str_getcsv($value));
    }
}
