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

$ls_jenis_formula			= $_POST["jenis_formula"];
$ls_kode_sebab_klaim		= $_POST["kode_sebab_klaim"];
$ls_kode_segmen				= $_POST["kode_segmen"];
$ls_kode_kelompok_peserta	= $_POST["kode_kelompok_peserta"];
$ls_kode_jenis_kasus		= $_POST["kode_jenis_kasus"];
$ls_kode_parameter_satu		= $_POST["kode_parameter_satu"];
$ls_kode_parameter_operator	= urldecode($_POST["kode_parameter_operator"]);
$ls_kode_parameter_dua		= $_POST["kode_parameter_dua"];
$ls_pesan_tidak_layak		= $_POST["pesan_tidak_layak"];
$ls_no_urut					= $_POST["no_urut"];
$ls_status_nonaktif			= $_POST["status_nonaktif"];
$ls_keterangan				= $_POST["keterangan"];

if(($TYPE =='DEL') && ($DATAID != ''))
{
	$sql = "delete from sijstk.pn_kode_formula 
			where (jenis_formula || kode_sebab_klaim || kode_segmen || kode_kelompok_peserta || kode_jenis_kasus ||  no_urut) = '".$DATAID."'";
		
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
else if(($TYPE =='EDIT') && ($DATAID != ''))
{	
	$ls_tgl_nonaktif = "null";
	$ls_petugas_nonaktif = "";
	if($ls_status_nonaktif == 'Y')
	{
		$ls_tgl_nonaktif = 'sysdate';
		$ls_petugas_nonaktif = $ls_user_login;
	}
	
	$sql = "update sijstk.pn_kode_formula
			set kode_sebab_klaim 	= '".$ls_kode_sebab_klaim."',
			kode_segmen 			= '".$ls_kode_segmen."',
			kode_kelompok_peserta 	= '".$ls_kode_kelompok_peserta."',
			kode_jenis_kasus 		= '".$ls_kode_jenis_kasus."',
			no_urut 				= '".$ls_no_urut."',
			kode_parameter1 		= '".$ls_kode_parameter_satu."',
			kode_parameter2 		= '".$ls_kode_parameter_operator."',
			kode_parameter3 		= '".$ls_kode_parameter_dua."',
			pesan_tidaklayak 		= '".$ls_pesan_tidak_layak."',
			keterangan 				= '".$ls_keterangan."',
			status_nonaktif 		= '".$ls_status_nonaktif."',
			tgl_nonaktif			= ".$ls_tgl_nonaktif.",
			petugas_nonaktif		= '".$ls_petugas_nonaktif."',
			tgl_ubah 				= sysdate,
			petugas_ubah			= '".$ls_user_login."'
			where (jenis_formula || kode_sebab_klaim || kode_segmen || kode_kelompok_peserta || kode_jenis_kasus ||  no_urut) = '".$DATAID."'";
	
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, Data berhasil diupdate!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal diupdate!"}';
	}
} 
else if(($TYPE =='NEW') && ($ls_no_urut != '') && ($ls_jenis_formula != '') && ($ls_kode_sebab_klaim != '') && ($ls_kode_segmen != '')
		 && ($ls_kode_kelompok_peserta != '') && ($ls_kode_jenis_kasus != '') && ($ls_kode_parameter_satu != '') && ($ls_kode_parameter_operator != '') 
		 && ($ls_kode_parameter_dua != ''))
{
	$sql = "insert into sijstk.pn_kode_formula
			(
				jenis_formula,
				kode_sebab_klaim,
				kode_segmen,
				kode_kelompok_peserta,
				kode_jenis_kasus,
				no_urut,
				kode_parameter1,
				kode_parameter2,
				kode_parameter3,
				keterangan,
				pesan_tidaklayak,
				status_nonaktif,
				tgl_rekam,
				petugas_rekam
			)
			values 
			(
				'".$ls_jenis_formula."',
				'".$ls_kode_sebab_klaim."',
				'".$ls_kode_segmen."',
				'".$ls_kode_kelompok_peserta."',
				'".$ls_kode_jenis_kasus."',
				'".$ls_no_urut."',
				'".$ls_kode_parameter_satu."',
				'".$ls_kode_parameter_operator."',
				'".$ls_kode_parameter_dua."',
				'".$ls_keterangan."',
				'".$ls_pesan_tidak_layak."',
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