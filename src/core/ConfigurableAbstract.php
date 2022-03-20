<?php

namespace Mudde\Import\Core;

abstract class ConfigurableAbstract
{

    private array $config;

    abstract function getDefaultConfig(): array;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    function configuring(): void
    {
        $config = $this->config;
        $defaultConfig = $this->getDefaultConfig();

        foreach ($defaultConfig as $key => $value) {
            $methodName = 'configure' . ucfirst($key);
            $hasMethod = method_exists($this, $methodName);
            $value = $config[$key] ? $config[$key] : $value;

            if ($hasMethod) {
                $this->$methodName($value);
            } else {
                $this->$key = $value;
            }
        }
    }

    public function setConfig(array $config): ConfigurableAbstract
    {
        $this->config = $config;
        return $this;
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}