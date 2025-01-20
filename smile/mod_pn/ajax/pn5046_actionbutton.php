<script type="text/javascript">
	$(document).ready(function() {
		$("#btn_close").click(function() {
			sessionStorage.clear();
		});
		$("#btn_new").click(function() {
			sessionStorage.clear();
		});
		$("#btn_edit").click(function() {
			sessionStorage.clear();
		});
	});
</script>	

<?php
	$btn_submit_penetapan      = $_POST["btn_submit_penetapan"];
	$ls_kode_klaim 		  = $_POST["kode_klaim"];

?>

<!-- ACTION BUTTON ---------------------------------------------------------->
  <div id="actmenu">
  	<div id="actbutton">
  		<div style="float:left;">
  		<?PHP
  		if(isset($_REQUEST["task"]))
  		{
  		 	?>
  			<?PHP
  			if($_REQUEST["task"] == "Edit" || $_REQUEST["task"] == "New")
  			{
  			 	?>
          <?PHP
        }; 
        ?>        
        <div style="float:left;"><div class="icon">
        	<a id="btn_close"  href="http://<?=$HTTP_HOST;?>/mod_pn/form/pn5046.php?mid=<?=$mid;?>"><img src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0"> Close</a> 
        </div></div>
        <?PHP
      } 
  		else 
  		{
        ?>
        <!--<div class="icon">
        	<a href="javascript:void(0)" id="btn_view">
        	<img src="http://<?=$HTTP_HOST;?>/images/application_get.png" align="absmiddle" border="0"> View</a></div></div>-->
        <div style="float:left;"><div class="icon">
        	<a id="btn_edit" href="javascript:void(0)" ><img src="http://<?=$HTTP_HOST;?>/images/app_form_edit.png" align="absmiddle" border="0"> Edit</a></div></div>
        <!--<div style="float:left;"><div class="icon">
        	<a id="btn_delete" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/app_form_delete.png" align="absmiddle" border="0"> Delete</a></div></div>
        -->
				<div style="float:left;">
        <div class="icon"><a id="btn_new" href="javascript:void(0)">
        	<img src="http://<?=$HTTP_HOST;?>/images/app_form_add.png" align="absmiddle" border="0"> New</a>
  			</div></div>
        <?PHP
  		}
  		?>
  		</div>	
  	</div>
  </div>
	
	<?
	if($_REQUEST["task"] == "Edit" || $_REQUEST["task"] == "View" || $_REQUEST["task"] == "New")
  {
	  if($_REQUEST["task"] == "New")
    {
      /*----------------------------------------------------------------------*/
      /* PF A.0.1 Create Initial Value 
      ------------------------------------------------------------------------*/
      //Kantor -----------------------------------------------------------------
      $ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;
      if($ls_kode_kantor=="")
      {
       	$ls_kode_kantor =  $gs_kantor_aktif;
      }		
		}else
		{
      //query data -------------------------------------------------------------
      $sql = "select 
                  a.kode_klaim, a.no_klaim, a.kode_kantor, a.kode_segmen, a.kode_perusahaan, 
      						(select nama_perusahaan from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan) nama_perusahaan,
      						(select npp from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan) npp, 
    							a.kode_divisi, (select nama_divisi from sijstk.kn_divisi where kode_perusahaan = a.kode_perusahaan and kode_segmen = a.kode_segmen and kode_divisi=a.kode_divisi) nama_divisi,
                  a.kode_proyek, (select nama_proyek from sijstk.jn_proyek where kode_proyek = a.kode_proyek) nama_proyek, 
									(select no_proyek from sijstk.jn_proyek where kode_proyek = a.kode_proyek) no_proyek, 
      						a.kode_tk, a.nama_tk, a.kpj,
                  a.nomor_identitas, a.jenis_identitas, a.kode_kantor_tk, substr(a.kode_tipe_klaim,1,3) jenis_klaim, 
                  a.kode_tipe_klaim, (select nama_tipe_klaim from sijstk.pn_kode_tipe_klaim where kode_tipe_klaim = a.kode_tipe_klaim) nama_tipe_klaim, 
                  a.kode_sebab_klaim, (select nama_sebab_klaim from sijstk.pn_kode_sebab_klaim where kode_sebab_klaim = a.kode_sebab_klaim) nama_sebab_klaim,
									(select keyword from sijstk.pn_kode_sebab_klaim where kode_sebab_klaim = a.kode_sebab_klaim) keyword_sebab_klaim,
									(select nvl(flag_meninggal,'T') from sijstk.pn_kode_sebab_klaim where kode_sebab_klaim = a.kode_sebab_klaim) flag_meninggal,
                  to_char(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim,
                  to_char(a.tgl_lapor,'dd/mm/yyyy') tgl_lapor,     
                  a.kanal_pelayanan, a.flag_rtw, a.keterangan,               
    							a.kode_pelaporan, a.kanal_pelayanan, nvl(a.flag_rtw,'T') flag_rtw,
    							to_char(a.tgl_kecelakaan,'dd/mm/yyyy') tgl_kecelakaan, 
									a.kode_jam_kecelakaan, (select keterangan from sijstk.ms_lookup where tipe='KLMJAMKERJ' and kode = a.kode_jam_kecelakaan) nama_jam_kecelakaan, 
                  a.kode_jenis_kasus, (select nama_jenis_kasus from sijstk.pn_kode_jenis_kasus where kode_jenis_kasus=a.kode_jenis_kasus) nama_jenis_kasus, 
									a.kode_lokasi_kecelakaan, (select nama_lokasi_kecelakaan from sijstk.pn_kode_lokasi_kecelakaan where kode_lokasi_kecelakaan=a.kode_lokasi_kecelakaan) nama_lokasi_kecelakaan,
									a.nama_tempat_kecelakaan, 
                  a.kode_tindakan_bahaya, 
									a.kode_kondisi_bahaya, 
									a.kode_corak, (select keterangan from sijstk.ms_lookup where tipe='KLMCORAK' and kode=a.kode_corak) nama_corak,
                  a.kode_sumber_cedera, (select keterangan from sijstk.ms_lookup where tipe='KLMSMBRCDR' and kode=a.kode_sumber_cedera) nama_sumber_cedera,
									a.kode_bagian_sakit, (select keterangan from sijstk.ms_lookup where tipe='KLMBGSAKIT' and kode=a.kode_bagian_sakit) nama_bagian_sakit,
									a.kode_akibat_diderita, (select nama_akibat_diderita from sijstk.pn_kode_akibat_diderita where kode_akibat_diderita=a.kode_akibat_diderita) nama_akibat_diderita,
                  a.kode_lama_bekerja, 
									a.kode_penyakit_timbul, 
									a.kode_tipe_upah, 
									(select nomor_identitas from pn.pn_klaim_penerima_manfaat where kode_klaim = a.kode_klaim and rownum = 1) 	nomor_identitas_penerima,	
                  a.nom_upah_terakhir, a.kode_tempat_perawatan, a.kode_berobat_jalan, 
                  a.kode_ppk, (select nama_faskes from sijstk.tc_faskes where kode_faskes = a.kode_ppk) nama_ppk,
									a.nama_faskes_reimburse, a.kode_kondisi_terakhir, 
									(select nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir where kode_kondisi_terakhir = a.kode_kondisi_terakhir) nama_kondisi_terakhir,
                  to_char(a.tgl_kondisi_terakhir,'dd/mm/yyyy') tgl_kondisi_terakhir, 
    							to_char(a.tgl_kematian,'dd/mm/yyyy') tgl_kematian, 
    							to_char(a.tgl_mulai_pensiun,'dd/mm/yyyy') tgl_mulai_pensiun, 
                  a.status_pernikahan, a.ket_tambahan,
                  nvl(a.status_kelayakan,'B') status_kelayakan, a.ket_kelayakan,
    							nvl(a.status_submit_agenda,'T') status_submit_agenda, tgl_submit_agenda, petugas_submit_agenda, 
                  nvl(a.status_submit_pengajuan,'T') status_submit_pengajuan, tgl_submit_pengajuan, petugas_submit_pengajuan, 
                  nvl(a.status_submit_agenda2,'T') status_submit_agenda2, tgl_submit_agenda2, petugas_submit_agenda2, 
                  nvl(a.status_submit_penetapan,'T') status_submit_penetapan, tgl_submit_penetapan, petugas_submit_penetapan,
									to_char(a.tgl_penetapan,'dd/mm/yyyy') tgl_penetapan, a.no_penetapan,a.petugas_penetapan, 
                  nvl(a.status_approval,'T') status_approval, tgl_approval, petugas_approval,							  
                  nvl(a.status_batal,'T') status_batal, a.tgl_batal, a.petugas_batal, a.ket_batal, 
                  nvl(a.status_lunas,'T') status_lunas, a.tgl_lunas, a.petugas_lunas,
    							a.kode_klaim_induk, a.kode_klaim_anak,
    							to_char(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian, a.status_kepesertaan, 
      						a.kode_perlindungan, to_char(a.tgl_awal_perlindungan,'dd/mm/yyyy') tgl_awal_perlindungan, 
    							to_char(a.tgl_akhir_perlindungan,'dd/mm/yyyy') tgl_akhir_perlindungan,
    							to_char(a.tgl_awal_perlindungan,'dd/mm/yyyy')||' s.d '||to_char(a.tgl_akhir_perlindungan,'dd/mm/yyyy') ket_masa_perlindungan,									
                	a.status_klaim, a.kode_pointer_asal, a.id_pointer_asal, a.tipe_pelaksana_kegiatan, a.nama_pelaksana_kegiatan,
                  case when a.kode_pointer_asal = 'PROMOTIF' then
                      (   select x.kode_kegiatan||'-'||x.nama_sub_kegiatan||'-'||x.nama_detil_kegiatan from sijstk.pn_promotif_realisasi x
                          where kode_realisasi = a.id_pointer_asal          
                      ) 
                  else
                      'KLAIM'
                  end nama_kegiatan,
									(
  									select count(*) from sijstk.pn_klaim_manfaat_detil x, sijstk.pn_kode_manfaat y
                    where x.kode_klaim = a.kode_klaim
                    and x.kode_manfaat = y.kode_manfaat
                    and nvl(y.flag_berkala,'T')='Y'
                    and nvl(x.nom_biaya_disetujui,0)<>0
									) cnt_berkala,
									(
  									select count(*) from sijstk.pn_klaim_manfaat_detil x, sijstk.pn_kode_manfaat y
                    where x.kode_klaim = a.kode_klaim
                    and x.kode_manfaat = y.kode_manfaat
                    and nvl(y.flag_berkala,'T')='T'
                    and nvl(x.nom_biaya_disetujui,0)<>0
									) cnt_lumpsum,
									(
  									select count(*) from sijstk.pn_klaim_penerima_berkala x
                    where x.kode_klaim = a.kode_klaim
									) cnt_penerima_berkala,
									(select count(*) from sijstk.pn_klaim_manfaat where kode_klaim = a.kode_klaim and kode_manfaat = '2' )flag_kode_manfaat_beasiswa,
									nvl(status_cek_amalgamasi,'T') status_cek_amalgamasi,
									(select no_penetapan from sijstk.pn_klaim where kode_klaim = a.kode_klaim_induk) no_penetapan_induk,
									(select nama_tipe_klaim from pn.pn_kode_tipe_klaim where kode_tipe_klaim = a.kode_tipe_klaim)||' | '||(select keyword||' - '||nama_sebab_klaim from pn.pn_kode_sebab_klaim where kode_sebab_klaim = a.kode_sebab_klaim) ket_jenis_klaim,
									 a.nama_tk||' (No.Referensi: '||a.kpj||' | NIK: '||a.nomor_identitas||' | NPP: '||(select npp from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan)||' - '||(select nama_perusahaan from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan)||')' ket_nama_tk																		
  						from sijstk.pn_klaim a
            	where kode_klaim = '".$_GET['dataid']."' ";
      //echo $sql;
      $DB->parse($sql);
      $DB->execute();
      $row = $DB->nextrow();
      $ls_kode_klaim 						 		= $row['KODE_KLAIM'];		
      $ls_no_klaim 									= $row['NO_KLAIM'];
			$ls_ket_klaim_atasnama				= $row['KET_NAMA_TK'];
			$ls_ket_jenis_klaim						= $row['KET_JENIS_KLAIM'];
      $ls_kode_kantor 							= $row['KODE_KANTOR'];
      $ls_kode_segmen 							= $row['KODE_SEGMEN'];
      $ls_kode_perusahaan 					= $row['KODE_PERUSAHAAN'];
      $ls_nama_perusahaan						= $row['NAMA_PERUSAHAAN'];
      $ls_npp 											= $row['NPP'];
      $ls_kode_divisi 							= $row['KODE_DIVISI'];
      $ls_nama_divisi 							= $row['NAMA_DIVISI'];
      $ls_kode_proyek 							= $row['KODE_PROYEK'];
      $ls_nama_proyek  							= $row['NAMA_PROYEK'];
			$ls_no_proyek  								= $row['NO_PROYEK'];
      $ls_kode_tk 									= $row['KODE_TK'];
      $ls_kpj 											= $row['KPJ'];
      $ls_nama_tk 									= $row['NAMA_TK'];
      $ls_nomor_identitas 					= $row['NOMOR_IDENTITAS'];
	  $ls_nomor_identitas_penerima 					= $row['NOMOR_IDENTITAS_PENERIMA'];
      $ls_jenis_identitas 					= $row['JENIS_IDENTITAS'];
      $ls_kode_kantor_tk 						= $row['KODE_KANTOR_TK'];
      $ls_kode_tipe_klaim 					= $row['KODE_TIPE_KLAIM'];
      $ls_nama_tipe_klaim						= $row['NAMA_TIPE_KLAIM'];
      $ls_kode_sebab_klaim 					= $row['KODE_SEBAB_KLAIM'];
      $ls_nama_sebab_klaim 					= $row['NAMA_SEBAB_KLAIM'];
			$ls_keyword_sebab_klaim				= $row['KEYWORD_SEBAB_KLAIM'];
  		$ls_jenis_klaim 							= $row['JENIS_KLAIM'];
      $ld_tgl_klaim 								= $row['TGL_KLAIM'];
      $ld_tgl_lapor 								= $row['TGL_LAPOR'];
  		$ld_tgl_kejadian							= $row['TGL_KEJADIAN'];
  		$ls_status_kepesertaan 				= $row['STATUS_KEPESERTAAN'];
    	$ls_kode_perlindungan					= $row['KODE_PERLINDUNGAN'];
  		$ld_tgl_awal_perlindungan			= $row['TGL_AWAL_PERLINDUNGAN'];
  		$ld_tgl_akhir_perlindungan 		= $row['TGL_AKHIR_PERLINDUNGAN'];
  		$ls_ket_masa_perlindungan	 		= $row['KET_MASA_PERLINDUNGAN'];	
      $ls_keterangan 								= $row['KETERANGAN'];	    
      $ls_kode_pelaporan 						= $row['KODE_PELAPORAN'];
      $ls_kanal_pelayanan 					= $row['KANAL_PELAYANAN'];
      $ls_flag_rtw 									= $row['FLAG_RTW'];
      $ls_kode_klaim_induk 					= $row['KODE_KLAIM_INDUK'];
      $ls_kode_klaim_anak 					= $row['KODE_KLAIM_ANAK'];
			$ls_no_penetapan_induk 				= $row['NO_PENETAPAN_INDUK'];
      $ld_tgl_kecelakaan						= $row['TGL_KECELAKAAN'];
  		$ls_kode_jam_kecelakaan 			= $row['KODE_JAM_KECELAKAAN'];
			$ls_nama_jam_kecelakaan 			= $row['NAMA_JAM_KECELAKAAN'];
      $ls_kode_jenis_kasus				  = $row['KODE_JENIS_KASUS'];
			$ls_nama_jenis_kasus				  = $row['NAMA_JENIS_KASUS'];
  		$ls_kode_lokasi_kecelakaan		= $row['KODE_LOKASI_KECELAKAAN'];
			$ls_nama_lokasi_kecelakaan		= $row['NAMA_LOKASI_KECELAKAAN'];
  		$ls_nama_tempat_kecelakaan 		= $row['NAMA_TEMPAT_KECELAKAAN'];	
      $ls_kode_tindakan_bahaya			= $row['KODE_TINDAKAN_BAHAYA'];
  		$ls_kode_kondisi_bahaya				= $row['KODE_KONDISI_BAHAYA'];
  		$ls_kode_corak 								= $row['KODE_CORAK'];	
			$ls_nama_corak 								= $row['NAMA_CORAK'];
      $ls_kode_sumber_cedera				= $row['KODE_SUMBER_CEDERA'];
			$ls_nama_sumber_cedera				= $row['NAMA_SUMBER_CEDERA'];
  		$ls_kode_bagian_sakit					= $row['KODE_BAGIAN_SAKIT'];
			$ls_nama_bagian_sakit					= $row['NAMA_BAGIAN_SAKIT'];
  		$ls_kode_akibat_diderita 			= $row['KODE_AKIBAT_DIDERITA'];
			$ls_nama_akibat_diderita 			= $row['NAMA_AKIBAT_DIDERITA'];	
      $ls_kode_lama_bekerja					= $row['KODE_LAMA_BEKERJA'];
  		$ls_kode_penyakit_timbul			= $row['KODE_PENYAKIT_TIMBUL'];
  		$ls_kode_tipe_upah 						= $row['KODE_TIPE_UPAH'];
      $ln_nom_upah_terakhir					= $row['NOM_UPAH_TERAKHIR'];
  		$ls_kode_tempat_perawatan			= $row['KODE_TEMPAT_PERAWATAN'];
  		$ls_kode_berobat_jalan 				= $row['KODE_BEROBAT_JALAN'];
      $ls_kode_ppk									= $row['KODE_PPK'];
			$ls_nama_ppk									= $row['NAMA_PPK'];
  		$ls_nama_faskes_reimburse			= $row['NAMA_FASKES_REIMBURSE'];
  		$ls_kode_kondisi_terakhir 		= $row['KODE_KONDISI_TERAKHIR'];
			$ls_nama_kondisi_terakhir 		= $row['NAMA_KONDISI_TERAKHIR'];
      $ld_tgl_kondisi_terakhir			= $row['TGL_KONDISI_TERAKHIR'];
      $ld_tgl_kematian							= $row['TGL_KEMATIAN'];
      $ld_tgl_mulai_pensiun 				= $row['TGL_MULAI_PENSIUN'];
      $ls_status_pernikahan					= $row['STATUS_PERNIKAHAN'];
  		$ls_ket_tambahan    					= $row['KET_TAMBAHAN'];
  		$ls_status_kelayakan 					= $row['STATUS_KELAYAKAN'];
      $ls_ket_kelayakan 						= $row['KET_KELAYAKAN'];
      $ls_status_submit_agenda			= $row['STATUS_SUBMIT_AGENDA'];
  		$ld_tgl_submit_agenda					= $row['TGL_SUBMIT_AGENDA'];
  		$ls_petugas_submit_agenda 		= $row['PETUGAS_SUBMIT_AGENDA'];
      $ls_status_submit_pengajuan		= $row['STATUS_SUBMIT_PENGAJUAN'];
  		$ld_tgl_submit_pengajuan			= $row['TGL_SUBMIT_PENGAJUAN'];
  		$ls_petugas_submit_pengajuan 	= $row['PETUGAS_SUBMIT_PENGAJUAN'];
      $ls_status_submit_agenda2			= $row['STATUS_SUBMIT_AGENDA2'];
  		$ld_tgl_submit_agenda2				= $row['TGL_SUBMIT_AGENDA2'];
  		$ls_petugas_submit_agenda2 		= $row['PETUGAS_SUBMIT_AGENDA2'];
      $ls_status_submit_penetapan		= $row['STATUS_SUBMIT_PENETAPAN'];
  		$ld_tgl_submit_penetapan			= $row['TGL_SUBMIT_PENETAPAN'];
  		$ls_petugas_submit_penetapan 	= $row['PETUGAS_SUBMIT_PENETAPAN'];
			$ld_tgl_penetapan							= $row['TGL_PENETAPAN'];
			$ls_no_penetapan							= $row['NO_PENETAPAN'];
			$ls_petugas_penetapan					= $row['PETUGAS_PENETAPAN'];
      $ls_status_approval						= $row['STATUS_APPROVAL'];
  		$ld_tgl_approval							= $row['TGL_APPROVAL'];
  		$ls_petugas_approval					= $row['PETUGAS_APPROVAL'];		
  	 	$ls_status_batal 							= $row['STATUS_BATAL'];
      $ls_tgl_batal 								= $row['TGL_BATAL'];
      $ls_petugas_batal 						= $row['PETUGAS_BATAL'];
      $ls_ket_batal 								= $row['KET_BATAL'];    
      $ls_status_lunas 							= $row['STATUS_LUNAS'];
      $ld_tgl_lunas 								= $row['TGL_LUNAS'];
      $ls_petugas_lunas 						= $row['PETUGAS_LUNAS'];
  		$ls_status_klaim 							= $row['STATUS_KLAIM'];
  		$ls_kode_pointer_asal					= $row['KODE_POINTER_ASAL'];
  		$ls_id_pointer_asal						= $row['ID_POINTER_ASAL'];
  		$ls_tipe_pelaksana_kegiatan		= $row['TIPE_PELAKSANA_KEGIATAN'];
  		$ls_nama_pelaksana_kegiatan		= $row['NAMA_PELAKSANA_KEGIATAN'];
  		$ls_nama_kegiatan							= $row['NAMA_KEGIATAN'];
			$ls_flag_meninggal						= $row['FLAG_MENINGGAL'];	
			$ln_cnt_berkala								= $row['CNT_BERKALA'];
			$ln_cnt_lumpsum								= $row['CNT_LUMPSUM'];
			$ln_cnt_penerima_berkala			= $row['CNT_PENERIMA_BERKALA'];
			$ls_status_cek_amalgamasi 		= $row['STATUS_CEK_AMALGAMASI'];
			$ls_flag_kode_manfaat_beasiswa 		= $row['FLAG_KODE_MANFAAT_BEASISWA'];
			
			if ($ln_cnt_berkala>"0")
			{
			 	 $ls_flag_berkala						= "Y";		
			}else
			{
				$ls_flag_berkala						= "T";
			}	
			
			if ($ln_cnt_lumpsum>"0")
			{
			 	 $ls_flag_lumpsum						= "Y";		
			}else
			{
				$ls_flag_lumpsum							= "T";
			}						
		}
	}
	//-- end ACTION BUTTON ------------------------------------------------------
	?>	
	
	<?
	//SUBMIT BUTTON -------------------------------------------------------------
  if($btn_task=="Gen_Penetapan_Ulang")
  {
		$ls_kode_klaim_induk = $_POST['kode_klaim_induk'];
		//cek kelayakan ----------------------------------------------------------
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_GEN_PENETAPAN_ULANG('$ls_kode_klaim_induk','$gs_kantor_aktif','$username',:p_kode_klaim_anak,:p_sukses,:p_mess);END;";											 	
    $proc = $DB->parse($qry);				
    oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
    oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
		oci_bind_by_name($proc, ":p_kode_klaim_anak", $p_kode_klaim_anak,100);
    $DB->execute();				
    $ls_sukses = $p_sukses;
		$ls_mess = $p_mess;	
		$ls_kode_klaim = $p_kode_klaim_anak;
  	
		if ($ls_sukses=="1")
		{
		  $msg = "Generate Data Penetapan Ulang berhasil, session dilanjutkan..";	 
		}else
		{
 	   	$msg = $ls_mess;
		}		
    
    echo "<script language=\"JavaScript\" type=\"text/javascript\">";
    echo "window.location.replace('?&task=View&mid=$mid&kode_klaim=$ls_kode_klaim&dataid=$ls_kode_klaim&msg=$msg');";
		echo "alert('$msg')";
    echo "</script>";		
  }

//update submit penetapan ulang data antrian
  if($btn_task=="submit_penetapan_tanpa_otentikasi")
  {
  	//submit data penetapan ----------------------------------------------------
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_POST_PENETAPAN('$ls_kode_klaim','$username',:p_sukses,:p_mess);END;";											 	
    $proc = $DB->parse($qry);				
    oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
    oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ls_sukses = $p_sukses;
    $ls_mess = $p_mess;		
  	
  	if ($ls_sukses =="1")
  	{			
    	$msg = "Submit Data berhasil dilakukan, session dilanjutkan..."; 	
    }else
  	{
  	  $ls_error = "1";
  		$msg = $ls_mess; 
  	}
    $msg = "Submit Data berhasil dilakukan, session dilanjutkan..."; 	
  	
    echo "<script language=\"JavaScript\" type=\"text/javascript\">";						
    echo "window.location.replace('?task=Edit&dataid=$ls_kode_klaim&kode_klaim=$ls_kode_klaim&mid=$mid&msg=$msg');";
    echo "</script>";											
  }
	
 
	
  if($btn_task=="hitung_manfaatJht")
  {
    //cek kelayakan ----------------------------------------------------------
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNFULANG_JHTRINCI('$ls_kode_klaim','$username',:p_sukses,:p_mess);END;";											 	
    $proc = $DB->parse($qry);				
    oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
    oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ls_sukses = $p_sukses;
    $ls_mess = $p_mess;	
    
    $msg = "Proses Hitung Manfaat selesai, session dilanjutkan...";
    $task = "edit";   		
    
    echo "<script language=\"JavaScript\" type=\"text/javascript\">";						
    echo "window.location.replace('?task=Edit&dataid=$ls_kode_klaim&kode_klaim=$ls_kode_klaim&mid=$mid&msg=$msg');";
    echo "</script>";	
  }		
	//end SUBMIT BUTTON ----------------------------------------------------------
	?>
	