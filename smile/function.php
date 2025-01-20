<?
switch ($dbtype) {
	case "oracle":
		$conn = oci_connect($dbuser, $dbpass, $dbtest) or die ("error connect");

		if (!$conn) {
			$e = oci_error();
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		
		function db_query($conn, $sql){
			$stid = oci_parse($conn, $sql);
			
			if(oci_execute($stid)){
				return $stid;
			}
			else
			{
               return false; 	
			}
			
		}
		
		function db_fetch_array($stid){
			return oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
		}
		
		function db_fetch_assoc($stid){
			return oci_fetch_assoc($stid);
		}
		function db_num_rows($stid){
			return oci_num_rows($stid);
		}
		function db_real_escape_string($string){
			return mysql_real_escape_string($string);
		}
	break;
	
	case "mysql":
			$conn = mysql_connect($dbhost,$dbuser,$dbpass);
			if (!$conn) {
				die('Could not connect: ' . mysql_error());
			}
			@mysql_select_db($dbname,$conn) or die("<center><h1>Unable to Select Database</h1></center>");
			
			function db_query($conn, $sql){
				return mysql_query($sql);
				}	
			function db_fetch_array($stid){
				return mysql_fetch_array($stid);
			}
			
			function db_fetch_assoc($stid){
				return mysql_fetch_assoc($stid);
			}
			function db_num_rows($stid){
				return mysql_num_rows($stid);
			}
			function db_real_escape_string($string){
				return mysql_real_escape_string($string);
			}
	break;
}

ini_set('display_errors', $display_errors); 
ini_set('log_errors', $log_errors); 
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
//error_reporting(E_ALL);

	function html($data){
		$code = array('&lt;','&gt;','&quot;');
		$replace = array('<','>','"');
		$data = str_replace($code,$replace, $data);
		$data = str_replace('&amp;nbsp;','&nbsp;', $data);
		return $data;
	}
	function html2($data){
		if($data != ""){
			$code = array('&lt;','&gt;','&quot;');
			$replace = array('<','>','"');
			$data = str_replace($code,$replace, $data);
			$data = str_replace('&amp;nbsp;','&nbsp;', $data);
			return $data;
		} else {			
			return '<ol><li></li></ol>';
		}
	}
	function vhtml($data){
		if($data != '<ol><li></li></ol>'){
			$code = array('<','>','"');
			$replace = array('&lt;','&gt;','&quot;');
			$data = str_replace($code,$replace, $data);
			//$data = iconv("UTF-8","ISO-8859-1",$data);
			return $data;
		} else {
			$data = '';
			return $data;
		}
	}
	?>
    <?PHP
if($developer != "http://www.jamsostek.co.id"){
	header("Location: login.php");
	exit();
}
function upstext($text){
	$text = ucwords($text); 
	$text = ucwords(strtolower($text));
	return $text;
}
function upstext2($text){
	$text = ucwords($text); 
	$text = ucwords(strtolower($text));
	$text = str_replace('_',' ', $text);
	return $text;
}
function downstext($text){
	$text = ucfirst($text); 
	$text = ucfirst(strtolower($text));
	return $text;
}
function dd_mm_yy($date){
	if($date != ''){
	$dt = explode(' ', $date);
	$dh = explode('-', $dt[0]);
	return $dh[2] . '/' . $dh[1] . '/' . $dh[0];
	}
}

function mm_yy2($date){
	if($date != ''){
	$dt = explode(' ', $date);
	$dh = explode('/', $dt[0]);
	return $dh[1] . '/' . $dh[2];
	}
}
function mm_yy3($date){
	if($date != ''){
	$dt = explode('-', $date);
	switch ($dt[1]){
		case 'JAN':
			$dt[1] = '01';
			break;
		case 'FEB':
			$dt[1] = '02';
			break;
		case 'MAR':
			$dt[1] = '03';
			break;
		case 'APR':
			$dt[1] = '04';
			break;
		case 'MAY':
			$dt[1] = '05';
			break;
		case 'JUN':
			$dt[1] = '06';
			break;
		case 'JUL':
			$dt[1] = '07';
			break;
		case 'AUG':
			$dt[1] = '08';
			break;
		case 'SEP':
			$dt[1] = '09';
			break;
		case 'OCT':
			$dt[1] = '10';
			break;
		case 'NOV':
			$dt[1] = '11';
			break;
		case 'DEC':
			$dt[1] = '12';
			break;
	}
	return $dt[0] . '/20' . $dt[1];
	}
}
function dd_mm_yy2($date){
	if($date != ''){
	$dt = explode('-', $date);
	switch ($dt[1]){
		case 'JAN':
			$dt[1] = '01';
			break;
		case 'FEB':
			$dt[1] = '02';
			break;
		case 'MAR':
			$dt[1] = '03';
			break;
		case 'APR':
			$dt[1] = '04';
			break;
		case 'MAY':
			$dt[1] = '05';
			break;
		case 'JUN':
			$dt[1] = '06';
			break;
		case 'JUL':
			$dt[1] = '07';
			break;
		case 'AUG':
			$dt[1] = '08';
			break;
		case 'SEP':
			$dt[1] = '09';
			break;
		case 'OCT':
			$dt[1] = '10';
			break;
		case 'NOV':
			$dt[1] = '11';
			break;
		case 'DEC':
			$dt[1] = '12';
			break;
	}
	return $dt[0] . '/' . $dt[1] . '/20' . $dt[2];
	}
}
function mm_yy($date){
	if($date != ''){
	$dt = explode(' ', $date);
	$dh = explode('-', $dt[0]);
	return $dh[1] . '/' . $dh[0];
	}
}
function mm_yy4($date){
	if($date != ''){
	$dt = explode(' ', $date);
	$dh = explode('-', $dt[0]);
	return $dh[0] . '/' . $dh[1];
	}
}

function maxid($Field, $Table){
	global $conn;
	$lsquery = "select max(".$Field.")as maximum from ".$Table."";
	//$lsquery = "SELECT ".$Field." FROM ".$Table." ORDER BY ".$Field." DESC";
	$qryProses = db_query($conn,$lsquery);
	$Num = db_fetch_array($qryProses);
	$Nomor = $Num["maximum"]+1;
	return $Nomor;
}
function clear($tulisan) {	
	if($tulisan != ""){
		$tulisan = strip_tags($tulisan);
		$tulisan = str_replace('<p>', '', $tulisan); 
		$tulisan = str_replace('</p>', '', $tulisan); 
		$tulisan = str_replace('<span>', '', $tulisan); 
		$tulisan = str_replace('</span>', '', $tulisan); 
		$tulisan = str_replace('<b>', '', $tulisan); 
		$tulisan = str_replace('</b>', '', $tulisan); 
		$tulisan = str_replace('<i>', '', $tulisan); 
		$tulisan = str_replace('</i>', '', $tulisan);
		$tulisan = str_replace('<strong>', '', $tulisan); 
		$tulisan = str_replace('</strong>', '', $tulisan); 
		$tulisan = preg_replace("/<img[^>]+\>/i", "", $tulisan); 
		return $tulisan;
	}
}
function json_encode_merge($name1, $name2, $array1, $array2){
	$show = '[';
	for($i=0; $i<count($array1); $i++){
		$show .= '{'.$name1.':'.$array1[$i].','.$name2.':"'.$array2[$i].'"},';	
	}
	$show .= ']';
	return $show;
}
function menu_header($nomor){
?>
	<div class="header">
    	<ul class="headermenu">
        	<li<?php if($nomor == 1){ echo ' class="current"'; }?>><a href="home.php"><span class="icon icon-flatscreen"></span>Home</a></li>
            <li<?php if($nomor == 2){ echo ' class="current"'; }?>><a href="kepesertaan.php"><span class="icon icon-pencil"></span>Kepesertaan</a></li>
            <li<?php if($nomor == 3){ echo ' class="current"'; }?>><a href="klaim.php"><span class="icon icon-pencil"></span>Klaim</a></li>
          	<li<?php if($nomor == 4){ echo ' class="current"'; }?>><a href="pelaporan.php"><span class="icon icon-chart"></span>Pelaporan</a></li>
        </ul>
        <div style="float:right; margin-right:20px;"><img src="images/jamsostek-top.png" />
    </div>
    </div>
<?PHP
}
function menu_left($nomor){
?>
<div class="vernav2 iconmenu">
    	<ul>
        	<li<?php if(($nomor == 1)|| ($nomor == 2) || ($nomor == 3)){ echo ' class="current"'; }?>><a href="#form" class="editor">Kepesertaan</a>
            	<span class="arrow"></span>
            	<ul id="form">
               		<li<?php if($nomor == 1){ echo ' class="current"'; }?>><a href="#kep1">Agenda Perusahaan</a></li>
                    <li<?php if($nomor == 2){ echo ' class="current"'; }?>><a href="#kep2">Perekaman Data</a>
                    <li<?php if($nomor == 3){ echo ' class="current"'; }?>><a href="#kep3">Penetapan Iuran</a></li>
                </ul>
            </li>
            <li<?php if(($nomor == 4)|| ($nomor == 5) || ($nomor == 6)){ echo ' class="current"'; }?>><a href="#pelaporan" class="gallery">Pelaporan</a>
            	<span class="arrow"></span>
            	<ul id="pelaporan">
               		<li<?php if($nomor == 4){ echo ' class="current"'; }?>><a href="#rpt.agenda.php">Tanda terima Agenda</a></li>
                    <li<?php if($nomor == 5){ echo ' class="current"'; }?>><a href="#rpt.frm1.php">Form 1</a></li>
                    <li<?php if($nomor == 6){ echo ' class="current"'; }?>><a href="#rpt.frm1a.php">Form 1a</a></li>
                    <li<?php if($nomor == 5){ echo ' class="current"'; }?>><a href="#rpt.datakeluarga.php">Data Keluarga</a></li>
                    <li<?php if($nomor == 6){ echo ' class="current"'; }?>><a href="#rpt.frm2a.php">Form 2a</a></li>
                    <li<?php if($nomor == 6){ echo ' class="current"'; }?>><a href="#rpt.frm2.php">Form 2</a></li>
                </ul>
            </li>
            <li<?php if(($nomor == 7)|| ($nomor == 8) || ($nomor == 9)){ echo ' class="current"'; }?>><a href="#setting" class="support">Setting</a>
            	<span class="arrow"></span>
            	<ul id="setting">
               		<li<?php if($nomor == 7){ echo ' class="current"'; }?>><a href="#f.parameter.php">Parameter</a></li>
                    <li<?php if($nomor == 8){ echo ' class="current"'; }?>><a href="#f.hakakses.php">Hak Akses</a></li>
                    <li<?php if($nomor == 9){ echo ' class="current"'; }?>><a href="#r.backup.php">Compact & Repair</a></li>
                </ul>
            </li>
        </ul>
        <a class="togglemenu"></a>
        <br /><br />
    </div>
<?PHP
}
function css(){
	echo '<link href="./assets/css/lib/bootstraps.css" rel="stylesheet" />
		  <link href="./assets/css/lib/bootstrap-responsive.css" rel="stylesheet" />
		  <link href="./assets/css/extension.css" rel="stylesheet" />
		  <link href="./assets/css/boo.css" rel="stylesheet" />
		  <link href="./assets/css/style.css" rel="stylesheet" />
		  <link href="./assets/css/fonts.css" rel="stylesheet" />
		  <link href="./assets/css/boo-coloring.css" rel="stylesheet" />
		  <link href="./assets/css/boo-utility.css" rel="stylesheet" />
		  <link rel="stylesheet" type="text/css" href="./js/datetime/jquery.datetimepicker.css"/>';
}

?>
<?
  function rgbcode($id){
    	return '#'.substr(md5($id), 0, 6);
	}
?>