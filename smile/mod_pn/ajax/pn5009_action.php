<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
//print_r($_POST);

$TYPE		= $_POST["TYPE"];
$DATAID		= $_POST["DATAID"];
$DATAID2	= $_POST["DATAID2"];
$DATATKID	= $_POST["DATATKID"];
$STATUSAPPROVAL		= $_POST["STATUSAPPROVAL"];
$KETERANGANAPPROVAL	= $_POST["KETERANGANAPPROVAL"];

$ls_user_login = $_SESSION["USER"];
$gs_kantor_aktif = $_SESSION['gs_kantor_aktif'];
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;

$ls_kode_promotif				= $DATAID;
$ls_keterangan_verifikasi		= $_POST["keterangan_verifikasi"];

if(($TYPE =='VIEW') && ($DATAID != ''))
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
			tgl_verif_permintaan 		= sysdate,
			petugas_verif_permintaan	= '".$ls_user_login."',
			keterangan_verif_permintaan	= '".$ls_keterangan_verifikasi."',
			tgl_ubah 					= sysdate,
			petugas_ubah				= '".$ls_user_login."'
			where kode_promotif = '".$DATAID."'";
		
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, simpan data permintaan verifikasi perusahaan oleh KBL berhasil!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, simpan data permintaan verifikasi perusahaan oleh KBL tidak berhasil!"}';
	}
}
else if(($TYPE =='APPROVE') && ($DATAID != ''))
{	
	$sql = "update sijstk.pn_promotif
			set 	
			tgl_verif_permintaan 		= sysdate,
			petugas_verif_permintaan	= '".$ls_user_login."',
			keterangan_verif_permintaan	= '".$KETERANGANAPPROVAL."',
			status_verif_permintaan		= '".$STATUSAPPROVAL."',
			sub_status					= 'PERSETUJUAN_PERENCANAAN',
			tgl_ubah 					= sysdate,
			petugas_ubah				= '".$ls_user_login."'
			where kode_promotif = '".$DATAID."'";
		
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, approval data permintaan verifikasi perusahaan berhasil!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, approval data permintaan verifikasi perusahaan tidak berhasil!"}';
	}
}
else
{
	echo '{"ret":-1,"msg":"Proses gagal"}';
}	
?>