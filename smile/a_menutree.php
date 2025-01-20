<?php

header('Content-Type: application/json');
include('includes/connsql.php');
include('includes/class_database.php');
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$gs_kantor_aktif = $_SESSION['gs_kantor_aktif'];

//$url = $wsIp.'/JSCore/ListMenu/';
$url = $wsIp.'/JSCoreSys/GetMenu/';

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

// set HTTP header
$headers = array(
	'Content-Type: application/json',
	'X-Forwarded-For: '.$ipfwd,
);
$decrypt_role = encrypt_decrypt('decrypt',$_REQUEST["role"]); 
$rolenum = explode("-",$decrypt_role)[0];
$exp_role = explode("|", explode("-",$decrypt_role)[2]);
$regrole = $exp_role[0];
$kdkantorrole = $exp_role[1];

if($gs_kantor_aktif != "")
{
	$fields = array(
		'chId' => $chId,
		'kodeUser' => $_SESSION["USER"],
		'kodeFungsi' => $rolenum
	);
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
if($ls_is_exists == "T") {
	echo '{"success": false, "errors": "Anda tidak memiliki privillage dalam mengakses role tersebut!" }';
	die;
}
//print_r($fields);

// Open connection
$ch = curl_init();

// Set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

// Execute post
$result = curl_exec($ch);

// Close connection
curl_close($ch);

$result = json_decode($result);
//print_r($result);
print_r(json_encode($result->data));
//echo 'test';

?>
