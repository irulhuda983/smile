<?php
session_start();
$user["agent"] 	= $_SERVER['HTTP_USER_AGENT'];
$user["ip"] 	= getenv('HTTP_X_FORWARDED_FOR');
if ($user["ip"]=='') $user["ip"] = getenv('REMOTE_ADDR');
ini_set("session.cookie_lifetime","3600");
$cache_expire = session_cache_expire();
$sestime = ini_get("session.gc_maxlifetime")/60;
echo $sestime;
?>