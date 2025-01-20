<?php

namespace Syslog\Types;

class Config
{
    public $appName;
    public $requireLogPhase;
    public $stdOutput;
    public $encodingOutput;
    public $development;
    public $fileDirPath;
    public $fileMaxSize;
    public $fileMaxBackups;
    public $fileMaxAge;
    public $fileIsCompress;
    public $consoleSeparator;
    public $maxLenstringContent;

    public function __construct($appName, $stdOutput, $encodingOutput)
    {
        $this->appName = $appName;
        $this->stdOutput = $stdOutput;
        $this->encodingOutput = $encodingOutput;
        $this->requireLogPhase = false;
        $this->development = false;
        $this->fileDirPath = '';
        $this->fileMaxSize = 0;
        $this->fileMaxAge = 0;
        $this->fileMaxBackups = 0;
        $this->fileIsCompress = true;
        $this->consoleSeparator = "\t";
        $this->maxLenstringContent = 16000;
    }
}
