<?
//-- ACTION BUTTON -------------------------------------------------------------
if($ls_btn_task=="submitInformasiPenerima")
{
 	$ln_no_urut_keluarga				= $_POST['no_urut_keluarga'];					
	$ls_nama_lengkap						= $_POST['nama_lengkap'];	
	$ls_tempat_lahir						= $_POST['tempat_lahir'];	
	$ld_tgl_lahir								= $_POST['tgl_lahir'];	
	$ls_jenis_kelamin						= $_POST['jenis_kelamin'];	
	$ls_kode_hubungan						= $_POST['kode_hubungan'];	
	$ls_no_kartu_keluarga				= $_POST['no_kartu_keluarga'];	
	
	$ls_alamat									= $_POST['alamat'];	
	$ls_rt											= $_POST['rt'];	
	$ls_rw											= $_POST['rw'];	
	$ls_kode_pos								= $_POST['kode_pos'];	
	$ls_kode_kelurahan					= $_POST['kode_kelurahan'];	
	$ls_kode_kecamatan					= $_POST['kode_kecamatan'];	
	$ls_kode_kabupaten					= $_POST['kode_kabupaten'];	
	$ls_telepon_area						= $_POST['telepon_area'];	
	$ls_telepon									= $_POST['telepon'];	
	$ls_telepon_ext							= $_POST['telepon_ext'];	
	$ls_handphone								= $_POST['handphone'];	
	$ls_email										= $_POST['email'];	
	$ls_npwp										= $_POST['npwp'];	
	$ls_nomor_identitas					= $_POST['nomor_identitas'];	
	$ls_keterangan							= $_POST['keterangan'];	
	
	$ls_nama_bank_penerima			= $_POST['nama_bank_penerima'];	
	$ls_kode_bank_penerima			= $_POST['kode_bank_penerima'];	
	$ls_id_bank_penerima				= $_POST['id_bank_penerima'];	
	$ls_metode_transfer					= $_POST['metode_transfer'];	
	$ls_no_rekening_penerima		= $_POST['no_rekening_penerima'];	
	$ls_nama_rekening_penerima	= $_POST['nama_rekening_penerima'];	
	$ls_status_valid_rekening_penerima = $_POST['status_valid_rekening_penerima'];	
	$ls_kode_bank_pembayar			= $_POST['kode_bank_pembayar'];	
	$ls_status_rekening_sentral	= $_POST['status_rekening_sentral'];	
	$ls_kantor_rekening_sentral	= $_POST['kantor_rekening_sentral'];	

	//update informasi penerima jp berkala ---------------------------------------
  $qry = "BEGIN ".
         "	update sijstk.pn_klaim_penerima_berkala ".
         "	set    nomor_identitas                = :p_nomor_identitas, ".
         "         alamat                         = :p_alamat, ".
         "         rt                             = :p_rt, ".
         "         rw                             = :p_rw, ".
         "         kode_kelurahan                 = :p_kode_kelurahan, ".
         "         kode_kecamatan                 = :p_kode_kecamatan, ".
         "         kode_kabupaten                 = :p_kode_kabupaten, ".
         "         kode_pos                       = :p_kode_pos, ".
         "         telepon_area                   = :p_telepon_area, ".
         "         telepon                        = :p_telepon, ".
         "         telepon_ext                    = :p_telepon_ext, ".
         "         handphone                      = :p_handphone, ".
         "         email                          = :p_email, ".
         "         npwp                           = :p_npwp, ".
         "         nama_penerima                  = :p_nama_penerima, ".
         "         bank_penerima                  = :p_bank_penerima, ".
         "         no_rekening_penerima           = :p_no_rekening_penerima, ".
         "         nama_rekening_penerima         = :p_nama_rekening_penerima, ".
         "         kode_bank_pembayar             = :p_kode_bank_pembayar, ".
         "         keterangan                     = :p_keterangan, ".
         "         tgl_ubah                   		= sysdate, ".
				 "         petugas_ubah                   = :p_petugas_ubah, ".
         "         kode_bank_penerima             = :p_kode_bank_penerima, ".
         "         id_bank_penerima               = :p_id_bank_penerima, ".
         "         status_valid_rekening_penerima = :p_status_valid_rekening_penerima, ".
         "         status_rekening_sentral        = :p_status_rekening_sentral, ".
         "         kantor_rekening_sentral        = :p_kantor_rekening_sentral, ".
         "         metode_transfer                = :p_metode_transfer ".
         "	where  kode_klaim                     = :p_kode_klaim ".
         "	and    no_urut_keluarga               = :p_no_urut_keluarga; ".		
          "END;";											 	
  $proc = $DB->parse($qry);				
	
  oci_bind_by_name($proc, ":p_nomor_identitas", 								$ls_nomor_identitas,		 	 					30);
  oci_bind_by_name($proc, ":p_alamat", 				 								$ls_alamat,							 						300);
  oci_bind_by_name($proc, ":p_rt", 						 								$ls_rt,									 						5);
  oci_bind_by_name($proc, ":p_rw", 						 								$ls_rw,									 						5);
  oci_bind_by_name($proc, ":p_kode_kelurahan",  								$ls_kode_kelurahan,			 						20);
  oci_bind_by_name($proc, ":p_kode_kecamatan",  								$ls_kode_kecamatan,			 						10);
  oci_bind_by_name($proc, ":p_kode_kabupaten",  								$ls_kode_kabupaten,			 						20);
  oci_bind_by_name($proc, ":p_kode_pos", 			 								$ls_kode_pos,						 						10);
  oci_bind_by_name($proc, ":p_telepon_area", 	 								$ls_telepon_area,				 						5);
  oci_bind_by_name($proc, ":p_telepon", 				 								$ls_telepon,						 						20);
  oci_bind_by_name($proc, ":p_telepon_ext", 		 								$ls_telepon_ext,				 						5);
  oci_bind_by_name($proc, ":p_handphone", 			 								$ls_handphone,					 						20);
  oci_bind_by_name($proc, ":p_email", 					 								$ls_email,							 						200);
  oci_bind_by_name($proc, ":p_npwp", 					 								$ls_npwp,								 						30);
  oci_bind_by_name($proc, ":p_nama_penerima", 	 						 		$ls_nama_lengkap,				 						100);
  oci_bind_by_name($proc, ":p_bank_penerima", 	 								$ls_nama_bank_penerima,	 						100);
  oci_bind_by_name($proc, ":p_no_rekening_penerima", 					$ls_no_rekening_penerima,						30);
  oci_bind_by_name($proc, ":p_nama_rekening_penerima", 				$ls_nama_rekening_penerima,					100);
  oci_bind_by_name($proc, ":p_kode_bank_pembayar", 						$ls_kode_bank_pembayar,							5);
  oci_bind_by_name($proc, ":p_keterangan", 										$ls_keterangan,											300);
  oci_bind_by_name($proc, ":p_petugas_ubah", 									$username,													30);
  oci_bind_by_name($proc, ":p_kode_bank_penerima", 						$ls_kode_bank_penerima,							5);
  oci_bind_by_name($proc, ":p_id_bank_penerima", 							$ls_id_bank_penerima,								50);
  oci_bind_by_name($proc, ":p_status_valid_rekening_penerima", $ls_status_valid_rekening_penerima,	1);
  oci_bind_by_name($proc, ":p_status_rekening_sentral", 				$ls_status_rekening_sentral,				1);
  oci_bind_by_name($proc, ":p_kantor_rekening_sentral", 				$ls_kantor_rekening_sentral,				5);
  oci_bind_by_name($proc, ":p_metode_transfer", 								$ls_metode_transfer,								4);
	oci_bind_by_name($proc, ":p_kode_klaim", 										$ls_kode_klaim,											30);
	oci_bind_by_name($proc, ":p_no_urut_keluarga", 							$ln_no_urut_keluarga,								10);
  $DB->execute();
  
  $ls_ket_submit = "UPDATE DATA PENERIMA MANFAAT JP BERKALA PADA SAAT PROSES KONFIRMASI JP BERKALA";

  //generate aktivitas klaim -----------------------------------------------
  $qry = "BEGIN SIJSTK.P_PN_PN5040.X_INSERT_AKTIVITAS('$ls_kode_klaim', 'UPDATE', substr(upper('$ls_ket_submit'),1,300), '$username',:p_sukses,:p_mess);END;";											 	
  $proc = $DB->parse($qry);				
  oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
  oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
  $DB->execute();				
  $ls_sukses = $p_sukses;
  $ls_mess = $p_mess;	
  //end generate aktivitas klaim -------------------------------------------
  
  //post update ----------------------------------------------------------      
  $msg = "Submit Data Penerima Manfaat JP Berkala berhasil tersimpan, session dilanjutkan...";
	
  echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	echo "window.location.replace('?task=Edit&dataid=$ls_kode_klaim&kode_klaim=$ls_kode_klaim&no_konfirmasi=$ln_no_konfirmasi&mid=$mid&rg_kategori=$ls_rg_kategori&msg=$msg');";
  echo "</script>";										
}
//end ACTION BUTTON ----------------------------------------------------------
?>

	