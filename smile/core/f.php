<?php
session_start();
if($_SESSION["STATUS"] != "LOGIN"){
	echo "<script>window.location='/core/login.bpjs?error=Silahkan Login ulang';</script>";
}
include('includes/connsql.php');
require_once "includes/class_database.php";
?>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<?PHP
	if(isset($_REQUEST["kodeFungsi"])){
		$kode_fungsi  		= explode('|', $_REQUEST["kodeFungsi"]);
		$kode_menu  		= $_REQUEST["kodeMenu"];
		$kode_menu			= str_replace('FM-','', $kode_menu);
		if (strpos($kode_menu,'http') !== false) {
			$DB = new Database($dbuser,$dbpass,$dbname); //connect to db
			$sql = "select RAWTOHEX(hr.p_hr_crypt_exim.fn_encrypt('".$_SESSION["USER"]."')) AS ENCRIPT1,TO_CHAR (SYSDATE, 'MMDDHHMI') AS ENCRIPT2 from dual";				
			$DB->parse($sql);
			$DB->execute();
			$data = $DB->nextrow();
			//print_r($data);
			/*http://172.28.201.109/kpi.bsc/logkpi/to/|kdkantor|/|kdrole|/|encript1|/|encript2|*/
			$kode_menu			= str_replace('|kdkantor|',$_SESSION['KDKANTOR'], $kode_menu);
			$kode_menu			= str_replace('|kdrole|',$kode_fungsi[0], $kode_menu);
			$kode_menu			= str_replace('|encript1|',$data["ENCRIPT1"], $kode_menu); 
			$kode_menu			= str_replace('|encript2|',$data["ENCRIPT2"], $kode_menu);
			//echo $kode_menu; 
			echo "<script>window.location='".$kode_menu."';</script>";
		} else {
			$link 				= $kode_menu;
			echo '<iframe src="'.$coreform.''.$link.'" height="100%" width="100%" frameborder="0"  style="border:0; height:100%; width:100%;"></iframe>';	
		}
		
	}
?>