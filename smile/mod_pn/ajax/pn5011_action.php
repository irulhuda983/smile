<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE			= $_POST["TYPE"];
$DATAID		= $_POST["DATAID"];
$DATAID2	= $_POST["DATAID2"];

$ls_kode_realisasi					= $_POST["kode_realisasi"];
$ls_kode_kantor							= $_POST["kode_kantor"];	
$ls_kode_segmen			 				= $_POST["kode_segmen"];
$ls_kode_kegiatan						= $_POST["kode_kegiatan"];
$ls_kode_sub_kegiatan				= $_POST["kode_sub_kegiatan"];
$ls_nama_sub_kegiatan				= $_POST["nama_sub_kegiatan"];
$ls_nama_detil_kegiatan			= $_POST["nama_detil_kegiatan"];
$ld_tgl_realisasi			 			= $_POST["tgl_realisasi"];
$ld_tgl_kegiatan			 			= $_POST["tgl_kegiatan"];
$ls_kategori_pelaksana			= $_POST["kategori_pelaksana"];	
$ls_kode_klaim							= $_POST["kode_klaim"];
$ls_kode_promotif						= $_POST["kode_promotif"];
$ls_npp											= $_POST["npp"];
$ls_nama_pelaksana					= $_POST["nama_pelaksana"];
$ls_alamat_pelaksana				= $_POST["alamat_pelaksana"];
$ls_npwp_pelaksana					= $_POST["npwp_pelaksana"];
$ln_nom_diajukan						= str_replace(',','',$_POST["nom_diajukan"]);
$ln_nom_disetujui						= str_replace(',','',$_POST["nom_disetujui"]);
$ls_keterangan							= $_POST["keterangan"];
$ls_bentuk_kegiatan					= $_POST["bentuk_kegiatan"];
$ls_kode_jasa								= $_POST["kode_jasa"];
$ls_no_faktur								= $_POST["no_faktur"];

