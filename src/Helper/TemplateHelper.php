<?php

namespace Mudde\Import\Helper;

use DateTime;
use Mustache_Engine;
use Symfony\Component\String\UnicodeString;

class TemplateHelper
{
    public static $mustache;

    static function render(string $text, \ArrayObject $data): string
    {
        $mustache = self::getEngine();

        return $mustache->render($text, $data);
    }

    static private function getEngine(): Mustache_Engine
    {
        if (self::$mustache) return self::$mustache;

        self::$mustache = $mustache = new Mustache_Engine(['pragmas' => [Mustache_Engine::PRAGMA_FILTERS]]);

        $mustache->addHelper('string', [
            'lower' => function ($value) {
                return strtolower((string) $value);
            },
            'upper' => function ($value) {
                return strtoupper((string) $value);
            },
            'left' => function ($value, $char) {
                return substr($value, 0, strpos($value, $char) - 1);
            },
            'right' => function ($value, $char) {
                return substr($value, strpos($value, $char) + 1);
            },
            'crypt' => function ($value, $salt) {
                return crypt($value, $salt);
            },
            'crc32' => function ($value) {
                return crc32($value);
            },
            'camel' => function ($value) {
                $x = new UnicodeString($value);
                return $x->camel()->toString();
            },
            'length'=> function ($value) {
                $x = new UnicodeString($value);
                return $x->length();
            },
            'snake'=> function ($value) {
                $x = new UnicodeString($value);
                return $x->snake()->toString();
            },
        ]);
        $mustache->addHelper('number', [
            'int' => function ($value) {
                return (int) $value;
            },
            'float' => function ($value) {
                return (float) $value;
            },
            'abs' => function ($value) {
                return abs((float) $value);
            }
        ]);
        $mustache->addHelper('date', [
            'atom' => function ($value) {
                return date(DateTime::ATOM, strtotime($value));
            },
            'cookie' => function ($value) {
                return date(DateTime::COOKIE, strtotime($value));
            },
            'iso8601' => function ($value) {
                return date(DateTime::ISO8601, strtotime($value));
            },
            'rss' => function ($value) {
                return date(DateTime::RSS, strtotime($value));
            },
            'w3c' => function ($value) {
                return date(DateTime::W3C, strtotime($value));
            }
        ]);

        return self::$mustache;
    }
}
