<?php

namespace Mudde\Import\Core;

use ArrayObject;

abstract class ContentTypeAbstract
{
    abstract public function process(string $content): ArrayObject;
}