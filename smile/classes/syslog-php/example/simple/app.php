<?php

require __DIR__ . "/../../monolog/logger.php";

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
