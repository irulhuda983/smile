<?php
session_start();

require __DIR__ . "/../../monolog/logger.php";
include_once __DIR__ . "/config.php";

use Syslog\Monolog\MonologLogger;
use Syslog\Types\CallerInfo;
use Syslog\Types\Core;

$validUsername = "user";
$validPassword = "password";

// init logger
$logger = new MonologLogger($cfg);

// use http req info
$logger = $logger->useHttpReq();

// init caller (opt)
$caller = new CallerInfo($validUsername, 'SMILE-APP', ["APP" => "SMILE", "CHANNEL" => "INTERNAL"]);
$initFields = [];
array_push($initFields, Core::protocol("HTPP"));
array_push($initFields, Core::executionMethod("SYNC"));
array_push($initFields, Core::callerInfo($caller->string()));

// use initial fields (opt)
$logger = $logger->use(...$initFields);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // start logging
    $logger->with(Core::eventName("nyoba login"))->start()->info("Mulai login");
    
    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username === $validUsername && $password === $validPassword) {
        // stop logging
        $logger->with(Core::eventName("nyoba login"))->stop()->info("Login selesai");
        $_SESSION["username"] = $username;
        
        header("Location: secure.php");
        die();
    } else {
        // stop logging warning
        $logger->with(Core::eventName("nyoba login"))->stop()->warn("Selesai login");
        echo "Invalid username or password. Please try again.";
    }
}