//VIEW -------------------------------------------------------------------------
if ($TYPE=="View" && $DATAID != '')
{
  //query data --------------------------------------------------------		
}
else if ($TYPE=="New")
{		
 	//INSERT ---------------------------------------------------------------------
  //generate kode klaim --------------------------------------------------------
  $sql = 	"select sijstk.p_pn_genid.f_gen_kodepromotifreal as v_kode_realisasi from dual ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_kode_realisasi = $row["V_KODE_REALISASI"];
	
	//insert data ----------------------------------------------------	 
  $sql = "insert into sijstk.pn_promotif_realisasi ( ".
         "	kode_realisasi, kode_kantor, tgl_realisasi, kategori_pelaksana,  ". 
         "	nama_pelaksana, alamat_pelaksana, email_pelaksana, pic_pelaksana, no_telp_pic_pelaksana, ". 
         "	kode_kegiatan, kode_sub_kegiatan, nama_sub_kegiatan, nama_detil_kegiatan, nom_diajukan, nom_disetujui, keterangan, ". 
         "	tgl_rekam, petugas_rekam, kode_segmen, kode_promotif,tgl_kegiatan, ".
				 "	bentuk_kegiatan, kode_jasa,npwp_pelaksana) ".
         "values ( ".
				 "	 '$ls_kode_realisasi', '$ls_kode_kantor', to_date('$ld_tgl_realisasi','dd/mm/yyyy'), '$ls_kategori_pelaksana', ".
				 "	 '$ls_nama_pelaksana', '$ls_alamat_pelaksana', null, null, null, ".
				 "	 '$ls_kode_kegiatan', '$ls_kode_sub_kegiatan', '$ls_nama_sub_kegiatan', '$ls_nama_detil_kegiatan', '$ln_nom_diajukan', '$ln_nom_disetujui','$ls_keterangan', ".
				 "	 sysdate,'$username','$ls_kode_segmen','$ls_kode_promotif', to_date('$ld_tgl_kegiatan','dd/mm/yyyy'), ".
				 "	 '$ls_bentuk_kegiatan', '$ls_kode_jasa', '$ls_npwp_pelaksana' ". 	 	  		 
				 ") ";				 
  $DB->parse($sql);	
  if($DB->execute())
	{		 	    
		//jalankan proses post insert ----------------------------------------------
		$qry = "BEGIN SIJSTK.P_PN_PN5011.X_POST_INSERT('$ls_kode_realisasi','$username',:p_sukses,:p_mess);END;";											 	
    $proc = $DB->parse($qry);				
    oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ls_sukses = $p_sukses;	
		$ls_mess = $p_mess;		 									 
		
		echo '{"ret":0,"msg":"Sukses, Data berhasil disimpan, session dilanjutkan..","DATAID":"'.$ls_kode_realisasi.'"}';		
  }else {
  	echo '{"ret":-1,"msg":"Proses gagal, data gagal ditambahkan...!!!"}';
  }
}
else if ($TYPE=="Edit" && $DATAID != '')
{	 
	//UPDATE data ----------------------------------------------------	 
  $sql = "update sijstk.pn_promotif_realisasi set ".
         "	kode_kantor					= '$ls_kode_kantor', ".
				 "	tgl_realisasi				= to_date('$ld_tgl_realisasi','dd/mm/yyyy'), ". 
				 "	kategori_pelaksana	= '$ls_kategori_pelaksana', ". 
         "	nama_pelaksana			= '$ls_nama_pelaksana', ". 
				 "	alamat_pelaksana		= '$ls_alamat_pelaksana', ". 
				 "	kode_kegiatan				= '$ls_kode_kegiatan', ". 
				 "	kode_sub_kegiatan		= '$ls_kode_sub_kegiatan', ".
				 "	nama_sub_kegiatan		= '$ls_nama_sub_kegiatan', ". 
				 "	nama_detil_kegiatan	= '$ls_nama_detil_kegiatan', ". 
				 "	keterangan					= '$ls_keterangan', ". 
         "	tgl_ubah						= sysdate, ". 
				 "	petugas_ubah				= '$username', ". 
				 "	kode_segmen					= '$ls_kode_segmen', ". 
				 "	kode_promotif				= '$ls_kode_promotif', ".
				 "	tgl_kegiatan				= to_date('$ld_tgl_kegiatan','dd/mm/yyyy'), ".
				 "	bentuk_kegiatan			= '$ls_bentuk_kegiatan', ".
				 "	kode_jasa						= '$ls_kode_jasa', ".
				 "	npwp_pelaksana			= '$ls_npwp_pelaksana', ".
				 "	no_faktur						= '$ls_no_faktur' ".
         "where kode_realisasi = '$ls_kode_realisasi' ";	 
  $DB->parse($sql);	
  if($DB->execute())
	{		 	
    //reset data ---------------------------------------------------------------
		$sql = 	"delete from sijstk.pn_promotif_realisasi_detil where kode_realisasi = '$ls_kode_realisasi' ";
    $DB->parse($sql);
    $DB->execute();			
		//update data rincian biaya ------------------------------------------------    
		$ln_panjang = $_POST['d_realdtl_kounter_dtl'];
    for($i=0;$i<=$ln_panjang-1;$i++)
    {		 	           												 		        
      $ls_d_realdtl_kode_promotif			= $_POST['d_realdtl_kode_promotif'.$i];
			$ls_d_realdtl_kode_perusahaan		= $_POST['d_realdtl_kode_perusahaan'.$i];
			$ls_d_realdtl_bentuk_kegiatan		= $_POST['d_realdtl_bentuk_kegiatan'.$i];
      $ld_d_realdtl_kode_tk						= $_POST['d_realdtl_kode_tk'.$i];
			$ls_d_realdtl_kode_jenis_barang	= $_POST['d_realdtl_kode_jenis_barang'.$i];
			$ls_d_realdtl_nama_barang				= $_POST['d_realdtl_nama_barang'.$i];
			$ln_d_realdtl_jml_paket_barang	= str_replace(',','',$_POST["d_realdtl_jml_paket_barang".$i]);
			$ln_d_realdtl_harga_perpaket_barang	= str_replace(',','',$_POST["d_realdtl_harga_perpaket_barang".$i]);
			$ln_d_realdtl_nom_pokok					= str_replace(',','',$_POST["d_realdtl_nom_pokok".$i]);
			$ln_d_realdtl_nom_ppn						= str_replace(',','',$_POST["d_realdtl_nom_ppn".$i]);			
			$ln_d_realdtl_nom_diajukan			= str_replace(',','',$_POST["d_realdtl_nom_diajukan".$i]);
			$ls_d_realdtl_keterangan				= $_POST['d_realdtl_keterangan'.$i];	
      
      if ($ls_d_realdtl_kode_promotif!="")
      {
        $sql = 	"select nvl(max(no_realisasi),0)+1 as v_no from sijstk.pn_promotif_realisasi_detil ".
						 		"where kode_realisasi = '$ls_kode_realisasi' ";
        $DB->parse($sql);
        $DB->execute();
        $row = $DB->nextrow();
        $ln_no_realisasi = $row["V_NO"];
	        
				$sql = "insert into pn.pn_promotif_realisasi_detil ( ".
						 	 "	kode_realisasi, no_realisasi, kode_promotif, ".
               "	kode_perusahaan, bentuk_kegiatan, kode_tk, ". 
               "	kode_jenis_barang, nama_barang, jml_paket_barang, ". 
               "	harga_perpaket_barang, nom_pokok, nom_ppn, ". 
               "	nom_diajukan, keterangan, ". 
               "	tgl_rekam, petugas_rekam ".
               ") ".
							 "values ( ".
							 "	'$ls_kode_realisasi', '$ln_no_realisasi', '$ls_d_realdtl_kode_promotif', ".
							 "	'$ls_d_realdtl_kode_perusahaan','$ls_d_realdtl_bentuk_kegiatan','$ld_d_realdtl_kode_tk', ".
							 "	'$ls_d_realdtl_kode_jenis_barang', '$ls_d_realdtl_nama_barang', '$ln_d_realdtl_jml_paket_barang', ".
							 "	'$ln_d_realdtl_harga_perpaket_barang', '$ln_d_realdtl_nom_pokok', '$ln_d_realdtl_nom_ppn', ".
							 "	'$ln_d_realdtl_nom_diajukan', '$ls_d_realdtl_keterangan', ".
							 "	sysdate, '$username' ".  			 			 
							 ")";		
        $DB->parse($sql);
        $DB->execute();
				//echo $sql;
      }							
    }     			
		//end update data rincian biaya --------------------------------------------
		
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
		$qry = "BEGIN SIJSTK.P_PN_PN5011.X_POST_UPDATE('$ls_kode_realisasi','$username',:p_sukses,:p_mess);END;";											 	
    $proc = $DB->parse($qry);				
    oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ls_sukses = $p_sukses;	
		$ls_mess = $p_mess;		 									 
		
		echo '{"ret":0,"msg":"Sukses, Data agenda berhasil disimpan, session dilanjutkan..","DATAID":"'.$ls_kode_realisasi.'"}';		
  } else {
  	echo '{"ret":-1,"msg":"Proses gagal, data gagal ditambahkan...!!!"}';
  }
}
else if (($TYPE =='DEL') && ($DATAID != ''))
{
 	//DELETE ----------------------------------------------------------------------  
  $qry = "BEGIN SIJSTK.P_PN_PN5011.X_POST_DELETE('$DATAID','$username',:p_sukses,:p_mess);END;";											 	
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
}else if($TYPE == 'CETAK_REPORT'){
  $KODE_KLAIM     = $_POST["KODE_KLAIM"];
  $KODE_REALISASI = $_POST["KODE_REALISASI"];
  $JENIS_KLAIM    = $_POST["JENIS_KLAIM"];
  $KODE_REPORT    = $_POST["KODE_REPORT"];
  $KODE_PELAKSANA = $_POST["KODE_PELAKSANA"];
  $KODE_KANTOR    = $_SESSION["kdkantorrole"];
  $USER           = $_SESSION["USER"];

  $sql = "select url_report, parameter_report from sijstk.kn_kode_report where kode_report = '".$KODE_REPORT."'";
  $DB->parse($sql);
  $DB->execute();

  $data      = $DB->nextrow();
  $URL       = $data['URL_REPORT'];
  $PARAMETER = $data['PARAMETER_REPORT'];

  if($KODE_REPORT == 'PNR500804'){
    $PARAM = str_replace("P_KODE_KLAIM%3DPARAM_KODE_KLAIM", "P_KODE_KLAIM%3D$KODE_KLAIM", $PARAMETER);
    $PARAM = str_replace("P_KODE_USER%3DPARAM_KODE_USER", "P_KODE_USER%3D$USER", $PARAM);
  }else if($KODE_REPORT == 'PNR500805'){
    $PARAM = str_replace("P_KODE_KLAIM%3DPARAM_KODE_KLAIM", "P_KODE_KLAIM%3D$KODE_KLAIM", $PARAMETER);
    $PARAM = str_replace("P_KODE_USER%3DPARAM_KODE_USER", "P_KODE_USER%3D$USER", $PARAM);
  }else if($KODE_REPORT == 'PNR500806'){
    $PARAM = str_replace("P_KODE_KLAIM%3DPARAM_KODE_KLAIM", "P_KODE_KLAIM%3D$KODE_KLAIM", $PARAMETER);
    $PARAM = str_replace("P_KODE_USER%3DPARAM_KODE_USER", "P_KODE_USER%3D$USER", $PARAM);
    $PARAM = str_replace("P_KODE_KANTOR%3DPARAM_KODE_KANTOR", "P_KODE_KANTOR%3D$KODE_KANTOR", $PARAM);
  }else if($KODE_REPORT == 'PNR500807'){
    $PARAM = str_replace("P_KODE_KLAIM%3DPARAM_KODE_KLAIM", "P_KODE_KLAIM%3D$KODE_KLAIM", $PARAMETER);
    $PARAM = str_replace("P_KODE_USER%3DPARAM_KODE_USER", "P_KODE_USER%3D$USER", $PARAM);
    $PARAM = str_replace("P_KODE_KANTOR%3DPARAM_KODE_KANTOR", "P_KODE_KANTOR%3D$KODE_KANTOR", $PARAM);
  }

  $IP_ADDRESS= $ip_svr;
  $IP_ADDRESS_RDF = $ip_rdf;
  
  $ls_link  = "http://".$IP_ADDRESS."/sijstk/includes/rptBPJS.php?url=";
  // $ls_user  = "core_sijstk";
  // $ls_pass  = "banding2016";
  // $ls_sid   = "sijstkoltp";
  $ls_user  = "sijstk";
  $ls_pass  = "welcome1";
  $ls_sid   = "dbdevelop";
  $ls_path  = "/data/jms/SIPT/KP/REPORT";
  $ls_pdf   = '1'; 
  $report["link"]   = $ls_link;
  $report["user"]   = $ls_user;
  $report["password"] = $ls_pass;
  $report["sid"]    = $ls_sid;
  $report["path"]   = urlencode($ls_path);
  $report["file"]   = $ls_nama_rpt;

  $URL = str_replace("usr", $ls_user, $URL);
  $URL = str_replace("pswd", $ls_pass, $URL);
  $URL = str_replace("dbid", $ls_sid, $URL);
  
  // $rwservlet = "http://".$IP_ADDRESS_RDF.":7779".$URL.$PARAM;
  $rwservlet = "http://".$IP_ADDRESS_RDF.":7781".$URL.$PARAM;
  $rwservlet = str_replace("'","",$rwservlet);
  $link = $report["link"].base64_encode($rwservlet);
  echo $link;

}
?>
