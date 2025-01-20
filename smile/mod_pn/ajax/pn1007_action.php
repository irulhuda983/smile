<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
//print_r($_POST);

$TYPE		= $_POST["TYPE"];
$DATAID		= $_POST["DATAID"];

$ls_user_login = $_SESSION["USER"];
$gs_kantor_aktif = $_SESSION['gs_kantor_aktif'];
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;

$ls_kode_tipe_penerima	= $_POST["kode_tipe_penerima"];
$ls_nama_tipe_penerima	= $_POST["nama_tipe_penerima"];
$ls_status_nonaktif	= $_POST["status_nonaktif"];
$ls_no_urut	= $_POST["no_urut"];

if(($TYPE =='DEL') && ($DATAID != ''))
{
	$sql = "delete from sijstk.pn_kode_tipe_penerima 
			where (kode_tipe_penerima || no_urut) = '".$DATAID."'";
		
	$DB->parse($sql);
	if($DB->execute()){
		echo '{"ret":0,"msg":"Proses Sukses, Data berhasil dihapus!"}';
	}
} 
else if(($TYPE =='VIEW') && ($DATAID != ''))
{
	$sql = "select 
			* 
			from
			(
			  select 
			  (kode_tipe_penerima || no_urut) kode_data,
			  kode_tipe_penerima, 
			  nama_tipe_penerima, 
			  no_urut,
			  status_nonaktif 
			  from sijstk.pn_kode_tipe_penerima 
			)
			where kode_data = '".$DATAID."'";		
	
	$DB->parse($sql);
	$DB->execute(); 
	$i = 0;
	$data = $DB->nextrow();
	$jsondata = json_encode($data);
	$jsondataStart = '{"ret":0,"count":0,"data":[';
	$jsondata .= ']}';
	$jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
	$jsondata = str_replace('},]}', '}]}', $jsondata);
	print_r($jsondata);

} 
else if(($TYPE =='EDIT') && ($DATAID != '')
		&& ($ls_kode_tipe_penerima != '') && ($ls_nama_tipe_penerima != '') && ($ls_no_urut != ''))
{	
	$ls_tgl_nonaktif = "null";
	$ls_petugas_nonaktif = "";
	if($ls_status_nonaktif == 'Y')
	{
		$ls_tgl_nonaktif = 'sysdate';
		$ls_petugas_nonaktif = $ls_user_login;
	}

	$sql = "update sijstk.pn_kode_tipe_penerima
			set kode_tipe_penerima 		= '".$ls_kode_tipe_penerima."',
			nama_tipe_penerima 			= '".$ls_nama_tipe_penerima."',
			status_nonaktif 		= '".$ls_status_nonaktif."',
			tgl_nonaktif			= ".$ls_tgl_nonaktif.",
			petugas_nonaktif		= '".$ls_petugas_nonaktif."',
			tgl_ubah 				= sysdate,
			petugas_ubah			= '".$ls_user_login."'
			where (kode_tipe_penerima || no_urut) = '".$DATAID."'";
	
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, Data berhasil diupdate!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal diupdate!"}';
	}
} 
else if(($TYPE =='NEW') && ($ls_kode_tipe_penerima != '') && ($ls_nama_tipe_penerima != '') 
						&& ($ls_no_urut != ''))
{
	$sql = "insert into sijstk.pn_kode_tipe_penerima
			(
				kode_tipe_penerima,
				nama_tipe_penerima,
				no_urut,
				status_nonaktif,
				tgl_rekam,
				petugas_rekam
			)
			values 
			(
				'".$ls_kode_tipe_penerima."',
				'".$ls_nama_tipe_penerima."',
				'".$ls_no_urut."',
				'".$ls_status_nonaktif."',
				sysdate,
				'".$ls_user_login."'
			)";
	
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, Data berhasil ditambahkan!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal ditambahkan!"}';
	}
} 
?>