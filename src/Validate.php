<?php

namespace Mudde\Import;

use ArrayObject;
use Mudde\Import\Core\ConfigurableAbstract;
use Mudde\Import\Helper\ObjectHelper;

class Validate extends ConfigurableAbstract
{

    private ArrayObject $validations;

    public function getDefaultConfig(): array
    {
        return [
            'validators' => new ArrayObject()
        ];
    }

    public function configureValidate(array $config): void
    {
        $configValidator = $this->validators = new ArrayObject();

        foreach ($config as $key => $item) {
            $configValidator[$key][] = ObjectHelper::getObject($item, 'Mudde\Import\Validate\\');
        }
    }

    public function getValidations()
    {
        return $this->validations;
    }

    public function setValidations($validations): self
    {
        $this->validations = $validations;

        return $this;
    }

    public function validate(ArrayObject $data): ArrayObject
    {
        $output = new ArrayObject();

        foreach ($this->validation as $key => $validators) {
            $output[$key] = new ArrayObject();
            foreach ($validators as $validator) {
                if (!$validator->isValid($data[$key])) {
                    $output[$key][] = $validator->getError();
                }
            }
        }

        return $output;
    }
}
