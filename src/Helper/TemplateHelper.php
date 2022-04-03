<?php

namespace Mudde\Import\Helper;

use Mustache_Engine;

class TemplateHelper
{
    public static $mustache;

    static function render($text, $data): string
    {
        $mustache = self::getEngine();

        return $mustache->render($text, $data);
    }

    static private function getEngine(): Mustache_Engine
    {
        if (self::$mustache) return self::$mustache;

        self::$mustache = $mustache = new Mustache_Engine(['pragmas' => [Mustache_Engine::PRAGMA_FILTERS]]);
        $mustache->addHelper('lower', function ($value) {
            return strtolower((string) $value);
        });
        $mustache->addHelper('upper', function ($value) {
            return strtoupper((string) $value);
        });

        return self::$mustache;
    }
}
