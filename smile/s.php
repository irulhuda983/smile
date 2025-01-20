<?php
session_start();
include('includes/connsql.php');
require_once "includes/class_database.php";
if($_SESSION["STATUS"] != "LOGIN"){
	echo "<script>window.location='".$host."/login.bpjs';</script>";
}


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
	if(isset($_REQUEST["kodeMenu"])){
		$kode_role  		= $_SESSION['regrole'];
		$kode_menu  		= strtoupper($_REQUEST["kodeMenu"]);
		if ($kode_menu!="") {
			$DB = new Database($dbuser,$dbpass,$dbname); //connect to db
			$sql ="select a.kode_menu, a.nama_menu,a.path 
  					from sijstk.sc_menu a, sijstk.sc_fungsi_menu b
  					where a.kode_menu = b.kode_menu
  					and b.kode_fungsi='".$kode_role."'
  					and a.nama_file='".$kode_menu."'
  					and nvl(a.aktif,'T')='Y'";
			$DB->parse($sql);
			$DB->execute();
			$data = $DB->nextrow();
			if($data['PATH'] != ''){
				$link 				= $data['PATH'].'?mid='.$data['KODE_MENU'];
				$nama_menu			= $data['NAMA_MENU'];
				echo '<script>window.parent.Ext.getCmp(\''.$_REQUEST["kodeMenu"].'\').setTitle(\''.$nama_menu.'\'); </script>';
				echo '<iframe src="'.$coreform.''.$link.'" height="100%" width="100%" frameborder="0" id="form" name="form" style="border:0; height:100%; width:100%;"></iframe>';
			} else {
				echo '<center><h3>Menu tidak ditemukan!</h3></center>';
			}
		} else {
			echo '<center><h3>Menu tidak ditemukan!</h3></center>';	
		}
		
	}
?>