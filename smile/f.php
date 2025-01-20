<?php
// session_start();
if (session_status() == PHP_SESSION_NONE) {
	session_start();
 }
include('includes/connsql.php');
require_once "includes/class_database.php";
if($_SESSION["STATUS"] != "LOGIN"){
	echo "<script>window.location='".$host."/login.bpjs';</script>";
}


$ip_address = "";
if (!empty($_SERVER['HTTP_CLIENT_IP']))   
  {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
  }
//whether ip is from proxy
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
  {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
  }
//whether ip is from remote address
else
  {
    $ip_address = $_SERVER['REMOTE_ADDR'];
  }

//var_dump($_REQUEST);


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
		$menu  				= explode('?', $_REQUEST["kodeMenu"]);
		$kode_menu  		= $_REQUEST["kodeMenu"];
		$kode_menu			= str_replace('FM-','', $kode_menu);


		$headers = array(
			'Content-Type' => 'application/json',
			'X-Forwarded-For' => $ip_address,
		);

		$fields = array(
			'chId' => $chId,
			'reqId' => $_SESSION["USER"].time(),
			'user'=>$_SESSION["USER"],
			'sessionId'=>$_SESSION['sessid'],
			'ipAddr1'=>$_SESSION['ipaddress'],
			'role'=>$_REQUEST["kodeFungsi"],
			"menu"=>$menu[0]
		);

		$ch = curl_init();
		//Set the url, number of POST vars, POST data
		

		curl_setopt($ch, CURLOPT_URL, $wsIp.'/JSLOG/InsertMenuLog');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		
		// Execute post
		$resultWs = curl_exec($ch);
		$resultArry = json_decode($resultWs);
		curl_close($ch);

		if (strpos($kode_menu,'http') !== false) {
			$DB = new Database($dbuser,$dbpass,$dbname); //connect to db
			/*$sql = "select RAWTOHEX(hr.p_hr_crypt_exim.fn_encrypt('".$_SESSION["USER"]."')) AS ENCRIPT1,TO_CHAR (SYSDATE, 'MMDDHHMI') AS ENCRIPT2 from dual";	*/		
			$sql = "select RAWTOHEX(KPI.F_ENCRYPT_BGJ('".$_SESSION["USER"]."')) AS ENCRIPT1,TO_CHAR (SYSDATE, 'MMDDHHMI') AS ENCRIPT2 from dual";		
			$DB->parse($sql);
			$DB->execute();
			$data = $DB->nextrow();

			
			//print_r($data);
			/*http://172.28.201.109/kpi.bsc/logkpi/to/|kdkantor|/|kdrole|/|encript1|/|encript2|*/
			$kode_menu			= str_replace('|kdkantor|',$kode_fungsi[1], $kode_menu);
			$kode_menu			= str_replace('|kdrole|',$kode_fungsi[0], $kode_menu);
			$kode_menu			= str_replace('|encript1|',$data["ENCRIPT1"], $kode_menu); 
			$kode_menu			= str_replace('|encript2|',$data["ENCRIPT2"], $kode_menu);
			//echo $kode_menu; 
			echo "<script>window.location='".$kode_menu."';</script>";
		} else {

			$link 				= $kode_menu;
			/*var_dump($link);
			die;*/
			echo '<iframe src="'.$coreform.''.$link.'" height="100%" width="100%" frameborder="0" id="form" name="form" style="border:0; height:100%; width:100%;"></iframe>';	
		}
		
	}
?>