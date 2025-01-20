<?
session_start();
$pagetitle = "CORE SIJSTK";
include "./includes/conf_global.php"; 
include "./includes/class_database.php"; 
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
include "./mod_sc/sc_login2.php";
?>