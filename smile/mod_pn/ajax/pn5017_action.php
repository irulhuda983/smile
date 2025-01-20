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
$JENISPELAKSANASUBMIT		= $_POST["JENISPELAKSANASUBMIT"];
$KETERANGANSUBMIT			= $_POST["KETERANGANSUBMIT"];

$ls_user_login = $_SESSION["USER"];
$gs_kantor_aktif = $_SESSION['gs_kantor_aktif'];
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;

$ls_kode_promotif					= $DATAID;
$ls_jenis_pelaksana_kegiatan		= $_POST["jenis_pelaksana_kegiatan"];
$ls_keterangan_penetapan_pelaksana	= $_POST["keterangan_penetapan_pelaksana"];

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
			jenis_pelaksana_kegiatan		= '".$ls_jenis_pelaksana_kegiatan."',
			kategori_pelaksana				= '".$ls_jenis_pelaksana_kegiatan."',
			tgl_penentuan_pelaksana 			= sysdate,
			petugas_penentuan_pelaksana		= '".$ls_user_login."',
			keterangan_penentu_pelaksana	= '".$ls_keterangan_penetapan_pelaksana."',
			tgl_ubah 						= sysdate,
			petugas_ubah					= '".$ls_user_login."'
			where kode_promotif = '".$DATAID."'";
		
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, entri penetapan pelaksana kegiatan berhasil!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, entri penetapan pelaksana kegiatan tidak berhasil!"}';
	}
}
else if(($TYPE =='SUBMIT') && ($DATAID != ''))
{	
	$sql = "update sijstk.pn_promotif 
			set status						= 'PENGAJUAN',
			sub_status						= 'PENETAPAN_PELAKSANA',
			jenis_pelaksana_kegiatan		= '".$JENISPELAKSANASUBMIT."',
			kategori_pelaksana				= '".$JENISPELAKSANASUBMIT."',
			tgl_penentuan_pelaksana 			= sysdate,
			petugas_penentuan_pelaksana		= '".$ls_user_login."',
			keterangan_penentu_pelaksana	= '".$KETERANGANSUBMIT."',
			tgl_submit_pelaksana_kegiatan 	= sysdate,
			petugas_submit_pelaksana_keg	= '".$ls_user_login."',
			tgl_ubah 						= sysdate,
			petugas_ubah					= '".$ls_user_login."'
			where kode_promotif = '".$DATAID."'";

	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, submit entri penetapan pelaksana kegiatan berhasil!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, submit entri penetapan pelaksana kegiatan tidak berhasil!"}';
	}
}
else
{
	echo '{"ret":-1,"msg":"Proses gagal"}';
}	
?>