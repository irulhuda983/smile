<?php
session_start();
require_once "../../includes/conf_global.php";

// Ambil session login, bila masih aktif lanjutkan, bila tidak arahkan ke hal login
if($_SESSION["STATUS"] != "LOGIN"){
	echo "<script>window.location='index.php?error=Silahkan Login ulang';</script>";
}

// cek apakah user session sama dg param user yg dikirim, bila tidak arahkan ke hal login
$username = $_SESSION['USER'];
$u = base64_decode($_GET["u"]);

if($u != $username){
	echo "<script>window.location='index.php?error=Silahkan Login ulang';</script>";
}

$path = base64_decode($_GET["p"]);
$namafile = base64_decode($_GET["f"]);
$url_path = $wsIpStorage.$path;

// Ambil content
$content = file_get_contents($url_path);
$pattern = "/^content-type\s*:\s*(.*)$/i";
if (($header = array_values(preg_grep($pattern, $http_response_header))) && (preg_match($pattern, $header[0], $match) !== false)) $content_type = $match[1];

header("Content-Type: " . $content_type);
header("Content-Length: " . strlen($content));
header("Content-Disposition: inline; filename=" . $namafile);
echo $content;
?>
