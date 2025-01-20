<?php

require __DIR__ . "/../classes/syslog-php/monolog/logger.php";
include_once __DIR__ . "/config.php";

use Syslog\Monolog\MonologLogger;
use Syslog\Types\CallerInfo;
use Syslog\Types\Core;

$session_logger = $_SESSION;

$user_log = $_SESSION["USER"];
$kodekantor_log = $_SESSION['namarole'] ?? '';
$validPassword = "password";

// init logger
$logger = new MonologLogger($cfg);

// use http req info
$logger = $logger->useHttpReq();

// init caller (opt)
$caller = new CallerInfo($user_log, $kodekantor_log, ["APP" => "SMILE", "CHANNEL" => "INTERNAL"]);
$initFields = [];
array_push($initFields, Core::protocol("REST"));
array_push($initFields, Core::executionMethod("SYNC"));
array_push($initFields, Core::callerInfo($caller->string()));

$logger = $logger->use(...$initFields);


function flashInfo(){
    var_dump('masuk info');die;
}

function dbExecuteLog($function_name = null, $event_name = null, $body = null, $result = null){
    $logger = $GLOBALS['logger'];
    
    $fields = [];
    if ($function_name) {
        array_push($fields, Core::functionName($function_name));
    }
    if ($event_name) {
        array_push($fields, Core::eventName($event_name));
    }
    					
    $withfields = [];
    if ($body) {
        array_push($withfields, Core::body($body));
    }   
    if ($result) {
        array_push($withfields, Core::result($result));
    }
    $logger->elapsed()->with(...$withfields)->info('information', ...$fields);
}

function startLog($logger, $event_name = null, $info_process = null){
    $logger->with(Core::eventName($event_name))->start()->info($info_process);
}

function stopLog($status, $msg = null){
    $logger = $GLOBALS['logger'];

    if ($status == "GAGAL") {
        $logger->elapsed()->error('error', Core::error($msg));
        $logger->stop()->error('stopping', Core::result($msg.' '.$status));
    }else{
        $logger->stop()->info('stopping', Core::result($msg));
    }
}

function dbExecuteErrorLog($function_name = null, $event_name = null, $body = null, $result = null){
    $logger = $GLOBALS['logger'];

    $fields = [];
    if ($function_name) {
        array_push($fields, Core::functionName($function_name));
    }
    if ($event_name) {
        array_push($fields, Core::eventName($event_name));
    }
    					
    $withfields = [];
    if ($body) {
        array_push($withfields, Core::body($body));
    }   
    if ($result) {
        array_push($withfields, Core::result($result));
    }
    $logger->elapsed()->with(...$withfields)->error('error', ...$fields);
}