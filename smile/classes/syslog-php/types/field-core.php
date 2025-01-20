<?php

namespace Syslog\Types;

require_once __DIR__ . '/../helper/string.php';
require_once __DIR__ . '/enum-field.php';
require_once __DIR__ . '/field.php';

use Syslog\Types\Field;
use Syslog\Types\FieldType;
use Syslog\Types\FieldOrder;
use Syslog\Helper\StringHelper;

$MaxLenString = 16000;

class Core
{
    private static function setString($type, $order, $value): Field
    {
        global $MaxLenString;
        $val = StringHelper::safeSubstring($value, 0, $MaxLenString);
        return new Field($type, $order, $val);
    }

    public static function timestamp($value): Field
    {
        return Core::setString(FieldType::Timestamp, FieldOrder::Timestamp, $value);
    }

    public static function logLevel($value): Field
    {
        return Core::setString(FieldType::LogLevel, FieldOrder::LogLevel, $value);
    }

    public static function transactionID($value): Field
    {
        return Core::setString(FieldType::TransactionID, FieldOrder::TransactionID, $value);
    }

    public static function serviceName($value): Field
    {
        return Core::setString(FieldType::ServiceName, FieldOrder::ServiceName, $value);
    }

    public static function endpoint($value): Field
    {
        return Core::setString(FieldType::Endpoint, FieldOrder::Endpoint, $value);
    }

    public static function protocol($value): Field
    {
        return Core::setString(FieldType::Protocol, FieldOrder::Protocol, $value);
    }

    public static function httpMethodType($value): Field
    {
        return Core::setString(FieldType::HttpMethodType, FieldOrder::HttpMethodType, $value);
    }

    public static function executionMethod($value): Field
    {
        return Core::setString(FieldType::ExecutionMethod, FieldOrder::ExecutionMethod, $value);
    }

    public static function contentType($value): Field
    {
        return Core::setString(FieldType::ContentType, FieldOrder::ContentType, $value);
    }

    public static function functionName($value): Field
    {
        return Core::setString(FieldType::FunctionName, FieldOrder::FunctionName, $value);
    }

    public static function callerInfo($value): Field
    {
        return Core::setString(FieldType::CallerInfo, FieldOrder::CallerInfo, $value);
    }

    public static function executionTime($value): Field
    {
        return Core::setString(FieldType::ExecutionTime, FieldOrder::ExecutionTime, $value);
    }

    public static function serverIP($value): Field
    {
        return Core::setString(FieldType::ServerIP, FieldOrder::ServerIP, $value);
    }

    public static function clientIP($value): Field
    {
        return Core::setString(FieldType::ClientIP, FieldOrder::ClientIP, $value);
    }

    public static function eventName($value): Field
    {
        return Core::setString(FieldType::EventName, FieldOrder::EventName, $value);
    }

    public static function traceID($value): Field
    {
        return Core::setString(FieldType::TraceID, FieldOrder::TraceID, $value);
    }

    public static function prevTransactionID($value): Field
    {
        return Core::setString(FieldType::PrevTransactionID, FieldOrder::PrevTransactionID, $value);
    }

    public static function body($value): Field
    {
        return Core::setString(FieldType::Body, FieldOrder::Body, $value);
    }

    public static function result($value): Field
    {
        return Core::setString(FieldType::Result, FieldOrder::Result, $value);
    }

    public static function error($value): Field
    {
        return Core::setString(FieldType::Error, FieldOrder::Error, $value);
    }

    public static function logPhase($value): Field
    {
        return Core::setString(FieldType::LogPhase, FieldOrder::LogPhase, $value);
    }

    public static function message($value): Field
    {
        return Core::setString(FieldType::Message, FieldOrder::Message, $value);
    }
}
