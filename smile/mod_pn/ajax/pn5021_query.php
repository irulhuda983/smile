<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE			= $_POST["TYPE"];
$KEYWORD 		= $_POST["KEYWORD"];
$ls_user_login 	= $_SESSION["USER"];
$gs_kantor_aktif= $_SESSION['gs_kantor_aktif'];
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;

//if($TYPE!=''){
	$sql = "select 
			case 
			when a.sub_status = 'ENTRI_PERENCANAAN' then 
			  case 
				when a.TGL_PERMINTAAN_VERIFIKASI is null then 'MENUNGGU KIRIM PERMINTAAN VERIFIKASI KE KBP/WASRIK'
				else 'SUDAH KIRIM PERMINTAAN VERIFIKASI KE KBP/WASRIK'
			  end
			when a.sub_status = 'VERIFIKASI_PERENCANAAN' then 
			  case 
				when a.TGL_VERIF_PERMINTAAN is null then 'MENUNGGU VERIFIKASI KBP/WASRIK'
				else 'SUDAH VERIFIKASI KBP/WASRIK DAN ' || a.STATUS_VERIF_PERMINTAAN
			  end
			when a.sub_status = 'PERSETUJUAN_PERENCANAAN' then 
			  case 
				when a.TGL_APPROVAL_VERIFIKASI is null then 'MENUNGGU APPROVAL KBL'
				else 'SUDAH APPROVAL KBL DAN ' || a.STATUS_VERIF_APPROVAL
			  end
			when a.sub_status = 'CETAK_PEMBERITAHUAN' then 
			  case 
				when a.TGL_KONFIRM_PEMBERITAHUAN is null then 'BELUM KONFIRMASI CETAK'
				else 'SUDAH KONFIRMASI CETAK'
			  end
			when a.sub_status = 'ENTRI_PENGAJUAN' then 
			  case 
				when a.TGL_SUBMIT_PENGAJUAN is null then 'BELUM SUBMIT ENTRI PENGAJUAN'
				else 'SUDAH SUBMIT ENTRI PENGAJUAN'
			  end
			when a.sub_status = 'VERIFIKASI_PENGAJUAN' then 
			  case 
				when a.TGL_SUBMIT_VERIF_PENGAJUAN is null then 'MENUNGGU VERIFIKASI OLEH PMP'
				else 'SUDAH VERIFIKASI OLEH PMP DAN ' || a.STATUS_VERIFIKASI_PENGAJUAN
			  end
			when a.sub_status = 'PENETAPAN_KEGIATAN' then 
			  case 
				when a.TGL_SUBMIT_PENETAPAN_KEGIATAN is null then 'MENUNGGU PENETAPAN KEGIATAN OLEH KBL'
				else 'SUDAH PENETAPAN KEGIATAN OLEH KBL DAN ' || a.STATUS_PENETAPAN_KEGIATAN
			  end
			when a.sub_status = 'PENETAPAN_PELAKSANA' then 
			  case 
				when a.TGL_SUBMIT_PELAKSANA_KEGIATAN is null then 'BELUM SUBMIT PELAKSANA KEGIATAN'
				else 'SUDAH SUBMIT PELAKSANA KEGIATAN'
			  end
			when a.sub_status = 'PERSETUJUAN_PENGAJUAN' then 
			  case 
				when a.TGL_SETUJU_PENGAJUAN is null then 'MENUNGGU APPROVAL KAKACAB'
				else 'SUDAH APPROVAL KAKACAB DAN ' || a.STATUS_APPROVAL_PENGAJUAN
			  end
			when a.sub_status = 'CETAK_PERSETUJUAN' then 
			  case 
				when a.TGL_KONFIRMASI_CETAKSETUJU is null then 'BELUM KONFIRMASI CETAK'
				else 'SUDAH KONFIRMASI CETAK'
			  end
				end status_proses,
				case 
			when a.sub_status = 'ENTRI_PERENCANAAN' then a.TGL_PROMOTIF
			when a.sub_status = 'VERIFIKASI_PERENCANAAN' then a.TGL_VERIF_PERMINTAAN
			when a.sub_status = 'PERSETUJUAN_PERENCANAAN' then a.TGL_APPROVAL_VERIFIKASI
			when a.sub_status = 'CETAK_PEMBERITAHUAN' then a.TGL_KONFIRM_PEMBERITAHUAN
			when a.sub_status = 'ENTRI_PENGAJUAN' then a.TGL_REKAM_PENGAJUAN
			when a.sub_status = 'VERIFIKASI_PENGAJUAN' then a.TGL_VERIFIKASI_PENGAJUAN
			when a.sub_status = 'PENETAPAN_KEGIATAN' then a.TGL_PENETAPAN_KEGIATAN
			when a.sub_status = 'PENETAPAN_PELAKSANA' then a.TGL_PENENTUAN_PELAKSANA
			when a.sub_status = 'PERSETUJUAN_PENGAJUAN' then a.TGL_SETUJU_PENGAJUAN
			when a.sub_status = 'CETAK_PERSETUJUAN' then a.TGL_KONFIRMASI_CETAKSETUJU
				end tgl_proses,
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
	$sql .= " rownum <= 500 and a.kode_kantor = '".$ls_kode_kantor."' ";
	
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