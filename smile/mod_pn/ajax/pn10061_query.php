<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE		= $_POST["TYPE"];
$KEYWORD 	= $_POST["KEYWORD"];
$ls_user_login = $_SESSION["USER"];
$gs_kantor_aktif = $_SESSION['gs_kantor_aktif'];
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;

if($TYPE!=''){
	$sql = "select 
			* 
			from
			(
			  	select 
				(b.jenis_formula || b.kode_sebab_klaim || b.kode_segmen || b.kode_kelompok_peserta || b.kode_jenis_kasus ||  b.no_urut) kode_data,
				b.jenis_formula,
				b.kode_sebab_klaim,
				(select a.nama_sebab_klaim from sijstk.pn_kode_sebab_klaim a where a.kode_sebab_klaim = b.kode_sebab_klaim and rownum=1) nama_sebab_klaim,
				b.kode_segmen,
				b.kode_kelompok_peserta,
				(select a.nama_kelompok_peserta from sijstk.kn_kode_kelompok_peserta a where a.kode_kelompok_peserta = b.kode_kelompok_peserta) nama_kelompok_peserta,
				b.kode_jenis_kasus,
				b.no_urut,
				b.kode_parameter1,
				(select a.nama_parameter from sijstk.pn_kode_parameter a where a.kode_parameter = b.kode_parameter1 and rownum=1) nama_parameter1,
				b.kode_parameter2,
				(select a.nama_parameter from sijstk.pn_kode_parameter a where a.kode_parameter = b.kode_parameter2 and rownum=1) nama_parameter2,
				b.kode_parameter3,
				(select a.nama_parameter from sijstk.pn_kode_parameter a where a.kode_parameter = b.kode_parameter3 and rownum=1) nama_parameter3,
				replace(replace(replace(replace(replace(b.keterangan,'.',''),'-',''),'/',''),chr(10),''),chr(13),'') keterangan,
				b.pesan_tidaklayak,
				b.status_nonaktif
				from sijstk.pn_kode_formula b
			)
			where ";				
						
	if($KEYWORD != '') {
		if (preg_match("/%/i", $KEYWORD)) {			
			$sql .= ' '.$TYPE . " like '".$KEYWORD."'";
		} else {
			$sql .= ' '.$TYPE . "1 like '%".$KEYWORD."%' ";
			$sql .= ' or '.$TYPE . "2 like '%".$KEYWORD."%' ";
			$sql .= ' or '.$TYPE . "3 like '%".$KEYWORD."%' ";
		};
		$sql .= " and ";
	}
	$sql .= 'rownum <= 500';
	
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
} 
?>