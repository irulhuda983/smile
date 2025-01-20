<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

//$TYPE		= $_POST["TYPE"];
//$KEYWORD 	= $_POST["KEYWORD"];
$ls_user_login = $_SESSION["USER"];
$gs_kantor_aktif = $_SESSION['gs_kantor_aktif'];
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;

$ls_kode_promotif = $_GET['kode_promotif'];

//if($TYPE!=''){
	$sql = "select kode_promotif,kode_tk,nama_tk,kpj,nomor_identitas,tgl_lahir from sijstk.pn_promotif_detil 
			where kode_promotif = '".$ls_kode_promotif."'";				
						
	if($KEYWORD != '') {
		if (preg_match("/%/i", $KEYWORD)) {			
			$sql .= ' '.$TYPE . " like '".$KEYWORD."'";
		} else {
			$sql .= ' '.$TYPE . " like '%".$KEYWORD."%' ";
		};
		$sql .= " and ";
	}
	$sql .= ' and rownum <= 500';
echo $sql;
	$DB->parse($sql);
	$DB->execute(); 
	$i = 0;
	while($data = $DB->nextrow())
	{
		$jsondata .= json_encode($data);
		$jsondata .= ',';
		$i++;
	}
	$jsondataStart = '{"ret":0,"count":'.$i.',"data":[';
	$jsondata .= ']}';
	$jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
	$jsondata = str_replace('},]}', '}]}', $jsondata);
	print_r($jsondata);
//} 
?>