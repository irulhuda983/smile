<?php
    require __DIR__ . "/../classes/syslog-php/monolog/logger.php";

    use Syslog\Monolog\MonologLogger;
    use Syslog\Types\Core;
    use Syslog\Types\Config;

    function SysLogger() {
        $cfg = new Config("smile", "file", "console");
        $cfg->requireLogPhase = true;
        $cfg->development = true;
        $cfg->fileDirPath = __DIR__ . "/../logs/";
        $cfg->fileMaxSize = 0;
        $cfg->fileMaxBackups = 10;
        $cfg->fileMaxAge = 0;
        $cfg->fileIsCompress = false;
        $cfg->consoleSeparator = "\t";
        $cfg->maxLenstringContent = 16000;
        
        $logger = new MonologLogger($cfg);
        $logger = $logger->useHttpReq();
        $initFields = [];
        array_push($initFields, Core::protocol("HTTP"));
        array_push($initFields, Core::executionMethod("SYNC"));
        $logger = $logger->use(...$initFields);
        
        $logger = $logger->useHttpReq();
        return $logger;
    }
?>