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

$ls_kode_promotif		= $DATAID;
$ld_tgl_pengajuan		= date("d-m-Y", strtotime(str_replace("/","-", urldecode($_POST["tgl_pengajuan"]))));
$ls_jenis_kegiatan		= $_POST["jenis_kegiatan"];
$ls_nama_kegiatan		= $_POST["nama_kegiatan"];
$ls_kategori_pelaksana	= $_POST["kategori_pelaksana"];
$ls_nama_pelaksana		= $_POST["nama_pelaksana"];
$ls_alamat_pelaksana	= $_POST["alamat_pelaksana"];
$ls_email_pelaksana		= $_POST["email_pelaksana"];
$ls_pic_pelaksana		= $_POST["pic_pelaksana"];
$ls_no_telp_picpelaksana= $_POST["no_telp_picpelaksana"];
$ls_keterangan			= urldecode(htmlspecialchars($_POST["keterangan"]));

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
	$sql = "select kode_promotif,kode_perusahaan,npp,kode_divisi,nama_perusahaan,jml_kasus_kk,jml_kasus_pak,jml_tk,insiden_rate,kode_kantor,tgl_pengajuan,
			kode_kegiatan,kode_sub_kegiatan,nama_detil_kegiatan,kategori_pelaksana,nama_pelaksana,alamat_pelaksana,email_pelaksana,pic_pelaksana,no_telp_pic_pelaksana
			from sijstk.pn_promotif
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
			set tgl_pengajuan 		= to_date('".$ld_tgl_pengajuan."','DD-MM-YYYY'),
			jenis_kegiatan 			= '".$ls_jenis_kegiatan."',
			nama_kegiatan 			= '".$ls_nama_kegiatan."',
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
else if(($TYPE =='KIRIM') && ($DATAID != ''))
{	
	$sql = "update sijstk.pn_promotif
			set status 				= 'VERIFIKASI_PERENCANAAN',
			tgl_ubah 				= sysdate,
			tgl_permintaan_verifikasi = sysdate,
			petugas_ubah			= '".$ls_user_login."'
			where kode_promotif = '".$DATAID."'";
			
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, Pengajuan data berhasil dilakukan!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal diupdate!"}';
	}
}
else
{
	echo '{"ret":-1,"msg":"Proses gagal"}';
}	
?>