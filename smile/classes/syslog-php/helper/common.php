<?php
namespace Syslog\Helper;

require_once __DIR__ . '/../vendor/autoload.php';

use Ramsey\Uuid\Uuid;

class CommonHelper {
    public static function uuid() {
        return Uuid::uuid4()->toString();
    }
    
    public static function traceID() {
        return "TC-" . CommonHelper::uuid();
    }
    
    public static function transactionID() {
        return "TR-" . CommonHelper::uuid();
    }
    
    public static function getServerIP() {
        return $_SERVER['SERVER_ADDR'];
    }   
    
    public static function mapString($map) {
        $result = "";
        $idx = 0;
        foreach ($map as $key => $value) {
            if ($idx > 0) {
                $result .= ",";
            }
            $result .= $key . "=" . $value;
            $idx++;
        }
        return $result;
    }
    
    public static function toSnakeCase($input) {
        $snakeCase = preg_replace_callback('/(?:^|\.?)([A-Z])/', function($matches) {
            return '_' . strtolower($matches[1]);
        }, $input);
    
        return ltrim(str_replace('-', '_', $snakeCase), '_');
    }
}
