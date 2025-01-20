<?PHP
session_start();
if($_REQUEST["name"] != "login"){
	if($_SESSION["STATUS"] != "LOGIN"){
		echo "<script>window.location='".$host."/login.bpjs?error=Silahkan Login ulang';</script>";
	}
}
//=====================
//ACTION DATA
//=====================
if($_REQUEST["name"] == "penerimaan_iuran"){
	include('r_penerimaan_iuran.php');
	
} else if($_REQUEST["name"] == "kdkantorlist"){
	include('a_kdkantorlist.php');
	
} else if($_REQUEST["name"] == "setrule"){
	include('a_setrule.php');
	
} else {
	echo '<h1>Not Found</h1>
	The requested URL was not found on this server.';
}
?>
