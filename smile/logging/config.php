<?php

use Syslog\Types\Config;

$cfg = new Config("smile-akses", "file", "console");
$cfg->requireLogPhase = true;
$cfg->development = true;
$cfg->fileDirPath = "./logging/data/logs/";
$cfg->fileMaxSize = 0;
$cfg->fileMaxBackups = 10;
$cfg->fileMaxAge = 0;
$cfg->fileIsCompress = false;
$cfg->consoleSeparator = "\t";
$cfg->maxLenstringContent = 160000;