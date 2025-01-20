<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
//print_r($_POST);

$TYPE		= $_POST["TYPE"];
$DATAID		= $_POST["DATAID"];
$DATAID2	= $_POST["DATAID2"];

$ls_user_login = $_SESSION["USER"];
$gs_kantor_aktif = $_SESSION['gs_kantor_aktif'];
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;

$ls_kode_promotif				= $DATAID;
$ls_nama_kegiatan				= $_POST["nama_kegiatan"];
$ld_tgl_kegiatan				= date("d-m-Y", strtotime(str_replace("/","-", urldecode($_POST["tgl_kegiatan"]))));
$ls_kode_jenis_kegiatan			= $_POST["kode_jenis_kegiatan"];
$ls_kode_sub_jenis_kegiatan		= $_POST["kode_sub_jenis_kegiatan"];
$ls_latar_belakang_pengajuan	= $_POST["latar_belakang_pengajuan"];
$ls_jumlah_tk					= $_POST["jumlah_tk"];
$ls_jumlah_kasuskk				= $_POST["jumlah_kasuskk"];
$ls_jenis_usaha					= $_POST["jenis_usaha"];


if(($TYPE =='DEL') && ($DATAID != ''))
{
	$sql = "delete from sijstk.pn_promotif 
			where kode_promotif = '".$DATAID."'";
		
	$DB->parse($sql);
	if($DB->execute()){
		echo '{"ret":0,"msg":"Proses Sukses, Data berhasil dihapus!"}';
	}
} 
else if(($TYPE =='VIEW') && ($DATAID != ''))
{
	$sql = "select a.*,b.nama_kegiatan, c.nama_sub_kegiatan
			from sijstk.pn_promotif a left join sijstk.pn_kode_promotif_kegiatan b on b.kode_kegiatan = a.kode_kegiatan
			left join sijstk.pn_kode_promotif_sub c on c.kode_sub_kegiatan = a.kode_sub_kegiatan
			where a.kode_kantor = '".$ls_kode_kantor."' and a.kode_promotif = '".$DATAID."'";		

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
	$sql = "update sijstk.pn_promotif 
			set 
			tgl_terima_surat_pengajuan 	= sysdate,
			tgl_rekam_pengajuan 		= sysdate,
			petugas_rekam_pengajuan		= '".$ls_user_login."',
			jml_tk_pengajuan			= ".$ls_jumlah_tk.",
			jml_kk_pengajuan			= ".$ls_jumlah_kasuskk.",
			jenis_usaha_pengajuan		= '".$ls_jenis_usaha."',
			latar_belakang_pengajuan	= '".$ls_latar_belakang_pengajuan."',
			tgl_kegiatan_pengajuan 		= to_date('".$ld_tgl_kegiatan."','DD-MM-YYYY'),
			nama_detil_kegiatan 		= '".$ls_nama_kegiatan."',
			kode_kegiatan 				= '".$ls_kode_jenis_kegiatan."',
			kode_sub_kegiatan 			= '".$ls_kode_sub_jenis_kegiatan."',
			tgl_ubah 					= sysdate,
			petugas_ubah				= '".$ls_user_login."'
			where kode_promotif = '".$DATAID."'";
		
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, entri pengajuan kegiatan berhasil!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, entri pengajuan kegiatan tidak berhasil!"}';
	}
}
else if(($TYPE =='SUBMIT') && ($DATAID != ''))
{	
	$sql = "update sijstk.pn_promotif 
			set status					= 'PENGAJUAN',
			sub_status					= 'ENTRI_PENGAJUAN',
			tgl_submit_pengajuan 		= sysdate,
			petugas_submit_pengajuan	= '".$ls_user_login."',
			tgl_ubah 					= sysdate,
			petugas_ubah				= '".$ls_user_login."'
			where kode_promotif = '".$DATAID."'";

	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, submit entri pengajuan kegiatan berhasil!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, submit entri pengajuan kegiatan tidak berhasil!"}';
	}
}
else
{
	echo '{"ret":-1,"msg":"Proses gagal"}';
}	
?>