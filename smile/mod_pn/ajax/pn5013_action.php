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

$ls_keterangan_verifikasi	= $_POST['keterangan_verifikasi']=="" ? urldecode($_GET['keterangan_verifikasi']) : urldecode($_POST['keterangan_verifikasi']);

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
else if(($TYPE =='KONFIRMASI') && ($DATAID != ''))
{	
	$sql = "update sijstk.pn_promotif
			set 
			tgl_cetak_pemberitahuan 	  	= sysdate,
			petugas_cetak_pemberitahuan 	= '".$ls_user_login."',
			tgl_konfirm_pemberitahuan 	  	= sysdate,
			petugas_konfirm_pemberitahuan 	= '".$ls_user_login."',
			sub_status 						= 'CETAK_PEMBERITAHUAN',
			tgl_ubah 						= sysdate,
			petugas_ubah					= '".$ls_user_login."'
			where kode_promotif = '".$DATAID."'";
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, konfirmasi cetak surat pemberitahuan berhasil!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, konfirmasi cetak surat pemberitahuan tidak berhasil!"}';
	}
}
else if(($TYPE =='CETAK') && ($DATAID != ''))
{	
	$sql = "update sijstk.pn_promotif
			set 
			tgl_cetak_pemberitahuan 	  	= sysdate,
			petugas_cetak_pemberitahuan 	= '".$ls_user_login."',
			tgl_ubah 						= sysdate,
			petugas_ubah					= '".$ls_user_login."'
			where kode_promotif = '".$DATAID."'";
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, Cetak surat pemberitahuan berhasil!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, cetak surat pemberitahuan tidak berhasil!"}';
	}
}
else
{
	echo '{"ret":-1,"msg":"Proses gagal"}';
}	
?>