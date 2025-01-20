<?php
// session_start();
if (session_status() == PHP_SESSION_NONE) {
	session_start();
 }
include('includes/conf_global.php');
include('includes/connsql.php');
include('includes/class_database.php');
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

// $wsnotifikasi = new SoapClient(WSNOTIFIKASI, array("exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));
if(isset($_REQUEST['role'])){
	$role = explode('|',$_REQUEST['role']);
	$regrole = $role[0];
	$kdkantorrole = $role[1];
	$rolename = $_REQUEST["rolename"];
	
} else {
	$regrole = '';
}

$sql = "
select 	case when count(1) > 0 then 'Y' else 'T' end is_exists 
from 		ms.sc_user_fungsi 
where 	kode_user = :KODE_USER 
				and kode_fungsi = :regrole 
				and kode_kantor = :kdkantorrole
				and status_fungsi = 'Y' ";

$proc = $DB->parse($sql);
oci_bind_by_name($proc, ':KODE_USER', $_SESSION["USER"] );
oci_bind_by_name($proc, ':regrole', $regrole );
oci_bind_by_name($proc, ':kdkantorrole', $kdkantorrole );

$DB->execute();
$row = $DB->nextrow();
$ls_is_exists = $row['IS_EXISTS'];

function encrypt_decrypt($action, $string)
{
    /* =================================================
     * ENCRYPTION-DECRYPTION
     * =================================================
     * ENCRYPTION: encrypt_decrypt('encrypt', $string);
     * DECRYPTION: encrypt_decrypt('decrypt', $string) ;
     */
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = $_SESSION["USER"];
    $secret_iv = 'WS-SERVICE-VALUE';
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    } else {
        if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
    }
    return $output;
}

if($ls_is_exists == "Y" && ($regrole != '') && (is_numeric($regrole))){
    
	// $con1 		=  $wsnotifikasi->execute(array('RequestInfo'=>
	// 				array('RequestID'=>$chId,
	// 					  'RequestSource'=>$chId,
	// 					  'RequestDate'=>date('Y-m-d'),
	// 					  'RequestUser'=>$_SESSION["USER"]),
	// 					  'Input' => array('KdFungsi'=>$regrole, 'KdUser'=>$_SESSION["USER"])));
	// $getData 	= get_object_vars($con1);
	// //print_r($getData);
	// //echo count($getData['Output']->ListNotifikasi->Notifikasi);
	// if(count($getData['Output']->ListNotifikasi->Notifikasi) > 0){
	// 	if(count($getData['Output']->ListNotifikasi->Notifikasi) == 1){
	// 		$total = $getData['Output']->ListNotifikasi->Notifikasi->TotalNotifikasi;
	// 		$url = $getData['Output']->ListNotifikasi->Notifikasi->UrlNotifikasi;
	// 	} else {
	// 		for ($i = 0; $i < count($getData['Output']->ListNotifikasi->Notifikasi); $i++) {	
	// 			$total = $getData['Output']->ListNotifikasi->Notifikasi[$i]->TotalNotifikasi;
	// 			$url = $getData['Output']->ListNotifikasi->Notifikasi[$i]->UrlNotifikasi;
	// 		}
	// 	}
	// } else {
	// 	$total = $getData['Output']->ListNotifikasi->Notifikasi->TotalNotifikasi;
	// 	$url = $getData['Output']->ListNotifikasi->Notifikasi->UrlNotifikasi;
	// }
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
	//$sql = "SELECT MAX(TAHUN) TAHUN FROM SIJSTK.BG_TAHUN_ANGGARAN WHERE STATUS = 'T' "; -- updates 23112017, tutup sementara
        $sql = "SELECT TO_CHAR(SYSDATE,'yyyy') AS TAHUN FROM DUAL ";
	$DB->parse($sql);
	$DB->execute();
	$row = $DB->nextrow();
	$_SESSION['x_tahun'] =  $row['TAHUN'];
	//echo json_encode($row);
	$sql = "SELECT NAMA_KANTOR FROM SIJSTK.MS_KANTOR WHERE KODE_KANTOR = '".$kdkantorrole."'";//AND TAHUN = '".$row['TAHUN']."' ";
	$DB->parse($sql);
	$DB->execute();
	$row = $DB->nextrow();
	$_SESSION['x_nama_kantor'] = $row['NAMA_KANTOR'];
	//end of [register session untuk modul anggaran]
	$encrypt_regrole = encrypt_decrypt('encrypt', $regrole."-".$_SESSION['USER']."-".$_REQUEST['role']);
	// echo '{"success": true, "rolenum":"'.$encrypt_regrole.'", "notiftotal":"'.ExtendedFunction::count($getData['Output']->ListNotifikasi->Notifikasi).'", "notifurl":"'.$url.'"}';
	echo '{"success": true, "rolenum":"'.$encrypt_regrole.'", "notiftotal":"", "notifurl":""}';
	// echo '{"success": true, "rolenum":"'.$encrypt_regrole.'", "notiftotal":"", "notifurl":"'.$url.'"}';
} else {
	echo '{"success": false, "errors": "Tidak ada role yang dipilih!" }';
}
?>
