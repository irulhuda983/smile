<?PHP
session_start();
include_once "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$DB_EC = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);
//$DB_EC = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE			= $_POST["TYPE"];
$DATAID		= $_POST["DATAID"];
$DATAID2	= $_POST["DATAID2"];
// 18112020, menambahkan kode kanal pelayanan
$ls_kode_kanal_pelayanan							= $_POST["kode_kanal_pelayanan"];
$ls_kode_klaim							= $_POST["kode_klaim"];
$ls_kode_kantor							= $_POST["kode_kantor"];	
$ls_kode_segmen			 				= $_POST["kode_segmen"];
$ld_tgl_klaim			 					= $_POST["tgl_klaim"];
$ls_kpj											= $_POST["kpj"];
$ls_nama_tk									= $_POST["nama_tk"];
$ls_kode_tk									= $_POST["kode_tk"];
$ls_nomor_identitas					= $_POST["nomor_identitas"];
$ls_jenis_identitas					= $_POST["jenis_identitas"];
$ls_kode_kantor_tk					= $_POST["kode_kantor_tk"];
$ls_no_proyek								= $_POST["no_proyek"];
$ls_nama_proyek							= $_POST["nama_proyek"];	
$ls_kode_proyek							= $_POST["kode_proyek"];
$ls_npp											= $_POST["npp"];
$ls_nama_perusahaan					= $_POST["nama_perusahaan"];
$ls_kode_perusahaan					= $_POST["kode_perusahaan"];
$ls_kode_divisi 						= $_POST["kode_divisi"];
$ls_nama_divisi							= $_POST["nama_divisi"];
$ls_kode_tipe_klaim					= $_POST["kode_tipe_klaim"];
$ls_nama_tipe_klaim					= $_POST["nama_tipe_klaim"];
$ls_kode_sebab_klaim				= $_POST["kode_sebab_klaim"];
$ls_nama_sebab_klaim				= $_POST["nama_sebab_klaim"];
$ld_tgl_lapor			 					= $_POST["tgl_lapor"];
$ls_keterangan							= $_POST["keterangan"];
$ls_jenis_klaim							= $_POST["jenis_klaim"];
$ls_mode_transaksi					= $_POST["mode_transaksi"];
$ls_status_klaim						= $_POST["status_klaim"];
$ld_tgl_kejadian			 			= $_POST["tgl_kejadian"];
$ls_status_kepesertaan			= $_POST["status_kepesertaan"];
$ls_kode_perlindungan				= $_POST["kode_perlindungan"];
$ld_tgl_awal_perlindungan	  = $_POST["tgl_awal_perlindungan"];
$ld_tgl_akhir_perlindungan	= $_POST["tgl_akhir_perlindungan"];

if ($ls_kode_segmen	!="TKI")
{
 	 $ls_kode_perlindungan = $ls_kode_segmen;
}
if ($ld_tgl_kejadian=="")
{
 $ld_tgl_kejadian = $ld_tgl_klaim;
}

/* Penambahan Post Input Data Integrasi Agenda Klaim dan Sistem Antrian*/
$ls_token_sisla				= $_POST["token_antrian"];
$ls_kode_jenis_antrian		= $_POST["kode_jenis_antrian"];
$ls_kode_status_antrian		= "ST01"; //SETUJU  //$_POST["kode_status_antrian"];
$ls_kode_sisla				= $_POST["kode_sisla"];
$ls_kode_kantor_antrian		= $_POST["kode_kantor_antrian"];
$ls_nomor_antrian			= $_POST["no_antrian"];
$ld_tgl_ambil_antrian		= $_POST["tgl_ambil_antrian"];
$ld_tgl_panggil_antrian		= $_POST["tgl_panggil_antrian"];
$ls_kode_petugas_antrian	= $_POST["kode_petugas_antrian"];
// $ls_nomor_identitas_antrian	= $_POST["nomor_identitas_antrian"];
$ls_no_hp_antrian	        = $_POST["no_hp_antrian"];
$ls_email_antrian	        = $_POST["email_antrian"];

if ($ls_kode_tipe_klaim == 'JHT01'){
	$ls_kode_jenis_antrian_detil = 'SA01JHT01';
} else if ($ls_kode_tipe_klaim == 'JKK01'){
	$ls_kode_jenis_antrian_detil = 'SA01JKK01';
} else if ($ls_kode_tipe_klaim == 'JKM01'){
	$ls_kode_jenis_antrian_detil = 'SA01JKM01';
} else if ($ls_kode_tipe_klaim == 'JPN01'){
	$ls_kode_jenis_antrian_detil = 'SA01JPN01';
} else if ($ls_kode_tipe_klaim == 'JKP01'){
	$ls_kode_jenis_antrian_detil = 'SA01JKP01';
} 

$ls_kode_antrian		    = $_POST["kode_antrian"];


