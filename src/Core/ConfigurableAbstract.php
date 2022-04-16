<?php

namespace Mudde\Import\Core;

use Exception;
use Mudde\Import\Helper\ObjectHelper;

abstract class ConfigurableAbstract
{
    abstract function getDefaultConfig(): array;

    public function __construct(array $config)
    {
        $this->configuring($config);
    }

    function configuring(array $config): void
    {
        $defaultConfig = $this->getDefaultConfig();

        foreach ($defaultConfig as $key => $value) {
            $properKey = ObjectHelper::properName($key);
            $methodName = 'configure' . $properKey;
            $hasMethod = method_exists($this, $methodName);
            $methodName = $hasMethod ? $methodName  :'set' . $properKey;
            $value = $config[$key] ?? $value;
            
            if(!method_exists($this, $methodName)) throw new Exception($properKey . ' property not found!');

            $this->$methodName($value);
        }
    }
}
