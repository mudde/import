<?php

namespace Mudde\Import\Helper;

use Mustache_Engine;

class TemplateHelper
{
    public static $m;

    static function render($text, $data): string
    {
        $m = self::$m ?? self::$m = new Mustache_Engine();

        return $m->render($text, $data);
    }
}
