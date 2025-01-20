<?php

require __DIR__ . "/../../monolog/logger.php";

use Syslog\Monolog\MonologLogger;
use Syslog\Types\Core;
use Syslog\Types\Config;
use Syslog\Types\Message;
use Syslog\Types\CallerInfo;
use Syslog\Types\Protocol;
use Syslog\Types\ExecutionMethod;

$cfg = new Config("simple-smile", "file", "console");
$cfg->requireLogPhase = true;
$cfg->development = true;
$cfg->fileDirPath = "logs/";
$cfg->fileMaxSize = 0;
$cfg->fileMaxBackups = 10;
$cfg->fileMaxAge = 0;
$cfg->fileIsCompress = false;
$cfg->consoleSeparator = "\t";
$cfg->maxLenstringContent = 160000;

// set: LOG_REQUIRE_LOG_PHASE=true
// note:
// - initial fields akan diteruskan ke proses logging selanjutnya
// - initial fields: Timestamp, LogLevel, ServiceName, ExecutionTime, ServerIP
$logger = new MonologLogger($cfg);
$logger = $logger->useHttpReq();
$initFields = [];
array_push($initFields, Core::protocol("HTPP"));
array_push($initFields, Core::executionMethod("SYNC"));
$logger = $logger->use(...$initFields);

// StdLog.UseHttpReq digunakan untuk mengambil informasi dari http request sebagai initial fields
// ex: field tambahan: HttpMethod=,ContentType=,URLPath=,ClientIP=,TraceID=
$logger = $logger->useHttpReq();

// StdLog.Use() digunakan untuk mengambil informasi parameter fields (yang ditentukan developer) sebagai initial fields
// ex: fields tambahan: Protocol=HTTP, ExecutionMethod=SYNC,CallerInfo='KEMENTERIAN' as 'DataAggregator'. APP=PTI
$caller = new CallerInfo('KEMENTERIAN', 'DataAggregator', [ 'APP' => 'PTI', 'CHANNEL' => 'MITRA']);
$initFields = [];
array_push($initFields, Core::protocol(Protocol::Http));
array_push($initFields, Core::executionMethod(ExecutionMethod::Sync));
array_push($initFields, Core::callerInfo($caller->string()));;
$logger = $logger->use(...$initFields);

// StdLog.With() digunakan untuk mengambil informasi dari fields param (yang ditentukan developer) sebagai temporary fields
// ex: fields tambahan: Body=
$userDummyJson = '{"username":"AN172860", "password":"**********"}';
$withfields = [];
array_push($withfields, Core::body($userDummyJson));

// temporary fields dapat di-set saat melakukan logging sebagai parameters
$fields = [];
array_push($fields, Core::functionName('Repository.GetDataKementrian'));
array_push($fields, Core::eventName('Get data dari api GOOGLE'));

// ex: menggunakan StdLog.Start() untuk generate fields TransactionId=TR-, TraceID=TC, LogPhase=[START]
$logger->start()->info('starting');

// ex: menggunakan StdLog.Elapsed() untuk menghitung execution time, fields ExecTime=200ms, LogPhase=[ONGOING]
// ex: fields tambahan: FunctionName=Repository.GetDataKementrian,EventName=Get data dari api GOOGLE
usleep(200000);
$logger->elapsed()->debug('debugging', ...$fields);

// ex: menggunakan StdLog.Elapsed() untuk menghitung execution time, fields ExecTime=400ms, LogPhase=[ONGOING]
// ex: menggunakan StdLog.With() untuk menambahkan fields Body=,FunctionName=Repository.GetDataKementrian,EventName=Get data dari api GOOGLE
usleep(200000);
$logger->elapsed()->with(...$withfields)->info('information', ...$fields);

// ex: menggunakan StdLog.Elapsed() untuk menghitung execution time, fields ExecTime=600ms, LogPhase=[ONGOING]
// ex: tidak menambahkan fields apapun
usleep(200000);
$logger->elapsed()->warn('warning');

// ex: menggunakan StdLog.Elapsed() untuk menghitung execution time, fields ExecTime=800ms, LogPhase=[ONGOING]
// ex: menambahkan fields Error=gagal call api FACEBOOK
usleep(200000);
$logger->elapsed()->error('error', Core::error('gagal call api FACEBOOK'));

// ex: menambahkan field message dengan template yang sudah ditentukan
$message = new Message(
    "get data dari google api",
    "google.api",
    "gagal",
    "400",
    "Bad request",
    "gagal get data dari google api");

$logger->elapsed()->info($message->string(), Core::result('berhasil stopping'));

usleep(200000);
$logger->stop()->info('stopping', Core::result('berhasil stopping'));

echo 'Logging Hello World..';