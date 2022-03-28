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
        $x = explode(PHP_EOL, $content);
        $header = null;
        $output = [];

        foreach ($x as $value) {
            if ($header === null) {
                $header = str_getcsv($value);
                continue;
            }

            $data = str_getcsv($value);
            $output[] = array_combine($header, $data);
        }

        return $output;
    }
}
