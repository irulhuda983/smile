# Standard Library System Logging - NodeJS

## Peringatan
Data sensitive tidak diperbolehkan disimpan ke dalam system log!

## Latest Version
```
syslog-php@v0.1.7

minimum:
php 8.0.16
```

## Quick Start
```js
// create new folder
mkdir syslog-node-poc
cd syslog-node-poc

// download syslog module
https://git.bpjsketenagakerjaan.go.id/ptk-lab/syslog-php/-/tags/v0.1.7

// create app.js
touch app.php
```

Edit app.php file
```php
<?php

require __DIR__ . "/syslog-php/monolog/logger.php";

use Syslog\Monolog\MonologLogger;
use Syslog\Types\Config;
use Syslog\Types\FieldOrder;

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

$logger = new MonologLogger($cfg);
$logger = $logger->useHttpReq();

$logger->start()->info("START");
usleep(200000);
$logger->elapsed()->info("ON PROCESS");
usleep(200000);
$logger->stop()->info("STOP");

// get field trace id
$f = $logger->get(FieldOrder::TraceID);
echo '<br>Trace ID: ' . $f->value;

echo "<br>Success";

?>

```

## Catatan
- Log disimpan dalam sebuah logfile dengan format penamaan file : <nama_services>-yyyy-mm-dd.log
  <br>Contoh:
  <br>jsejkn-2022-08-22.log
  <br>jmo-mobileapps-gateway-2022-08-22.log

## Fields
```
Dapat menggunakan referensi dari project syslog golang: https://git.bpjsketenagakerjaan.go.id/ptk-lab/syslog-php
```

## Contoh Penggunaan
Dapat dilihat pada repository:
https://git.bpjsketenagakerjaan.go.id/ptk-lab/syslog-php/example/all/index.php

```php
<?php

require __DIR__ . "/syslog-php/monolog/logger.php";

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
```

## Contoh Logging
```
curl --location --request GET 'http://localhost:8080/example/all/index.php' \
--header 'Content-Type: application/json' \
--header 'X-TRACE-ID: TC-DUMMY400-e29b-41d4-a716-446655440000' \
--data-raw '{}'

Hasil:
2023-10-03 16:08:54.114	INFO	TR-00cff54b-bc34-4769-bcc2-8e9a5defbce7	http_server_log	/use-http-usefields-withfields-manual-startstop-message	HTTP	GET	SYNC	application/json		'KEMENTERIAN' as 'DataAggregator'. 'APP=PTI,CHANNEL=MITRA'		172.26.33.144	0.0.0.0		TC-DUMMY400-e29b-41d4-a716-446655440000				[START]	starting
2023-10-03 16:08:54.324	DEBUG	TR-00cff54b-bc34-4769-bcc2-8e9a5defbce7	http_server_log	/use-http-usefields-withfields-manual-startstop-message	HTTP	GET	SYNC	application/json	Repository.GetDataKementrian	'KEMENTERIAN' as 'DataAggregator'. 'APP=PTI,CHANNEL=MITRA'	208ms	172.26.33.144	0.0.0.0	Get data dari api GOOGLE	TC-DUMMY400-e29b-41d4-a716-446655440000				[ONGOING]	debugging
2023-10-03 16:08:54.535	INFO	TR-00cff54b-bc34-4769-bcc2-8e9a5defbce7	http_server_log	/use-http-usefields-withfields-manual-startstop-message	HTTP	GET	SYNC	application/json	Repository.GetDataKementrian	'KEMENTERIAN' as 'DataAggregator'. 'APP=PTI,CHANNEL=MITRA'	421ms	172.26.33.144	0.0.0.0	Get data dari api GOOGLE	TC-DUMMY400-e29b-41d4-a716-446655440000	{"username":"AN172860", "password":"**********"}			[ONGOING]	information
2023-10-03 16:08:54.739	WARN	TR-00cff54b-bc34-4769-bcc2-8e9a5defbce7	http_server_log	/use-http-usefields-withfields-manual-startstop-message	HTTP	GET	SYNC	application/json		'KEMENTERIAN' as 'DataAggregator'. 'APP=PTI,CHANNEL=MITRA'	625ms	172.26.33.144	0.0.0.0		TC-DUMMY400-e29b-41d4-a716-446655440000				[ONGOING]	warning
2023-10-03 16:08:54.943	ERROR	TR-00cff54b-bc34-4769-bcc2-8e9a5defbce7	http_server_log	/use-http-usefields-withfields-manual-startstop-message	HTTP	GET	SYNC	application/json		'KEMENTERIAN' as 'DataAggregator'. 'APP=PTI,CHANNEL=MITRA'	829ms	172.26.33.144	0.0.0.0		TC-DUMMY400-e29b-41d4-a716-446655440000			gagal call api FACEBOOK	[ONGOING]	error
2023-10-03 16:08:54.949	INFO	TR-00cff54b-bc34-4769-bcc2-8e9a5defbce7	http_server_log	/use-http-usefields-withfields-manual-startstop-message	HTTP	GET	SYNC	application/json		'KEMENTERIAN' as 'DataAggregator'. 'APP=PTI,CHANNEL=MITRA'	832ms	172.26.33.144	0.0.0.0		TC-DUMMY400-e29b-41d4-a716-446655440000		berhasil stopping		[ONGOING]	'get data dari google api' on 'google.api' with result 'gagal' with error '400:Bad request'. 'gagal get data dari google api'
2023-10-03 16:08:55.152	INFO	TR-00cff54b-bc34-4769-bcc2-8e9a5defbce7	http_server_log	/use-http-usefields-withfields-manual-startstop-message	HTTP	GET	SYNC	application/json		'KEMENTERIAN' as 'DataAggregator'. 'APP=PTI,CHANNEL=MITRA'	1038ms	172.26.33.144	0.0.0.0		TC-DUMMY400-e29b-41d4-a716-446655440000		berhasil stopping		[STOP]	stopping

```