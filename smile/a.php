<?PHP
// session_start();
if (session_status() == PHP_SESSION_NONE) {
	session_start();
 }
if($_REQUEST["name"] != "login"){
	if($_SESSION["STATUS"] != "LOGIN"){
		echo "<script>window.location='/ncs/login.bpjs?error=Silahkan Login ulang';</script>";
	}
}
//=====================
//ACTION DATA
//=====================
if($_REQUEST["name"] == "login"){
	include('a_login.php');
	
} else if($_REQUEST["name"] == "pilihrole"){
	include('a_pilihrole.php');
	
} else if($_REQUEST["name"] == "kdkantorlist"){
	include('a_kdkantorlist.php');
	
} else if($_REQUEST["name"] == "setrule"){
	include('a_setrule.php');
	
} else if($_REQUEST["name"] == "listmppa"){
	include('a_listmppa.php');

} else if($_REQUEST["name"] == "menutree"){
	include('a_menutree.php');
		
} else if($_REQUEST["name"] == "addfav"){
	include('a_addfav.php');
		
} else {
	echo '<h1>Not Found</h1>
	The requested URL was not found on this server.';
}
?>
