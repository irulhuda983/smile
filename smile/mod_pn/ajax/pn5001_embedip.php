<?php

require_once "../../includes/conf_global.php";

$get_param = $_GET['data'];
$file = file_get_contents($wsIpStorage.base64_decode($get_param));

header("Content-type:application/pdf");
echo $file;
?>