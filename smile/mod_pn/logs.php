<?php
require __DIR__ . "../../classes/syslog-php/monolog/logger.php";
use Syslog\Monolog\MonologLogger;
use Syslog\Types\Core;
use Syslog\Types\Config;
use Syslog\Types\Message;
use Syslog\Types\CallerInfo;
use Syslog\Types\Protocol;
use Syslog\Types\ExecutionMethod;
use Syslog\Types\Field;


class Logs {
    public $cfg;
    public $logger;
    public $body = '-';
    public $message = '-';
    public $result = '-';
    public $error = '-';
    public $user = '-';
    public $functionName = '-';
    public $eventName = '-';
    public $protocol = '-';
    public $executionMethod = '-';
    public $fields = [];
    public $contentType = '-';
    public $arrMask = ['ALAMAT', 'TELEPON', 'FAX', 'KODE_POS', 'NPWP', 'EMAIL', 'EMAIL_KANTOR', 'KPJ', 'NIK', 'NOMOR_IDENTITAS', 'NO_REKENING_PENERIMA_BARU', 'NO_REKENING_PENERIMA', 'NAMA_PENERIMA_MANFAAT', 'NAMA_TK', 'KODE_KLAIM', 'KODE_AGENDA_KOREKSI', 'NAMA_REKENING_PENERIMA_BARU', 'KODE_BANK_PENERIMA_BARU', 'NAMA_REKENING_PENERIMA', 'PETUGAS_KOREKSI', 'KODE_BANK_PENERIMA_BARU', 'KODE_BANK_PENERIMA', 'KODE_BANK_PEMBAYAR', 'KODE_BANK_PEMBAYAR_BARU', 'PENJABAT','NAMA_PETUGAS','NAMA_PETUGAS_APPROVAL','BANK_PENERIMA', 'NAMA_KANTOR_KOREKSI', 'KODE_USER', 'NAMA_PETUGAS_KOREKSI', 'STATUS_APPROVAL', 'BANK_PENERIMA_BARU', 'KODE_JABATAN', 'KODE_KANTOR_APPROVAL', 'NOM_MANFAAT', 'STATUS_APPROVAL_KOREKSI', 'KD_PRG,', 'NAMA_JABATAN', 'KODE_KANTOR', 'NAMA_KANTOR', 'PETUGAS_APPROVAL'];

    public function start() {
        $this->initFieldLogger();
        $this->logger->start()->info('starting');
    }

    public function stop($body = '-', $result = '-', $msg = '-') {
        //Write Log
        if($result !== "-") {
            $result = json_encode($result);
        }
        $this->logger->stop()->info($msg, Core::result($result));
        
        return $this;
    }

    public function info($body, $result, $msg) {
        $body =  $body ?? '-';
        $result = $result ?? '-';
        $msg = $msg ?? '-';
        $withfields = [];
        array_push($this->fields, $this->setBody($body)->body);
        array_push($this->fields, $this->setResult($result)->result);
       
        //Write Log
        $this->logger->elapsed()->with(...$withfields)->info($msg, ...$this->fields);


        return $this;
    }

    public function error($body = '-', $result = '-', $msg = '-') {
          //Add Body & Result
          $this->setFields();
          array_push($this->fields, $this->setBody($body)->body);
          $this->logger->use(...$this->fields);
  
          //Write Log
          $this->logger->elapsed()->error($msg, Core::error($result));

          return $this;
    }

    private function setCfg(){
        $cfg = new Config("smile_mod_pn", "file", "console");
        $cfg->requireLogPhase = true;
        $cfg->development = true;
        $cfg->fileDirPath = "../data/logs/";
        $cfg->fileMaxSize = 0;
        $cfg->fileMaxBackups = 10;
        $cfg->fileMaxAge = 0;
        $cfg->fileIsCompress = false;
        $cfg->consoleSeparator = "\t";
        $cfg->maxLenstringContent = 160000;

        $this->cfg =  $cfg;
        return $this;
    
    }

    private function resetFields() {
        $this->fields = [];
        return $this;
    }

    private function setFields() {
        $this->resetFields();
        array_push($this->fields, $this->setProtocol()->protocol);
        array_push($this->fields, $this->setExecutionMethod()->executionMethod);
        array_push($this->fields, $this->setUser()->user);
        array_push($this->fields, $this->functionName);
        array_push($this->fields, $this->eventName);
        array_push($this->fields, $this->setBody('-')->body);
        array_push($this->fields, $this->setResult('-')->result);
        array_push($this->fields, $this->setError('-')->error);
        array_push($this->fields, $this->setContentType('application/x-www-form-urlencoded; charset=UTF-8')->contentType);

        return $this;
    }

    private function setContentType($contentType) {
        $this->contentType = Core::contentType($contentType);
        return $this;
    }

    private function setMessage($msg) {
        $this->message = $msg;
        return $this;
    }

    private function setBody($body) {
        $this->body = Core::body($body);
        return $this;
    }

    private function setResult($result) {
        if($result !== "-"){
            $result =  json_decode($result, true);
            if(is_array($result)) {
                $result = $this->maskArrayKeys($result, $this->arrMask);
                $result = json_encode($result);
            }
        }

        $this->result = Core::result($result);
        return $this;
    }

    private function setError($err) {
        $this->error = Core::error($err);
        return $this;
    }

    private function setExecutionMethod() {
        $this->executionMethod =  Core::executionMethod(ExecutionMethod::Sync);
        return $this;
    }

    private function setProtocol() {
        $this->protocol =  Core::protocol(Protocol::Rest);
        return $this;
    }

    public function setEventName($eventName) {
        $this->eventName = Core::eventName($eventName);
        return $this;
    }

    private function setUser() {
        $user = new CallerInfo($_SESSION['USER'], $_SESSION['namarole'], [ 'APP' => 'SMILE', 'CHANNEL' => 'INTERNAL']);
        $this->user =  Core::callerInfo($user->string());
        return $this;
    }

    public function setFunctionName($functionnName) {
        $this->functionName = Core::functionName($functionnName);
        return $this;
    }

    private function initFieldLogger() {
        $logger = new MonologLogger($this->setCfg()->cfg);
        $logger = $logger->useHttpReq();
        $this->setFields();
        $logger = $logger->use(...$this->fields);
        $this->logger =  $logger;
    }

    public function maskArrayKeys($array, $keysToMask, $maskingCharacter = '*****') {
        foreach ($array as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $array[$key] = $this->maskArrayKeys($value, $keysToMask, $maskingCharacter);
    
            } else {
                if (in_array($key, $keysToMask)) {
                    $array[$key] = $maskingCharacter;
                }
            }
        }
    
        return $array;
    }
}