//VIEW -------------------------------------------------------------------------
if ($TYPE=="View" && $DATAID != '')
{
  //query data --------------------------------------------------------		
}
else if ($TYPE=="New")
{		
	//--- start pengecekan pengajuan antrian online
	// ditambahkan 26052020
	// pengecekan pengajuan ke antrian online untuk mengecek apakah sedang ada pengajuan yang masih aktif, jika masih ada maka tidak bisa di agendakan si SMILE
	// agenda manual bisa dilakukan jika pengajuan di antrian online sudah dilakukan proses persetujuan (SETUJU/TOLAK)
	// jika Peserta Datang Ke Cabang Lain/ Datang Langsung Sebelum Jadwal
	// pengecekan dilakukan dengan mengecek NIK 100%, KPJ 100%, Nama 75%
	$sql_antol = "
					select count(*) jml_pengajuan 
					from ANTRIAN.ATR_BOOKING 
					where KPJ = :p_kpj
					and NIK = :p_nik
					AND UTL_MATCH.EDIT_DISTANCE_SIMILARITY (NAMA, :p_nama_tk) >= 75
					AND nvl(STATUS_APPROVAL,'X') = 'T'
				"; 

	$proc = $DB_EC->parse($sql_antol);
	$param_bv = array(':p_kpj' => $ls_kpj, ':p_nik' => $ls_nomor_identitas, ':p_nama_tk' => $ls_nama_tk);
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
	// 20-03-2024 penyesuaian bind variable
	$DB_EC->execute();
	$row_antol = $DB_EC->nextrow();
	$ls_jml_pengajuan_antol = $row_antol["JML_PENGAJUAN"];
	//echo $ls_jml_pengajuan_antol ;
	//exit;
	
	$sql_antol = "
					select KODE_BOOKING, KODE_KANTOR 
					from ANTRIAN.ATR_BOOKING 
					where KPJ = :p_kpj
					and NIK = :p_nik
					AND UTL_MATCH.EDIT_DISTANCE_SIMILARITY (NAMA, :p_nama_tk) >= 75
					AND nvl(STATUS_APPROVAL,'X') = 'T'
					AND ROWNUM=1
				";
	$proc = $DB_EC->parse($sql_antol);
	$param_bv = array(':p_kpj' => $ls_kpj, ':p_nik' => $ls_nomor_identitas, ':p_nama_tk' => $ls_nama_tk);
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
	// 20-03-2024 penyesuaian bind variable
	$DB_EC->execute();
	$row_antol = $DB_EC->nextrow();
	$ls_kode_booking_antol = $row_antol["KODE_BOOKING"];
	$ls_kode_kantor_antol = $row_antol["KODE_KANTOR"];
	$ls_nama_kantor_antol = get_nama_kantor($row_antol["KODE_KANTOR"])." (".$ls_kode_kantor_antol.")";
	
	//--- end pengecekan pengajuan antrian online
	
	//--- start pengecekan pengajuan klaim lewat JMO yang masih proses (diluar status KLA5         = DIBAYAR dan KLA6         = DIBATALKAN)
	// ditambahkan 13082021
	// pengecekan pengajuan ke JMO klaim untuk mengecek apakah sedang ada pengajuan yang masih aktif, jika masih ada maka tidak bisa di agendakan di SMILE
	// agenda manual bisa dilakukan jika pengajuan di JMO Klaim sudah dilakukan proses pembayaran atau dibatalkan (DIBAYAR/DIBATALKAN)
	// jika Peserta Datang Ke Cabang Lain/ Datang Langsung 
	// pengecekan dilakukan dengan mengecek NIK 100%, KPJ 100%, Nama 75%
	$sql_jmoklaim = "
					select count(*) jml_pengajuan 
					from BPJSTKU.ASIK_KLAIM 
					where KPJ = :p_kpj
					and NOMOR_IDENTITAS = :p_nik
					AND UTL_MATCH.EDIT_DISTANCE_SIMILARITY (NAMA_TK, :p_nama_tk) >= 75
					and nvl(STATUS_BATAL,'X') = 'T'
					and STATUS_PENGAJUAN not in ('KLA5','KLA6')
				"; 

	$proc = $DB_EC->parse($sql_jmoklaim);
	$param_bv = array(':p_kpj' => $ls_kpj, ':p_nik' => $ls_nomor_identitas, ':p_nama_tk' => $ls_nama_tk);
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
	// 20-03-2024 penyesuaian bind variable
	$DB_EC->execute();
	$row_jmoklaim = $DB_EC->nextrow();
	$ls_jml_pengajuan_jmoklaim = $row_jmoklaim["JML_PENGAJUAN"];
	
	$sql_jmoklaim = "
					select KODE_PENGAJUAN, KODE_KANTOR
					from BPJSTKU.ASIK_KLAIM 
					where KPJ = :p_kpj
					and NOMOR_IDENTITAS = :p_nik
					AND UTL_MATCH.EDIT_DISTANCE_SIMILARITY (NAMA_TK, :p_nama_tk) >= 75
					and nvl(STATUS_BATAL,'X') = 'T'
					and STATUS_PENGAJUAN not in ('KLA5','KLA6')
					AND ROWNUM=1
				";
	$proc = $DB_EC->parse($sql_jmoklaim);
	$param_bv = array(':p_kpj' => $ls_kpj, ':p_nik' => $ls_nomor_identitas, ':p_nama_tk' => $ls_nama_tk);
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
	$DB_EC->execute();
	$row_jmoklaim = $DB_EC->nextrow();
	$ls_kode_booking_jmoklaim = $row_jmoklaim["KODE_PENGAJUAN"];
	$ls_kode_kantor_jmoklaim = $row_jmoklaim["KODE_KANTOR"];
	$ls_nama_kantor_jmoklaim = get_nama_kantor($row_jmoklaim["KODE_KANTOR"])." (".$ls_kode_kantor_jmoklaim.")";
	
	//--- end pengecekan pengajuan klaim lewat JMO

	//--- start pengecekan pengajuan klaim lewat SIPP

	$sql_sippklaim = "
					SELECT SUM (CNT_ATR) CNT_ANTRIAN
						FROM (SELECT COUNT (*) CNT_ATR
								FROM NSP.NSP_KLAIM_KOLEKTIF_DETIL a
							WHERE     a.NOMOR_IDENTITAS = :p_nik
									AND a.KPJ = :p_kpj
									AND EXISTS
											(SELECT *
											FROM NSP.NSP_KLAIM_KOLEKTIF b
											WHERE     b.KODE_PENGAJUAN_KOLEKTIF =
														a.KODE_PENGAJUAN_KOLEKTIF
													AND NVL (b.STATUS_BATAL, 'X') = 'T'
													AND b.KODE_STATUS_PENGAJUAN IN ('ST01', 'ST02'))
							UNION
							-- CEK KLAIM JHT KOLEKTIF DARI SIPP YANG DISETUJUI NAMUN BELUM MENDAPATKAN ANTRIAN
							SELECT COUNT (*) CNT_ATR
								FROM NSP.NSP_KLAIM_KOLEKTIF_DETIL a
							WHERE     a.NOMOR_IDENTITAS = :p_nik
									AND a.KPJ = :p_kpj
									AND EXISTS
											(SELECT *
											FROM NSP.NSP_KLAIM_KOLEKTIF b
											WHERE     b.KODE_PENGAJUAN_KOLEKTIF =
														a.KODE_PENGAJUAN_KOLEKTIF
													AND NVL (b.STATUS_BATAL, 'X') = 'T'
													AND b.KODE_STATUS_PENGAJUAN IN ('ST03'))
									AND NOT EXISTS
											(SELECT *
											FROM ANTRIAN.ATR_BOOKING c
											WHERE c.KODE_TK = a.KODE_TK)
									AND NOT EXISTS
											(
											  SELECT * FROM ANTRIAN.ATR_BOOKING_HIST c
											  where c.KODE_TK = a.KODE_TK
											  and c.KODE_BOOKING = a.KODE_BOOKING
											  and nvl(c.STATUS_APPROVAL,'X') = 'R'
											)
									AND NOT EXISTS
											(
												SELECT * FROM PN.PN_KLAIM@TO_KN c
												WHERE  c.KODE_TK = a.KODE_TK
													AND c.NOMOR_IDENTITAS = a.NOMOR_IDENTITAS
													AND c.KODE_TIPE_KLAIM = 'JHT01'
													AND c.STATUS_BATAL = 'T'
													AND c.STATUS_KLAIM = 'SELESAI'
											)			
							UNION
							-- CEK KLAIM JHT KOLEKTIF DARI SIPP YANG DITOLAK NAMUN BELUM MENDAPATKAN ANTRIAN
							SELECT COUNT (*) CNT_ATR
								FROM NSP.NSP_KLAIM_KOLEKTIF_DETIL a
							WHERE     a.NOMOR_IDENTITAS = :p_nik
									AND a.KPJ = :p_kpj
									AND EXISTS
											(SELECT *
											FROM NSP.NSP_KLAIM_KOLEKTIF b
											WHERE     b.KODE_PENGAJUAN_KOLEKTIF =
														a.KODE_PENGAJUAN_KOLEKTIF
													AND NVL (b.STATUS_BATAL, 'X') = 'T'
													AND b.KODE_STATUS_PENGAJUAN IN ('ST03'))
									AND NOT EXISTS
												(SELECT *
												FROM ANTRIAN.ATR_BOOKING_HIST c
												WHERE     c.KODE_TK = a.KODE_TK
														AND c.KODE_BOOKING = a.KODE_BOOKING
														AND NVL (c.STATUS_APPROVAL, 'X') = 'R')
									AND NOT EXISTS
										(
											SELECT * FROM PN.PN_KLAIM@TO_KN c
											WHERE  c.KODE_TK = a.KODE_TK
												AND c.NOMOR_IDENTITAS = a.NOMOR_IDENTITAS
												AND c.KODE_TIPE_KLAIM = 'JHT01'
												AND c.STATUS_BATAL = 'T'
												AND c.STATUS_KLAIM = 'SELESAI'
										))
				"; 
	$proc = $DB_EC->parse($sql_sippklaim);
	$param_bv = array(':p_kpj' => $ls_kpj, ':p_nik' => $ls_nomor_identitas);
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
	$DB_EC->execute();
	$row_sippklaim = $DB_EC->nextrow();
	$ls_cnt_atr_sipp = $row_sippklaim["CNT_ANTRIAN"];
	
	//--- start pengecekan pengajuan klaim lewat SIPP
	
	if ($ls_jml_pengajuan_antol > 0 && ($ls_kode_tipe_klaim == "JHT01" || $ls_kode_tipe_klaim == "JHM01"))
	{
		echo '{"ret":-1,"msg":"Proses gagal, data gagal disimpan karena KPJ '.$ls_kpj.', NIK '.$ls_nomor_identitas.' dan Nama '.$ls_nama_tk.' masih dalam proses persetujuan pengajuan klaim antrian online di kantor cabang pengajuan '.$ls_nama_kantor_antol.' dengan kode booking '.$ls_kode_booking_antol.'!!!"}';
	}
	elseif ($ls_cnt_atr_sipp > 0 && ($ls_kode_tipe_klaim == "JHT01" || $ls_kode_tipe_klaim == "JHM01"))
	{
		echo '{"ret":-1,"msg":"Proses gagal, data gagal disimpan karena KPJ '.$ls_kpj.', NIK '.$ls_nomor_identitas.' dan Nama '.$ls_nama_tk.' masih dalam proses pengajuan klaim JHT kolektif di SIPP."}';
	}
	elseif ($ls_jml_pengajuan_jmoklaim > 0 && ($ls_kode_tipe_klaim == "JHT01" || $ls_kode_tipe_klaim == "JHM01"))
	{
		echo '{"ret":-1,"msg":"Proses gagal, data gagal disimpan karena KPJ '.$ls_kpj.', NIK '.$ls_nomor_identitas.' dan Nama '.$ls_nama_tk.' masih dalam proses pengajuan klaim lewat JMO di kantor cabang pengajuan '.$ls_nama_kantor_jmoklaim.' dengan kode booking '.$ls_kode_booking_jmoklaim.'!!!"}';
	}
	else
	{
		  //INSERT ---------------------------------------------------------------------
		  //generate kode klaim --------------------------------------------------------
		  $sql = 	"select sijstk.p_pn_genid.f_gen_kodeklaim as v_kode_klaim from dual ";
		  $DB->parse($sql);
		  $DB->execute();
		  $row = $DB->nextrow();
		  $ls_kode_klaim = $row["V_KODE_KLAIM"];
			
			//insert data ----------------------------------------------------	 
		  $sql = "insert into sijstk.pn_klaim ( ".
				 "	 kode_klaim, no_klaim, kode_kantor, kode_segmen, ".
						 "	 kode_perusahaan, kode_divisi, kode_proyek, kode_tk, nama_tk, kpj, ".
				 "	 nomor_identitas, jenis_identitas, kode_kantor_tk, ".
				 "	 kode_tipe_klaim, kode_sebab_klaim, tgl_klaim, ".			
				 "	 tgl_lapor, keterangan, tgl_kejadian, ".
						 "	 status_kepesertaan, kode_perlindungan, ".
						 "	 tgl_awal_perlindungan, tgl_akhir_perlindungan, ".  
						 "	 petugas_agenda, tgl_rekam, petugas_rekam, kanal_pelayanan) ".
				 "values ( ".
						 "	 :p_kode_klaim,:p_kode_klaim,:p_kode_kantor,:p_kode_segmen, ".
						 "	 :p_kode_perusahaan,:p_kode_divisi,:p_kode_proyek,:p_kode_tk,:p_nama_tk,:p_kpj, ".
						 "	 :p_nomor_identitas,:p_jenis_identitas,:p_kode_kantor_tk, ".
						 "	 :p_kode_tipe_klaim,:p_kode_sebab_klaim, to_date(:p_tgl_klaim,'dd/mm/yyyy'), ".
						 "	 to_date(:p_tgl_lapor,'dd/mm/yyyy'), :p_keterangan, to_date(:p_tgl_kejadian,'dd/mm/yyyy'), ".
						 "	 :p_status_kepesertaan, :p_kode_perlindungan, ". 
						 "	 to_date(:p_tgl_awal_perlindungan,'dd/mm/yyyy'), to_date(:p_tgl_akhir_perlindungan,'dd/mm/yyyy'), ".
						 "	 :p_username, sysdate,:p_username, :p_kode_kanal_pelayanan ".
						 ") ";
			$proc = $DB->parse($sql);
			$param_bv = array(
				':p_kode_klaim' => $ls_kode_klaim,
				':p_kode_kantor' => $ls_kode_kantor,
				':p_kode_segmen' => $ls_kode_segmen,
				':p_kode_perusahaan' => $ls_kode_perusahaan,
				':p_kode_divisi' => $ls_kode_divisi,
				':p_kode_proyek' => $ls_kode_proyek,
				':p_kode_tk' => $ls_kode_tk,
				':p_nama_tk' => $ls_nama_tk,
				':p_kpj' => $ls_kpj,
				':p_nomor_identitas' => $ls_nomor_identitas,
				':p_jenis_identitas' => $ls_jenis_identitas,
				':p_kode_kantor_tk' => $ls_kode_kantor_tk,
				':p_kode_tipe_klaim' => $ls_kode_tipe_klaim,
				':p_kode_sebab_klaim' => $ls_kode_sebab_klaim,
				':p_tgl_klaim' => $ld_tgl_klaim,
				':p_tgl_lapor' => $ld_tgl_lapor,
				':p_keterangan' => $ls_keterangan,
				':p_tgl_kejadian' => $ld_tgl_kejadian,
				':p_status_kepesertaan' => $ls_status_kepesertaan,
				':p_kode_perlindungan' => $ls_kode_perlindungan,
				':p_tgl_awal_perlindungan' => $ld_tgl_awal_perlindungan,
				':p_tgl_akhir_perlindungan' => $ld_tgl_akhir_perlindungan,
				':p_username' => $username,
				':p_kode_kanal_pelayanan' => $ls_kode_kanal_pelayanan,
			);
			foreach ($param_bv as $key => $value) {
				oci_bind_by_name($proc, $key, $param_bv[$key]);
			}
		  if($DB->execute())
			{		 	
			//jalankan proses post insert ----------------------------------------------
				$qry = "BEGIN SIJSTK.P_PN_PN5040.X_POST_INSERT(:p_kode_klaim,:p_username,:p_sukses,:p_mess);END;";											 	
				$proc = $DB->parse($qry);				
				oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim,100);
				oci_bind_by_name($proc, ":p_username", $username,100);
				oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
				oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
				$DB->execute();				
				$ls_sukses = $p_sukses;	
				$ls_mess = $p_mess;		 									 
				
				/* Penambahan Terhadap Integrasi Agenda Klaim dan Sistem Antrian*/
				//if($ls_kode_sisla != ""){
					$ls_kode_antrian = "";
					$ls_kode_pointer_asal = "PN5001";
					$qry = "
					begin 
						pn.p_pn_antrian.x_insert_antrian
						(
							:p_kode_jenis_antrian        ,
							:p_kode_status_antrian       , 
							:p_token_antrian             ,
							:p_kode_sisla                ,
							:p_kode_kantor_antrian       ,
							:p_no_antrian                ,
							:p_tgl_ambil                 ,
							:p_tgl_panggil               ,
							:p_kode_petugas_antrian      ,
							:p_nomor_identitas           ,  
							:p_no_hp                     ,
							:p_email                     ,
							:p_kode_klaim                ,
							:p_kode_jenis_antrian_detil  ,
							:p_keterangan                , 
							:p_kode_pointer_asal         ,      
							:p_user                      , 
							:p_sukses                    , 
							:p_mess                      ,
							:p_kode_antrian               
						); 
					end;
					";
					$proc = $DB->parse($qry);     
					oci_bind_by_name($proc, ":p_token_antrian", $ls_token_sisla, 500);
					oci_bind_by_name($proc, ":p_kode_jenis_antrian", $ls_kode_jenis_antrian,10);  
					oci_bind_by_name($proc, ":p_kode_sisla", $ls_kode_sisla, 100);
					oci_bind_by_name($proc, ":p_no_antrian", $ls_nomor_antrian,30);  
					oci_bind_by_name($proc, ":p_kode_kantor_antrian", $ls_kode_kantor_antrian, 10);
					oci_bind_by_name($proc, ":p_tgl_ambil", $ld_tgl_ambil_antrian,50);  
					oci_bind_by_name($proc, ":p_tgl_panggil", $ld_tgl_panggil_antrian, 50);
					oci_bind_by_name($proc, ":p_kode_petugas_antrian", $ls_kode_petugas_antrian,30); 
					oci_bind_by_name($proc, ":p_nomor_identitas", $ls_nomor_identitas,20);  
					oci_bind_by_name($proc, ":p_no_hp", $ls_no_hp_antrian, 20);
					oci_bind_by_name($proc, ":p_email", $ls_email_antrian,50);  
					oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim, 30);
					oci_bind_by_name($proc, ":p_kode_status_antrian", $ls_kode_status_antrian, 10);
					oci_bind_by_name($proc, ":p_kode_jenis_antrian_detil", $ls_kode_jenis_antrian_detil,10);  
					oci_bind_by_name($proc, ":p_keterangan", $ls_keterangan, 1000);
					oci_bind_by_name($proc, ":p_kode_pointer_asal", $ls_kode_pointer_asal, 50);
					oci_bind_by_name($proc, ":p_user", $username,1000);         
					oci_bind_by_name($proc, ":p_sukses", $p_sukses, 2);
					oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
					oci_bind_by_name($proc, ":p_kode_antrian", $p_kode_antrian, 30);
					$DB->execute();				
					$ls_sukses_antrian = $p_sukses;	
					$ls_mess_antrian = $p_mess;	
					$ls_kode_antrian = $p_kode_antrian;	

					/*
					//generate kode klaim --------------------------------------------------------
					$sql = 	"select pn.p_pn_genid.f_gen_kode_antrian as kode_antrian from dual ";
					$DB->parse($sql);
					$DB->execute();
					$row = $DB->nextrow();
					$ls_kode_antrian = $row["KODE_ANTRIAN"];

					//insert data pn_antrian----------------------------------------------------	 
					$sql = 
					"insert into pn.pn_antrian ( ".
					"	 kode_antrian, kode_jenis_antrian, kode_status_antrian, ". 
					"    token_sisla, kode_sisla, kode_kantor, nomor_antrian, ". 
					"    tgl_ambil_antrian, tgl_panggil_antrian, petugas_panggil, ".
					"    id_pointer_asal, kode_pointer_asal, nomor_identitas, no_hp, email, ".
					"    kode_klaim_agenda, keterangan, tgl_rekam, petugas_rekam) ".
					"values ( ".
					"	 '$ls_kode_antrian', '$ls_kode_jenis_antrian', '$ls_kode_status_antrian', ".
					"	 '$ls_token_sisla', '$ls_kode_sisla','$ls_kode_kantor_antrian','$ls_nomor_antrian', ".
					"	 to_date('$ld_tgl_ambil_antrian','YYYY-MM-DD HH24:MI:SS'), to_date('$ld_tgl_panggil_antrian','YYYY-MM-DD HH24:MI:SS'), '$ls_kode_petugas_antrian', ".
					"	 '$ls_kode_antrian', 'PN5001','$ls_nomor_identitas', '$ls_no_hp_antrian', '$ls_email_antrian', ".
					"	 '$ls_kode_klaim', '$ls_keterangan', sysdate, '$username' ".
					") ";				 
					$DB->parse($sql);
					$DB->execute();

					//insert data pn_antrian_program----------------------------------------------------	 
					$sql = 
					"insert into pn.pn_antrian_program ( ".
					"	 kode_antrian, kode_jenis_antrian, kode_jenis_antrian_detil, ". 
					"    keterangan, tgl_rekam, petugas_rekam) ".
					"values ( ".
					"	 '$ls_kode_antrian', '$ls_kode_jenis_antrian', '$ls_kode_jenis_antrian_detil', ".
					"	 '$ls_keterangan', sysdate, '$username' ".
					") ";				 
					$DB->parse($sql);
					$DB->execute();

					//insert data pn_antrian_dokumen----------------------------------------------------	 
					$sql = 
					"insert into pn.pn_antrian_dokumen ( ".
					"	 kode_antrian, kode_dokumen, flag_upload, ". 
					"    keterangan, tgl_rekam, petugas_rekam) ".
					"values ( ".
					"	 '$ls_kode_antrian', 'D221', 'T', ".
					"	 '$ls_keterangan', sysdate, '$username' ".
					") ";				 
					$DB->parse($sql);
					$DB->execute();
					*/
				//}
				/* End Penambahan Terhadap Integrasi Agenda Klaim dan Sistem Antrian*/

				echo '{"ret":0,"msg":"Sukses, Data agenda berhasil disimpan, session dilanjutkan..","DATAID":"'.$ls_kode_klaim.'","kodeAntrian":"'.$ls_kode_antrian.'"}';		
		  }else {
			echo '{"ret":-1,"msg":"Proses gagal, data gagal ditambahkan...!!!"}';
		  }
	}
}
else if ($TYPE=="Edit" && $DATAID != '')
{	 
  $ls_sukses = "0";
  $sql_cek_status_klaim = "select status_klaim from pn.pn_klaim where kode_klaim = :p_kode_klaim ";
	$proc = $DB->parse($sql_cek_status_klaim);
	oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
	// 20-03-2024 penyesuaian bind variable
	$DB->execute();
	$row = $DB->nextrow();
	$ls_cek_status_klaim = $row['STATUS_KLAIM'];

  	if($ls_cek_status_klaim != $ls_status_klaim && $ls_kode_tipe_klaim == 'JKK01' && ($ls_kode_kanal_pelayanan == 'KANAL03' || $ls_kode_kanal_pelayanan == '66'))
  	{
		echo '{"ret":-1,"msg":"Data klaim sudah diproses sampai dengan '.$ls_cek_status_klaim.'. Silahkan lakukan pengecekan pada menu PN5030 - DAFTAR KLAIM.","DATAID":"'.$ls_kode_klaim.'"}';
	} else {
		//update jika status klaim masih agenda/agenda tahap I -----------------------
		if (($ls_status_klaim=="AGENDA") || ($ls_status_klaim=="AGENDA_TAHAP_I"))
		{
			$sql = "update sijstk.pn_klaim set ".
			"	 kode_kantor			= :p_kode_kantor, ". 
					"	 kode_segmen			= :p_kode_segmen, ".
					"	 kode_perusahaan	= :p_kode_perusahaan, ". 
					"	 kode_divisi			= :p_kode_divisi, ". 
					"	 kode_proyek			= :p_kode_proyek, ".
					"	 kode_tk					= :p_kode_tk, ". 
					"	 nama_tk					= :p_nama_tk, ".
						"	 kpj							= :p_kpj, ".
			"	 nomor_identitas	= :p_nomor_identitas, ". 
					"	 jenis_identitas	= :p_jenis_identitas, ". 
					"	 kode_kantor_tk		= :p_kode_kantor_tk, ".
			"	 kode_tipe_klaim	= :p_kode_tipe_klaim, ". 
					"	 kode_sebab_klaim	= :p_kode_sebab_klaim, ". 
					"	 tgl_klaim				= to_date(:p_tgl_klaim,'dd/mm/yyyy'), ".
			"	 tgl_lapor				= to_date(:p_tgl_lapor,'dd/mm/yyyy'), ". 
					"	 tgl_kejadian = to_date(:p_tgl_kejadian,'dd/mm/yyyy'), ".
					"	 status_kepesertaan = :p_status_kepesertaan, ".
					"	 kode_perlindungan 	= :p_kode_perlindungan, ".
					"	 tgl_awal_perlindungan = to_date(:p_tgl_awal_perlindungan,'dd/mm/yyyy'), ".
					"	 tgl_akhir_perlindungan =to_date(:p_tgl_akhir_perlindungan,'dd/mm/yyyy') , ".
					"	 keterangan				= :p_keterangan, ". 
						"	 status_klaim 		= :p_status_klaim, ".
					"	 tgl_ubah					= sysdate, ". 
					"	 petugas_ubah 		= :p_username, ".
					"	 kanal_pelayanan 		= :p_kode_kanal_pelayanan ".
           "where kode_klaim = :p_kode_klaim ";
	$proc = $DB->parse($sql);
	$param_bv = array(
		':p_kode_kantor' => $ls_kode_kantor,
		':p_kode_segmen' => $ls_kode_segmen,
		':p_kode_perusahaan' => $ls_kode_perusahaan,
		':p_kode_divisi' => $ls_kode_divisi,
		':p_kode_proyek' => $ls_kode_proyek,
		':p_kode_tk' => $ls_kode_tk,
		':p_nama_tk' => $ls_nama_tk,
		':p_kpj' => $ls_kpj,
		':p_nomor_identitas' => $ls_nomor_identitas,
		':p_jenis_identitas' => $ls_jenis_identitas,
		':p_kode_kantor_tk' => $ls_kode_kantor_tk,
		':p_kode_tipe_klaim' => $ls_kode_tipe_klaim,
		':p_kode_sebab_klaim' => $ls_kode_sebab_klaim,
		':p_tgl_klaim' => $ld_tgl_klaim,
		':p_tgl_lapor' => $ld_tgl_lapor,
		':p_tgl_kejadian' => $ld_tgl_kejadian,
		':p_status_kepesertaan' => $ls_status_kepesertaan,
		':p_kode_perlindungan' => $ls_kode_perlindungan,
		':p_tgl_awal_perlindungan' => $ld_tgl_awal_perlindungan,
		':p_tgl_akhir_perlindungan' => $ld_tgl_akhir_perlindungan,
		':p_keterangan' => $ls_keterangan,
		':p_status_klaim' => $ls_status_klaim,
		':p_username' => $username,
		':p_kode_kanal_pelayanan' => $ls_kode_kanal_pelayanan,
		':p_kode_klaim' => $ls_kode_klaim,
	);
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
	// 20-03-2024 penyesuaian bind variable
    if($DB->execute())
  	{
			// ---------------- KLAIM JHT --------------------------------------------
			if ($ls_jenis_klaim=="JHT")
			{
  		 	 if ($ls_status_klaim=="AGENDA")
  			 {
      			//update data input klaim jht --------------------------------------
            $ls_jhtinput_kode_tipe_penerima_old	= $_POST["jhtinput_kode_tipe_penerima_old"];
						$ls_jhtinput_kode_manfaat						= $_POST["jhtinput_kode_manfaat"];
						$ls_jhtinput_no_urut								= $_POST["jhtinput_no_urut"];
						
						$ls_jhtinput_kode_tipe_penerima 	= $_POST["jhtinput_kode_tipe_penerima"];
            $ls_jhtinput_kode_hubungan 				= $_POST["jhtinput_kode_hubungan"];
            $ls_jhtinput_ket_hubungan_lainnya	= $_POST["jhtinput_ket_hubungan_lainnya"];
            $ls_jhtinput_no_urut_keluarga 		= $_POST["jhtinput_no_urut_keluarga"];
            $ls_jhtinput_nomor_identitas 			= $_POST["jhtinput_nomor_identitas"];
            $ls_jhtinput_nama_pemohon 				= $_POST["jhtinput_nama_pemohon"];
            $ls_jhtinput_tempat_lahir 				= $_POST["jhtinput_tempat_lahir"];
            $ld_jhtinput_tgl_lahir 						= $_POST["jhtinput_tgl_lahir"];
            $ls_jhtinput_jenis_kelamin 				= $_POST["jhtinput_jenis_kelamin"];
						$ls_jhtinput_golongan_darah				= $_POST["jhtinput_golongan_darah"];
            $ls_jhtinput_alamat 							= $_POST["jhtinput_alamat"];
            $ls_jhtinput_rt 									= $_POST["jhtinput_rt"];
            $ls_jhtinput_rw 									= $_POST["jhtinput_rw"];
            $ls_jhtinput_kode_kelurahan 			= $_POST["jhtinput_kode_kelurahan"];
            $ls_jhtinput_nama_kelurahan 			= $_POST["jhtinput_nama_kelurahan"];
            $ls_jhtinput_kode_kecamatan 			= $_POST["jhtinput_kode_kecamatan"];
            $ls_jhtinput_nama_kecamatan 			= $_POST["jhtinput_nama_kecamatan"];
            $ls_jhtinput_kode_kabupaten 			= $_POST["jhtinput_kode_kabupaten"];
            $ls_jhtinput_nama_kabupaten 			= $_POST["jhtinput_nama_kabupaten"];
            $ls_jhtinput_kode_pos 						= $_POST["jhtinput_kode_pos"];
            $ls_jhtinput_telepon_area 				= $_POST["jhtinput_telepon_area"];
            $ls_jhtinput_telepon 							= $_POST["jhtinput_telepon"];
            $ls_jhtinput_telepon_ext 					= $_POST["jhtinput_telepon_ext"];
            $ls_jhtinput_handphone 						= $_POST["jhtinput_handphone"];
            $ls_jhtinput_email 								= $_POST["jhtinput_email"];
            $ls_jhtinput_npwp 								= $_POST["jhtinput_npwp"];
			$ls_jhtinput_kode_bhp							= $_POST["jhtinput_kode_bhp"]; //update 28/01/2020 bhp

			if ($ls_jhtinput_handphone =="" and $ls_no_hp_antrian != ""){
				$ls_jhtinput_handphone = $ls_no_hp_antrian;
			}

			if ($ls_jhtinput_email =="" and $ls_email_antrian != ""){
				$ls_jhtinput_email = $ls_email_antrian;
			}
  					
					if ($ls_jhtinput_ket_hubungan_lainnya!="")
  					{
  					 	$ls_jhtinput_keterangan = $ls_jhtinput_ket_hubungan_lainnya; 
  					}else
  					{
  					 	$ls_jhtinput_keterangan = "";	 
  					}
												
						//sesuaikan tipe penerima yg di manfaat detil ----------------------
						//06012022, untuk kebutuhan syariah, jika $ls_jhtinput_no_urut kosong maka di set dengan in (1,2) untuk 1 = konvensional dan 2 = syariah
						if($ls_jhtinput_no_urut !="")
						{
							$sql2 = "update sijstk.pn_klaim_manfaat_detil set ".
  								 	"	kode_tipe_penerima 	 = :p_jhtinput_kode_tipe_penerima, ".                  
									"	tgl_ubah						 = sysdate, ". 
  									"	petugas_ubah				 = p_username ".
									"where kode_klaim = :p_kode_klaim ".
  									"and kode_manfaat = :p_jhtinput_kode_manfaat ".
  									"and no_urut = :p_jhtinput_no_urut ";			
							$proc = $DB->parse($sql2);
							$param_bv = array(
								':p_jhtinput_kode_tipe_penerima' => $ls_jhtinput_kode_tipe_penerima,
								':p_username' => $username,
								':p_kode_klaim' => $ls_kode_klaim,
								':p_jhtinput_kode_manfaat' => $ls_jhtinput_kode_manfaat,
								':p_jhtinput_no_urut' => $ls_jhtinput_no_urut,
							);
							foreach ($param_bv as $key => $value) {
								oci_bind_by_name($proc, $key, $param_bv[$key]);
							}
							// 20-03-2024 penyesuaian bind variable
							$DB->execute();		
						}else
						{
							$sql2 = "update sijstk.pn_klaim_manfaat_detil set ".
  								 	"	kode_tipe_penerima 	 = :p_jhtinput_kode_tipe_penerima, ".                  
									"	tgl_ubah						 = sysdate, ". 
  									"	petugas_ubah				 = :p_username ".
									"where kode_klaim = :p_kode_klaim ".
  									"and kode_manfaat = :p_jhtinput_kode_manfaat ".
  									"and no_urut in (1,2) ";			
							$proc = $DB->parse($sql2);
							$param_bv = array(
								':p_jhtinput_kode_tipe_penerima' => $ls_jhtinput_kode_tipe_penerima,
								':p_username' => $username,
								':p_kode_klaim' => $ls_kode_klaim,
								':p_jhtinput_kode_manfaat' => $ls_jhtinput_kode_manfaat,
							);
							foreach ($param_bv as $key => $value) {
								oci_bind_by_name($proc, $key, $param_bv[$key]);
							}
							// 20-03-2024 penyesuaian bind variable
							$DB->execute();	
						}
												
						//update data penerima manfaat -------------------------------------
						$sql = "update sijstk.pn_klaim_penerima_manfaat set ".
								 	 "	kode_tipe_penerima 	 = :p_jhtinput_kode_tipe_penerima, ".		 
                   "	kode_hubungan				 = :p_jhtinput_kode_hubungan, ". 
									 "	ket_hubungan_lainnya = :p_jhtinput_ket_hubungan_lainnya, ".
									 "	no_urut_keluarga		 = :p_jhtinput_no_urut_keluarga, ".  
                   "	nomor_identitas			 = :p_jhtinput_nomor_identitas, ".  
									 "	nama_pemohon				 = :p_jhtinput_nama_pemohon, ". 
									 "	tempat_lahir				 = :p_jhtinput_tempat_lahir, ".  
									 "	tgl_lahir						 = to_date(:p_jhtinput_tgl_lahir,'dd/mm/yyyy'), ".  
									 "	jenis_kelamin				 = :p_jhtinput_jenis_kelamin, ".
									 "	golongan_darah			 = :p_jhtinput_golongan_darah, ".  
                   "	alamat							 = :p_jhtinput_alamat, ". 
									 "	rt									 = :p_jhtinput_rt, ".  
									 "	rw									 = :p_jhtinput_rw, ".  
									 "	kode_kelurahan			 = :p_jhtinput_kode_kelurahan, ".  
									 "	kode_kecamatan			 = :p_jhtinput_kode_kecamatan, ". 
									 "	kode_kabupaten			 = :p_jhtinput_kode_kabupaten, ".  
									 "	kode_pos						 = :p_jhtinput_kode_pos, ".  
                   "	telepon_area				 = :p_jhtinput_telepon_area, ".  
									 "	telepon							 = :p_jhtinput_telepon, ".  
									 "	telepon_ext					 = :p_jhtinput_telepon_ext, ".  
									 "	handphone						 = :p_jhtinput_handphone, ". 
									 "	email								 = :p_jhtinput_email, ". 
									 "	npwp								 = :p_jhtinput_npwp, ". 
									 "	nama_penerima				 = :p_jhtinput_nama_pemohon, ".  
									 "	kode_bhp				 		 = :p_jhtinput_kode_bhp, ".
                   "	tgl_ubah						 = sysdate, ". 
									 "	petugas_ubah				 = :p_username ".
                   "where kode_klaim = :p_kode_klaim ".
									 "and kode_tipe_penerima = :p_jhtinput_kode_tipe_penerima_old ";			
			$proc = $DB->parse($sql);
			$param_bv = array(
				':p_jhtinput_kode_tipe_penerima' => $ls_jhtinput_kode_tipe_penerima,
				':p_jhtinput_kode_hubungan' => $ls_jhtinput_kode_hubungan,
				':p_jhtinput_ket_hubungan_lainnya' => $ls_jhtinput_ket_hubungan_lainnya,
				':p_jhtinput_no_urut_keluarga' => $ls_jhtinput_no_urut_keluarga,
				':p_jhtinput_nomor_identitas' => $ls_jhtinput_nomor_identitas,
				':p_jhtinput_nama_pemohon' => $ls_jhtinput_nama_pemohon,
				':p_jhtinput_tempat_lahir' => $ls_jhtinput_tempat_lahir,
				':p_jhtinput_tgl_lahir' => $ld_jhtinput_tgl_lahir,
				':p_jhtinput_jenis_kelamin' => $ls_jhtinput_jenis_kelamin,
				':p_jhtinput_golongan_darah' => $ls_jhtinput_golongan_darah,
				':p_jhtinput_alamat' => $ls_jhtinput_alamat,
				':p_jhtinput_rt' => $ls_jhtinput_rt,
				':p_jhtinput_rw' => $ls_jhtinput_rw,
				':p_jhtinput_kode_kelurahan' => $ls_jhtinput_kode_kelurahan,
				':p_jhtinput_kode_kecamatan' => $ls_jhtinput_kode_kecamatan,
				':p_jhtinput_kode_kabupaten' => $ls_jhtinput_kode_kabupaten,
				':p_jhtinput_kode_pos' => $ls_jhtinput_kode_pos,
				':p_jhtinput_telepon_area' => $ls_jhtinput_telepon_area,
				':p_jhtinput_telepon' => $ls_jhtinput_telepon,
				':p_jhtinput_telepon_ext' => $ls_jhtinput_telepon_ext,
				':p_jhtinput_handphone' => $ls_jhtinput_handphone,
				':p_jhtinput_email' => $ls_jhtinput_email,
				':p_jhtinput_npwp' => $ls_jhtinput_npwp,
				':p_jhtinput_nama_pemohon' => $ls_jhtinput_nama_pemohon,
				':p_jhtinput_kode_bhp' => $ls_jhtinput_kode_bhp,
				':p_username' => $username,
				':p_kode_klaim' => $ls_kode_klaim,
				':p_jhtinput_kode_tipe_penerima_old' => $ls_jhtinput_kode_tipe_penerima_old,
			);
			foreach ($param_bv as $key => $value) {
				oci_bind_by_name($proc, $key, $param_bv[$key]);
			}
			// 20-03-2024 penyesuaian bind variable
            $DB->execute();
			
			//update 28/01/2020 - bhp ------------------------------------------
			//jika tipe penerima bhp maka sekaligus ambilkan data rekeningnya --
			if ($ls_jhtinput_kode_tipe_penerima=="BH" && $ls_jhtinput_kode_bhp!="")
			{ 
              $sql = "select ". 
                     "	bank_penerima_bhp, no_rekening_penerima_bhp, nama_rekening_penerima_bhp ".
                     "from pn.pn_kode_bhp ".
                     "where kode_bhp = :p_kode_bhp";
              $proc = $DB->parse($sql);
				oci_bind_by_name($proc, ':p_kode_bhp', $ls_jhtinput_kode_bhp, 100);
				// 20-03-2024 penyesuaian bind variable
              $DB->execute();
              $row = $DB->nextrow();
              $ls_bank_penerima_bhp	 	 				= $row['BANK_PENERIMA_BHP'];
      				$ls_no_rekening_penerima_bhp		= $row['NO_REKENING_PENERIMA_BHP'];
      				$ls_nama_rekening_penerima_bhp	= $row['NAMA_REKENING_PENERIMA_BHP'];
							
							$sql = "update sijstk.pn_klaim_penerima_manfaat set ".
  								 	 "	bank_penerima					 = :p_bank_penerima_bhp, ". 
										 "	no_rekening_penerima	 = :p_no_rekening_penerima_bhp, ". 
										 "	nama_rekening_penerima = :p_nama_rekening_penerima_bhp ".
                     "where kode_klaim = :p_kode_klaim ".
  									 "and kode_tipe_penerima = :p_jhtinput_kode_tipe_penerima ";			
              $proc = $DB->parse($sql);
				$param_bv = array(
					':p_bank_penerima_bhp' => $ls_bank_penerima_bhp,
					':p_no_rekening_penerima_bhp' => $ls_no_rekening_penerima_bhp,
					':p_nama_rekening_penerima_bhp' => $ls_nama_rekening_penerima_bhp,
					':p_kode_klaim' => $ls_kode_klaim,
					':p_jhtinput_kode_tipe_penerima' => $ls_jhtinput_kode_tipe_penerima,
				);
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
				// 20-03-2024 penyesuaian bind variable
              $DB->execute();					
			}
			//end jika tipe penerima bhp maka sekaligus ambilkan data rekeningnya --
						
						//sinkronisasi data tk keluarga ------------------------------------
            $sql = 	"select count(*) as v_cnt from sijstk.kn_tk_keluarga ".
            		 		"where kode_tk = :p_kode_tk and kode_hubungan = :p_jhtinput_kode_hubungan ";
            $proc = $DB->parse($sql);
			oci_bind_by_name($proc, ':p_kode_tk', $ls_kode_tk, 100);
			oci_bind_by_name($proc, ':p_jhtinput_kode_hubungan', $ls_jhtinput_kode_hubungan, 100);
			// 20-03-2024 penyesuaian bind variable
            $DB->execute();
            $row = $DB->nextrow();
            $ln_cnt = $row["V_CNT"];
													
						if ($ln_cnt=="0")
						{
              $sql = 	"select nvl(max(no_urut_keluarga),0)+1 as v_no from sijstk.kn_tk_keluarga ".
              		 		"where kode_tk = :p_kode_tk ";
              $proc = $DB->parse($sql);
				oci_bind_by_name($proc, ':p_kode_tk', $ls_kode_tk, 100);
				// 20-03-2024 penyesuaian bind variable
              $DB->execute();
              $row = $DB->nextrow();
              $ln_no_urut = $row["V_NO"];
										
  						$sql = "insert into sijstk.kn_tk_keluarga ( ".
                     "    kode_tk, no_urut_keluarga, kode_hubungan, nama_lengkap, no_kartu_keluarga, tempat_lahir, ". 
                     "    tgl_lahir, jenis_kelamin, golongan_darah, alamat, kode_kabupaten, kode_pos, ". 
                     "    telepon_area, telepon, telepon_ext, fax_area, fax, handphone, email, npwp, keterangan, ". 
                     "    kpj_tertanggung, aktif, nomor_identitas, rt, rw, kode_kelurahan, kode_kecamatan, ".
                     "    tgl_rekam, petugas_rekam) ". 
                     "values ( ".
										 "    :p_kode_tk, :p_no_urut, :p_jhtinput_kode_hubungan, :p_jhtinput_nama_pemohon, null, :p_jhtinput_tempat_lahir, ". 
                     "    to_date(nvl(:p_jhtinput_tgl_lahir,'01/01/1800'),'dd/mm/yyyy'), :p_jhtinput_jenis_kelamin, :p_jhtinput_golongan_darah, :p_jhtinput_alamat, :p_jhtinput_kode_kabupaten, :p_jhtinput_kode_pos, ". 
                     "    :p_jhtinput_telepon_area, :p_jhtinput_telepon, :p_jhtinput_telepon_ext, null, null, :p_jhtinput_handphone, :p_jhtinput_email, :p_jhtinput_npwp, :p_jhtinput_keterangan, ". 
                     "    null, 'Y', :p_jhtinput_nomor_identitas, :p_jhtinput_rt, :p_jhtinput_rw, :p_jhtinput_kode_kelurahan, :p_jhtinput_kode_kecamatan, ".
                     "    sysdate, :p_username ". 
										 ") "; 
              $proc = $DB->parse($sql);
				$param_bv = array(
					':p_kode_tk' => $ls_kode_tk,
					':p_no_urut' => $ln_no_urut,
					':p_jhtinput_kode_hubungan' => $ls_jhtinput_kode_hubungan,
					':p_jhtinput_nama_pemohon' => $ls_jhtinput_nama_pemohon,
					':p_jhtinput_tempat_lahir' => $ls_jhtinput_tempat_lahir,
					':p_jhtinput_tgl_lahir' => $ld_jhtinput_tgl_lahir,
					':p_jhtinput_jenis_kelamin' => $ls_jhtinput_jenis_kelamin,
					':p_jhtinput_golongan_darah' => $ls_jhtinput_golongan_darah,
					':p_jhtinput_alamat' => $ls_jhtinput_alamat,
					':p_jhtinput_kode_kabupaten' => $ls_jhtinput_kode_kabupaten,
					':p_jhtinput_kode_pos' => $ls_jhtinput_kode_pos,
					':p_jhtinput_telepon_area' => $ls_jhtinput_telepon_area,
					':p_jhtinput_telepon' => $ls_jhtinput_telepon,
					':p_jhtinput_telepon_ext' => $ls_jhtinput_telepon_ext,
					':p_jhtinput_handphone' => $ls_jhtinput_handphone,
					':p_jhtinput_email' => $ls_jhtinput_email,
					':p_jhtinput_npwp' => $ls_jhtinput_npwp,
					':p_jhtinput_keterangan' => $ls_jhtinput_keterangan,
					':p_jhtinput_nomor_identitas' => $ls_jhtinput_nomor_identitas,
					':p_jhtinput_rt' => $ls_jhtinput_rt,
					':p_jhtinput_rw' => $ls_jhtinput_rw,
					':p_jhtinput_kode_kelurahan' => $ls_jhtinput_kode_kelurahan,
					':p_jhtinput_kode_kecamatan' => $ls_jhtinput_kode_kecamatan,
					':p_username' => $username,
				);
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
				// 20-03-2024 penyesuaian bind variable
              $DB->execute();				
						}else
						{
  						if ($ls_jhtinput_no_urut_keluarga == "")
							{
  							$sql = "update sijstk.kn_tk_keluarga set ".
                       "    nama_lengkap				= :p_jhtinput_nama_pemohon, ". 
  										 "		tempat_lahir				= :p_jhtinput_tempat_lahir, ".
                       "    tgl_lahir						= to_date(nvl(:p_jhtinput_tgl_lahir,'01/01/1800'),'dd/mm/yyyy'), ".
  										 "		jenis_kelamin				= :p_jhtinput_jenis_kelamin, ".
											 "		golongan_darah			= :p_jhtinput_golongan_darah, ".
  										 "		alamat							= :p_jhtinput_alamat, ".
  										 "		rt									= :p_jhtinput_rt, ".
  										 "		rw									= :p_jhtinput_rw, ".
  										 "		kode_kelurahan			= :p_jhtinput_kode_kelurahan, ".
  										 "		kode_kecamatan			= :p_jhtinput_kode_kecamatan, ".											 
  										 "		kode_kabupaten			= :p_jhtinput_kode_kabupaten, ".
  										 "		kode_pos						= :p_jhtinput_kode_pos, ".
                       "    telepon_area				= :p_jhtinput_telepon_area, ".
  										 "		telepon							= :p_jhtinput_telepon, ".
  										 "		telepon_ext					= :p_jhtinput_telepon_ext, ".
  										 "		handphone						= :p_jhtinput_handphone, ".
  										 "		email								= :p_jhtinput_email, ".
  										 "		npwp								= :p_jhtinput_npwp, ".
  										 "		keterangan					= :p_jhtinput_keterangan, ".
                       "		aktif								= 'Y', ".
  										 "		nomor_identitas			= :p_jhtinput_nomor_identitas, ".
                       "    tgl_ubah						= sysdate, ".
  										 "		petugas_ubah				= :p_username ".
                       "where kode_tk = :p_kode_tk ".
  										 "and kode_hubungan = :p_jhtinput_kode_hubungan ";
                $proc = $DB->parse($sql);
				$param_bv = array(
					':p_jhtinput_nama_pemohon' => $ls_jhtinput_nama_pemohon,
					':p_jhtinput_tempat_lahir' => $ls_jhtinput_tempat_lahir,
					':p_jhtinput_tgl_lahir' => $ld_jhtinput_tgl_lahir,
					':p_jhtinput_jenis_kelamin' => $ls_jhtinput_jenis_kelamin,
					':p_jhtinput_golongan_darah' => $ls_jhtinput_golongan_darah,
					':p_jhtinput_alamat' => $ls_jhtinput_alamat,
					':p_jhtinput_rt' => $ls_jhtinput_rt,
					':p_jhtinput_rw' => $ls_jhtinput_rw,
					':p_jhtinput_kode_kelurahan' => $ls_jhtinput_kode_kelurahan,
					':p_jhtinput_kode_kecamatan' => $ls_jhtinput_kode_kecamatan,
					':p_jhtinput_kode_kabupaten' => $ls_jhtinput_kode_kabupaten,
					':p_jhtinput_kode_pos' => $ls_jhtinput_kode_pos,
					':p_jhtinput_telepon_area' => $ls_jhtinput_telepon_area,
					':p_jhtinput_telepon' => $ls_jhtinput_telepon,
					':p_jhtinput_telepon_ext' => $ls_jhtinput_telepon_ext,
					':p_jhtinput_handphone' => $ls_jhtinput_handphone,
					':p_jhtinput_email' => $ls_jhtinput_email,
					':p_jhtinput_npwp' => $ls_jhtinput_npwp,
					':p_jhtinput_keterangan' => $ls_jhtinput_keterangan,
					':p_jhtinput_nomor_identitas' => $ls_jhtinput_nomor_identitas,
					':p_username' => $username,
					':p_kode_tk' => $ls_kode_tk,
					':p_jhtinput_kode_hubungan' => $ls_jhtinput_kode_hubungan,
				);
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
				// 20-03-2024 penyesuaian bind variable
                $DB->execute();	 
							}else
							{
  							$sql = "update sijstk.kn_tk_keluarga set ".
                       "    nama_lengkap				= :p_jhtinput_nama_pemohon, ". 
  										 "		tempat_lahir				= :p_jhtinput_tempat_lahir, ".
                       "    tgl_lahir						= to_date(nvl(:p_jhtinput_tgl_lahir,'01/01/1800'),'dd/mm/yyyy'), ".
											 "		golongan_darah			= :p_jhtinput_golongan_darah, ".
  										 "		jenis_kelamin				= :p_jhtinput_jenis_kelamin, ".
  										 "		alamat							= :p_jhtinput_alamat, ".
  										 "		rt									= :p_jhtinput_rt, ".
  										 "		rw									= :p_jhtinput_rw, ".
  										 "		kode_kelurahan			= :p_jhtinput_kode_kelurahan, ".
  										 "		kode_kecamatan			= :p_jhtinput_kode_kecamatan, ".											 
  										 "		kode_kabupaten			= :p_jhtinput_kode_kabupaten, ".
  										 "		kode_pos						= :p_jhtinput_kode_pos, ".
                       "    telepon_area				= :p_jhtinput_telepon_area, ".
  										 "		telepon							= :p_jhtinput_telepon, ".
  										 "		telepon_ext					= :p_jhtinput_telepon_ext, ".
  										 "		handphone						= :p_jhtinput_handphone, ".
  										 "		email								= :p_jhtinput_email, ".
  										 "		npwp								= :p_jhtinput_npwp, ".
  										 "		keterangan					= :p_jhtinput_keterangan, ".
                       "		aktif								= 'Y', ".
  										 "		nomor_identitas			= :p_jhtinput_nomor_identitas, ".
                       "    tgl_ubah						= sysdate, ".
  										 "		petugas_ubah				= :p_username ".
                       "where kode_tk = :p_kode_tk ".
  										 "and no_urut_keluarga = :p_jhtinput_no_urut_keluarga ";
                $proc = $DB->parse($sql);
				$param_bv = array(
					':p_jhtinput_nama_pemohon' => $ls_jhtinput_nama_pemohon,
					':p_jhtinput_tempat_lahir' => $ls_jhtinput_tempat_lahir,
					':p_jhtinput_tgl_lahir' => $ld_jhtinput_tgl_lahir,
					':p_jhtinput_golongan_darah' => $ls_jhtinput_golongan_darah,
					':p_jhtinput_jenis_kelamin' => $ls_jhtinput_jenis_kelamin,
					':p_jhtinput_alamat' => $ls_jhtinput_alamat,
					':p_jhtinput_rt' => $ls_jhtinput_rt,
					':p_jhtinput_rw' => $ls_jhtinput_rw,
					':p_jhtinput_kode_kelurahan' => $ls_jhtinput_kode_kelurahan,
					':p_jhtinput_kode_kecamatan' => $ls_jhtinput_kode_kecamatan,
					':p_jhtinput_kode_kabupaten' => $ls_jhtinput_kode_kabupaten,
					':p_jhtinput_kode_pos' => $ls_jhtinput_kode_pos,
					':p_jhtinput_telepon_area' => $ls_jhtinput_telepon_area,
					':p_jhtinput_telepon' => $ls_jhtinput_telepon,
					':p_jhtinput_telepon_ext' => $ls_jhtinput_telepon_ext,
					':p_jhtinput_handphone' => $ls_jhtinput_handphone,
					':p_jhtinput_email' => $ls_jhtinput_email,
					':p_jhtinput_npwp' => $ls_jhtinput_npwp,
					':p_jhtinput_keterangan' => $ls_jhtinput_keterangan,
					':p_jhtinput_nomor_identitas' => $ls_jhtinput_nomor_identitas,
					':p_username' => $username,
					':p_kode_tk' => $ls_kode_tk,
					':p_jhtinput_no_urut_keluarga' => $ls_jhtinput_no_urut_keluarga,
				);
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
				// 20-03-2024 penyesuaian bind variable
                $DB->execute();								
							} // end if ($ls_jhtinput_no_urut_keluarga == "") ----------------					
						}//end sinkronisasi data tk keluarga -------------------------------	
						
						$ls_sukses = "1";												 		
  			 }//end if if ($ls_status_klaim=="AGENDA") -----------------------------			
			}elseif ($ls_jenis_klaim=="JHM")
			{
  		 	 if ($ls_status_klaim=="AGENDA")
  			 {
      			//update data input klaim jht --------------------------------------
            $ls_jhminput_kode_tipe_penerima_old	= $_POST["jhminput_kode_tipe_penerima_old"];
						$ls_jhminput_kode_manfaat						= $_POST["jhminput_kode_manfaat"];
						$ls_jhminput_no_urut								= $_POST["jhminput_no_urut"];
						
						$ls_jhminput_kode_tipe_penerima 	= $_POST["jhminput_kode_tipe_penerima"];
            $ls_jhminput_kode_hubungan 				= $_POST["jhminput_kode_hubungan"];
            $ls_jhminput_ket_hubungan_lainnya	= $_POST["jhminput_ket_hubungan_lainnya"];
            $ls_jhminput_no_urut_keluarga 		= $_POST["jhminput_no_urut_keluarga"];
            $ls_jhminput_nomor_identitas 			= $_POST["jhminput_nomor_identitas"];
            $ls_jhminput_nama_pemohon 				= $_POST["jhminput_nama_pemohon"];
            $ls_jhminput_tempat_lahir 				= $_POST["jhminput_tempat_lahir"];
            $ld_jhminput_tgl_lahir 						= $_POST["jhminput_tgl_lahir"];
            $ls_jhminput_jenis_kelamin 				= $_POST["jhminput_jenis_kelamin"];
						$ls_jhminput_golongan_darah				= $_POST["jhminput_golongan_darah"];
            $ls_jhminput_alamat 							= $_POST["jhminput_alamat"];
            $ls_jhminput_rt 									= $_POST["jhminput_rt"];
            $ls_jhminput_rw 									= $_POST["jhminput_rw"];
            $ls_jhminput_kode_kelurahan 			= $_POST["jhminput_kode_kelurahan"];
            $ls_jhminput_nama_kelurahan 			= $_POST["jhminput_nama_kelurahan"];
            $ls_jhminput_kode_kecamatan 			= $_POST["jhminput_kode_kecamatan"];
            $ls_jhminput_nama_kecamatan 			= $_POST["jhminput_nama_kecamatan"];
            $ls_jhminput_kode_kabupaten 			= $_POST["jhminput_kode_kabupaten"];
            $ls_jhminput_nama_kabupaten 			= $_POST["jhminput_nama_kabupaten"];
            $ls_jhminput_kode_pos 						= $_POST["jhminput_kode_pos"];
            $ls_jhminput_telepon_area 				= $_POST["jhminput_telepon_area"];
            $ls_jhminput_telepon 							= $_POST["jhminput_telepon"];
            $ls_jhminput_telepon_ext 					= $_POST["jhminput_telepon_ext"];
            $ls_jhminput_handphone 						= $_POST["jhminput_handphone"];
            $ls_jhminput_email 								= $_POST["jhminput_email"];
            $ls_jhminput_npwp 								= $_POST["jhminput_npwp"];
			$ls_jhminput_kode_bhp							= $_POST["jhminput_kode_bhp"]; //update 28/01/2020 bhp
  					
						if ($ls_jhminput_ket_hubungan_lainnya!="")
  					{
  					 	$ls_jhminput_keterangan = $ls_jhminput_ket_hubungan_lainnya; 
  					}else
  					{
  					 	$ls_jhminput_keterangan = "";	 
  					}

            $ld_jhminput_tgl_kematian				  = $_POST["jhminput_tgl_kematian"];
            $ls_jhminput_ket_tambahan					= $_POST["jhminput_ket_tambahan"];         
      			 			 			
            $sql = "update sijstk.pn_klaim set ".
                   "	 tgl_kematian			      = to_date(:p_jhminput_tgl_kematian,'dd/mm/yyyy'), ".                   
                   "   ket_tambahan						= :p_jhminput_ket_tambahan, ".                 					 
                   "	 tgl_ubah								= sysdate, ". 
                   "	 petugas_ubah 					= :p_username ".
                   "where kode_klaim = :p_kode_klaim ";				
            $proc = $DB->parse($sql);
			$param_bv = array(
				':p_jhminput_tgl_kematian' => $ld_jhminput_tgl_kematian,
				':p_jhminput_ket_tambahan' => $ls_jhminput_ket_tambahan,
				':p_username' => $username,
				':p_kode_klaim' => $ls_kode_klaim,
			);
			foreach ($param_bv as $key => $value) {
				oci_bind_by_name($proc, $key, $param_bv[$key]);
			}
			// 20-03-2024 penyesuaian bind variable
            $DB->execute();	
																		
						//sesuaikan tipe penerima yg di manfaat detil ----------------------
						//06012022, untuk kebutuhan syariah, jika $ls_jhtinput_no_urut kosong maka di set dengan in (1,2) untuk 1 = konvensional dan 2 = syariah
						if($ls_jhminput_no_urut !="")
						{
							$sql2 = "update sijstk.pn_klaim_manfaat_detil set ".
  								 	"	kode_tipe_penerima 	 = :p_jhminput_kode_tipe_penerima, ".                  
									"	tgl_ubah						 = sysdate, ". 
  									"	petugas_ubah				 = :p_username ".
									"where kode_klaim = :p_kode_klaim ".
  									"and kode_manfaat = :p_jhminput_kode_manfaat ".
  									"and no_urut = :p_jhminput_no_urut ";	
							$proc = $DB->parse($sql2);
							$param_bv = array(
								':p_jhminput_kode_tipe_penerima' => $ls_jhminput_kode_tipe_penerima,
								':p_username' => $username,
								':p_kode_klaim' => $ls_kode_klaim,
								':p_jhminput_kode_manfaat' => $ls_jhminput_kode_manfaat,
								':p_jhminput_no_urut' => $ls_jhminput_no_urut,
							);
							foreach ($param_bv as $key => $value) {
								oci_bind_by_name($proc, $key, $param_bv[$key]);
							}
							// 20-03-2024 penyesuaian bind variable
							$DB->execute();	
						}else
						{
							$sql2 = "update sijstk.pn_klaim_manfaat_detil set ".
  								 	"	kode_tipe_penerima 	 = :p_jhminput_kode_tipe_penerima, ".                  
									"	tgl_ubah						 = sysdate, ". 
  									"	petugas_ubah				 = :p_username ".
									"where kode_klaim = :p_kode_klaim ".
  									"and kode_manfaat = :p_jhminput_kode_manfaat ".
  									"and no_urut in (1,2) ";
							$proc = $DB->parse($sql2);
							$param_bv = array(
								':p_jhminput_kode_tipe_penerima' => $ls_jhminput_kode_tipe_penerima,
								':p_username' => $username,
								':p_kode_klaim' => $ls_kode_klaim,
								':p_jhminput_kode_manfaat' => $ls_jhminput_kode_manfaat,
							);
							foreach ($param_bv as $key => $value) {
								oci_bind_by_name($proc, $key, $param_bv[$key]);
							}
							// 20-03-2024 penyesuaian bind variable
							$DB->execute();	
						}
												
						//update data penerima manfaat -------------------------------------
						$sql = "update sijstk.pn_klaim_penerima_manfaat set ".
								 	 "	kode_tipe_penerima 	 = :p_jhminput_kode_tipe_penerima, ".		 
                   "	kode_hubungan				 = :p_jhminput_kode_hubungan, ". 
									 "	ket_hubungan_lainnya = :p_jhminput_ket_hubungan_lainnya, ".
									 "	no_urut_keluarga		 = :p_jhminput_no_urut_keluarga, ".  
                   "	nomor_identitas			 = :p_jhminput_nomor_identitas, ".  
									 "	nama_pemohon				 = :p_jhminput_nama_pemohon, ". 
									 "	tempat_lahir				 = :p_jhminput_tempat_lahir, ".  
									 "	tgl_lahir						 = to_date(:p_jhminput_tgl_lahir,'dd/mm/yyyy'), ".  
									 "	jenis_kelamin				 = :p_jhminput_jenis_kelamin, ".
									 "	golongan_darah			 = :p_jhminput_golongan_darah, ".  
                   "	alamat							 = :p_jhminput_alamat, ". 
									 "	rt									 = :p_jhminput_rt, ".  
									 "	rw									 = :p_jhminput_rw, ".  
									 "	kode_kelurahan			 = :p_jhminput_kode_kelurahan, ".  
									 "	kode_kecamatan			 = :p_jhminput_kode_kecamatan, ". 
									 "	kode_kabupaten			 = :p_jhminput_kode_kabupaten, ".  
									 "	kode_pos						 = :p_jhminput_kode_pos, ".  
                   "	telepon_area				 = :p_jhminput_telepon_area, ".  
									 "	telepon							 = :p_jhminput_telepon, ".  
									 "	telepon_ext					 = :p_jhminput_telepon_ext, ".  
									 "	handphone						 = :p_jhminput_handphone, ". 
									 "	email								 = :p_jhminput_email, ". 
									 "	npwp								 = :p_jhminput_npwp, ". 
									 "	nama_penerima				 = :p_jhminput_nama_pemohon, ".  
									 "	kode_bhp				 		 = :p_jhminput_kode_bhp, ". 
                   "	tgl_ubah						 = sysdate, ". 
									 "	petugas_ubah				 = :p_username ".
                   "where kode_klaim = :p_kode_klaim ".
									 "and kode_tipe_penerima = :p_jhminput_kode_tipe_penerima_old ";			
            $proc = $DB->parse($sql);
			$param_bv = array(
				':p_jhminput_kode_tipe_penerima' => $ls_jhminput_kode_tipe_penerima,
				':p_jhminput_kode_hubungan' => $ls_jhminput_kode_hubungan,
				':p_jhminput_ket_hubungan_lainnya' => $ls_jhminput_ket_hubungan_lainnya,
				':p_jhminput_no_urut_keluarga' => $ls_jhminput_no_urut_keluarga,
				':p_jhminput_nomor_identitas' => $ls_jhminput_nomor_identitas,
				':p_jhminput_nama_pemohon' => $ls_jhminput_nama_pemohon,
				':p_jhminput_tempat_lahir' => $ls_jhminput_tempat_lahir,
				':p_jhminput_tgl_lahir' => $ld_jhminput_tgl_lahir,
				':p_jhminput_jenis_kelamin' => $ls_jhminput_jenis_kelamin,
				':p_jhminput_golongan_darah' => $ls_jhminput_golongan_darah,
				':p_jhminput_alamat' => $ls_jhminput_alamat,
				':p_jhminput_rt' => $ls_jhminput_rt,
				':p_jhminput_rw' => $ls_jhminput_rw,
				':p_jhminput_kode_kelurahan' => $ls_jhminput_kode_kelurahan,
				':p_jhminput_kode_kecamatan' => $ls_jhminput_kode_kecamatan,
				':p_jhminput_kode_kabupaten' => $ls_jhminput_kode_kabupaten,
				':p_jhminput_kode_pos' => $ls_jhminput_kode_pos,
				':p_jhminput_telepon_area' => $ls_jhminput_telepon_area,
				':p_jhminput_telepon' => $ls_jhminput_telepon,
				':p_jhminput_telepon_ext' => $ls_jhminput_telepon_ext,
				':p_jhminput_handphone' => $ls_jhminput_handphone,
				':p_jhminput_email' => $ls_jhminput_email,
				':p_jhminput_npwp' => $ls_jhminput_npwp,
				':p_jhminput_nama_pemohon' => $ls_jhminput_nama_pemohon,
				':p_jhminput_kode_bhp' => $ls_jhminput_kode_bhp,
				':p_username' => $username,
				':p_kode_klaim' => $ls_kode_klaim,
				':p_jhminput_kode_tipe_penerima_old' => $ls_jhminput_kode_tipe_penerima_old,
			);
			foreach ($param_bv as $key => $value) {
				oci_bind_by_name($proc, $key, $param_bv[$key]);
			}
			// 20-03-2024 penyesuaian bind variable
            $DB->execute();
			
			//update 28/01/2020 - bhp ------------------------------------------
			//jika tipe penerima bhp maka sekaligus ambilkan data rekeningnya --
			if ($ls_jhminput_kode_tipe_penerima=="BH" && $ls_jhminput_kode_bhp!="")
			{ 
			  $sql = "select ". 
					 "	bank_penerima_bhp, no_rekening_penerima_bhp, nama_rekening_penerima_bhp ".
					 "from pn.pn_kode_bhp ".
					 "where kode_bhp = :p_jhminput_kode_bhp ";
			  $proc = $DB->parse($sql);
				oci_bind_by_name($proc, ':p_jhminput_kode_bhp', $ls_jhminput_kode_bhp, 100);
				// 20-03-2024 penyesuaian bind variable
			  $DB->execute();
			  $row = $DB->nextrow();
			  $ls_bank_penerima_bhp	 	 				= $row['BANK_PENERIMA_BHP'];
					$ls_no_rekening_penerima_bhp		= $row['NO_REKENING_PENERIMA_BHP'];
					$ls_nama_rekening_penerima_bhp	= $row['NAMA_REKENING_PENERIMA_BHP'];
							
							$sql = "update sijstk.pn_klaim_penerima_manfaat set ".
									 "	bank_penerima					 = :p_bank_penerima_bhp, ". 
										 "	no_rekening_penerima	 = :p_no_rekening_penerima_bhp, ". 
										 "	nama_rekening_penerima = :p_nama_rekening_penerima_bhp ".
					 "where kode_klaim = :p_kode_klaim ".
									 "and kode_tipe_penerima = :p_jhminput_kode_tipe_penerima ";			
			  $proc = $DB->parse($sql);
				$param_bv = array(
					':p_bank_penerima_bhp' => $ls_bank_penerima_bhp,
					':p_no_rekening_penerima_bhp' => $ls_no_rekening_penerima_bhp,
					':p_nama_rekening_penerima_bhp' => $ls_nama_rekening_penerima_bhp,
					':p_kode_klaim' => $ls_kode_klaim,
					':p_jhminput_kode_tipe_penerima' => $ls_jhminput_kode_tipe_penerima,
				);
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
				// 20-03-2024 penyesuaian bind variable
			  $DB->execute();					
			}
			//end jika tipe penerima bhp maka sekaligus ambilkan data rekeningnya --
						
						//sinkronisasi data tk keluarga ------------------------------------
            $sql = 	"select count(*) as v_cnt from sijstk.kn_tk_keluarga ".
            		 		"where kode_tk = :p_kode_tk and kode_hubungan = :p_kode_hubungan ";
            $proc = $DB->parse($sql);
			oci_bind_by_name($proc, ':p_kode_tk', $ls_kode_tk, 100);
			oci_bind_by_name($proc, ':p_kode_hubungan', $ls_jhminput_kode_hubungan, 100);
			// 20-03-2024 penyesuaian bind variable
            $DB->execute();
            $row = $DB->nextrow();
            $ln_cnt = $row["V_CNT"];
													
						if ($ln_cnt=="0")
						{
              $sql = 	"select nvl(max(no_urut_keluarga),0)+1 as v_no from sijstk.kn_tk_keluarga ".
              		 		"where kode_tk = :p_kode_tk ";
              $proc = $DB->parse($sql);
				oci_bind_by_name($proc, ':p_kode_tk', $ls_kode_tk, 100);
				// 20-03-2024 penyesuaian bind variable
              $DB->execute();
              $row = $DB->nextrow();
              $ln_no_urut = $row["V_NO"];
										
  						$sql = "insert into sijstk.kn_tk_keluarga ( ".
                     "    kode_tk, no_urut_keluarga, kode_hubungan, nama_lengkap, no_kartu_keluarga, tempat_lahir, ". 
                     "    tgl_lahir, jenis_kelamin, golongan_darah, alamat, kode_kabupaten, kode_pos, ". 
                     "    telepon_area, telepon, telepon_ext, fax_area, fax, handphone, email, npwp, keterangan, ". 
                     "    kpj_tertanggung, aktif, nomor_identitas, rt, rw, kode_kelurahan, kode_kecamatan, ".
                     "    tgl_rekam, petugas_rekam) ". 
                     "values ( ".
										 "    :p_kode_tk, :p_no_urut, :p_jhminput_kode_hubungan, :p_jhminput_nama_pemohon, null, :p_jhminput_tempat_lahir, ". 
                     "    to_date(nvl(:p_jhminput_tgl_lahir,'01/01/1800'),'dd/mm/yyyy'), :p_jhminput_jenis_kelamin, :p_jhminput_golongan_darah, :p_jhminput_alamat, :p_jhminput_kode_kabupaten, :p_jhminput_kode_pos, ". 
                     "    :p_jhminput_telepon_area, :p_jhminput_telepon, :p_jhminput_telepon_ext, null, null, :p_jhminput_handphone, :p_jhminput_email, :p_jhminput_npwp, :p_jhminput_keterangan, ". 
                     "    null, 'Y', :p_jhminput_nomor_identitas, :p_jhminput_rt, :p_jhminput_rw, :p_jhminput_kode_kelurahan, :p_jhminput_kode_kecamatan, ".
                     "    sysdate, :p_username ". 
										 ") "; 
              $proc = $DB->parse($sql);
				$param_bv = array(
					':p_kode_tk' => $ls_kode_tk,
					':p_no_urut' => $ln_no_urut,
					':p_jhminput_kode_hubungan' => $ls_jhminput_kode_hubungan,
					':p_jhminput_nama_pemohon' => $ls_jhminput_nama_pemohon,
					':p_jhminput_tempat_lahir' => $ls_jhminput_tempat_lahir,
					':p_jhminput_tgl_lahir' => $ld_jhminput_tgl_lahir,
					':p_jhminput_jenis_kelamin' => $ls_jhminput_jenis_kelamin,
					':p_jhminput_golongan_darah' => $ls_jhminput_golongan_darah,
					':p_jhminput_alamat' => $ls_jhminput_alamat,
					':p_jhminput_kode_kabupaten' => $ls_jhminput_kode_kabupaten,
					':p_jhminput_kode_pos' => $ls_jhminput_kode_pos,
					':p_jhminput_telepon_area' => $ls_jhminput_telepon_area,
					':p_jhminput_telepon' => $ls_jhminput_telepon,
					':p_jhminput_telepon_ext' => $ls_jhminput_telepon_ext,
					':p_jhminput_handphone' => $ls_jhminput_handphone,
					':p_jhminput_email' => $ls_jhminput_email,
					':p_jhminput_npwp' => $ls_jhminput_npwp,
					':p_jhminput_keterangan' => $ls_jhminput_keterangan,
					':p_jhminput_nomor_identitas' => $ls_jhminput_nomor_identitas,
					':p_jhminput_rt' => $ls_jhminput_rt,
					':p_jhminput_rw' => $ls_jhminput_rw,
					':p_jhminput_kode_kelurahan' => $ls_jhminput_kode_kelurahan,
					':p_jhminput_kode_kecamatan' => $ls_jhminput_kode_kecamatan,
					':p_username' => $username,
				);
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
				// 20-03-2024 penyesuaian bind variable
              $DB->execute();				
						}else
						{
  						if ($ls_jhminput_no_urut_keluarga == "")
							{
  							$sql = "update sijstk.kn_tk_keluarga set ".
                       "    nama_lengkap				= :p_jhminput_nama_pemohon, ". 
  										 "		tempat_lahir				= :p_jhminput_tempat_lahir, ".
                       "    tgl_lahir						= to_date(nvl(:p_jhminput_tgl_lahir,'01/01/1800'),'dd/mm/yyyy'), ".
  										 "		jenis_kelamin				= :p_jhminput_jenis_kelamin, ".
											 "		golongan_darah			= :p_jhminput_golongan_darah, ".
  										 "		alamat							= :p_jhminput_alamat, ".
  										 "		rt									= :p_jhminput_rt, ".
  										 "		rw									= :p_jhminput_rw, ".
  										 "		kode_kelurahan			= :p_jhminput_kode_kelurahan, ".
  										 "		kode_kecamatan			= :p_jhminput_kode_kecamatan, ".											 
  										 "		kode_kabupaten			= :p_jhminput_kode_kabupaten, ".
  										 "		kode_pos						= :p_jhminput_kode_pos, ".
                       "    telepon_area				= :p_jhminput_telepon_area, ".
  										 "		telepon							= :p_jhminput_telepon, ".
  										 "		telepon_ext					= :p_jhminput_telepon_ext, ".
  										 "		handphone						= :p_jhminput_handphone, ".
  										 "		email								= :p_jhminput_email, ".
  										 "		npwp								= :p_jhminput_npwp, ".
  										 "		keterangan					= :p_jhminput_keterangan, ".
                       "		aktif								= 'Y', ".
  										 "		nomor_identitas			= :p_jhminput_nomor_identitas, ".
                       "    tgl_ubah						= sysdate, ".
  										 "		petugas_ubah				= :p_username ".
                       "where kode_tk = :p_kode_tk ".
  										 "and kode_hubungan = :p_jhminput_kode_hubungan ";
                $proc = $DB->parse($sql);
				$param_bv = array(
					':p_jhminput_nama_pemohon' => $ls_jhminput_nama_pemohon,
					':p_jhminput_tempat_lahir' => $ls_jhminput_tempat_lahir,
					':p_jhminput_tgl_lahir' => $ld_jhminput_tgl_lahir,
					':p_jhminput_jenis_kelamin' => $ls_jhminput_jenis_kelamin,
					':p_jhminput_golongan_darah' => $ls_jhminput_golongan_darah,
					':p_jhminput_alamat' => $ls_jhminput_alamat,
					':p_jhminput_rt' => $ls_jhminput_rt,
					':p_jhminput_rw' => $ls_jhminput_rw,
					':p_jhminput_kode_kelurahan' => $ls_jhminput_kode_kelurahan,
					':p_jhminput_kode_kecamatan' => $ls_jhminput_kode_kecamatan,
					':p_jhminput_kode_kabupaten' => $ls_jhminput_kode_kabupaten,
					':p_jhminput_kode_pos' => $ls_jhminput_kode_pos,
					':p_jhminput_telepon_area' => $ls_jhminput_telepon_area,
					':p_jhminput_telepon' => $ls_jhminput_telepon,
					':p_jhminput_telepon_ext' => $ls_jhminput_telepon_ext,
					':p_jhminput_handphone' => $ls_jhminput_handphone,
					':p_jhminput_email' => $ls_jhminput_email,
					':p_jhminput_npwp' => $ls_jhminput_npwp,
					':p_jhminput_keterangan' => $ls_jhminput_keterangan,
					':p_jhminput_nomor_identitas' => $ls_jhminput_nomor_identitas,
					':p_username' => $username,
					':p_kode_tk' => $ls_kode_tk,
					':p_jhminput_kode_hubungan' => $ls_jhminput_kode_hubungan,
				);
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
				// 20-03-2024 penyesuaian bind variable
                $DB->execute();	 
							}else
							{
  							$sql = "update sijstk.kn_tk_keluarga set ".
                       "    nama_lengkap				= :p_jhminput_nama_pemohon, ". 
  										 "		tempat_lahir				= :p_jhminput_tempat_lahir, ".
                       "    tgl_lahir						= to_date(nvl(:p_jhminput_tgl_lahir,'01/01/1800'),'dd/mm/yyyy'), ".
											 "		golongan_darah			= :p_jhminput_golongan_darah, ".
  										 "		jenis_kelamin				= :p_jhminput_jenis_kelamin, ".
  										 "		alamat							= :p_jhminput_alamat, ".
  										 "		rt									= :p_jhminput_rt, ".
  										 "		rw									= :p_jhminput_rw, ".
  										 "		kode_kelurahan			= :p_jhminput_kode_kelurahan, ".
  										 "		kode_kecamatan			= :p_jhminput_kode_kecamatan, ".											 
  										 "		kode_kabupaten			= :p_jhminput_kode_kabupaten, ".
  										 "		kode_pos						= :p_jhminput_kode_pos, ".
                       "    telepon_area				= :p_jhminput_telepon_area, ".
  										 "		telepon							= :p_jhminput_telepon, ".
  										 "		telepon_ext					= :p_jhminput_telepon_ext, ".
  										 "		handphone						= :p_jhminput_handphone, ".
  										 "		email								= :p_jhminput_email, ".
  										 "		npwp								= :p_jhminput_npwp, ".
  										 "		keterangan					= :p_jhminput_keterangan, ".
                       "		aktif								= 'Y', ".
  										 "		nomor_identitas			= :p_jhminput_nomor_identitas, ".
                       "    tgl_ubah						= sysdate, ".
  										 "		petugas_ubah				= :p_username ".
                       "where kode_tk = :p_kode_tk ".
  										 "and no_urut_keluarga = :p_jhminput_no_urut_keluarga ";
                $proc = $DB->parse($sql);
				$param_bv = array(
					':p_jhminput_nama_pemohon' => $ls_jhminput_nama_pemohon,
					':p_jhminput_tempat_lahir' => $ls_jhminput_tempat_lahir,
					':p_jhminput_tgl_lahir' => $ld_jhminput_tgl_lahir,
					':p_jhminput_golongan_darah' => $ls_jhminput_golongan_darah,
					':p_jhminput_jenis_kelamin' => $ls_jhminput_jenis_kelamin,
					':p_jhminput_alamat' => $ls_jhminput_alamat,
					':p_jhminput_rt' => $ls_jhminput_rt,
					':p_jhminput_rw' => $ls_jhminput_rw,
					':p_jhminput_kode_kelurahan' => $ls_jhminput_kode_kelurahan,
					':p_jhminput_kode_kecamatan' => $ls_jhminput_kode_kecamatan,
					':p_jhminput_kode_kabupaten' => $ls_jhminput_kode_kabupaten,
					':p_jhminput_kode_pos' => $ls_jhminput_kode_pos,
					':p_jhminput_telepon_area' => $ls_jhminput_telepon_area,
					':p_jhminput_telepon' => $ls_jhminput_telepon,
					':p_jhminput_telepon_ext' => $ls_jhminput_telepon_ext,
					':p_jhminput_handphone' => $ls_jhminput_handphone,
					':p_jhminput_email' => $ls_jhminput_email,
					':p_jhminput_npwp' => $ls_jhminput_npwp,
					':p_jhminput_keterangan' => $ls_jhminput_keterangan,
					':p_jhminput_nomor_identitas' => $ls_jhminput_nomor_identitas,
					':p_username' => $username,
					':p_kode_tk' => $ls_kode_tk,
					':p_jhminput_no_urut_keluarga' => $ls_jhminput_no_urut_keluarga,
				);
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
				// 20-03-2024 penyesuaian bind variable
                $DB->execute();								
							} // end if ($ls_jhminput_no_urut_keluarga == "") ----------------					
						}//end sinkronisasi data tk keluarga -------------------------------	
						
						$ls_sukses = "1";												 		
  			 }//end if if ($ls_status_klaim=="AGENDA") -----------------------------					
			}elseif ($ls_jenis_klaim=="JKK")
			{
  		 	 if ($ls_status_klaim=="AGENDA_TAHAP_I")
  			 {
      			//update data agenda jkk tahap I -------------------------------------
            $ls_jkk1_tipe_negara_kejadian		 = $_POST["jkk1_tipe_negara_kejadian"];
						$ld_jkk1_tgl_kecelakaan					 = $_POST["jkk1_tgl_kecelakaan"];
            $ls_jkk1_kode_jam_kecelakaan		 = $_POST["jkk1_kode_jam_kecelakaan"];
            $ls_jkk1_kode_jenis_kasus				 = $_POST["jkk1_kode_jenis_kasus"];
            $ls_jkk1_kode_lokasi_kecelakaan  = $_POST["jkk1_kode_lokasi_kecelakaan"];										
            $ls_jkk1_nama_tempat_kecelakaan	 = $_POST["jkk1_nama_tempat_kecelakaan"];
            $ls_jkk1_ket_tambahan						 = $_POST["jkk1_ket_tambahan"];         
      			 			 			
            $sql = "update sijstk.pn_klaim set ".
                   "	 tgl_kecelakaan			    = to_date(:p_jkk1_tgl_kecelakaan,'dd/mm/yyyy'), ".
                   "   kode_jam_kecelakaan 		= :p_jkk1_kode_jam_kecelakaan, ".
  								 "   kode_jenis_kasus				= :p_jkk1_kode_jenis_kasus, ".
  								 "	 kode_lokasi_kecelakaan	= :p_jkk1_kode_lokasi_kecelakaan, ". 
                   "   nama_tempat_kecelakaan = :p_jkk1_nama_tempat_kecelakaan, ".
                   "   ket_tambahan						= :p_jkk1_ket_tambahan, ". 
									 "   tipe_negara_kejadian 	= :p_jkk1_tipe_negara_kejadian, ".                					 
                   "	 tgl_ubah								= sysdate, ". 
                   "	 petugas_ubah 					= :p_username ".
                   "where kode_klaim = :p_kode_klaim ";				
            $proc = $DB->parse($sql);
			$param_bv = array(
				':p_jkk1_tgl_kecelakaan' => $ld_jkk1_tgl_kecelakaan,
				':p_jkk1_kode_jam_kecelakaan' => $ls_jkk1_kode_jam_kecelakaan,
				':p_jkk1_kode_jenis_kasus' => $ls_jkk1_kode_jenis_kasus,
				':p_jkk1_kode_lokasi_kecelakaan' => $ls_jkk1_kode_lokasi_kecelakaan,
				':p_jkk1_nama_tempat_kecelakaan' => $ls_jkk1_nama_tempat_kecelakaan,
				':p_jkk1_ket_tambahan' => $ls_jkk1_ket_tambahan,
				':p_jkk1_tipe_negara_kejadian' => $ls_jkk1_tipe_negara_kejadian,
				':p_username' => $username,
				':p_kode_klaim' => $ls_kode_klaim,
			);
			foreach ($param_bv as $key => $value) {
				oci_bind_by_name($proc, $key, $param_bv[$key]);
			}
			// 20-03-2024 penyesuaian bind variable
            $DB->execute();
						
						if ($ls_kode_segmen=="JAKON")
						{
        			//update tk jakons -----------------------------------------------
              $ls_tkjakon_kode_tk				 			 = $_POST["tkjakon_kode_tk"];
							$ls_tkjakon_nama_tk		 					 = $_POST["tkjakon_nama_tk"];
              $ls_tkjakon_jenis_identitas  		 = $_POST["tkjakon_jenis_identitas"];	
							$ls_tkjakon_nomor_identitas  		 = $_POST["tkjakon_nomor_identitas"];											
              $ls_tkjakon_alamat_domisili	 		 = $_POST["tkjakon_alamat_domisili"];
              $ls_tkjakon_kode_pekerjaan			 = $_POST["tkjakon_kode_pekerjaan"];         
        			$ld_tkjakon_tgl_lahir						 = $_POST["tkjakon_tgl_lahir"];
							
							if ($ls_tkjakon_kode_tk=="")
							{
                //generate kode tk jakon ---------------------------------------
                $sql = 	"select sijstk.p_pn_genid.f_gen_kodetkjakon as v_kode_tkjakon from dual ";
                $DB->parse($sql);
                $DB->execute();
                $row = $DB->nextrow();
                $ls_tkjakon_kode_tk = $row["V_KODE_TKJAKON"];
							}
							 			 			
              $sql = "update sijstk.pn_klaim set ".
                     "   kode_tk 						 		= :p_tkjakon_kode_tk, ".
    								 "   nama_tk								= :p_tkjakon_nama_tk, ".
    								 "	 jenis_identitas				= :p_tkjakon_jenis_identitas, ". 
                     "   nomor_identitas 				= :p_tkjakon_nomor_identitas, ".
                     "   alamat_domisili				= :p_tkjakon_alamat_domisili, ". 
										 "   kode_pekerjaan					= :p_tkjakon_kode_pekerjaan, ". 
										 "   tgl_lahir							= to_date(:p_tkjakon_tgl_lahir,'dd/mm/yyyy'), ".                					 
                     "	 tgl_ubah								= sysdate, ". 
                     "	 petugas_ubah 					= :p_username ".
                     "where kode_klaim = :p_kode_klaim ";				
              $proc = $DB->parse($sql);
				$param_bv = array(
					':p_tkjakon_kode_tk' => $ls_tkjakon_kode_tk,
					':p_tkjakon_nama_tk' => $ls_tkjakon_nama_tk,
					':p_tkjakon_jenis_identitas' => $ls_tkjakon_jenis_identitas,
					':p_tkjakon_nomor_identitas' => $ls_tkjakon_nomor_identitas,
					':p_tkjakon_alamat_domisili' => $ls_tkjakon_alamat_domisili,
					':p_tkjakon_kode_pekerjaan' => $ls_tkjakon_kode_pekerjaan,
					':p_tkjakon_tgl_lahir' => $ld_tkjakon_tgl_lahir,
					':p_username' => $username,
					':p_kode_klaim' => $ls_kode_klaim,
				);
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
				// 20-03-2024 penyesuaian bind variable
              $DB->execute();						
						}
						
						$ls_sukses = "1";			 		
  			 }else if ($ls_status_klaim=="AGENDA")
  			 {
      			//update data agenda jkk (tanpa melalui pengajuan dan jkk tahap 2, langsung ke penetapan)
            $ld_jkk_tgl_kecelakaan					= $_POST["jkk_tgl_kecelakaan"];
            $ls_jkk_kode_jam_kecelakaan			= $_POST["jkk_kode_jam_kecelakaan"];
            $ls_jkk_kode_jenis_kasus				= $_POST["jkk_kode_jenis_kasus"];
            $ls_jkk_kode_lokasi_kecelakaan	= $_POST["jkk_kode_lokasi_kecelakaan"];
            $ls_jkk_nama_tempat_kecelakaan	= $_POST["jkk_nama_tempat_kecelakaan"];
            $ls_jkk_ket_tambahan						= $_POST["jkk_ket_tambahan"];
            $ls_jkk_kode_tindakan_bahaya		= $_POST["jkk_kode_tindakan_bahaya"];
            $ls_jkk_kode_kondisi_bahaya			= $_POST["jkk_kode_kondisi_bahaya"];				
            $ls_jkk_kode_corak      				= $_POST["jkk_kode_corak"];
            $ls_jkk_nama_corak      				= $_POST["jkk_nama_corak"];
            $ls_jkk_kode_sumber_cedera      = $_POST["jkk_kode_sumber_cedera"];
            $ls_jkk_nama_sumber_cedera      = $_POST["jkk_nama_sumber_cedera"];
            $ls_jkk_kode_bagian_sakit      	= $_POST["jkk_kode_bagian_sakit"];
            $ls_jkk_nama_bagian_sakit      	= $_POST["jkk_nama_bagian_sakit"];
            $ls_jkk_kode_akibat_diderita    = $_POST["jkk_kode_akibat_diderita"];
            $ls_jkk_nama_akibat_diderita    = $_POST["jkk_nama_akibat_diderita"];
            $ls_jkk_kode_lama_bekerja      	= $_POST["jkk_kode_lama_bekerja"];
            $ls_jkk_kode_penyakit_timbul    = $_POST["jkk_kode_penyakit_timbul"];
            $ls_jkk_nama_penyakit_timbul    = $_POST["jkk_nama_penyakit_timbul"];
            $ls_jkk_kode_tempat_perawatan   = $_POST["jkk_kode_tempat_perawatan"];
            $ls_jkk_kode_berobat_jalan      = $_POST["jkk_kode_berobat_jalan"];
            $ls_jkk_kode_ppk      					= $_POST["jkk_kode_ppk"];
            $ls_jkk_nama_ppk      					= $_POST["jkk_nama_ppk"];
            $ls_jkk_nama_faskes_reimburse   = $_POST["jkk_nama_faskes_reimburse"];            
            $ls_jkk_kode_tipe_upah      		= $_POST["jkk_kode_tipe_upah"];
            $ln_jkk_nom_upah_terakhir      	= str_replace(',','',$_POST["jkk_nom_upah_terakhir"]);
            $ls_jkk_kode_kondisi_terakhir   = $_POST["jkk_kode_kondisi_terakhir"];
            $ld_jkk_tgl_kondisi_terakhir    = $_POST["jkk_tgl_kondisi_terakhir"];      
      			$ls_jkk_tipe_negara_kejadian		= $_POST["jkk_tipe_negara_kejadian"];
						 			 				
            $sql = "update sijstk.pn_klaim set ".
                   "	 tgl_kecelakaan			    = to_date(:p_jkk_tgl_kecelakaan,'dd/mm/yyyy'), ".
                   "   kode_jam_kecelakaan 		= :p_jkk_kode_jam_kecelakaan, ".
  								 "   kode_jenis_kasus				= :p_jkk_kode_jenis_kasus, ".
  								 "	 kode_lokasi_kecelakaan	= :p_jkk_kode_lokasi_kecelakaan, ". 
                   "   nama_tempat_kecelakaan = :p_jkk_nama_tempat_kecelakaan, ".
                   "   ket_tambahan						= :p_jkk_ket_tambahan, ".
          			 	 "   kode_tindakan_bahaya   = :p_jkk_kode_tindakan_bahaya, ".
                   "   kode_kondisi_bahaya    = :p_jkk_kode_kondisi_bahaya, ".
                   "   kode_corak             = :p_jkk_kode_corak, ".
                   "   kode_sumber_cedera     = :p_jkk_kode_sumber_cedera, ".
                   "   kode_bagian_sakit      = :p_jkk_kode_bagian_sakit, ".
                   "   kode_akibat_diderita   = :p_jkk_kode_akibat_diderita, ".
                   "   kode_lama_bekerja      = :p_jkk_kode_lama_bekerja, ".
                   "   kode_penyakit_timbul   = :p_jkk_kode_penyakit_timbul, ".
                   "   kode_tipe_upah         = :p_jkk_kode_tipe_upah, ".
                   "   nom_upah_terakhir      = :p_jkk_nom_upah_terakhir, ".
                   "   kode_tempat_perawatan  = :p_jkk_kode_tempat_perawatan, ".
                   "   kode_berobat_jalan     = :p_jkk_kode_berobat_jalan, ".
                   "   kode_ppk               = :p_jkk_kode_ppk, ".
                   "   nama_faskes_reimburse  = :p_jkk_nama_faskes_reimburse, ".
                   "   kode_kondisi_terakhir 	= :p_jkk_kode_kondisi_terakhir, ". 
                   "	 tgl_kondisi_terakhir	  = to_date(:p_jkk_tgl_kondisi_terakhir,'dd/mm/yyyy'), ".
									 "   tipe_negara_kejadian 	= :p_jkk_tipe_negara_kejadian, ". 				 									                  					 
                   "	 tgl_ubah								= sysdate, ". 
                   "	 petugas_ubah 					= :p_username ".
                   "where kode_klaim = :p_kode_klaim ";				
            $proc = $DB->parse($sql);
			$param_bv = array(
				':p_jkk_tgl_kecelakaan' => $ld_jkk_tgl_kecelakaan,
				':p_jkk_kode_jam_kecelakaan' => $ls_jkk_kode_jam_kecelakaan,
				':p_jkk_kode_jenis_kasus' => $ls_jkk_kode_jenis_kasus,
				':p_jkk_kode_lokasi_kecelakaan' => $ls_jkk_kode_lokasi_kecelakaan,
				':p_jkk_nama_tempat_kecelakaan' => $ls_jkk_nama_tempat_kecelakaan,
				':p_jkk_ket_tambahan' => $ls_jkk_ket_tambahan,
				':p_jkk_kode_tindakan_bahaya' => $ls_jkk_kode_tindakan_bahaya,
				':p_jkk_kode_kondisi_bahaya' => $ls_jkk_kode_kondisi_bahaya,
				':p_jkk_kode_corak' => $ls_jkk_kode_corak,
				':p_jkk_kode_sumber_cedera' => $ls_jkk_kode_sumber_cedera,
				':p_jkk_kode_bagian_sakit' => $ls_jkk_kode_bagian_sakit,
				':p_jkk_kode_akibat_diderita' => $ls_jkk_kode_akibat_diderita,
				':p_jkk_kode_lama_bekerja' => $ls_jkk_kode_lama_bekerja,
				':p_jkk_kode_penyakit_timbul' => $ls_jkk_kode_penyakit_timbul,
				':p_jkk_kode_tipe_upah' => $ls_jkk_kode_tipe_upah,
				':p_jkk_nom_upah_terakhir' => $ln_jkk_nom_upah_terakhir,
				':p_jkk_kode_tempat_perawatan' => $ls_jkk_kode_tempat_perawatan,
				':p_jkk_kode_berobat_jalan' => $ls_jkk_kode_berobat_jalan,
				':p_jkk_kode_ppk' => $ls_jkk_kode_ppk,
				':p_jkk_nama_faskes_reimburse' => $ls_jkk_nama_faskes_reimburse,
				':p_jkk_kode_kondisi_terakhir' => $ls_jkk_kode_kondisi_terakhir,
				':p_jkk_tgl_kondisi_terakhir' => $ld_jkk_tgl_kondisi_terakhir,
				':p_jkk_tipe_negara_kejadian' => $ls_jkk_tipe_negara_kejadian,
				':p_username' => $username,
				':p_kode_klaim' => $ls_kode_klaim,
			);
			foreach ($param_bv as $key => $value) {
				oci_bind_by_name($proc, $key, $param_bv[$key]);
			}
			// 20-03-2024 penyesuaian bind variable
            $DB->execute();	
						
            //insert data diagnosa ---------------------------------------------------
            $sql = "delete from sijstk.pn_klaim_diagnosa where kode_klaim = :p_kode_klaim ";				
            $proc = $DB->parse($sql);
			oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
			// 20-03-2024 penyesuaian bind variable
            $DB->execute();
            
            $ln_panjang = $_POST['jkk_dtldiag_kounter_dtl'];
            for($i=0;$i<=$ln_panjang-1;$i++)
            {		 	           												 		        
              $ls_jkk_dtldiag_nama_tenaga_medis		= $_POST['jkk_dtldiag_nama_tenaga_medis'.$i];
              $ls_jkk_dtldiag_kode_diagnosa_detil	= $_POST['jkk_dtldiag_kode_diagnosa_detil'.$i];
              $ls_jkk_dtldiag_keterangan					= $_POST['jkk_dtldiag_keterangan'.$i];
              
              if ($ls_jkk_dtldiag_nama_tenaga_medis!="" || $ls_jkk_dtldiag_kode_diagnosa_detil!="" || $ls_jkk_dtldiag_keterangan!="")
              {
                $sql = 	"select nvl(max(no_urut),0)+1 as v_no from sijstk.pn_klaim_diagnosa ".
                		 		"where kode_klaim = :p_kode_klaim ";
                $proc = $DB->parse($sql);
				oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
				// 20-03-2024 penyesuaian bind variable
                $DB->execute();
                $row = $DB->nextrow();
                $ln_no_urut = $row["V_NO"];	
                
                $sql = "insert into sijstk.pn_klaim_diagnosa ( ".
                       "  kode_klaim, no_urut, nama_tenaga_medis, kode_diagnosa_detil, keterangan, ". 
                       "  tgl_rekam, petugas_rekam) ".
                       "values ( ".
                       "	:p_kode_klaim,:p_no_urut,:p_jkk_dtldiag_nama_tenaga_medis,:p_jkk_dtldiag_kode_diagnosa_detil,:p_jkk_dtldiag_keterangan, ".
                       "	sysdate, :p_username ". 		 
                       ")";		
                $proc = $DB->parse($sql);
				$param_bv = array(
					':p_kode_klaim' => $ls_kode_klaim,
					':p_no_urut' => $ln_no_urut,
					':p_jkk_dtldiag_nama_tenaga_medis' => $ls_jkk_dtldiag_nama_tenaga_medis,
					':p_jkk_dtldiag_kode_diagnosa_detil' => $ls_jkk_dtldiag_kode_diagnosa_detil,
					':p_jkk_dtldiag_keterangan' => $ls_jkk_dtldiag_keterangan,
					':p_username' => $username,
				);
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
				// 20-03-2024 penyesuaian bind variable
                $DB->execute();
              }							
            }     			
            //end insert data diagnosa -----------------------------------------------
            
            //insert data aktivitas pelaporan ----------------------------------------
            $sql = "delete from sijstk.pn_klaim_aktivitas_pelaporan where kode_klaim = :p_kode_klaim ";				
            $proc = $DB->parse($sql);
			oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
			// 20-03-2024 penyesuaian bind variable
            $DB->execute();
            
            $ln_panjang = $_POST['jkk_dtlaktvpelap_kounter_dtl'];
            for($i=0;$i<=$ln_panjang-1;$i++)
            {				 	           												 		        
              $ld_jkk_dtlaktvpelap_tgl_aktivitas	= $_POST['jkk_dtlaktvpelap_tgl_aktivitas'.$i];
              $ls_jkk_dtlaktvpelap_nama_aktivitas	= $_POST['jkk_dtlaktvpelap_nama_aktivitas'.$i];
              $ls_jkk_dtlaktvpelap_nama_sumber		= $_POST['jkk_dtlaktvpelap_nama_sumber'.$i];
              $ls_jkk_dtlaktvpelap_profesi_sumber	= $_POST['jkk_dtlaktvpelap_profesi_sumber'.$i];
              $ls_jkk_dtlaktvpelap_alamat					= $_POST['jkk_dtlaktvpelap_alamat'.$i];
              $ls_jkk_dtlaktvpelap_telepon_area		= $_POST['jkk_dtlaktvpelap_telepon_area'.$i];
              $ls_jkk_dtlaktvpelap_telepon				= $_POST['jkk_dtlaktvpelap_telepon'.$i];
              $ls_jkk_dtlaktvpelap_keterangan			= $_POST['jkk_dtlaktvpelap_keterangan'.$i];
              
              if ($ld_jkk_dtlaktvpelap_tgl_aktivitas!="" || $ls_jkk_dtlaktvpelap_nama_aktivitas!="")
              {
                $sql = 	"select nvl(max(no_urut),0)+1 as v_no from sijstk.pn_klaim_aktivitas_pelaporan ".
                		 		"where kode_klaim = :p_kode_klaim ";
                $proc = $DB->parse($sql);
				oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
				// 20-03-2024 penyesuaian bind variable
                $DB->execute();
                $row = $DB->nextrow();
                $ln_no_urut = $row["V_NO"];	
                
                $sql = "insert into sijstk.pn_klaim_aktivitas_pelaporan ( ".
                       "	kode_klaim, no_urut, tgl_aktivitas, nama_aktivitas, nama_sumber, profesi_sumber, ". 
                       "	alamat, rt, rw, kode_kelurahan, kode_kecamatan, kode_kabupaten, kode_pos, ". 
                       "	telepon_area, telepon, telepon_ext, handphone, email, npwp, status, hasil_tindak_lanjut, ". 
                       "	keterangan, tgl_rekam, petugas_rekam) ".
                       "values ( ".
                       "	:p_kode_klaim,:p_no_urut,to_date(:p_jkk_dtlaktvpelap_tgl_aktivitas,'dd/mm/yyyy'),:p_jkk_dtlaktvpelap_nama_aktivitas,:p_jkk_dtlaktvpelap_nama_sumber,:p_jkk_dtlaktvpelap_profesi_sumber, ".
                       "	:p_jkk_dtlaktvpelap_alamat, null, null, null, null, null, null, ".
                       "	:p_jkk_dtlaktvpelap_telepon_area, :p_jkk_dtlaktvpelap_telepon, null, null, null, null, null, null, ". 
                       "	:p_jkk_dtlaktvpelap_keterangan, sysdate, :p_username ". 		 
                       ")";		
                $proc = $DB->parse($sql);
				$param_bv = array(
					':p_kode_klaim' => $ls_kode_klaim,
					':p_no_urut' => $ln_no_urut,
					':p_jkk_dtlaktvpelap_tgl_aktivitas' => $ld_jkk_dtlaktvpelap_tgl_aktivitas,
					':p_jkk_dtlaktvpelap_nama_aktivitas' => $ls_jkk_dtlaktvpelap_nama_aktivitas,
					':p_jkk_dtlaktvpelap_nama_sumber' => $ls_jkk_dtlaktvpelap_nama_sumber,
					':p_jkk_dtlaktvpelap_profesi_sumber' => $ls_jkk_dtlaktvpelap_profesi_sumber,
					':p_jkk_dtlaktvpelap_alamat' => $ls_jkk_dtlaktvpelap_alamat,
					':p_jkk_dtlaktvpelap_telepon_area' => $ls_jkk_dtlaktvpelap_telepon_area,
					':p_jkk_dtlaktvpelap_telepon' => $ls_jkk_dtlaktvpelap_telepon,
					':p_jkk_dtlaktvpelap_keterangan' => $ls_jkk_dtlaktvpelap_keterangan,
					':p_username' => $username,
				);
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
				// 20-03-2024 penyesuaian bind variable	
                $DB->execute();
              }							
            }     			
            //end insert data aktivitas pelaporan ----------------------------------------								 		
  			 							
						$ls_sukses = "1";
				 }	
			}elseif ($ls_jenis_klaim=="JKM")
			{
  		 	 if ($ls_status_klaim=="AGENDA")
  			 {
      			//update data agenda jkm -------------------------------------------
            $ld_jkm_tgl_kematian					 	 = $_POST["jkm_tgl_kematian"];
            $ls_jkm_ket_tambahan						 = $_POST["jkm_ket_tambahan"];         
      			$ls_jkm_tipe_negara_kejadian 		 = $_POST["jkm_tipe_negara_kejadian"];
				$ls_flag_meninggal_cuti				 = (($_POST["flag_meninggal_cuti"] == "" || $_POST["flag_meninggal_cuti"] == null)) ? "T" : $_POST["flag_meninggal_cuti"];
				
						 			 			
            $sql = "update sijstk.pn_klaim set ".
                   "	 tgl_kematian			      = to_date(:p_jkm_tgl_kematian,'dd/mm/yyyy'), ".                   
                   "   ket_tambahan						= :p_jkm_ket_tambahan, ". 
									 "   tipe_negara_kejadian 	= :p_jkm_tipe_negara_kejadian, ".
									 "   flag_meninggal_cuti 	= :p_flag_meninggal_cuti, ".                  					 
                   "	 tgl_ubah								= sysdate, ". 
                   "	 petugas_ubah 					= :p_username ".
                   "where kode_klaim = :p_kode_klaim ";				
            $proc = $DB->parse($sql);
			$param_bv = array(
				':p_jkm_tgl_kematian' => $ld_jkm_tgl_kematian,
				':p_jkm_ket_tambahan' => $ls_jkm_ket_tambahan,
				':p_jkm_tipe_negara_kejadian' => $ls_jkm_tipe_negara_kejadian,
				':p_flag_meninggal_cuti' => $ls_flag_meninggal_cuti,
				':p_username' => $username,
				':p_kode_klaim' => $ls_kode_klaim,
			);
			foreach ($param_bv as $key => $value) {
				oci_bind_by_name($proc, $key, $param_bv[$key]);
			}
			// 20-03-2024 penyesuaian bind variable
            $DB->execute();	
						
						if ($ls_kode_segmen=="JAKON")
						{
        			//update tk jakons -----------------------------------------------
              $ls_tkjakon_kode_tk				 			 = $_POST["tkjakon_kode_tk"];
							$ls_tkjakon_nama_tk		 					 = $_POST["tkjakon_nama_tk"];
              $ls_tkjakon_jenis_identitas  		 = $_POST["tkjakon_jenis_identitas"];	
							$ls_tkjakon_nomor_identitas  		 = $_POST["tkjakon_nomor_identitas"];											
              $ls_tkjakon_alamat_domisili	 		 = $_POST["tkjakon_alamat_domisili"];
              $ls_tkjakon_kode_pekerjaan			 = $_POST["tkjakon_kode_pekerjaan"];         
        			$ld_tkjakon_tgl_lahir						 = $_POST["tkjakon_tgl_lahir"];
							
							if ($ls_tkjakon_kode_tk=="")
							{
                //generate kode tk jakon ---------------------------------------
                $sql = 	"select sijstk.p_pn_genid.f_gen_kodetkjakon as v_kode_tkjakon from dual ";
                $DB->parse($sql);
                $DB->execute();
                $row = $DB->nextrow();
                $ls_tkjakon_kode_tk = $row["V_KODE_TKJAKON"];
							}
							 			 			
              $sql = "update sijstk.pn_klaim set ".
                     "   kode_tk 						 		= :p_tkjakon_kode_tk, ".
    								 "   nama_tk								= :p_tkjakon_nama_tk, ".
    								 "	 jenis_identitas				= :p_tkjakon_jenis_identitas, ". 
                     "   nomor_identitas 				= :p_tkjakon_nomor_identitas, ".
                     "   alamat_domisili				= :p_tkjakon_alamat_domisili, ". 
										 "   kode_pekerjaan					= :p_tkjakon_kode_pekerjaan, ".
										 "   tgl_lahir							= to_date(:p_tkjakon_tgl_lahir,'dd/mm/yyyy'), ".                 					 
                     "	 tgl_ubah								= sysdate, ". 
                     "	 petugas_ubah 					= :p_username ".
                     "where kode_klaim = :p_kode_klaim ";				
              $proc = $DB->parse($sql);
				$param_bv = array(
					':p_tkjakon_kode_tk' => $ls_tkjakon_kode_tk,
					':p_tkjakon_nama_tk' => $ls_tkjakon_nama_tk,
					':p_tkjakon_jenis_identitas' => $ls_tkjakon_jenis_identitas,
					':p_tkjakon_nomor_identitas' => $ls_tkjakon_nomor_identitas,
					':p_tkjakon_alamat_domisili' => $ls_tkjakon_alamat_domisili,
					':p_tkjakon_kode_pekerjaan' => $ls_tkjakon_kode_pekerjaan,
					':p_tkjakon_tgl_lahir' => $ld_tkjakon_tgl_lahir,
					':p_username' => $username,
					':p_kode_klaim' => $ls_kode_klaim,
				);
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
				// 20-03-2024 penyesuaian bind variable
              $DB->execute();						
						}
												
						$ls_sukses = "1";		 		
  			 }				
			}elseif ($ls_jenis_klaim=="JPN")
			{
        //update data agenda jpn -----------------------------------------------
        $ls_jpn_tk_kode_jenis_kasus			 				= $_POST["jpn_tk_kode_jenis_kasus"];
        $ld_jpn_tk_tgl_jenis_kasus			 				= $_POST["jpn_tk_tgl_jenis_kasus"];
        $ls_jpn_tk_kode_kondisi_terakhir				= $_POST["jpn_tk_kode_kondisi_terakhir"];
        $ld_jpn_tk_tgl_kondisi_terakhir					= $_POST["jpn_tk_tgl_kondisi_terakhir"];
        
        $ls_jpn_pasangan_kode_kondisi_terakhir	= $_POST["jpn_pasangan_kode_kondisi_terakhir"];
        $ld_jpn_pasangan_tgl_kondisi_terakhir		= $_POST["jpn_pasangan_tgl_kondisi_terakhir"];
        
        $ls_jpn_anak1_kode_kondisi_terakhir			= $_POST["jpn_anak1_kode_kondisi_terakhir"];
        $ld_jpn_anak1_tgl_kondisi_terakhir			= $_POST["jpn_anak1_tgl_kondisi_terakhir"];
        
        $ls_jpn_anak2_kode_kondisi_terakhir			= $_POST["jpn_anak2_kode_kondisi_terakhir"];
        $ld_jpn_anak2_tgl_kondisi_terakhir			= $_POST["jpn_anak2_tgl_kondisi_terakhir"];
        
        $ls_jpn_ortu_kode_kondisi_terakhir			= $_POST["jpn_ortu_kode_kondisi_terakhir"];
        $ld_jpn_ortu_tgl_kondisi_terakhir				= $_POST["jpn_ortu_tgl_kondisi_terakhir"];
						
        //kondisi tk -----------------------------------------------------------
        $sql = "update sijstk.pn_klaim_penerima_berkala ". 
               "set kode_kondisi_terakhir = :p_jpn_tk_kode_kondisi_terakhir, ".
               "    tgl_kondisi_terakhir = to_date(:p_jpn_tk_tgl_kondisi_terakhir,'dd/mm/yyyy'), ".
               "	 	tgl_ubah			= sysdate, ". 
               "	 	petugas_ubah = :p_username ".
               "where kode_klaim = :p_kode_klaim ".
               "and kode_penerima_berkala = 'TK' "; 
        $proc = $DB->parse($sql);
		$param_bv = array(
			':p_jpn_tk_kode_kondisi_terakhir' => $ls_jpn_tk_kode_kondisi_terakhir,
			':p_jpn_tk_tgl_kondisi_terakhir' => $ld_jpn_tk_tgl_kondisi_terakhir,
			':p_username' => $username,
			':p_kode_klaim' => $ls_kode_klaim,
		);
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
		// 20-03-2024 penyesuaian bind variable
        $DB->execute();	
				//echo $sql;
        //kondisi klaim --------------------------------------------------------
        $sql = "update sijstk.pn_klaim ". 
               "set kode_jenis_kasus = :p_jpn_tk_kode_jenis_kasus, ".
               "    tgl_kejadian 		 = to_date(:p_jpn_tk_tgl_jenis_kasus,'dd/mm/yyyy'), ".
               "		kode_kondisi_terakhir = :p_jpn_tk_kode_kondisi_terakhir, ".
               "    tgl_kondisi_terakhir 	= to_date(:p_jpn_tk_tgl_kondisi_terakhir,'dd/mm/yyyy'), ".
               "	 	tgl_ubah				= sysdate, ". 
               "	 	petugas_ubah 		= :p_username ".
               "where kode_klaim = :p_kode_klaim ";
        $proc = $DB->parse($sql);
		$param_bv = array(
			':p_jpn_tk_kode_jenis_kasus' => $ls_jpn_tk_kode_jenis_kasus,
			':p_jpn_tk_tgl_jenis_kasus' => $ld_jpn_tk_tgl_jenis_kasus,
			':p_jpn_tk_kode_kondisi_terakhir' => $ls_jpn_tk_kode_kondisi_terakhir,
			':p_jpn_tk_tgl_kondisi_terakhir' => $ld_jpn_tk_tgl_kondisi_terakhir,
			':p_username' => $username,
			':p_kode_klaim' => $ls_kode_klaim,
		);
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
		// 20-03-2024 penyesuaian bind variable
        $DB->execute();
				//echo $sql;		
        //tk meninggal, update tgl meninggal -----------------------------------
        if ($ls_jpn_tk_kode_kondisi_terakhir == "KA11")
        {
          $sql = "update sijstk.pn_klaim ". 
                 "set tgl_kematian = to_date(:p_jpn_tk_tgl_kondisi_terakhir,'dd/mm/yyyy'), ".
                 "	 	tgl_ubah			= sysdate, ". 
                 "	 	petugas_ubah = :p_username ".
                 "where kode_klaim = :p_kode_klaim ";
                 
          // $sql = "update sijstk.pn_klaim ". 
          //        "set tgl_meninggal = to_date('$ld_jpn_tk_tgl_kondisi_terakhir','dd/mm/yyyy'), ".
          //        "	 	tgl_ubah			= sysdate, ". 
          //        "	 	petugas_ubah = '$username' ".
          //        "where kode_klaim = '$ls_kode_klaim' ";
                 
          $proc = $DB->parse($sql);
			$param_bv = array(
				':p_jpn_tk_tgl_kondisi_terakhir' => $ld_jpn_tk_tgl_kondisi_terakhir,
				':p_username' => $username,
				':p_kode_klaim' => $ls_kode_klaim,
			);
			foreach ($param_bv as $key => $value) {
				oci_bind_by_name($proc, $key, $param_bv[$key]);
			}
			// 20-03-2024 penyesuaian bind variable
          $DB->execute();						
        }
				
				$ls_sukses = "1";			
			} // end ($ls_jenis_klaim=="JPN") ----------------------------------------
		}else
		{
		 	$ls_sukses = "0";	 
		}
	//update jika status klaim agenda tahap II -----------------------------------	 	 
	}else
	{
    if ($ls_jenis_klaim=="JKK" && $ls_status_klaim=="AGENDA_TAHAP_II")
    {
      //update data agenda jkk tahap II -------------------------------------
      $ls_jkk2_kode_kondisi_terakhir	 = $_POST["jkk2_kode_kondisi_terakhir"];
      $ld_jkk2_tgl_kondisi_terakhir		 = $_POST["jkk2_tgl_kondisi_terakhir"];        
      
      $sql = "update sijstk.pn_klaim set ".
             "   kode_kondisi_terakhir 	= :p_jkk2_kode_kondisi_terakhir, ". 
  					 "	 tgl_kondisi_terakhir	  = to_date(:p_jkk2_tgl_kondisi_terakhir,'dd/mm/yyyy'), ".                           					 
             "	 tgl_ubah								= sysdate, ". 
             "	 petugas_ubah 					= :p_username ".
             "where kode_klaim = :p_kode_klaim ";				
      $proc = $DB->parse($sql);
		$param_bv = array(
			':p_jkk2_kode_kondisi_terakhir' => $ls_jkk2_kode_kondisi_terakhir,
			':p_jkk2_tgl_kondisi_terakhir' => $ld_jkk2_tgl_kondisi_terakhir,
			':p_username' => $username,
			':p_kode_klaim' => $ls_kode_klaim,
		);
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
		// 20-03-2024 penyesuaian bind variable
      $DB->execute();
      $ls_sukses = "1"; 
									
		}else
		{
		 	$ls_sukses = "0"; 
		}
	}//end update jika status klaim masih agenda ---------------------------------
	
	//post update ----------------------------------------------------------------
	if ($ls_sukses == "1")
	{
		//update data kelengkapan administrasi -------------------------------------
    $ln_panjang = $_POST['d_adm_kounter_dtl'];
    for($i=0;$i<=$ln_panjang-1;$i++)
    {		 	           												 		        
      $ls_d_adm_no_urut						= $_POST['d_adm_no_urut'.$i];
			$ls_d_adm_kode_dokumen			= $_POST['d_adm_kode_dokumen'.$i];
      $ls_d_adm_nama_dokumen			= $_POST['d_adm_nama_dokumen'.$i];
			$ld_d_adm_tgl_diserahkan		= $_POST['d_adm_tgl_diserahkan'.$i];
			$ls_d_adm_ringkasan					= $_POST['d_adm_ringkasan'.$i];
			$ls_d_adm_url								= $_POST['d_adm_url'.$i];
			$ls_d_adm_keterangan				= $_POST['d_adm_keterangan'.$i];
			$ls_d_adm_status_diserahkan	= $_POST['d_adm_status_diserahkan'.$i];
      if ($ls_d_adm_status_diserahkan=="on" || $ls_d_adm_status_diserahkan=="ON" || $ls_d_adm_status_diserahkan=="Y")
      {
      	$ls_d_adm_status_diserahkan = "Y";
      }else
      {
      	$ls_d_adm_status_diserahkan = "T";	 
      }			
      
      if ($ls_d_adm_no_urut!="")
      {
        $sql = "update sijstk.pn_klaim_dokumen set ".
               "	 tgl_diserahkan			= to_date(:p_d_adm_tgl_diserahkan,'dd/mm/yyyy'), ". 
      				 "	 ringkasan					= :p_d_adm_ringkasan, ".
      				 //"	 url								= :p_d_adm_url, ". 
      				 "	 keterangan					= :p_d_adm_keterangan, ". 
      				 "	 status_diserahkan	= :p_d_adm_status_diserahkan, ". 
      				 "	 tgl_ubah						= sysdate, ". 
      				 "	 petugas_ubah 			= :p_username ".
               "where kode_klaim = :p_kode_klaim ".
							 "and kode_dokumen = :p_d_adm_kode_dokumen ";		
        $proc = $DB->parse($sql);
		$param_bv = array(
			':p_d_adm_tgl_diserahkan' => $ld_d_adm_tgl_diserahkan,
			':p_d_adm_ringkasan' => $ls_d_adm_ringkasan,
			// ':p_d_adm_url' => $ls_d_adm_url,
			':p_d_adm_keterangan' => $ls_d_adm_keterangan,
			':p_d_adm_status_diserahkan' => $ls_d_adm_status_diserahkan,
			':p_username' => $username,
			':p_kode_klaim' => $ls_kode_klaim,
			':p_d_adm_kode_dokumen' => $ls_d_adm_kode_dokumen,
		);
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
		// 20-03-2024 penyesuaian bind variable
        $DB->execute();
      }							
    }     			
		//end update data kelengkapan administrasi ---------------------------------
		
    //jalankan proses post update ----------------------------------------------
		$qry = "BEGIN SIJSTK.P_PN_PN5040.X_POST_UPDATE(:p_kode_klaim,:p_username,:p_sukses,:p_mess);END;";											 	
    $proc = $DB->parse($qry);				
    oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim,32);
    oci_bind_by_name($proc, ":p_username", $username,32);
    oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ls_sukses = $p_sukses;	 
		$ls_mess = $p_mess;	

		
		/* Penambahan Terhadap Integrasi Agenda Klaim dan Sistem Antrian*/
			/*
			if ($ls_jhtinput_handphone !="" and $ls_no_hp_antrian == ""){
				$ls_no_hp_antrian = $ls_jhtinput_handphone;
			}

			if ($ls_jhtinput_email !="" and $ls_email_antrian == ""){
				$ls_email_antrian = $ls_jhtinput_email;
			}

			$qry = "
			begin 
				pn.p_pn_antrian.x_update_antrian_klaim
				(
					:p_kode_antrian              ,
					:p_kode_jenis_antrian        ,
					:p_kode_status_antrian       , 
					:p_token_antrian             ,
					:p_kode_sisla                ,
					:p_kode_kantor_antrian       ,
					:p_no_antrian                ,
					:p_tgl_ambil                 ,
					:p_tgl_panggil               ,
					:p_kode_petugas_antrian      ,
					:p_nomor_identitas           ,  
					:p_no_hp                     ,
					:p_email                     ,
					:p_kode_klaim                ,
					:p_kode_jenis_antrian_detil  ,
					:p_keterangan                ,      
					:p_user                      , 
					:p_sukses                    , 
					:p_mess                      ,
					:p_kode_antrian_new          
				); 
			end;
			";
			// $qry = "
			// begin 
			// 	pn.p_pn_antrian.x_update_antrian_klaim
			// 	(
			// 		'$ls_kode_antrian'              ,
			// 		'$ls_kode_jenis_antrian'        ,
			// 		'$ls_kode_status_antrian'       , 
			// 		'$ls_token_sisla'             ,
			// 		'$ls_kode_sisla'                ,
			// 		'$ls_kode_kantor_antrian'       ,
			// 		'$ls_nomor_antrian'                ,
			// 		'$ld_tgl_ambil_antrian'                 ,
			// 		'$ld_tgl_panggil_antrian'               ,
			// 		'$ls_kode_petugas_antrian'      ,
			// 		'$ls_nomor_identitas'           ,  
			// 		'$ls_no_hp_antrian'                     ,
			// 		'$ls_email_antrian'                     ,
			// 		'$ls_kode_klaim'                ,
			// 		'$ls_kode_jenis_antrian_detil'  ,
			// 		'$ls_keterangan'                ,      
			// 		'$username'                      , 
			// 		:p_sukses                    , 
			// 		:p_mess                       ,
			// 		:p_kode_antrian
			// 	); 
			// end;
			// ";
			// echo($qry); die();
			$proc = $DB->parse($qry);   
			oci_bind_by_name($proc, ":p_kode_antrian", $ls_kode_antrian, 30);  
			oci_bind_by_name($proc, ":p_token_antrian", $ls_token_sisla, 500);
			oci_bind_by_name($proc, ":p_kode_jenis_antrian", $ls_kode_jenis_antrian,10);  
			oci_bind_by_name($proc, ":p_kode_sisla", $ls_kode_sisla, 100);
			oci_bind_by_name($proc, ":p_no_antrian", $ls_nomor_antrian,30);  
			oci_bind_by_name($proc, ":p_kode_kantor_antrian", $ls_kode_kantor_antrian, 10);
			oci_bind_by_name($proc, ":p_tgl_ambil", $ld_tgl_ambil_antrian,50);  
			oci_bind_by_name($proc, ":p_tgl_panggil", $ld_tgl_panggil_antrian, 50);
			oci_bind_by_name($proc, ":p_kode_petugas_antrian", $ls_kode_petugas_antrian,30); 
			oci_bind_by_name($proc, ":p_nomor_identitas", $ls_nomor_identitas,20);  
			oci_bind_by_name($proc, ":p_no_hp", $ls_no_hp_antrian, 20);
			oci_bind_by_name($proc, ":p_email", $ls_email_antrian,50);  
			oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim, 30);
			oci_bind_by_name($proc, ":p_kode_status_antrian", $ls_kode_status_antrian, 10);
			oci_bind_by_name($proc, ":p_kode_jenis_antrian_detil", $ls_kode_jenis_antrian_detil,10);  
			oci_bind_by_name($proc, ":p_keterangan", $ls_keterangan, 1000);
			oci_bind_by_name($proc, ":p_user", $username,1000);         
			oci_bind_by_name($proc, ":p_sukses", $p_sukses, 2);
			oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
			oci_bind_by_name($proc, ":p_kode_antrian_new", $p_kode_antrian_new,1000);
			$DB->execute();				
			$ls_sukses_antrian = $p_sukses;	
			$ls_mess_antrian   = $p_mess;
			if($p_kode_antrian_new !=""){
				$ls_kode_antrian = 	$p_kode_antrian_new;
			}
			*/
			/*
			$sql = 
			"update pn.pn_antrian set ".
			// "	 kode_jenis_antrian			= '$ls_kode_jenis_antrian', ". 
			// "	 kode_status_antrian		= '$ls_kode_status_antrian', ".
			// "	 token_sisla				= '$ls_token_sisla', ". 
			// "	 kode_sisla					= '$ls_kode_sisla', ". 
			// "	 kode_kantor				= '$ls_kode_kantor_antrian', ". 
			// "	 nomor_antrian				= '$ls_nomor_antrian', ".
			// "	 tgl_ambil_antrian			= to_date('$ld_tgl_ambil_antrian','YYYY-MM-DD HH24:MI:SS'), ". 
			// "	 tgl_panggil_antrian		= to_date('$ld_tgl_panggil_antrian','YYYY-MM-DD HH24:MI:SS'), ".
			// "	 petugas_panggil			= '$ls_kode_petugas_antrian', ". 
			"	 nomor_identitas		    = '$ls_nomor_identitas', ".
			"    kode_klaim_agenda          = '$ls_kode_klaim', ".
			"	 no_hp						= '$ls_no_hp_antrian', ". 
			"	 email						= '$ls_email_antrian', ".
			"	 tgl_ubah					= sysdate, ". 
			"	 petugas_ubah 				= '$username' ".
			"where kode_antrian             = '$ls_kode_antrian'  ";		
			$DB->parse($sql);
			$DB->execute();

			$sql = 
			"update pn.pn_antrian_program set ".
			"	 kode_jenis_antrian			= '$ls_kode_jenis_antrian', ". 
			"	 kode_jenis_antrian_detil	= '$ls_kode_jenis_antrian_detil', ".
			"	 keterangan				    = '$ls_keterangan', ". 
			"	 tgl_ubah					= sysdate, ". 
			"	 petugas_ubah 				= '$username' ".
			"where kode_antrian             = '$ls_kode_antrian' ";		
			$DB->parse($sql);
			$DB->execute();
			*/

		
		/* End Penambahan Terhadap Integrasi Agenda Klaim dan Sistem Antrian*/
		 
		echo '{"ret":0,"msg":"Sukses, Data berhasil disimpan, session dilanjutkan..","DATAID":"'.$ls_kode_klaim.'","kodeAntrian":"'.$ls_kode_antrian.'"}';
	}else
	{
	 	echo '{"ret":-1,"msg":"Proses gagal, data tidak dapat diupdate...!!!"}';		 
	}
}
}
else if (($TYPE =='DEL') && ($DATAID != ''))
{
 	//DELETE ---------------------------------------------------------------------  
  $qry = "BEGIN SIJSTK.P_PN_PN5040.X_POST_DELETE(:p_dataid,:p_username,:p_sukses,:p_mess);END;";											 	
  $proc = $DB->parse($qry);				
  oci_bind_by_name($proc, ":p_dataid", $DATAID,32);
  oci_bind_by_name($proc, ":p_username", $username,32);
  oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
  oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
  $DB->execute();				
  $ls_sukses = $p_sukses;	
  $ls_mess = $p_mess;	
  		 
  if ($ls_sukses=="1")
  {	
  	echo '{"ret":0,"msg":"Sukses, Data berhasil dihapus, session dilanjutkan..","DATAID":"'.$DATAID.'"}';
  }else {
  	echo '{"ret":-1,"msg":"Data agenda sudah disubmit tidak dapat dihapus...!!!"}';
  }
}else if($TYPE == 'CETAKxx_REPORT'){
  $KODE_KLAIM  = $_POST["KODE_KLAIM"];
  $KODE_REPORT = $_POST["KODE_REPORT"];
  $KODE_KANTOR = $_SESSION["kdkantorrole"];

  if($KODE_REPORT == 'JKKA1'){
    $ls_nama_rpt = 'PNR500101.rdf';
  }elseif($KODE_REPORT == 'JKKA2'){
    $ls_nama_rpt = 'PNR500102.rdf';
  }elseif($KODE_REPORT == 'JKMA1'){
    $ls_nama_rpt = 'PNR500103.rdf';
  }elseif($KODE_REPORT == 'JHT'){
    $ls_nama_rpt = 'PNR500104.rdf';
  }

  $ls_user_param = " P_KODE_KANTOR='".$KODE_KANTOR."'";
  $ls_user_param .= " P_KODE_KLAIM='".$KODE_KLAIM."'";
  $ls_user_param .= " P_KODE_USER='".$USER."'";
  $ls_pdf = $ls_nama_rpt;
  exec_rpt($paramform="no",$ls_pdf,$ls_nama_rpt,$ls_user_param);

} else if ($TYPE=='hapus_qrcode'){

    $filepath = $_POST['FILE'];
    $path     = '../../temp_qrcode/';
    if (!unlink($path.$filepath))
    {
        echo ("Error deleting qrcode $filepath");
    }else{
        echo ("Deleted qrcode $filepath");
    }

}
else if ($TYPE=="cek_validasi_adminduk")
{		
    $wsektp = new SoapClient(WSEKTP, array("location" => WSEKTPEP, "exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));

    $NIK  = $_POST["NIK"];
		$chId = 'CORE';
    if(isset($_POST["NIK"])){
      $con    =  $wsektp->callNikVerification(
                  array('chId' => $chId,
                  'username' => 'bpjs2014',
                  'password' => 'Bpjs2757',
                  'nik' => $NIK));
      
      $getData    = get_object_vars($con);
      if($getData["return"]->ret == 0){
        if($getData["return"]->addValues != ''){
          $fototk = json_decode($getData["return"]->addValues);
          for($i=0; $i<ExtendedFunction::count($fototk->dataFoto); $i++){
            $showfoto[$fototk->dataFoto[$i]->NIK] = $fototk->dataFoto[$i]->PATH_URL;
          }
        } else {
          $showfoto = '';
        }
        
        $Agama          = $getData["return"]->nilaiAgama;
        $AktaKwn        = $getData["return"]->nilaiAktaKwn;
        $Alamat         = $getData["return"]->nilaiAlamat;
				$Alamat					= str_replace("'",'',$Alamat);
        $Dusun          = $getData["return"]->nilaiDusun;
        $EktpCreated    = $getData["return"]->nilaiEktpCreated;
        $EktpLocalId    = $getData["return"]->nilaiEktpLocalId;
        $EktpStatus     = $getData["return"]->nilaiEktpStatus;
        $GolDarah       = $getData["return"]->nilaiGolDarah;
        $HubKel         = $getData["return"]->nilaiHubKel;
        $KabNo          = $getData["return"]->nilaiKabNo;
        $KecNo          = $getData["return"]->nilaiKecNo;
        $KelNo          = $getData["return"]->nilaiKelNo;
        $KodePos        = $getData["return"]->nilaiKodePos;
        $NIK            = $getData["return"]->nilaiNIK;
        $NamaAyah       = $getData["return"]->nilaiNamaAyah;
        $NamaKab        = $getData["return"]->nilaiNamaKab;
        $NamaKec        = $getData["return"]->nilaiNamaKec;
        $NamaKel        = $getData["return"]->nilaiNamaKel;
        $NamaProp       = $getData["return"]->nilaiNamaProp;
        $NoAktaCerai    = $getData["return"]->nilaiNoAktaCerai;
        $NoAktaKwn      = $getData["return"]->nilaiNoAktaKwn;
        $NoKK           = $getData["return"]->nilaiNoKK;
        $PendAkhir      = $getData["return"]->nilaiPendAkhir;
        $PenyCct        = $getData["return"]->nilaiPenyCct;
        $PropNo         = $getData["return"]->nilaiPropNo;
        $RT             = $getData["return"]->nilaiRT;
        $RW             = $getData["return"]->nilaiRW;
        $StatKwn        = $getData["return"]->nilaiStatKwn;
        $TanggalCerai   = $getData["return"]->nilaiTanggalCerai;
        $TglKwn         = $getData["return"]->nilaiTglKwn;
        $aktaLahir      = $getData["return"]->nilaiaktaLahir;
        $jenisKelamin   = $getData["return"]->nilaijenisKelamin;
        $jenisPekerjaan = $getData["return"]->nilaijenisPekerjaan;
        $namaIbu        = $getData["return"]->nilainamaIbu;
				if ($namaIbu=="MAMA" || $namaIbu=="IBU" || $namaIbu=="MOTHER" || $namaIbu=="EMAK" || $namaIbu==".." || $namaIbu=="." || $namaIbu=="-" || strlen($namaIbu)<=2)
				{
				 	 $namaIbu = "KOSONG";
				}

        $namaLengkap    = $getData["return"]->nilainamaLengkap;
        $noAktaLahir    = $getData["return"]->nilainoAktaLahir;
        $tanggalLahir   = $getData["return"]->nilaitanggalLahir;
        $tempatLahir    = $getData["return"]->nilaitempatLahir;
        $tanggal_Lahir 	= date_format(date_create($tanggalLahir),'d/m/Y');
				
        echo  '{
              "ret":0,
              "success":true,
              "msg":"Sukses!",
              "data":{
                  "namaLengkap":"'.$namaLengkap.'",
                  "tempatLahir":"'.$tempatLahir.'",
                  "tanggalLahir":"'.$tanggal_Lahir.'",
                  "jenisKelamin":"'.$jenisKelamin.'",
                  "namaIbu":"'.$namaIbu.'",
                  "statKwn":"'.$StatKwn.'",
                  "alamat":"'.$Alamat.' RT '.$RT.' / RW '.$RW.' '.$Dusun.' '.$NamaKel.' '.$NamaKec.' '.$NamaKab.' '.$NamaProp.' KODE POS '.$KodePos.'",
                  "rt":"'.$RT.'",
                  "rw":"'.$RW.'",
                  "namaKab":"'.$NamaKab.'",
                  "namaProp":"'.$NamaProp.'",
									"propNo":"'.$PropNo.'",
                  "kabNo":"'.$KabNo.$PropNo.'",
                  "kecNo":"'.$KecNo.'",
                  "kelNo":"'.$KelNo.'",
                  "namaKec":"'.$NamaKec.'",
                  "namaKel":"'.$NamaKel.'",
                  "golDarah":"'.$GolDarah.'",
                  "kodePos":"'.$KodePos.'",
                  "jenisPekerjaan":"'.$jenisPekerjaan.'",
                  "pendAkhir":"'.$PendAkhir.'",
									"showFoto":"'.$fototk->dataFoto[0]->PATH_URL.'"
                  }
              }';
      } else {
        echo '{"success":false,"msg":"Data tidak tersedia di database Kependudukan. Harap melengkap informasi Nama dan Tanggal Lahir ...!"}';
      }
    } else {
      echo '{
          "ret":-1,
          "success":false,
          "msg":"Tidak ada respon"
        }';
    }
}		
else if ($TYPE=="cek_usia_penerima_beasiswa")
{		
    $TGL_LAHIR  = $_POST["TGL_LAHIR"];
		$TGL_KEJADIAN_KLAIM  = $_POST["TGL_KEJADIAN"];
		
		$sql = "select to_char(add_months(to_date(:P_TGL_LAHIR,'dd/mm/yyyy'),3*12),'yyyymmdd') v_usia_03, ".
				 	 "			 to_char(add_months(to_date(:P_TGL_LAHIR,'dd/mm/yyyy'),23*12),'yyyymmdd') v_usia_23, ".
					 "			 to_char(to_date(:P_TGL_KEJADIAN_KLAIM,'dd/mm/yyyy'),'yyyymmdd') v_tgl_kejadian_klaim ".
					 "from dual";
    $proc = $DB->parse($sql);
	$param_bv = array(
		':P_TGL_LAHIR' => $TGL_LAHIR,
		':P_TGL_KEJADIAN_KLAIM' => $TGL_KEJADIAN_KLAIM,
	);
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
	// 20-03-2024 penyesuaian bind variable
    if($DB->execute()){
      $data = $DB->nextrow();
    }
    $ld_usia_3thn = $data['V_USIA_03'];
		$ld_usia_23thn = $data['V_USIA_23'];
		$ld_tgl_kejadian = $data['V_TGL_KEJADIAN_KLAIM'];

    if($ld_tgl_kejadian>=$ld_usia_3thn && $ld_tgl_kejadian<$ld_usia_23thn)
		{
      echo  '{
              "ret":0,
              "success":true,
              "msg":"Sukses!"
							}';
    } else {
      echo '{
           	 	"ret":-1,
          		"success":false,
          		"msg":"Usia Penerima Manfaat Beasiswa harus antara 3 s/d 23 Tahun..!"
        			}';
    }
}
else if ($TYPE=="cek_usia_penerima_beasiswapp82")
{		
    $TGL_LAHIR  		= $_POST["TGL_LAHIR"];
		$TGL_PENGAJUAN  = $_POST["TGL_PENGAJUAN"];
		$KODE_KLAIM 		= $_POST["KODE_KLAIM"];
		$KODE_MANFAAT 	= $_POST["KODE_MANFAAT"];
		
		$sql = "select ".
           "    a.kd_prg, to_char(b.tgl_kejadian,'yyyymmdd') tgl_kejadian, ". 
           "    to_char(b.tgl_kondisi_terakhir,'yyyymmdd') tgl_kondisi_terakhir, ".
					 "		b.kode_klaim_induk ". 
           "from pn.pn_klaim_manfaat a, pn.pn_klaim b ".
           "where a.kode_klaim = b.kode_klaim ".
           "and a.kode_klaim = :P_KODE_KLAIM ".
           "and a.kode_manfaat = :P_KODE_MANFAAT";
    $proc = $DB->parse($sql);
	oci_bind_by_name($proc, ':P_KODE_KLAIM', $KODE_KLAIM, 100);
	oci_bind_by_name($proc, ':P_KODE_MANFAAT', $KODE_MANFAAT, 100);
	// 20-03-2024 penyesuaian bind variable
    if($DB->execute()){$data = $DB->nextrow();}
		$ls_kd_prg = $data['KD_PRG'];
		$ld_tgl_kejadian = $data['TGL_KEJADIAN'];
		$ld_tgl_kondisi_terakhir = $data['TGL_KONDISI_TERAKHIR'];		
		$ls_kode_klaim_induk = $data['KODE_KLAIM_INDUK'];
		if ($ls_kd_prg=="2")
		{
		 	$ld_tgl_kejadian = $ld_tgl_kondisi_terakhir; 
		}
		
		$sql = "select ".
				 	 "	to_char(to_date(:P_TGL_PENGAJUAN,'dd/mm/yyyy'),'yyyymmdd') v_tgl_pengajuan, ".
				 	 "	to_char(add_months(to_date(:P_TGL_LAHIR,'dd/mm/yyyy'),23*12),'yyyymmdd') v_usia_23, ".
					 "	to_char(add_months(to_date(:P_TGL_LAHIR,'dd/mm/yyyy'),23*12),'dd/mm/yyyy') v_usia_23_ddmmyyyy, ".
					 "	case when :P_LS_KD_PRG = '2' then ".
					 "		to_date(:P_TGL_LAHIR,'dd/mm/yyyy')-to_date(:P_LD_TGL_KONDISI_TERAKHIR,'yyyymmdd')	". 
					 "	else	".
					 "		to_date(:P_TGL_LAHIR,'dd/mm/yyyy')-to_date(:P_LD_TGL_KEJADIAN,'yyyymmdd')	". 
					 "	end v_jml_hari_lahir ".
					 "from dual";
    $proc = $DB->parse($sql);
	$param_bv = array(
		':P_TGL_PENGAJUAN' => $TGL_PENGAJUAN,
		':P_TGL_LAHIR' => $TGL_LAHIR,
		':P_LS_KD_PRG' => $ls_kd_prg,
		':P_LD_TGL_KONDISI_TERAKHIR' => $ld_tgl_kondisi_terakhir,
		':P_LD_TGL_KEJADIAN' => $ld_tgl_kejadian,
	);
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
	// 20-03-2024 penyesuaian bind variable
    if($DB->execute()){$data = $DB->nextrow();}
		$ls_tgl_pengajuan = $data['V_TGL_PENGAJUAN'];
    $ld_usia_23thn = $data['V_USIA_23'];
		$ld_usia_23thn_ddmmyyyy = $data['V_USIA_23_DDMMYYYY'];
		$ln_jml_hari_lahir = $data['V_JML_HARI_LAHIR'];

    $kode_kondisi_akhir="";
    $tgl_kondisi_akhir="";

		//kondisi akhir penerima manfaat beasiswa -----------------------
    // KA16	ANAK USIA MAKSIMAL 300 HARI DALAM KANDUNGAN
    // KA17	BELUM MENEMPUH PENDIDIKAN
    // KA18	SEDANG MENEMPUH PENDIDIKAN
    // KA13	SUDAH MENCAPAI USIA 23 TAHUN
    // KA12	MENIKAH
    // KA14	BEKERJA
    // KA11	MENINGGAL DUNIA		
								
		if ($ls_kode_klaim_induk=="")
		{
		 	// penetapan awal --------------------------------- 
			if(($ln_jml_hari_lahir>0 && $ln_jml_hari_lahir<=300) || ($ln_jml_hari_lahir<=0 && $ld_tgl_kejadian<$ld_usia_23thn))
			{
				if($ln_jml_hari_lahir>0 && $ln_jml_hari_lahir<=300)
				{
				 	$kode_kondisi_akhir = "KA16";	// KA16	ANAK USIA MAKSIMAL 300 HARI DALAM KANDUNGAN	 										 
				}
				$ret_tgl_lahir=$TGL_LAHIR;
				
				echo  '{
                "ret":0,
                "success":true,
                "msg":"Sukses!",
								"data":{
                  	"v_kode_kondisi_akhir":"'.$kode_kondisi_akhir.'",
										"v_tgl_kondisi_akhir":"'.$tgl_kondisi_akhir.'",
										"v_tgl_lahir":"'.$ret_tgl_lahir.'"
                  }
  							}';			 													 
			}else
			{
			 	if ($ln_jml_hari_lahir>300)
				{
          $ret_tgl_lahir="";
					echo '{
               	 	"ret":-1,
              		"success":false,
              		"msg":"Anak berusia lebih dari maximum 300 hari dalam kandungan, tidak berhak mendapatkan manfaat beasiswa ..!",
									"data":{
                    	"v_kode_kondisi_akhir":"'.$kode_kondisi_akhir.'",
  										"v_tgl_kondisi_akhir":"'.$tgl_kondisi_akhir.'",
											"v_tgl_lahir":"'.$ret_tgl_lahir.'"
                    }									
            			}';				
				}elseif ($ld_tgl_kejadian>=$ld_usia_23thn)
				{
  				$kode_kondisi_akhir = "KA13";		// KA13	SUDAH MENCAPAI USIA 23 TAHUN
					$tgl_kondisi_akhir  = $ld_usia_23thn_ddmmyyyy;
					$ret_tgl_lahir="";												 			
          echo '{
               	 	"ret":-1,
              		"success":false,
              		"msg":"Anak sudah mencapai usia 23 tahun pada saat tgl kejadian, tidak berhak mendapatkan beasiswa ..!",
									"data":{
                    	"v_kode_kondisi_akhir":"'.$kode_kondisi_akhir.'",
  										"v_tgl_kondisi_akhir":"'.$tgl_kondisi_akhir.'",
											"v_tgl_lahir":"'.$ret_tgl_lahir.'"
                    }
            			}';					
				}else
				{
          $ret_tgl_lahir="";
					echo '{
               	 	"ret":-1,
              		"success":false,
              		"msg":"Anak tidak berhak mendapatkan manfaat beasiswa ..!",
									"data":{
                    	"v_kode_kondisi_akhir":"'.$kode_kondisi_akhir.'",
  										"v_tgl_kondisi_akhir":"'.$tgl_kondisi_akhir.'",
											"v_tgl_lahir":"'.$ret_tgl_lahir.'"
                    }									
            			}';					
				}	 
			}
		}else
		{
		 	// penetapan ulang -------------------------------- 
			$ret_tgl_lahir=$TGL_LAHIR;
			if(($ln_jml_hari_lahir>0 && $ln_jml_hari_lahir<=300) || ($ln_jml_hari_lahir<=0 && $ls_tgl_pengajuan<$ld_usia_23thn))
			{
				if($ln_jml_hari_lahir>0 && $ln_jml_hari_lahir<=300)
				{
				 	$kode_kondisi_akhir = "KA16";		// KA16	ANAK USIA MAKSIMAL 300 HARI DALAM KANDUNGAN											 
				}
				
				echo  '{
                "ret":0,
                "success":true,
                "msg":"Sukses!",
								"data":{
                  	"v_kode_kondisi_akhir":"'.$kode_kondisi_akhir.'",
										"v_tgl_kondisi_akhir":"'.$tgl_kondisi_akhir.'",
										"v_tgl_lahir":"'.$ret_tgl_lahir.'"
                  }
  							}';			 													 
			}else
			{
			 	if ($ln_jml_hari_lahir>300)
				{
          echo '{
               	 	"ret":-1,
              		"success":false,
              		"msg":"Anak berusia lebih dari maximum 300 hari dalam kandungan, tidak berhak mendapatkan manfaat beasiswa ..!",
									"data":{
                    	"v_kode_kondisi_akhir":"'.$kode_kondisi_akhir.'",
  										"v_tgl_kondisi_akhir":"'.$tgl_kondisi_akhir.'",
											"v_tgl_lahir":"'.$ret_tgl_lahir.'"
                    }									
            			}';				
				}elseif ($ls_tgl_pengajuan>=$ld_usia_23thn)
				{
  				$kode_kondisi_akhir = "KA13";		// KA13	SUDAH MENCAPAI USIA 23 TAHUN
					$tgl_kondisi_akhir  = $ld_usia_23thn_ddmmyyyy;												 			
          echo '{
               	 	"ret":-1,
              		"success":false,
              		"msg":"Anak sudah mencapai usia 23 tahun pada saat tgl pengajuan, tidak berhak mendapatkan beasiswa ..!",
									"data":{
                    	"v_kode_kondisi_akhir":"'.$kode_kondisi_akhir.'",
  										"v_tgl_kondisi_akhir":"'.$tgl_kondisi_akhir.'",
											"v_tgl_lahir":"'.$ret_tgl_lahir.'"
                    }
            			}';					
				}else
				{
          echo '{
               	 	"ret":-1,
              		"success":false,
              		"msg":"Anak tidak berhak mendapatkan manfaat beasiswa ..!",
									"data":{
                    	"v_kode_kondisi_akhir":"'.$kode_kondisi_akhir.'",
  										"v_tgl_kondisi_akhir":"'.$tgl_kondisi_akhir.'",
											"v_tgl_lahir":"'.$ret_tgl_lahir.'"
                    }									
            			}';					
				}	 
			}			 
		}
}	
else if ($TYPE=="cek_kelayakan_penerima_beasiswapp82")
{		
    $KODE_KLAIM 		= $_POST["KODE_KLAIM"];
		$KODE_MANFAAT 	= $_POST["KODE_MANFAAT"];
		$TGL_LAHIR  		= $_POST["TGL_LAHIR"];
		$TGL_PENGAJUAN  = $_POST["TGL_PENGAJUAN"];
		$KONDISI_AKHIR_PB  = $_POST["KONDISI_AKHIR"];
		$TGL_KONDISI_AKHIR_PB = $_POST["TGL_KONDISI_AKHIR"];
		
		$sql = "select ".
				 	 "		a.kd_prg, ".
           "		case when b.kode_klaim_induk is null then ".	     
					 "			case when a.kd_prg = 2 then ".
					 "				to_char(b.tgl_kondisi_terakhir,'dd/mm/yyyy') ".				
					 "			else ".
					 "				to_char(b.tgl_kejadian,'dd/mm/yyyy') ".
					 "			end ". 
					 "		else ".
					 "			:P_TGL_PENGAJUAN ".
					 "		end tgl_validasi, ".
					 "		b.kode_klaim_induk ". 
           "from pn.pn_klaim_manfaat a, pn.pn_klaim b ".
           "where a.kode_klaim = b.kode_klaim ".
           "and a.kode_klaim = :P_KODE_KLAIM ".
           "and a.kode_manfaat = :P_KODE_MANFAAT";
    $proc = $DB->parse($sql);
	oci_bind_by_name($proc, ':P_TGL_PENGAJUAN', $TGL_PENGAJUAN, 100);
	oci_bind_by_name($proc, ':P_KODE_KLAIM', $KODE_KLAIM, 100);
	oci_bind_by_name($proc, ':P_KODE_MANFAAT', $KODE_MANFAAT, 100);
	// 20-03-2024 penyesuaian bind variable
    if($DB->execute()){$data = $DB->nextrow();}
		$ls_kd_prg = $data['KD_PRG'];
		$ld_tgl_validasi = $data['TGL_VALIDASI'];	
		$ls_kode_klaim_induk = $data['KODE_KLAIM_INDUK'];
		
		$sql = "select ".
				 	 "	to_char(to_date(:P_TGL_PENGAJUAN,'dd/mm/yyyy'),'yyyymmdd') v_tgl_pengajuan_yyyymmdd, ".
					 "	to_char(to_date(:P_TGL_KONDISI_AKHIR_PB,'dd/mm/yyyy'),'yyyymmdd') v_tgl_kondisi_akhir_pb_yyyymmd, ".
					 "	to_char(to_date(:P_ld_tgl_validasi,'dd/mm/yyyy'),'yyyymmdd') v_tgl_validasi_yyyymmdd, ".
					 "	to_char(to_date(:P_ld_tgl_validasi,'dd/mm/yyyy'),'yyyy') v_thn_validasi ".
					 "from dual";
    $proc = $DB->parse($sql);
	$param_bv = array(
		':P_TGL_PENGAJUAN' => $TGL_PENGAJUAN,
		':P_TGL_KONDISI_AKHIR_PB' => $TGL_KONDISI_AKHIR_PB,
		':P_ld_tgl_validasi' => $ld_tgl_validasi,
	);
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
	// 20-03-2024 penyesuaian bind variable
    if($DB->execute()){$data = $DB->nextrow();}
		$ls_tgl_pengajuan_yyyymmdd = $data['V_TGL_PENGAJUAN_YYYYMMDD'];
		$ls_tgl_kondisi_akhir_pb_yyyymmdd = $data['V_TGL_KONDISI_AKHIR_PB_YYYYMMD'];
		$ls_tgl_validasi_yyyymmdd = $data['V_TGL_VALIDASI_YYYYMMDD'];
		$ls_thn_validasi = $data['V_THN_VALIDASI'];
		
		//kondisi akhir penerima manfaat beasiswa -----------------------
    // KA16	ANAK USIA MAKSIMAL 300 HARI DALAM KANDUNGAN
    // KA17	BELUM MENEMPUH PENDIDIKAN
    // KA18	SEDANG MENEMPUH PENDIDIKAN
    // KA13	SUDAH MENCAPAI USIA 23 TAHUN
    // KA12	MENIKAH
    // KA14	BEKERJA
    // KA11	MENINGGAL DUNIA			

      $keterangan = "";						
      $beasiswa_flag_ditunda 	  = "T";
      $beasiswa_flag_dihentikan = "T";
      $beasiswa_flag_diterima 	= "T";	
											
			if ($KONDISI_AKHIR_PB!="")
			{
			 	 if ($KONDISI_AKHIR_PB=="KA16")
				 {
				 		// KA16	ANAK USIA MAKSIMAL 300 HARI DALAM KANDUNGAN ----------------
				 		$keterangan = "MANFAAT DITUNDA SAMPAI ANAK MENEMPUH PENDIDIKAN.";
						$beasiswa_flag_ditunda 	  = "Y";
						$beasiswa_flag_dihentikan = "T";
						$beasiswa_flag_diterima 	= "T";
				 }elseif ($KONDISI_AKHIR_PB=="KA17")
				 {
				 		// KA17	BELUM MENEMPUH PENDIDIKAN ----------------------------------
						$keterangan = "MANFAAT DITUNDA SAMPAI ANAK MENEMPUH PENDIDIKAN.";
						$beasiswa_flag_ditunda 	  = "Y";
						$beasiswa_flag_dihentikan = "T";
						$beasiswa_flag_diterima 	= "T";
				 }elseif ($KONDISI_AKHIR_PB=="KA18")
				 {
				 		// KA18	SEDANG MENEMPUH PENDIDIKAN ---------------------------------
						$keterangan = "SEDANG MENEMPUH PENDIDIKAN, MANFAAT DAPAT DITERIMA SAMPAI DENGAN TIMBULNYA KONDISI AKHIR USIA 23/BEKERJA/MENIKAH/MENINGGAL.";
						$beasiswa_flag_ditunda 	  = "T";
						$beasiswa_flag_dihentikan = "T";
						$beasiswa_flag_diterima 	= "Y";
				 }elseif ($KONDISI_AKHIR_PB=="KA13" || $KONDISI_AKHIR_PB=="KA12" || $KONDISI_AKHIR_PB=="KA14" || $KONDISI_AKHIR_PB=="KA11")
				 {
				 		// KA13	SUDAH MENCAPAI USIA 23 TAHUN -------------------------------
						// KA12	MENIKAH
    				// KA14	BEKERJA
    				// KA11	MENINGGAL DUNIA
						if ($ls_tgl_kondisi_akhir_pb_yyyymmdd!="")
						{
  						if ($ls_tgl_kondisi_akhir_pb_yyyymmdd<=$ls_tgl_validasi_yyyymmdd)
							{
  							if ($ls_kode_klaim_induk=="")
								{ 
  								if ($ls_kd_prg=="2")
  								{	 
  									$keterangan = "KONDISI AKHIR PENERIMA BEASISWA TGL $TGL_KONDISI_AKHIR_PB SEBELUM TGL KONDISI AKHIR JKK TGL $ld_tgl_validasi, MANFAAT BEASISWA TIDAK DAPAT DITERIMA."; 									
  								}else
  								{
  								 	$keterangan = "KONDISI AKHIR PENERIMA BEASISWA TGL $TGL_KONDISI_AKHIR_PB SEBELUM TGL KEJADIAN JKM TGL $ld_tgl_validasi, MANFAAT BEASISWA TIDAK DAPAT DITERIMA.";
      						}
								}else
								{
  								$keterangan = "KONDISI AKHIR PENERIMA BEASISWA TGL $TGL_KONDISI_AKHIR_PB SEBELUM TGL PENGAJUAN BEASISWA TGL $ld_tgl_validasi, MANFAAT BEASISWA TIDAK DAPAT DITERIMA."; 									
  							}
								$beasiswa_flag_ditunda 	  = "T";
    						$beasiswa_flag_dihentikan = "Y";
    						$beasiswa_flag_diterima 	= "T";
							}else
							{
  							if ($ls_kode_klaim_induk=="")
								{ 
  								if ($ls_kd_prg=="2")
  								{	 
  									$keterangan = "KONDISI AKHIR PENERIMA BEASISWA TGL $TGL_KONDISI_AKHIR_PB SETELAH TGL KONDISI AKHIR JKK TGL $ld_tgl_validasi SEHINGGA MANFAAT BEASISWA MASIH DAPAT DITERIMA UNTUK TAHUN $ls_thn_validasi DAN DIHENTIKAN UNTUK TAHUN SELANJUTNYA.";								
  								}else
  								{
  								 	$keterangan = "KONDISI AKHIR PENERIMA BEASISWA TGL $TGL_KONDISI_AKHIR_PB SETELAH TGL KEJADIAN JKM TGL $ld_tgl_validasi SEHINGGA MANFAAT BEASISWA MASIH DAPAT DITERIMA UNTUK TAHUN $ls_thn_validasi DAN DIHENTIKAN UNTUK TAHUN SELANJUTNYA.";
      						}
								}else
								{
  								$keterangan = "KONDISI AKHIR PENERIMA BEASISWA TGL $TGL_KONDISI_AKHIR_PB SETELAH TGL PENGAJUAN BEASISWA TGL $ld_tgl_validasi SEHINGGA MANFAAT BEASISWA MASIH DAPAT DITERIMA UNTUK TAHUN $ls_thn_validasi DAN DIHENTIKAN UNTUK TAHUN SELANJUTNYA.";						
  							}
								$beasiswa_flag_ditunda 	  = "T";
    						$beasiswa_flag_dihentikan = "Y";
    						$beasiswa_flag_diterima 	= "Y";							
							}
						}else
						{
              $keterangan = "BELUM DIINPUT TGL KONDISI AKHIR";						
              $beasiswa_flag_ditunda 	  = "T";
              $beasiswa_flag_dihentikan = "T";
              $beasiswa_flag_diterima 	= "T";	
						}
				 } 	  	 
			}
			
      echo  '{
        "ret":0,
        "success":true,
        "msg":"Sukses!",
        "data":{
          "v_beasiswa_flag_ditunda":"'.$beasiswa_flag_ditunda.'",
          "v_beasiswa_flag_dihentikan":"'.$beasiswa_flag_dihentikan.'",
          "v_beasiswa_flag_diterima":"'.$beasiswa_flag_diterima.'",
					"v_keterangan":"'.$keterangan.'"
        }
      }';			
}			
else if ($TYPE=="cek_exist_nik")
{		
    $KODE_KLAIM		= $_POST["KODE_KLAIM"];
		$NIK  				= $_POST["NIK"];
		$KODE_MANFAAT = $_POST["KODE_MANFAAT"];
		$NO_URUT  		= $_POST["NO_URUT"];
		
		//untuk tki, sejak 10/12/2018, maksimum penerima beasiswa adalah 2 org -----
		//untuk pu/bpu/jakon update 23/12/2019 pp82 --------------------------------
		$sql = "select count(distinct beasiswa_nik_penerima) as v_cnt from sijstk.pn_klaim_manfaat_detil a ".
           "where kode_manfaat = :P_KODE_MANFAAT ".
           "and kode_klaim||no_urut <> :P_KODE_KLAIM||nvl(:P_NO_URUT,999999) ".
           "and (nvl(nom_biaya_disetujui,0)<>0 or nvl(beasiswa_flag_ditunda,'T')='Y' or nvl(beasiswa_flag_dihentikan,'T')='Y') ".
           "and beasiswa_nik_penerima is not null ".
					 "and beasiswa_nik_penerima <> nvl(:P_NIK,'XyZ') ".
           "and kode_klaim in ".
           "( ". 
           "    select kode_klaim from pn.pn_klaim ". 
           "    start with kode_klaim = :P_KODE_KLAIM and nvl(status_batal,'T')='T' ". 
           "    connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')='T' ". 
           ")";
    $proc = $DB->parse($sql);
	$param_bv = array(
		':P_KODE_MANFAAT' => $KODE_MANFAAT,
		':P_KODE_KLAIM' => $KODE_KLAIM,
		':P_NO_URUT' => $NO_URUT,
		':P_NIK' => $NIK,
	);
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
	// 20-03-2024 penyesuaian bind variable
    if($DB->execute()){
      $data = $DB->nextrow();
    }
    $ln_cnt_penerima = $data['V_CNT'];
		if ($ln_cnt_penerima==""){$ln_cnt_penerima="0";}
		
		if ($ln_cnt_penerima=="2")
		{
		 	//jika sudah ada 2 penerima manfaat lain maka tidak dapat diinput lg ----
      echo '{
           	 	"ret":-1,
          		"success":false,
          		"msg":"Manfaat Beasiswa maksimum untuk 2 orang penerima..!"
        			}';
		}else
		{
		 	//cek apakah di klaim yg sedang ditetapkan, nik tsb sudah pernah direkam sbg penerima beasiswa
      $sql = "select count(*) as v_cnt2 from sijstk.pn_klaim_manfaat_detil a ".
             "where kode_klaim = :P_KODE_KLAIM ". 
						 "and kode_manfaat = :P_KODE_MANFAAT ".
             "and no_urut <> nvl(:P_NO_URUT,999999) ".
             "and (nvl(nom_biaya_disetujui,0)<>0 or nvl(beasiswa_flag_ditunda,'T')='Y' or nvl(beasiswa_flag_dihentikan,'T')='Y') ".
             "and nvl(beasiswa_nik_penerima,'AbC') = nvl(:P_NIK,'XyZ') ";
      $proc = $DB->parse($sql);
		$param_bv = array(
			':P_KODE_KLAIM' => $KODE_KLAIM,
			':P_KODE_MANFAAT' => $KODE_MANFAAT,
			':P_NO_URUT' => $NO_URUT,
			':P_NIK' => $NIK,
		);
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
		// 20-03-2024 penyesuaian bind variable
      if($DB->execute()){
      	$data = $DB->nextrow();
      }
      $ln_cnt_exist = $data['V_CNT2'];
      if ($ln_cnt_exist==""){$ln_cnt_exist="0";}
			
			if ($ln_cnt_exist=="0")
			{
        //cek apakah di kode_klaim lain (diluar klaim jajaran), nik tsb sudah pernah direkam sbg penerima beasiswa
    		$sql = "select count(distinct beasiswa_nik_penerima) as v_cnt from sijstk.pn_klaim_manfaat_detil a ".
               "where kode_manfaat = :P_KODE_MANFAAT ".
               "and kode_klaim||no_urut <> :P_KODE_KLAIM||nvl(:P_NO_URUT,999999) ".
               "and (nvl(nom_biaya_disetujui,0)<>0 or nvl(beasiswa_flag_ditunda,'T')='Y' or nvl(beasiswa_flag_dihentikan,'T')='Y') ".
               "and beasiswa_nik_penerima is not null ".
    					 "and beasiswa_nik_penerima = nvl(:P_NIK,'XyZ') ".
               "and kode_klaim not in ".
               "( ". 
               "    select kode_klaim from pn.pn_klaim ". 
               "    start with kode_klaim = :P_KODE_KLAIM and nvl(status_batal,'T')='T' ". 
               "    connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')='T' ". 
               ") and exists(
					select null from smile.pn_klaim where kode_klaim = a.kode_klaim and nvl(status_batal,'T')='T'
				)";
					

        $proc = $DB->parse($sql);
		$param_bv = array(
			':P_KODE_MANFAAT' => $KODE_MANFAAT,
			':P_KODE_KLAIM' => $KODE_KLAIM,
			':P_NO_URUT' => $NO_URUT,
			':P_NIK' => $NIK,
		);
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
		// 20-03-2024 penyesuaian bind variable
        if($DB->execute()){
          $data = $DB->nextrow();
        }
        $ln_exists_nik_penerima = $data['V_CNT'];
    		if ($ln_exists_nik_penerima==""){$ln_exists_nik_penerima="0";}				
				
				if ($ln_exists_nik_penerima>0)
				{
          //ambil salah satu kode_klaim di kode_klaim lain (diluar klaim jajaran), nik tsb sudah pernah direkam sbg penerima beasiswa
      		$sql = "select kode_klaim as v_kode_klaim_lain from sijstk.pn_klaim_manfaat_detil a ".
                 "where kode_manfaat = :P_KODE_MANFAAT ".
                 "and kode_klaim||no_urut <> :P_KODE_KLAIM||nvl(:P_NO_URUT,999999) ".
                 "and (nvl(nom_biaya_disetujui,0)<>0 or nvl(beasiswa_flag_ditunda,'T')='Y' or nvl(beasiswa_flag_dihentikan,'T')='Y') ".
                 "and beasiswa_nik_penerima is not null ".
      					 "and beasiswa_nik_penerima = nvl(:P_NIK,'XyZ') ".
                 "and kode_klaim not in ".
                 "( ". 
                 "    select kode_klaim from pn.pn_klaim ". 
                 "    start with kode_klaim = :P_KODE_KLAIM and nvl(status_batal,'T')='T' ". 
                 "    connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')='T' ". 
                 ") and exists(
					select null from smile.pn_klaim where kode_klaim = a.kode_klaim and nvl(status_batal,'T')='T'
				)".
								 " and rownum = 1";
					

          $proc = $DB->parse($sql);
			$param_bv = array(
				':P_KODE_MANFAAT' => $KODE_MANFAAT,
				':P_KODE_KLAIM' => $KODE_KLAIM,
				':P_NO_URUT' => $NO_URUT,
				':P_NIK' => $NIK,
				':P_KODE_KLAIM' => $KODE_KLAIM,
			);
			foreach ($param_bv as $key => $value) {
				oci_bind_by_name($proc, $key, $param_bv[$key]);
			}
			// 20-03-2024 penyesuaian bind variable
          if($DB->execute()){
            $data = $DB->nextrow();
          }
          $ls_kode_klaim_lain = $data['V_KODE_KLAIM_LAIN'];         	
					$ls_mess = "NIK sudah menerima manfaat beasiswa di kode_klaim ".$ls_kode_klaim_lain.", manfaat beasiswa tidak dapat diberikan kembali di penetapan klaim yang lain..!";
					echo '{
             	 	"ret":-1,
            		"success":false,
								"msg":"'.$ls_mess.'"
          			}';				
				}else
				{
  				echo  '{
                  "ret":0,
                  "success":true,
                  "msg":"Sukses!"
    							}';
				}							
			}else
			{
        echo '{
             	 	"ret":-1,
            		"success":false,
            		"msg":"NIK tidak boleh menerima manfaat beasiswa lebih dari satu kali untuk penetapan klaim yang sama..!"
          			}';			
			}	 
		}
}
else if ($TYPE=="f_getdata_tipepenerima_bph")
{		
    $vKodeKlaim		= $_POST["vKodeKlaim"];
		$vkode_tipe_penerima = $_POST["vkode_tipe_penerima"];
		
		//update 28/01/2020, ambil data balai harta peninggalan --------------------
		if ($vKodeKlaim!="")
    {
      $sql = "select kode_kantor, to_char(tgl_klaim,'dd/mm/yyyy') tgl_klaim from sijstk.pn_klaim ".
             "where kode_klaim = :p_kode_klaim ";
    	$proc = $DB->parse($sql);
	oci_bind_by_name($proc, ':p_kode_klaim', $vKodeKlaim, 100);
	// 20-03-2024 penyesuaian bind variable
      $DB->execute();
      $row = $DB->nextrow();
    	$ls_kode_kantor	= $row['KODE_KANTOR'];
			$ld_tgl_klaim		= $row['TGL_KLAIM'];
			
			if ($ls_kode_kantor!="")
			{
			 	//ambil mapping sesuai kode_kantor ------------------------------------
        $sql = "select kode_bhp from pn.pn_kode_bhp_kantor a ".
               "where a.kode_kantor = :p_kode_kantor ".
               "and nvl(to_char(a.tgl_nonaktif,'yyyymmdd'),'30001231')>to_char(to_date(:p_tgl_klaim,'dd/mm/yyyy'),'yyyymmdd') ".
               "and rownum = 1 ";
        $proc = $DB->parse($sql);
		oci_bind_by_name($proc, ':p_kode_kantor', $ls_kode_kantor, 100);
		oci_bind_by_name($proc, ':p_tgl_klaim', $ld_tgl_klaim, 100);
		// 20-03-2024 penyesuaian bind variable
        $DB->execute();
        $row = $DB->nextrow();
        $ls_kode_bhp	= $row['KODE_BHP'];
				
				//ambil data bhp ------------------------------------------------------
        $sql = "select ". 
               "	kode_bhp, nama_bhp, alamat_bhp, nama_penerima_bhp, ". 
               "	bank_penerima_bhp, no_rekening_penerima_bhp, nama_rekening_penerima_bhp, ". 
               "	telepon_area, telepon, fax_area, fax, email ".
               "from pn.pn_kode_bhp ".
               "where kode_bhp = :p_kode_bhp ";
        $proc = $DB->parse($sql);
		oci_bind_by_name($proc, ':p_kode_bhp', $ls_kode_bhp, 100);
		// 20-03-2024 penyesuaian bind variable
        $DB->execute();
        $row = $DB->nextrow();
				$ls_nama_bhp					= $row['NAMA_BHP']; 
				$ls_alamat_bhp				= $row['ALAMAT_BHP'];
				$ls_nama_penerima_bhp	= $row['NAMA_PENERIMA_BHP'];
        $ls_bank_penerima_bhp	= $row['BANK_PENERIMA_BHP'];
				$ls_no_rekening_penerima_bhp		= $row['NO_REKENING_PENERIMA_BHP'];
				$ls_nama_rekening_penerima_bhp	= $row['NAMA_REKENING_PENERIMA_BHP'];
				$ls_telepon_area			= $row['TELEPON_AREA'];
				$ls_telepon						= $row['TELEPON'];
				$ls_fax_area					= $row['FAX_AREA'];
				$ls_fax								= $row['FAX'];
				$ls_email							= $row['EMAIL'];

        echo  '{
          "ret":0,
          "success":true,
          "msg":"Sukses!",
          "data":{
            "v_kode_bhp":"'.$ls_kode_bhp.'",
            "v_nama_bhp":"'.$ls_nama_bhp.'",
            "v_alamat_bhp":"'.$ls_alamat_bhp.'",
            "v_nama_penerima_bhp":"'.$ls_nama_penerima_bhp.'",
            "v_bank_penerima_bhp":"'.$ls_bank_penerima_bhp.'",
            "v_no_rekening_penerima_bhp":"'.$ls_no_rekening_penerima_bhp.'",
            "v_nama_rekening_penerima_bhp":"'.$ls_nama_rekening_penerima_bhp.'",
            "v_telepon_area":"'.$ls_telepon_area.'",
            "v_telepon":"'.$ls_telepon.'",
            "v_fax_area":"'.$ls_fax_area.'",
            "v_fax":"'.$ls_fax.'",
            "v_email":"'.$ls_email.'"
          }
        }';				 
			}
			else
			{
        echo '{
             	 	"ret":-1,
            		"success":false,
            		"msg":"Kode Kantor kosong..!"
          			}';				
			}
    }
		else
		{
      echo '{
           	 	"ret":-1,
          		"success":false,
          		"msg":"Kode Klaim kosong..!"
        			}';		
		}
}

// set HTTP header -----------------------------------------------------------
$headers = array(
    'Content-Type' => 'application/json',
    'X-Forwarded-For' => $ipfwd,
  );


function get_json_encode($p_url, $p_fields)
{
  


  $ch = curl_init();
  // Set the url, number of POST vars, POST data ----------
  curl_setopt($ch, CURLOPT_URL, $p_url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($p_fields));
  
  // Execute post ---------------------------------------
  $result = curl_exec($ch);
  // Close connection
  curl_close($ch);
	
  return $result;
}// end get_json_encode --------------------------------------------------------


function get_nama_kantor($kode_kantor)
{
	global $DB, $gs_DBUser, $gs_DBPass, $gs_DBName;
	$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
	$sql = 	"select nama_kantor from ms.ms_kantor where kode_kantor = :p_kode_kantor and rownum=1 ";
	$proc = $DB->parse($sql);
	oci_bind_by_name($proc, ':p_kode_kantor', $kode_kantor, 100);
	// 20-03-2024 penyesuaian bind variable
	$DB->execute();
	$row = $DB->nextrow();
	return $row["NAMA_KANTOR"];
}

?>
