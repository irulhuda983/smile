<?php
header('Content-Type: application/json');
include('includes/connsql.php');
$wscom = new SoapClient(WSCOM, array("exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));

$username = $_SESSION['USER'];
/*
$sql = "SELECT
    A.KODE_FUNGSI as KODE, (INISIAL_FUNGSI||' - '||NAMA_FUNGSI) as NAMA, NAMA_FUNGSI as DETAIL
FROM
    SC.SC_USER_FUNGSI A, SC.SC_FUNGSI B
WHERE
    A.KODE_USER='".$username."'
    AND A.KODE_FUNGSI=B.KODE_FUNGSI
ORDER BY B.NAMA_FUNGSI ASC";
*/
//$_SESSION['KDKANTOR']
$con1		= $wscom->getListUserFungsi(array('chId' => $chId, 'kodeUser' => $username, 'kodeKantor' => ''));
$getData 	= get_object_vars($con1);
if($getData["return"]->ret == 0){
	//echo count($getData["return"]->userFungsiObj);
	for($i=0; $i<count($getData["return"]->userFungsiObj); $i++){
		//$show[] 	= $getData["return"]->userFungsiObj;
	}
}

//$show[] 	= $getData["return"]->userFungsiObj;

$data = array(
    'myrole' => $getData["return"]->userFungsiObj
);
echo json_encode($data);
?>