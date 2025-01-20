<?PHP
session_start();
include('includes/connsql.php');
include('../core123/includes/conf_global.php');
include('../core123/includes/class_database.php');
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$wsnotifikasi = new SoapClient(WSNOTIFIKASI, array("exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));
if(isset($_REQUEST['role'])){
	$role = explode('|',$_REQUEST['role']);
	$regrole = $role[0];
	$kdkantorrole = $role[1];
	$rolename = $_REQUEST["rolename"];
	
} else {
	$regrole = '';
}
if(($regrole != '') && (is_numeric($regrole))){
	$con1 		=  $wsnotifikasi->execute(array('RequestInfo'=>
					array('RequestID'=>$chId,
						  'RequestSource'=>$chId,
						  'RequestDate'=>date('Y-m-d'),
						  'RequestUser'=>$_SESSION["USER"]),
						  'Input' => array('KdFungsi'=>$regrole, 'KdUser'=>$_SESSION["USER"])));
	$getData 	= get_object_vars($con1);
	//print_r($getData);
	//echo count($getData['Output']->ListNotifikasi->Notifikasi);
	if(count($getData['Output']->ListNotifikasi->Notifikasi) > 0){
		if(count($getData['Output']->ListNotifikasi->Notifikasi) == 1){
			$total = $getData['Output']->ListNotifikasi->Notifikasi->TotalNotifikasi;
			$url = $getData['Output']->ListNotifikasi->Notifikasi->UrlNotifikasi;
		} else {
			for ($i = 0; $i < count($getData['Output']->ListNotifikasi->Notifikasi); $i++) {	
				$total = $getData['Output']->ListNotifikasi->Notifikasi[$i]->TotalNotifikasi;
				$url = $getData['Output']->ListNotifikasi->Notifikasi[$i]->UrlNotifikasi;
			}
		}
	} else {
		$total = $getData['Output']->ListNotifikasi->Notifikasi->TotalNotifikasi;
		$url = $getData['Output']->ListNotifikasi->Notifikasi->UrlNotifikasi;
	}
	$_SESSION['waktulogin'] 	= date('d-m-Y');
	$_SESSION['fullname']			= $_SESSION['NAMA'];
	$_SESSION['username']			= $_SESSION["USER"];
	$_SESSION['regrole']			= $regrole;
	$_SESSION['kdkantorrole']	= $kdkantorrole;
	$_SESSION['gs_kantor_aktif']	= $kdkantorrole;
	$_SESSION['namarole']			= $rolename;
	$_SESSION['gs_dashboard']	= '';
	$_SESSION['ipaddress']		= f_ipCheck();
	$_SESSION['sessid'] 			= session_id();	
	
	
	//added by Hotdin Demak [register session untuk modul anggaran]
	$_SESSION['x_kode_kantor']		= $kdkantorrole;
	$sql = "SELECT MAX(TAHUN) TAHUN FROM CORE_SIJSTK.BG_TAHUN_ANGGARAN WHERE STATUS = 'T' ";
	$DB->parse($sql);
	$DB->execute();
	$row = $DB->nextrow();
	$_SESSION['x_tahun'] =  $row['TAHUN'];
	//echo json_encode($row);
	$sql = "SELECT NAMA_KANTOR FROM CORE_SIJSTK.BG_KANTOR WHERE KODE_KANTOR = '".$kdkantorrole."' AND TAHUN = '".$row['TAHUN']."' ";
	$DB->parse($sql);
	$DB->execute();
	$row = $DB->nextrow();
	$_SESSION['x_nama_kantor'] = $row['NAMA_KANTOR'];
	//end of [register session untuk modul anggaran]
	
  //ambil atribut role investasi, 22/08/2016 -----------------------------------
  $sql = "select inv_id, prod_id, dashboard from core_sijstk.sc_fungsi where kode_fungsi='".$_SESSION['regrole']."'";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $_SESSION['gs_dashboard'] = $row["DASHBOARD"];
  $_SESSION['gs_inv_id'] = $row["INV_ID"];
  $_SESSION['gs_prod_id'] = $row["PROD_ID"];
				
	echo '{"success": true, "rolenum":"'.$regrole.'", "notiftotal":"'.count($getData['Output']->ListNotifikasi->Notifikasi).'", "notifurl":"'.$url.'"}';
} else {
	echo '{"success": false, "errors": "Tidak ada role yang dipilih!" }';
}
?>