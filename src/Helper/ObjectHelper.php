<?php

namespace Mudde\Import\Helper;

use Exception;

class ObjectHelper
{
    static $specialChar = ['-', '_', ' ', '/'];

    static function properName($name, $namespace = '')
    {
        $specialChar = self::$specialChar;

        return  $namespace .  str_replace($specialChar, '', ucwords($name, implode('', $specialChar)));
    }

    static function getObject($config, $namespace, $name = null)
    {
        $name = $name ?? $config['@type'] ?? null;
        if($name === null) throw new Exception('Get object error! No correct name!'. $config);

        $className = ObjectHelper::properName($name, $namespace);

        if (!class_exists($className)) throw new Exception($className . 'not FOUND!');

        return new $className($config);
    }
}
