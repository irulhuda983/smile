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

$ls_user_login = $_SESSION["USER"];
$gs_kantor_aktif = $_SESSION['gs_kantor_aktif'];
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;

$ls_kode_tk	= $_POST['kode_tk']=="" ? urldecode($_GET['kode_tk']) : urldecode($_POST['kode_tk']);



if(($TYPE =='DEL') && ($ls_kode_tk != ''))
{
	$sql = "delete from sijstk.pn_promotif_detil 
			where kode_tk = '".$ls_kode_tk."'";
		
	$DB->parse($sql);
	if($DB->execute()){
		echo '{"ret":0,"msg":"Proses Sukses, Data berhasil dihapus!"}';
	}
} 
else if(($TYPE =='VIEW') && ($DATAID != ''))
{
	$sql = "select a.kode_promotif,a.kode_perusahaan,a.npp,kode_divisi,a.nama_perusahaan,a.jml_kasus_kk,a.jml_kasus_pak,a.jml_tk,a.insiden_rate,a.kode_kantor,
			a.tgl_kegiatan,a.kode_kegiatan,b.nama_kegiatan, a.kode_sub_kegiatan,c.nama_sub_kegiatan,a.nama_detil_kegiatan,
			a.latar_belakang_pengajuan,a.jml_tk_pengajuan,a.jml_kk_pengajuan,a.jenis_usaha_pengajuan,
			a.kategori_pelaksana,a.nama_pelaksana,a.alamat_pelaksana,a.email_pelaksana,a.pic_pelaksana,a.no_telp_pic_pelaksana,a.keterangan,
			a.status_verifikasi,a.status_pengajuan,a.status
			from sijstk.pn_promotif a left join sijstk.pn_kode_promotif_kegiatan b on b.kode_kegiatan = a.kode_kegiatan
			left join sijstk.pn_kode_promotif_sub c on c.kode_sub_kegiatan = a.kode_sub_kegiatan
			where kode_promotif = '".$DATAID."'";		

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
			set nama_detil_kegiatan = '".$ls_nama_kegiatan."',
			tgl_kegiatan 			= to_date('".$ld_tgl_kegiatan."','DD-MM-YYYY'),
			kode_kegiatan 			= '".$ls_kode_jenis_kegiatan."',
			kode_sub_kegiatan 			= '".$ls_kode_sub_jenis_kegiatan."',
			latar_belakang_pengajuan	= '".$ls_latar_belakang_pengajuan."',
			jml_tk_pengajuan		= ".$ls_jumlah_tk.",
			jml_kk_pengajuan		= ".$ls_jumlah_kasuskk.",
			jenis_usaha_pengajuan	= '".$ls_jenis_usaha."',	
			kategori_pelaksana		= '".$ls_kategori_pelaksana."',
			nama_pelaksana			= '".$ls_nama_pelaksana."',
			email_pelaksana			= '".$ls_email_pelaksana."',
			alamat_pelaksana		= '".$ls_alamat_pelaksana."',
			pic_pelaksana			= '".$ls_pic_pelaksana."',
			no_telp_pic_pelaksana	= '".$ls_no_telp_picpelaksana."',
			keterangan				= '".$ls_keterangan."',
			tgl_ubah 				= sysdate,
			petugas_ubah			= '".$ls_user_login."'
			where kode_promotif = '".$DATAID."'";
		
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, Entri pengajuan kegiatan berhasil!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal diupdate!"}';
	}
}
else
{
	echo '{"ret":-1,"msg":"Proses gagal"}';
}	
?>