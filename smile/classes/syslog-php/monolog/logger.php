<?php
namespace Syslog\Monolog;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../logger.php';
require_once __DIR__ . "/../helper/common.php";
require_once __DIR__ . "/../helper/date.php";
require_once __DIR__ . "/../helper/http.php";
require_once __DIR__ . "/../helper/string.php";
require_once __DIR__ . "/../types/config.php";
require_once __DIR__ . "/../types/enum-content-type.php";
require_once __DIR__ . "/../types/enum-encoding.php";
require_once __DIR__ . "/../types/enum-exec-type.php";
require_once __DIR__ . "/../types/enum-field.php";
require_once __DIR__ . "/../types/enum-http-method.php";
require_once __DIR__ . "/../types/enum-log-level.php";
require_once __DIR__ . "/../types/enum-output.php";
require_once __DIR__ . "/../types/enum-phase.php";
require_once __DIR__ . "/../types/enum-protocol.php";
require_once __DIR__ . "/../types/field-core.php";
require_once __DIR__ . "/../types/field.php";
require_once __DIR__ . '/formatter.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;

use Syslog\LoggerInterface;
use Syslog\Monolog\CustomJsonFormatter;
use Syslog\Monolog\CustomLineFormatter;
use Syslog\Helper\HttpHelper;
use Syslog\Helper\CommonHelper;
use Syslog\Helper\DateHelper;
use Syslog\Types\Core;
use Syslog\Types\Config;
use Syslog\Types\StdOutput;
use Syslog\Types\EncodingOut;
use Syslog\Types\Field;
use Syslog\Types\FieldType;
use Syslog\Types\FieldOrder;
use Syslog\Types\Phase;
use Syslog\Types\Level;

use Exception;
use DateTime;

use function Types\serverIP;

class MonologLogger implements LoggerInterface {
    /** @var Config */
    public $config;
    /** @var Field[] */
    public $initialFields = [];
    /** @var array<string, Field> */
    public $withFields = [];
    public $logger; 
    public $logStart = null;
    public $logStop = null;

    public function __construct(Config $cfg) {
        $logger = new Logger('syslog_smile');
        $cfg->appName = CommonHelper::toSnakeCase($cfg->appName);
        $minLevel = $cfg->development ? Logger::DEBUG : Logger::INFO;
        $dirPath = $cfg->fileDirPath . DIRECTORY_SEPARATOR . $cfg->appName;
        $maxFiles = $cfg->fileMaxBackups;
        
        $this->config = $cfg;
        $this->initFields();

        $formatter = null;
        if ($cfg->encodingOutput == EncodingOut::EncodingOutConsole) {
            $formatter = new CustomLineFormatter($this->initialFields, "\t");
        } else if ($cfg->encodingOutput == EncodingOut::EncodingOutJson) {
            $formatter = new CustomJsonFormatter($this->initialFields);
        }

        $handler = null;
        $fileHandler = null;
        if ($cfg->stdOutput == StdOutput::StdOutConsole) {
            $handler = new StreamHandler('php://stdout', $minLevel);
        } else if ($cfg->stdOutput == StdOutput::StdOutFile) {
            $handler = new RotatingFileHandler($dirPath, $maxFiles, $minLevel); 
            $handler->setFilenameFormat('{filename}-{date}.log', 'Y-m-d');
        } else if ($cfg->stdOutput == StdOutput::StdMulti) {
            $handler = new StreamHandler('php://stdout', $minLevel);
            $fileHandler = new RotatingFileHandler($dirPath, $maxFiles, $minLevel); 
            $fileHandler->setFilenameFormat('{filename}-{date}.log', 'Y-m-d');
        }
        if ($formatter != null) {
            if ($handler != null) {
                $handler->setFormatter($formatter);
            }
            if ($fileHandler != null) {
                $fileHandler->setFormatter($formatter);
            }
        }

        if ($handler != null) {
            $logger->pushHandler($handler);
        }
        if ($fileHandler != null) {
            $logger->pushHandler($fileHandler);
        }
        return $this->logger = $logger;
    }

