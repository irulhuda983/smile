<?php
namespace Syslog\Types;

require_once __DIR__ . "/../helper/common.php";

use Syslog\Helper\CommonHelper;
use Syslog\Helper\StringHelper;

class Field {
    public $key;
    public $order;
    public $value;

    public function __construct($key, $order, $value) {
        $this->key = $key;
        $this->order = $order;
        $this->value = $value;
    }
}

class CallerInfo {
    public $callerInfoTemplate = "'{{userID}}' as '{{role}}'. '{{others}}'";
    public $userID;
    public $role;
    public $others = [];

    public function __construct($userID, $role, $others) {
        $this->userID = $userID;
        $this->role = $role;
        $this->others = $others;
    }

    public function string() {
        return StringHelper::renderTemplate($this->callerInfoTemplate, [
            'userID' => $this->userID,
            'role' => $this->role,
            'others' => CommonHelper::mapString($this->others)
        ]);
    }
}

class Message {
    public $messageTemplate = "'{{activity}}' on '{{objectPerformedOn}}' with result '{{resultOfActivity}}' with error '{{errorCode}}:{{errorMessage}}'. '{{shortDescription}}'";
    public $activity;
    public $objectPerformedOn;
    public $resultOfActivity;
    public $errorCode;
    public $errorMessage;
    public $shortDescription;

    public function __construct($activity, $objectPerformedOn, $resultOfActivity, $errorCode, $errorMessage, $shortDescription) {
        $this->activity = $activity;
        $this->objectPerformedOn = $objectPerformedOn;
        $this->resultOfActivity = $resultOfActivity;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
        $this->shortDescription = $shortDescription;
    }

    public function string() {
        return StringHelper::renderTemplate($this->messageTemplate, [
            'activity' => $this->activity,
            'objectPerformedOn' => $this->objectPerformedOn,
            'resultOfActivity' => $this->resultOfActivity,
            'errorCode' => $this->errorCode,
            'errorMessage' => $this->errorMessage,
            'shortDescription' => $this->shortDescription,
        ]);
    }
}