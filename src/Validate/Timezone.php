<?php


namespace Mudde\Import\Validate;

use DateTimeZone;
use Mudde\Import\Core\ValidationAbstract;

class TimeZone extends ValidationAbstract
{
    public function isValid(mixed $data):bool
    {
        foreach (DateTimeZone::listAbbreviations() as $timezone) {
            if ($data = $timezone['timezone_id']) {
                return true;
            }
        }

        return false;
    }

    public function getError():string
    {
        return 'Value is not a valid timezone';
    }
}