    private function initFields() {
        $this->initialFields[FieldOrder::Timestamp] = new Field(FieldType::Timestamp, FieldOrder::Timestamp, '');
        $this->initialFields[FieldOrder::LogLevel] = new Field(FieldType::LogLevel, FieldOrder::LogLevel, '');
        $this->initialFields[FieldOrder::TransactionID] = new Field(FieldType::TransactionID, FieldOrder::TransactionID, '');
        $this->initialFields[FieldOrder::ServiceName] = new Field(FieldType::ServiceName, FieldOrder::ServiceName, $this->config->appName);
        $this->initialFields[FieldOrder::Endpoint] = new Field(FieldType::Endpoint, FieldOrder::Endpoint, '');
        $this->initialFields[FieldOrder::Protocol] = new Field(FieldType::Protocol, FieldOrder::Protocol, '');
        $this->initialFields[FieldOrder::HttpMethodType] = new Field(FieldType::HttpMethodType, FieldOrder::HttpMethodType, '');
        $this->initialFields[FieldOrder::ExecutionMethod] = new Field(FieldType::ExecutionMethod, FieldOrder::ExecutionMethod, '');
        $this->initialFields[FieldOrder::ContentType] = new Field(FieldType::ContentType, FieldOrder::ContentType, '');
        $this->initialFields[FieldOrder::FunctionName] = new Field(FieldType::FunctionName, FieldOrder::FunctionName, '');
        $this->initialFields[FieldOrder::CallerInfo] = new Field(FieldType::CallerInfo, FieldOrder::CallerInfo, '');
        $this->initialFields[FieldOrder::ExecutionTime] = new Field(FieldType::ExecutionTime, FieldOrder::ExecutionTime, '');
        $this->initialFields[FieldOrder::ServerIP] = new Field(FieldType::ServerIP, FieldOrder::ServerIP, '');
        $this->initialFields[FieldOrder::ClientIP] = new Field(FieldType::ClientIP, FieldOrder::ClientIP, '');
        $this->initialFields[FieldOrder::EventName] = new Field(FieldType::EventName, FieldOrder::EventName, '');
        $this->initialFields[FieldOrder::TraceID] = new Field(FieldType::TraceID, FieldOrder::TraceID, '');
        $this->initialFields[FieldOrder::PrevTransactionID] = new Field(FieldType::PrevTransactionID, FieldOrder::PrevTransactionID, '');
        $this->initialFields[FieldOrder::Body] = new Field(FieldType::Body, FieldOrder::Body, '');
        $this->initialFields[FieldOrder::Result] = new Field(FieldType::Result, FieldOrder::Result, '');
        $this->initialFields[FieldOrder::Error] = new Field(FieldType::Error, FieldOrder::Error, '');
        $this->initialFields[FieldOrder::LogPhase] = new Field(FieldType::LogPhase, FieldOrder::LogPhase, '');
        $this->initialFields[FieldOrder::Message] = new Field(FieldType::Message, FieldOrder::Message, '');
    }

    /** @var Field[] */
    private function castFields(...$fields): array {
        $keyval = array();
        $tfields = array_slice($this->initialFields, 0);
        foreach ($this->withFields as $key => $value) {
            $tfields[$value->order] = $value;
        }
        foreach ($fields as $field) {
            $tfields[$field->order] = $field;
        }
        foreach ($tfields as $field) {
            $keyval[$field->key] = $field->value;
        }

        // clean up temporary value
        $this->withFields = [];

        return $keyval;
    }

    private function initLog($message, $level) {
        if ($this->config->requireLogPhase && $this->logStart == null) {
            throw new Exception('syslog fatal error: cannot continue logging because the logger has not been started');
        }
        $this->initialFields[FieldOrder::ServerIP] = Core::serverIP(CommonHelper::getServerIP());
        $this->initialFields[FieldOrder::Timestamp] = Core::timestamp(DateHelper::currentTimestampStr());
        $this->initialFields[FieldOrder::LogLevel] = Core::logLevel($level);
        $this->initialFields[FieldOrder::Message] = Core::message($message);

        if ($this->logStop != null) {
            $this->logStart = null;
            $this->logStop = null;
        }
    }

