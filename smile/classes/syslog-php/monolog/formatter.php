<?php
namespace Syslog\Monolog;

use Monolog\Formatter\NormalizerFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\LogRecord;
use Syslog\Types\Field;

class CustomJsonFormatter extends NormalizerFormatter
{
    /** @var Field[] */
    public $initialFields = [];
    public function __construct(array $fields)
    {
        $this->initialFields = $fields;
    }

    public function format(array $record): string
    {
        $logData = array();
        $initialFields = $this->initialFields;
        foreach ($initialFields as $field) {
            $key = $field->key;
            $logData[$key] = $record['context'][$key] ?? '';
        }
        
        return json_encode($logData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL;
    }
}

class CustomLineFormatter extends LineFormatter
{
    /** @var Field[] */
    public $initialFields = [];
    public $separator = "\t";

    public function __construct(array $fields, $separator)
    {
        $this->initialFields = $fields ?? [];
        $this->separator = $separator ?? "\t";
    }

    public function format(array $record): string
    {
        $output = "";
        $initialFields = $this->initialFields;
        foreach ($initialFields as $field) {
            $key = $field->key;
            $value = $record['context'][$key] ?? "";
            if ($output != '') {
                $output .= $this->separator;
            }
            $output .= $value;
        }
        
        return $output . PHP_EOL;
    }
}