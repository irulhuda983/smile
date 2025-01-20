<?php
namespace Syslog\Helper;

use DateTime;

class DateHelper {
    public static function timestampStr($time) {
        return $time->format('Y-m-d H:i:s.u');
    }
    
    public static function currentTimestampStr() {
        $time = new DateTime();
        return $time->format('Y-m-d H:i:s.u');
    }
    
    public static function dateStr($date) {
        return $date->format('Y-m-d');
    }
    
    public static function currentDateStr() {
        $date = new DateTime();
        return $date->format('Y-m-d');
    }
}