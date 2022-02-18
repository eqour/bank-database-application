<?php

namespace app\helpers;

use DateTime;
use Throwable;

class DateHelper {
    public static function trySetDate($stringDate, ?DateTime& $dateTime): bool {
        try {
            $dateTime = new DateTime($stringDate);
            return true;
        } catch(Throwable) {
            return false;
        }
    }
}