    public function debug(string $message, ...$fields): void {
        $this->initLog($message, Level::Debug);
        $newFields = $this->castFields(...$fields);
        $this->logger->debug($message, $newFields);
    }

    public function info(string $message, ...$fields): void {
        $this->initLog($message, Level::Info);
        $newFields = $this->castFields(...$fields);
        $this->logger->info($message, $newFields);
    }

    public function warn(string $message, ...$fields): void {
        $this->initLog($message, Level::Warn);
        $newFields = $this->castFields(...$fields);
        $this->logger->warning($message, $newFields);
    }

    public function error(string $message, ...$fields): void {
        $this->initLog($message, Level::Error);
        $newFields = $this->castFields(...$fields);
        $this->logger->error($message, $newFields);
    }

    public function fatal(string $message, ...$fields): void {
        $this->initLog($message, Level::Fatal);
        $newFields = $this->castFields(...$fields);
        $this->logger->emergency($message, $newFields);
    }

    public function with(...$fields): LoggerInterface {
        foreach ($fields as $field) {
            $this->withFields[$field->order] = $field;
        }
        return $this;
    }

    public function use(...$fields): LoggerInterface {
        foreach ($fields as $field) {
            $this->initialFields[$field->order] = $field;
        }
        return $this;
    }

    public function useHttpReq(): LoggerInterface {
        $this->initialFields[FieldOrder::HttpMethodType]->value = HttpHelper::getHttpMethodType() ?? $this->initialFields[FieldOrder::HttpMethodType]->value;
        $this->initialFields[FieldOrder::ContentType]->value = HttpHelper::getHeaderContentType() ?? $this->initialFields[FieldOrder::ContentType]->value;
        $this->initialFields[FieldOrder::Endpoint]->value = HttpHelper::getHttpURLPath() ?? $this->initialFields[FieldOrder::Endpoint]->value;
        $this->initialFields[FieldOrder::ClientIP]->value = HttpHelper::getClientIP() ?? $this->initialFields[FieldOrder::ClientIP]->value;
        $this->initialFields[FieldOrder::TraceID]->value = HttpHelper::getTraceID() ?? $this->initialFields[FieldOrder::TraceID]->value;
        $this->initialFields[FieldOrder::PrevTransactionID]->value = HttpHelper::getPrevTransID() ?? $this->initialFields[FieldOrder::PrevTransactionID]->value;
        return $this;
    }

    public function start(): LoggerInterface {
        $this->logStart = microtime(true);
        $logger = $this->use(
            new Field(FieldType::TransactionID, FieldOrder::TransactionID, CommonHelper::transactionID()),
            new Field(FieldType::LogPhase, FieldOrder::LogPhase, Phase::Start)
        );
        if ($this->initialFields[FieldOrder::TraceID]->value == '') {
            $logger = $this->use(new Field(FieldType::TraceID, FieldOrder::TraceID, CommonHelper::traceID()));
        }
        return $logger->with(
            new Field(FieldType::ExecutionTime, FieldOrder::ExecutionTime, '0ms')
        );
    }

    public function elapsed(): LoggerInterface {
        $now = microtime(true);
        $this->logStart = $this->logStart ?? $now;
        $timeDifference = ($now - $this->logStart) * 1000;
        $elapsedMilliseconds = $timeDifference . 'ms';
        return $this->with(
            new Field(FieldType::LogPhase, FieldOrder::LogPhase, Phase::Ongoing),
            new Field(FieldType::ExecutionTime, FieldOrder::ExecutionTime, $elapsedMilliseconds)
        );
    }

    public function stop(): LoggerInterface {
        $now = microtime(true);
        $this->logStop = $now;
        $this->logStart = $this->logStart ?? $now;
        $timeDifference = ($this->logStop - $this->logStart) * 1000;
        $elapsedMilliseconds = $timeDifference . 'ms';
        return $this->with(
            new Field(FieldType::LogPhase, FieldOrder::LogPhase, Phase::Stop),
            new Field(FieldType::ExecutionTime, FieldOrder::ExecutionTime, $elapsedMilliseconds)
        );
    }

    public function get(int $index) {
        return $this->initialFields[$index];
    }
}