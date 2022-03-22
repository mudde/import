<?php

namespace Mudde\Import\Core;

abstract class ContentTypeAbstract
{

    abstract public function process(): array;
}
