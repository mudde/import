<?php

namespace Mudde\Import\Helper;

use Exception;

class ObjectHelper
{
    static $specialChar = ['-', '_', ' ', '/'];

    static function properName(string $name, string $namespace = '')
    {
        $specialChar = self::$specialChar;
        $properName = str_replace($specialChar, '', ucwords($name, implode('', $specialChar)));
        
        return  $namespace . $properName;
    }

    static function getObject(array $config, string $namespace, $name = null)
    {
        $name = $name ?? $config['_type'] ?? null;
        if ($name === null) throw new Exception('Get object error! No correct name!' . json_encode($config));

        $className = ObjectHelper::properName($name, $namespace);

        if (!class_exists($className)) throw new Exception($className . ' not FOUND!');

        return new $className($config);
    }
}
