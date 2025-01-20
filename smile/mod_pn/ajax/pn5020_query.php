<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE		= $_POST["TYPE"];
$KEYWORD 	= $_POST["KEYWORD"];
$ls_user_login = $_SESSION["USER"];
$gs_kantor_aktif = $_SESSION['gs_kantor_aktif'];
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;

//if($TYPE!=''){
	$sql = "select 
			case 
				when (a.sub_status = 'PERSETUJUAN_PENGAJUAN' and a.status_approval_pengajuan = 'DISETUJUI') then a.status_approval_pengajuan
				when (a.sub_status = 'VERIFIKASI_PENGAJUAN' and a.status_verifikasi_pengajuan = 'DITOLAK') then a.status_verifikasi_pengajuan
				when (a.sub_status = 'PENETAPAN_KEGIATAN' and a.status_penetapan_kegiatan = 'DITOLAK') then a.status_penetapan_kegiatan
			end status_approval,
			case 
				when (a.sub_status = 'PERSETUJUAN_PENGAJUAN' and a.status_approval_pengajuan = 'DISETUJUI') then a.tgl_setuju_pengajuan
				when (a.sub_status = 'VERIFIKASI_PENGAJUAN' and a.status_verifikasi_pengajuan = 'DITOLAK') then a.tgl_verifikasi_pengajuan
				when (a.sub_status = 'PENETAPAN_KEGIATAN' and a.status_penetapan_kegiatan = 'DITOLAK') then a.tgl_penetapan_kegiatan
			end tgl_approval,
			replace(a.sub_status,'_',' ') status_kegiatan,
			a.*,b.nama_kegiatan, c.nama_sub_kegiatan
			from sijstk.pn_promotif a left join sijstk.pn_kode_promotif_kegiatan b on b.kode_kegiatan = a.kode_kegiatan
			left join sijstk.pn_kode_promotif_sub c on c.kode_sub_kegiatan = a.kode_sub_kegiatan
			where ";				
						
	if($KEYWORD != '') {
		if (preg_match("/%/i", $KEYWORD)) {			
			$sql .= ' a.'.$TYPE . " like '".$KEYWORD."'";
		} else {
			$sql .= ' a.'.$TYPE . " like '%".$KEYWORD."%' ";
		};
		$sql .= " and ";
	}
	$sql .= " rownum <= 500 and a.kode_kantor = '".$ls_kode_kantor."' and a.status = 'PENGAJUAN' 
	and (a.sub_status = 'PERSETUJUAN_PENGAJUAN' and a.status_approval_pengajuan = 'DISETUJUI') 
	or (a.sub_status = 'VERIFIKASI_PENGAJUAN' and a.status_verifikasi_pengajuan = 'DITOLAK')
	or (a.sub_status = 'PENETAPAN_KEGIATAN' and a.status_penetapan_kegiatan = 'DITOLAK') ";
	
	$DB->parse($sql);
	$DB->execute(); 
	$i = 0;
	while($data = $DB->nextrow())
	{
		$jsondata .= json_encode($data);
		$jsondata .= ',';
		$i++;
	}
	$jsondataStart = '{"ret":0,"count":'.$i.',"data":[';
	$jsondata .= ']}';
	$jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
	$jsondata = str_replace('},]}', '}]}', $jsondata);
	print_r($jsondata);
//} 
?>