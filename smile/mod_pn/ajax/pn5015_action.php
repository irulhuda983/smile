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

$ls_user_login = $_SESSION["USER"];
$gs_kantor_aktif = $_SESSION['gs_kantor_aktif'];
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;

$ls_kode_promotif					= $DATAID;
$ls_jumlah_tk_diajukan				= $_POST["jumlah_tk_diajukan"];
$ls_keterangan_verifikasi_pengajuan	= $_POST["keterangan_verifikasi_pengajuan"];
$ls_status_verifikasi_pengajuan		= $_POST["status_verifikasi_pengajuan"];

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
			jumlah_tk_verifikasi 		= ".$ls_jumlah_tk_diajukan.",
			tgl_verifikasi_pengajuan 	= sysdate,
			petugas_verifikasi_pengajuan= '".$ls_user_login."',
			keterangan_verifikasi_ajuan	= '".$ls_keterangan_verifikasi_pengajuan."',
			status_verifikasi_pengajuan	= '".$ls_status_verifikasi_pengajuan."',
			tgl_ubah 					= sysdate,
			petugas_ubah				= '".$ls_user_login."'
			where kode_promotif = '".$DATAID."'";
		
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, entri verifikasi pengajuan kegiatan berhasil!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, entri verifikasi pengajuan kegiatan tidak berhasil!"}';
	}
}
else if(($TYPE =='SUBMIT') && ($DATAID != ''))
{	
	// cek daftar TK yg diajukan apakaah sudah diinput
	$sql_tk = "select count(kode_tk)JML_TK from sijstk.pn_promotif_detil
			   where kode_promotif = '".$DATAID."'";
			   
	$DB->parse($sql_tk);
	$DB->execute();
	$row = $DB->nextrow();
	$total_tk = $row['JML_TK'];
	
	$sql_tk_diajukan = "select nvl(jumlah_tk_verifikasi,0) jumlah_tk_verifikasi from sijstk.pn_promotif
			   where kode_promotif = '".$DATAID."'";
	$DB->parse($sql_tk_diajukan);
	$DB->execute();
	$row = $DB->nextrow();
	$total_tk_diajukan = $row['JUMLAH_TK_VERIFIKASI'];
	
	if($total_tk > 0)
	{
		if($total_tk == $total_tk_diajukan)
		{
			$sql = "update sijstk.pn_promotif 
			set status					= 'PENGAJUAN',
			sub_status					= 'VERIFIKASI_PENGAJUAN',
			tgl_submit_verif_pengajuan 	= sysdate,
			petugas_submit_verif_ajuan	= '".$ls_user_login."',
			tgl_ubah 					= sysdate,
			petugas_ubah				= '".$ls_user_login."'
			where kode_promotif = '".$DATAID."'";

			$DB->parse($sql);
			if($DB->execute()){ 
				echo '{"ret":0,"msg":"Sukses, submit entri verifikasi pengajuan kegiatan berhasil!"}';
			} else {
				echo '{"ret":-1,"msg":"Proses gagal, submit entri verifikasi pengajuan kegiatan tidak berhasil!"}';
			}
		}
		else
		{
			echo '{"ret":-1,"msg":"Proses gagal, jumlah tenaga kerja diajukan tidak sama dengan jumlah daftar tenaga kerja pengajuan!"}';
		}
	}
	else
	{
		echo '{"ret":-1,"msg":"Proses gagal, silahkan menginput data daftar tenaga kerja pengajuan!"}';
	}
}
else if(($TYPE =='DEL') && ($DATAID != '') && ($KODETK != ''))
{
	$sql = "delete from sijstk.pn_promotif_detil 
			where kode_promotif = '".$DATAID."' and kode_tk = '".$KODETK."' ";
		
	$DB->parse($sql);
	if($DB->execute()){
		echo '{"ret":0,"msg":"Proses Sukses, hapus data tenaga kerja pengajuan berhasil!"}';
	}else {
		echo '{"ret":-1,"msg":"Proses gagal, hapus data tenaga kerja pengajuan tidak berhasil!"}';
	}
}
else
{
	echo '{"ret":-1,"msg":"Proses gagal"}';
}	
?>