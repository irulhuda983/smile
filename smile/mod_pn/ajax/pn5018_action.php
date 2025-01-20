<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
//print_r($_POST);

$TYPE		= $_POST["TYPE"];
$DATAID		= $_POST["DATAID"];
$DATAID2	= $_POST["DATAID2"];
$KODETK		= $_POST["KODETK"];
$NAMAPIHAKKETIGASUBMIT		= $_POST["NAMAPIHAKKETIGASUBMIT"];
$ALAMATPIHAKKETIGASUBMIT	= $_POST["ALAMATPIHAKKETIGASUBMIT"];
$EMAILPIHAKKETIGASUBMIT		= $_POST["EMAILPIHAKKETIGASUBMIT"];
$PICPIHAKKETIGASUBMIT		= $_POST["PICPIHAKKETIGASUBMIT"];
$NOTELPICPIHAKKETIGASUBMIT	= $_POST["NOTELPICPIHAKKETIGASUBMIT"];
$KETERANGANPIHAKKETIGASUBMIT= $_POST["KETERANGANPIHAKKETIGASUBMIT"];

$ls_user_login = $_SESSION["USER"];
$gs_kantor_aktif = $_SESSION['gs_kantor_aktif'];
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;

$ls_kode_promotif			= $DATAID;
$ls_nama_pihak_ketiga		= $_POST["nama_pihak_ketiga"];
$ls_alamat_pihak_ketiga		= $_POST["alamat_pihak_ketiga"];
$ls_email_pihak_ketiga		= $_POST["email_pihak_ketiga"];
$ls_pic_pihak_ketiga		= $_POST["pic_pihak_ketiga"];
$ls_no_telp_pic_pihak_ketiga= $_POST["no_telp_pic_pihak_ketiga"];
$ls_keterangan_pihak_ketiga	= $_POST["keterangan_pihak_ketiga"];

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
			nama_pelaksana					= '".$ls_nama_pihak_ketiga."',
			alamat_pelaksana				= '".$ls_alamat_pihak_ketiga."',
			email_pelaksana					= '".$ls_email_pihak_ketiga."',
			pic_pelaksana					= '".$ls_pic_pihak_ketiga."',
			no_telp_pic						= ".$ls_no_telp_pic_pihak_ketiga.",
			keterangan_rekam_pelaksana		= '".$ls_keterangan_pihak_ketiga."',
			tgl_rekam_pelaksana 			= sysdate,
			petugas_rekam_pelaksana			= '".$ls_user_login."',
			tgl_ubah 						= sysdate,
			petugas_ubah					= '".$ls_user_login."'
			where kode_promotif = '".$DATAID."'";
		
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, entri pihak ketiga pelaksana kegiatan berhasil!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, entri pihak ketiga pelaksana kegiatan tidak berhasil!"}';
	}
}
else if(($TYPE =='SUBMIT') && ($DATAID != ''))
{	
	$sql = "update sijstk.pn_promotif 
			set status						= 'PENGAJUAN',
			sub_status						= 'PERSETUJUAN_PENGAJUAN',
			nama_pelaksana					= '".$NAMAPIHAKKETIGASUBMIT."',
			alamat_pelaksana				= '".$ALAMATPIHAKKETIGASUBMIT."',
			email_pelaksana					= '".$EMAILPIHAKKETIGASUBMIT."',
			pic_pelaksana					= '".$PICPIHAKKETIGASUBMIT."',
			no_telp_pic						= ".$NOTELPICPIHAKKETIGASUBMIT.",
			keterangan_rekam_pelaksana		= '".$KETERANGANPIHAKKETIGASUBMIT."',
			tgl_rekam_pelaksana 			= sysdate,
			petugas_rekam_pelaksana			= '".$ls_user_login."',
			tgl_submit_pelaksanaketiga 		= sysdate,
			petugas_submit_pelaksanaketiga	= '".$ls_user_login."',
			tgl_ubah 						= sysdate,
			petugas_ubah					= '".$ls_user_login."'
			where kode_promotif = '".$DATAID."'";

	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, submit entri pihak ketiga pelaksana kegiatan berhasil!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, submit entri pihak ketiga pelaksana kegiatan tidak berhasil!"}';
	}
}
else
{
	echo '{"ret":-1,"msg":"Proses gagal"}';
}	
?>