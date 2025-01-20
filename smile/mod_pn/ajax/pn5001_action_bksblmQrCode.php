<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE			= $_POST["TYPE"];
$DATAID		= $_POST["DATAID"];
$DATAID2	= $_POST["DATAID2"];

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

//VIEW -------------------------------------------------------------------------
if ($TYPE=="View" && $DATAID != '')
{
  //query data --------------------------------------------------------		
}
else if ($TYPE=="New")
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
				 "	 tgl_rekam, petugas_rekam) ".
         "values ( ".
				 "	 '$ls_kode_klaim','$ls_kode_klaim','$ls_kode_kantor','$ls_kode_segmen', ".
				 "	 '$ls_kode_perusahaan','$ls_kode_divisi','$ls_kode_proyek','$ls_kode_tk','$ls_nama_tk','$ls_kpj', ".
				 "	 '$ls_nomor_identitas','$ls_jenis_identitas','$ls_kode_kantor_tk', ".
				 "	 '$ls_kode_tipe_klaim','$ls_kode_sebab_klaim', to_date('$ld_tgl_klaim','dd/mm/yyyy'), ".
				 "	 to_date('$ld_tgl_lapor','dd/mm/yyyy'), '$ls_keterangan', to_date('$ld_tgl_kejadian','dd/mm/yyyy'), ".
				 "	 '$ls_status_kepesertaan', '$ls_kode_perlindungan', ". 
				 "	 to_date('$ld_tgl_awal_perlindungan','dd/mm/yyyy'), to_date('$ld_tgl_akhir_perlindungan','dd/mm/yyyy'), ".
				 "	 sysdate,'$username' ". 	 	  		 
				 ") ";				 
	$DB->parse($sql);
  if($DB->execute())
	{		 	
    //jalankan proses post insert ----------------------------------------------
		$qry = "BEGIN SIJSTK.P_PN_PN5001.X_POST_INSERT('$ls_kode_klaim','$username',:p_sukses,:p_mess);END;";											 	
    $proc = $DB->parse($qry);				
    oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ls_sukses = $p_sukses;	
		$ls_mess = $p_mess;		 									 
		
		echo '{"ret":0,"msg":"Sukses, Data agenda berhasil disimpan, session dilanjutkan..","DATAID":"'.$ls_kode_klaim.'"}';		
  }else {
  	echo '{"ret":-1,"msg":"Proses gagal, data gagal ditambahkan...!!!"}';
  }
}
else if ($TYPE=="Edit" && $DATAID != '')
{	 
  $ls_sukses = "0";
	//update jika status klaim masih agenda --------------------------------------
	if (($ls_status_klaim=="AGENDA") || ($ls_status_klaim=="AGENDA_TAHAP_I"))
  {
  	$sql = "update sijstk.pn_klaim set ".
           "	 kode_kantor			= '$ls_kode_kantor', ". 
  				 "	 kode_segmen			= '$ls_kode_segmen', ".
  				 "	 kode_perusahaan	= '$ls_kode_perusahaan', ". 
  				 "	 kode_divisi			= '$ls_kode_divisi', ". 
  				 "	 kode_proyek			= '$ls_kode_proyek', ".
  				 "	 kode_tk					= '$ls_kode_tk', ". 
  				 "	 nama_tk					= '$ls_nama_tk', ".
					 "	 kpj							= '$ls_kpj', ".
           "	 nomor_identitas	= '$ls_nomor_identitas', ". 
  				 "	 jenis_identitas	= '$ls_jenis_identitas', ". 
  				 "	 kode_kantor_tk		= '$ls_kode_kantor_tk', ".
           "	 kode_tipe_klaim	= '$ls_kode_tipe_klaim', ". 
  				 "	 kode_sebab_klaim	= '$ls_kode_sebab_klaim', ". 
  				 "	 tgl_klaim				= to_date('$ld_tgl_klaim','dd/mm/yyyy'), ".
           "	 tgl_lapor				= to_date('$ld_tgl_lapor','dd/mm/yyyy'), ". 
  	 		 	 "	 tgl_kejadian = to_date('$ld_tgl_kejadian','dd/mm/yyyy'), ".
  				 "	 status_kepesertaan = '$ls_status_kepesertaan', ".
  				 "	 kode_perlindungan 	= '$ls_kode_perlindungan', ".
  				 "	 tgl_awal_perlindungan = to_date('$ld_tgl_awal_perlindungan','dd/mm/yyyy'), ".
  				 "	 tgl_akhir_perlindungan =to_date('$ld_tgl_akhir_perlindungan','dd/mm/yyyy') , ". 					 
  				 "	 keterangan				= '$ls_keterangan', ". 
					 "	 status_klaim 		= '$ls_status_klaim', ".
  				 "	 tgl_ubah					= sysdate, ". 
  				 "	 petugas_ubah 		= '$username' ".
           "where kode_klaim = '$ls_kode_klaim' ";				
    $DB->parse($sql);
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
  					
						if ($ls_jhtinput_ket_hubungan_lainnya!="")
  					{
  					 	$ls_jhtinput_keterangan = $ls_jhtinput_ket_hubungan_lainnya; 
  					}else
  					{
  					 	$ls_jhtinput_keterangan = "";	 
  					}
												
						//sesuaikan tipe penerima yg di manfaat detil ----------------------
						$sql2 = "update sijstk.pn_klaim_manfaat_detil set ".
  								 	"	kode_tipe_penerima 	 = '$ls_jhtinput_kode_tipe_penerima', ".                  
                    "	tgl_ubah						 = sysdate, ". 
  									"	petugas_ubah				 = '$username' ".
                    "where kode_klaim = '$ls_kode_klaim' ".
  									"and kode_manfaat = '$ls_jhtinput_kode_manfaat' ".
  									"and no_urut = '$ls_jhtinput_no_urut' ";			
            $DB->parse($sql2);
            $DB->execute();		
												
						//update data penerima manfaat -------------------------------------
						$sql = "update sijstk.pn_klaim_penerima_manfaat set ".
								 	 "	kode_tipe_penerima 	 = '$ls_jhtinput_kode_tipe_penerima', ".		 
                   "	kode_hubungan				 = '$ls_jhtinput_kode_hubungan', ". 
									 "	ket_hubungan_lainnya = '$ls_jhtinput_ket_hubungan_lainnya', ".
									 "	no_urut_keluarga		 = '$ls_jhtinput_no_urut_keluarga', ".  
                   "	nomor_identitas			 = '$ls_jhtinput_nomor_identitas', ".  
									 "	nama_pemohon				 = '$ls_jhtinput_nama_pemohon', ". 
									 "	tempat_lahir				 = '$ls_jhtinput_tempat_lahir', ".  
									 "	tgl_lahir						 = to_date('$ld_jhtinput_tgl_lahir','dd/mm/yyyy'), ".  
									 "	jenis_kelamin				 = '$ls_jhtinput_jenis_kelamin', ".
									 "	golongan_darah			 = '$ls_jhtinput_golongan_darah', ".  
                   "	alamat							 = '$ls_jhtinput_alamat', ". 
									 "	rt									 = '$ls_jhtinput_rt', ".  
									 "	rw									 = '$ls_jhtinput_rw', ".  
									 "	kode_kelurahan			 = '$ls_jhtinput_kode_kelurahan', ".  
									 "	kode_kecamatan			 = '$ls_jhtinput_kode_kecamatan', ". 
									 "	kode_kabupaten			 = '$ls_jhtinput_kode_kabupaten', ".  
									 "	kode_pos						 = '$ls_jhtinput_kode_pos', ".  
                   "	telepon_area				 = '$ls_jhtinput_telepon_area', ".  
									 "	telepon							 = '$ls_jhtinput_telepon', ".  
									 "	telepon_ext					 = '$ls_jhtinput_telepon_ext', ".  
									 "	handphone						 = '$ls_jhtinput_handphone', ". 
									 "	email								 = '$ls_jhtinput_email', ". 
									 "	npwp								 = '$ls_jhtinput_npwp', ". 
									 "	nama_penerima				 = '$ls_jhtinput_nama_pemohon', ".  
                   "	tgl_ubah						 = sysdate, ". 
									 "	petugas_ubah				 = '$username' ".
                   "where kode_klaim = '$ls_kode_klaim' ".
									 "and kode_tipe_penerima = '$ls_jhtinput_kode_tipe_penerima_old' ";			
            $DB->parse($sql);
            $DB->execute();
						
						//sinkronisasi data tk keluarga ------------------------------------
            $sql = 	"select count(*) as v_cnt from sijstk.kn_tk_keluarga ".
            		 		"where kode_tk = '$ls_kode_tk' and kode_hubungan = '$ls_jhtinput_kode_hubungan' ";
            $DB->parse($sql);
            $DB->execute();
            $row = $DB->nextrow();
            $ln_cnt = $row["V_CNT"];
													
						if ($ln_cnt=="0")
						{
              $sql = 	"select nvl(max(no_urut_keluarga),0)+1 as v_no from sijstk.kn_tk_keluarga ".
              		 		"where kode_tk = '$ls_kode_tk' ";
              $DB->parse($sql);
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
										 "    '$ls_kode_tk', '$ln_no_urut', '$ls_jhtinput_kode_hubungan', '$ls_jhtinput_nama_pemohon', null, '$ls_jhtinput_tempat_lahir', ". 
                     "    to_date(nvl('$ld_jhtinput_tgl_lahir','01/01/1800'),'dd/mm/yyyy'), '$ls_jhtinput_jenis_kelamin', '$ls_jhtinput_golongan_darah', '$ls_jhtinput_alamat', '$ls_jhtinput_kode_kabupaten', '$ls_jhtinput_kode_pos', ". 
                     "    '$ls_jhtinput_telepon_area', '$ls_jhtinput_telepon', '$ls_jhtinput_telepon_ext', null, null, '$ls_jhtinput_handphone', '$ls_jhtinput_email', '$ls_jhtinput_npwp', '$ls_jhtinput_keterangan', ". 
                     "    null, 'Y', '$ls_jhtinput_nomor_identitas', '$ls_jhtinput_rt', '$ls_jhtinput_rw', '$ls_jhtinput_kode_kelurahan', '$ls_jhtinput_kode_kecamatan', ".
                     "    sysdate, '$username' ". 
										 ") "; 
              $DB->parse($sql);
              $DB->execute();				
						}else
						{
  						if ($ls_jhtinput_no_urut_keluarga == "")
							{
  							$sql = "update sijstk.kn_tk_keluarga set ".
                       "    nama_lengkap				= '$ls_jhtinput_nama_pemohon', ". 
  										 "		tempat_lahir				= '$ls_jhtinput_tempat_lahir', ".
                       "    tgl_lahir						= to_date(nvl('$ld_jhtinput_tgl_lahir','01/01/1800'),'dd/mm/yyyy'), ".
  										 "		jenis_kelamin				= '$ls_jhtinput_jenis_kelamin', ".
											 "		golongan_darah			= '$ls_jhtinput_golongan_darah', ".
  										 "		alamat							= '$ls_jhtinput_alamat', ".
  										 "		rt									= '$ls_jhtinput_rt', ".
  										 "		rw									= '$ls_jhtinput_rw', ".
  										 "		kode_kelurahan			= '$ls_jhtinput_kode_kelurahan', ".
  										 "		kode_kecamatan			= '$ls_jhtinput_kode_kecamatan', ".											 
  										 "		kode_kabupaten			= '$ls_jhtinput_kode_kabupaten', ".
  										 "		kode_pos						= '$ls_jhtinput_kode_pos', ".
                       "    telepon_area				= '$ls_jhtinput_telepon_area', ".
  										 "		telepon							= '$ls_jhtinput_telepon', ".
  										 "		telepon_ext					= '$ls_jhtinput_telepon_ext', ".
  										 "		handphone						= '$ls_jhtinput_handphone', ".
  										 "		email								= '$ls_jhtinput_email', ".
  										 "		npwp								= '$ls_jhtinput_npwp', ".
  										 "		keterangan					= '$ls_jhtinput_keterangan', ".
                       "		aktif								= 'Y', ".
  										 "		nomor_identitas			= '$ls_jhtinput_nomor_identitas', ".
                       "    tgl_ubah						= sysdate, ".
  										 "		petugas_ubah				= '$username' ".
                       "where kode_tk = '$ls_kode_tk' ".
  										 "and kode_hubungan = '$ls_jhtinput_kode_hubungan' ";
                $DB->parse($sql);
                $DB->execute();	 
							}else
							{
  							$sql = "update sijstk.kn_tk_keluarga set ".
                       "    nama_lengkap				= '$ls_jhtinput_nama_pemohon', ". 
  										 "		tempat_lahir				= '$ls_jhtinput_tempat_lahir', ".
                       "    tgl_lahir						= to_date(nvl('$ld_jhtinput_tgl_lahir','01/01/1800'),'dd/mm/yyyy'), ".
											 "		golongan_darah			= '$ls_jhtinput_golongan_darah', ".
  										 "		jenis_kelamin				= '$ls_jhtinput_jenis_kelamin', ".
  										 "		alamat							= '$ls_jhtinput_alamat', ".
  										 "		rt									= '$ls_jhtinput_rt', ".
  										 "		rw									= '$ls_jhtinput_rw', ".
  										 "		kode_kelurahan			= '$ls_jhtinput_kode_kelurahan', ".
  										 "		kode_kecamatan			= '$ls_jhtinput_kode_kecamatan', ".											 
  										 "		kode_kabupaten			= '$ls_jhtinput_kode_kabupaten', ".
  										 "		kode_pos						= '$ls_jhtinput_kode_pos', ".
                       "    telepon_area				= '$ls_jhtinput_telepon_area', ".
  										 "		telepon							= '$ls_jhtinput_telepon', ".
  										 "		telepon_ext					= '$ls_jhtinput_telepon_ext', ".
  										 "		handphone						= '$ls_jhtinput_handphone', ".
  										 "		email								= '$ls_jhtinput_email', ".
  										 "		npwp								= '$ls_jhtinput_npwp', ".
  										 "		keterangan					= '$ls_jhtinput_keterangan', ".
                       "		aktif								= 'Y', ".
  										 "		nomor_identitas			= '$ls_jhtinput_nomor_identitas', ".
                       "    tgl_ubah						= sysdate, ".
  										 "		petugas_ubah				= '$username' ".
                       "where kode_tk = '$ls_kode_tk' ".
  										 "and no_urut_keluarga = '$ls_jhtinput_no_urut_keluarga' ";
                $DB->parse($sql);
                $DB->execute();								
							} // end if ($ls_jhtinput_no_urut_keluarga == "") ----------------					
						}//end sinkronisasi data tk keluarga -------------------------------	
						
						$ls_sukses = "1";												 		
  			 }//end if if ($ls_status_klaim=="AGENDA") -----------------------------			
			}elseif ($ls_jenis_klaim=="JHM")
			{
			
			}elseif ($ls_jenis_klaim=="JKK")
			{
  		 	 if ($ls_status_klaim=="AGENDA_TAHAP_I")
  			 {
      			//update data agenda jkk tahap I -------------------------------------
            $ld_jkk1_tgl_kecelakaan					 = $_POST["jkk1_tgl_kecelakaan"];
            $ls_jkk1_kode_jam_kecelakaan		 = $_POST["jkk1_kode_jam_kecelakaan"];
            $ls_jkk1_kode_jenis_kasus				 = $_POST["jkk1_kode_jenis_kasus"];
            $ls_jkk1_kode_lokasi_kecelakaan  = $_POST["jkk1_kode_lokasi_kecelakaan"];										
            $ls_jkk1_nama_tempat_kecelakaan	 = $_POST["jkk1_nama_tempat_kecelakaan"];
            $ls_jkk1_ket_tambahan						 = $_POST["jkk1_ket_tambahan"];         
      			 			 			
            $sql = "update sijstk.pn_klaim set ".
                   "	 tgl_kecelakaan			    = to_date('$ld_jkk1_tgl_kecelakaan','dd/mm/yyyy'), ".
                   "   kode_jam_kecelakaan 		= '$ls_jkk1_kode_jam_kecelakaan', ".
  								 "   kode_jenis_kasus				= '$ls_jkk1_kode_jenis_kasus', ".
  								 "	 kode_lokasi_kecelakaan	= '$ls_jkk1_kode_lokasi_kecelakaan', ". 
                   "   nama_tempat_kecelakaan = '$ls_jkk1_nama_tempat_kecelakaan', ".
                   "   ket_tambahan						= '$ls_jkk1_ket_tambahan', ".                 					 
                   "	 tgl_ubah								= sysdate, ". 
                   "	 petugas_ubah 					= '$username' ".
                   "where kode_klaim = '$ls_kode_klaim' ";				
            $DB->parse($sql);
            $DB->execute();
						
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
      			 			 				
            $sql = "update sijstk.pn_klaim set ".
                   "	 tgl_kecelakaan			    = to_date('$ld_jkk_tgl_kecelakaan','dd/mm/yyyy'), ".
                   "   kode_jam_kecelakaan 		= '$ls_jkk_kode_jam_kecelakaan', ".
  								 "   kode_jenis_kasus				= '$ls_jkk_kode_jenis_kasus', ".
  								 "	 kode_lokasi_kecelakaan	= '$ls_jkk_kode_lokasi_kecelakaan', ". 
                   "   nama_tempat_kecelakaan = '$ls_jkk_nama_tempat_kecelakaan', ".
                   "   ket_tambahan						= '$ls_jkk_ket_tambahan', ".
          			 	 "   kode_tindakan_bahaya   = '$ls_jkk_kode_tindakan_bahaya', ".
                   "   kode_kondisi_bahaya    = '$ls_jkk_kode_kondisi_bahaya', ".
                   "   kode_corak             = '$ls_jkk_kode_corak', ".
                   "   kode_sumber_cedera     = '$ls_jkk_kode_sumber_cedera', ".
                   "   kode_bagian_sakit      = '$ls_jkk_kode_bagian_sakit', ".
                   "   kode_akibat_diderita   = '$ls_jkk_kode_akibat_diderita', ".
                   "   kode_lama_bekerja      = '$ls_jkk_kode_lama_bekerja', ".
                   "   kode_penyakit_timbul   = '$ls_jkk_kode_penyakit_timbul', ".
                   "   kode_tipe_upah         = '$ls_jkk_kode_tipe_upah', ".
                   "   nom_upah_terakhir      = '$ln_jkk_nom_upah_terakhir', ".
                   "   kode_tempat_perawatan  = '$ls_jkk_kode_tempat_perawatan', ".
                   "   kode_berobat_jalan     = '$ls_jkk_kode_berobat_jalan', ".
                   "   kode_ppk               = '$ls_jkk_kode_ppk', ".
                   "   nama_faskes_reimburse  = '$ls_jkk_nama_faskes_reimburse', ".
                   "   kode_kondisi_terakhir 	= '$ls_jkk_kode_kondisi_terakhir', ". 
                   "	 tgl_kondisi_terakhir	  = to_date('$ld_jkk_tgl_kondisi_terakhir','dd/mm/yyyy'), ". 				 									                  					 
                   "	 tgl_ubah								= sysdate, ". 
                   "	 petugas_ubah 					= '$username' ".
                   "where kode_klaim = '$ls_kode_klaim' ";				
            $DB->parse($sql);
            $DB->execute();	
						
            //insert data diagnosa ---------------------------------------------------
            $sql = "delete from sijstk.pn_klaim_diagnosa where kode_klaim = '$ls_kode_klaim' ";				
            $DB->parse($sql);
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
                		 		"where kode_klaim = '$ls_kode_klaim' ";
                $DB->parse($sql);
                $DB->execute();
                $row = $DB->nextrow();
                $ln_no_urut = $row["V_NO"];	
                
                $sql = "insert into sijstk.pn_klaim_diagnosa ( ".
                       "  kode_klaim, no_urut, nama_tenaga_medis, kode_diagnosa_detil, keterangan, ". 
                       "  tgl_rekam, petugas_rekam) ".
                       "values ( ".
                       "	'$ls_kode_klaim','$ln_no_urut','$ls_jkk_dtldiag_nama_tenaga_medis','$ls_jkk_dtldiag_kode_diagnosa_detil','$ls_jkk_dtldiag_keterangan', ".
                       "	sysdate, '$username' ". 		 
                       ")";		
                $DB->parse($sql);
                $DB->execute();
              }							
            }     			
            //end insert data diagnosa -----------------------------------------------
            
            //insert data aktivitas pelaporan ----------------------------------------
            $sql = "delete from sijstk.pn_klaim_aktivitas_pelaporan where kode_klaim = '$ls_kode_klaim' ";				
            $DB->parse($sql);
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
                		 		"where kode_klaim = '$ls_kode_klaim' ";
                $DB->parse($sql);
                $DB->execute();
                $row = $DB->nextrow();
                $ln_no_urut = $row["V_NO"];	
                
                $sql = "insert into sijstk.pn_klaim_aktivitas_pelaporan ( ".
                       "	kode_klaim, no_urut, tgl_aktivitas, nama_aktivitas, nama_sumber, profesi_sumber, ". 
                       "	alamat, rt, rw, kode_kelurahan, kode_kecamatan, kode_kabupaten, kode_pos, ". 
                       "	telepon_area, telepon, telepon_ext, handphone, email, npwp, status, hasil_tindak_lanjut, ". 
                       "	keterangan, tgl_rekam, petugas_rekam) ".
                       "values ( ".
                       "	'$ls_kode_klaim','$ln_no_urut',to_date('$ld_jkk_dtlaktvpelap_tgl_aktivitas','dd/mm/yyyy'),'$ls_jkk_dtlaktvpelap_nama_aktivitas','$ls_jkk_dtlaktvpelap_nama_sumber','$ls_jkk_dtlaktvpelap_profesi_sumber', ".
                       "	'$ls_jkk_dtlaktvpelap_alamat', null, null, null, null, null, null, ".
                       "	'$ls_jkk_dtlaktvpelap_telepon_area', '$ls_jkk_dtlaktvpelap_telepon', null, null, null, null, null, null, ". 
                       "	'$ls_jkk_dtlaktvpelap_keterangan', sysdate, '$username' ". 		 
                       ")";		
                $DB->parse($sql);
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
      			//update data agenda jkk tahap I -------------------------------------
            $ld_jkm_tgl_kematian					 	 = $_POST["jkm_tgl_kematian"];
            $ls_jkm_ket_tambahan						 = $_POST["jkm_ket_tambahan"];         
      			 			 			
            $sql = "update sijstk.pn_klaim set ".
                   "	 tgl_kematian			      = to_date('$ld_jkm_tgl_kematian','dd/mm/yyyy'), ".                   
                   "   ket_tambahan						= '$ls_jkm_ket_tambahan', ".                 					 
                   "	 tgl_ubah								= sysdate, ". 
                   "	 petugas_ubah 					= '$username' ".
                   "where kode_klaim = '$ls_kode_klaim' ";				
            $DB->parse($sql);
            $DB->execute();	
						
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
               "set kode_kondisi_terakhir = '$ls_jpn_tk_kode_kondisi_terakhir', ".
               "    tgl_kondisi_terakhir = to_date('$ld_jpn_tk_tgl_kondisi_terakhir','dd/mm/yyyy'), ".
               "	 	tgl_ubah			= sysdate, ". 
               "	 	petugas_ubah = '$username' ".
               "where kode_klaim = '$ls_kode_klaim' ".
               "and kode_penerima_berkala = 'TK' "; 
        $DB->parse($sql);
        $DB->execute();	
				//echo $sql;
        //kondisi klaim --------------------------------------------------------
        $sql = "update sijstk.pn_klaim ". 
               "set kode_jenis_kasus = '$ls_jpn_tk_kode_jenis_kasus', ".
               "    tgl_kejadian 		 = to_date('$ld_jpn_tk_tgl_jenis_kasus','dd/mm/yyyy'), ".
               "		kode_kondisi_terakhir = '$ls_jpn_tk_kode_kondisi_terakhir', ".
               "    tgl_kondisi_terakhir 	= to_date('$ld_jpn_tk_tgl_kondisi_terakhir','dd/mm/yyyy'), ".
               "	 	tgl_ubah				= sysdate, ". 
               "	 	petugas_ubah 		= '$username' ".
               "where kode_klaim = '$ls_kode_klaim' ";
        $DB->parse($sql);
        $DB->execute();
				//echo $sql;		
        //tk meninggal, update tgl meninggal -----------------------------------
        if ($ls_jpn_tk_kode_kondisi_terakhir == "KA11")
        {
          $sql = "update sijstk.pn_klaim ". 
                 "set tgl_kematian = to_date('$ld_jpn_tk_tgl_kondisi_terakhir','dd/mm/yyyy'), ".
                 "	 	tgl_ubah			= sysdate, ". 
                 "	 	petugas_ubah = '$username' ".
                 "where kode_klaim = '$ls_kode_klaim' ";
          $DB->parse($sql);
          $DB->execute();						
        }
				
				$ls_sukses = "1";			
			} // end ($ls_jenis_klaim=="JPN") ----------------------------------------
		}else
		{
		 	$ls_sukses = "0";	 
		} 	 
	}else
	{
    if ($ls_jenis_klaim=="JKK" && $ls_status_klaim=="AGENDA_TAHAP_II")
    {
      //update data agenda jkk tahap II -------------------------------------
      $ls_jkk2_kode_kondisi_terakhir	 = $_POST["jkk2_kode_kondisi_terakhir"];
      $ld_jkk2_tgl_kondisi_terakhir		 = $_POST["jkk2_tgl_kondisi_terakhir"];        
      
      $sql = "update sijstk.pn_klaim set ".
             "   kode_kondisi_terakhir 	= '$ls_jkk2_kode_kondisi_terakhir', ". 
  					 "	 tgl_kondisi_terakhir	  = to_date('$ld_jkk2_tgl_kondisi_terakhir','dd/mm/yyyy'), ".                           					 
             "	 tgl_ubah								= sysdate, ". 
             "	 petugas_ubah 					= '$username' ".
             "where kode_klaim = '$ls_kode_klaim' ";				
      $DB->parse($sql);
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
               "	 tgl_diserahkan			= to_date('$ld_d_adm_tgl_diserahkan','dd/mm/yyyy'), ". 
      				 "	 ringkasan					= '$ls_d_adm_ringkasan', ".
      				 "	 url								= '$ls_d_adm_url', ". 
      				 "	 keterangan					= '$ls_d_adm_keterangan', ". 
      				 "	 status_diserahkan	= '$ls_d_adm_status_diserahkan', ". 
      				 "	 tgl_ubah						= sysdate, ". 
      				 "	 petugas_ubah 			= '$username' ".
               "where kode_klaim = '$ls_kode_klaim' ".
							 "and kode_dokumen = '$ls_d_adm_kode_dokumen' ";		
        $DB->parse($sql);
        $DB->execute();
      }							
    }     			
		//end update data kelengkapan administrasi ---------------------------------
		
    //jalankan proses post update ----------------------------------------------
		$qry = "BEGIN SIJSTK.P_PN_PN5001.X_POST_UPDATE('$ls_kode_klaim','$username',:p_sukses,:p_mess);END;";											 	
    $proc = $DB->parse($qry);				
    oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ls_sukses = $p_sukses;	
		$ls_mess = $p_mess;	
		 
		echo '{"ret":0,"msg":"Sukses, Data berhasil disimpan, session dilanjutkan..","DATAID":"'.$ls_kode_klaim.'"}';
	}else
	{
	 	echo '{"ret":-1,"msg":"Proses gagal, data tidak dapat diupdate...!!!"}';		 
	}
}
else if (($TYPE =='DEL') && ($DATAID != ''))
{
 	//DELETE ---------------------------------------------------------------------  
  $qry = "BEGIN SIJSTK.P_PN_PN5001.X_POST_DELETE('$DATAID','$username',:p_sukses,:p_mess);END;";											 	
  $proc = $DB->parse($qry);				
  oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
  oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
  $DB->execute();				
  $ls_sukses = $p_sukses;	
  $ls_mess = $p_mess;	
  		 
  if ($ls_sukses=="1")
  {	
  	echo '{"ret":0,"msg":"Sukses, Data berhasil dihapus, session dilanjutkan..","DATAID":"'.$DATAID.'"}';
  }else {
  	echo '{"ret":-1,"msg":"Proses gagal, data gagal diupdate...!!!"}';
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

}
?>
