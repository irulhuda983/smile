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
          <div style="float:left;"><div class="icon">
          	<a id="btn_save" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle" border="0" alt="Save"> Save</a>
          </div></div>
          <?PHP
        }; 
        ?>       
        <div style="float:left;"><div class="icon">
        	<a id="btn_close" href="http://<?=$HTTP_HOST;?>/mod_pn/form/pn5040.php?mid=<?=$mid;?>"><img src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0" alt="Close"> Close</a> 
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
        	<a id="btn_edit" href="javascript:void(0)" ><img src="http://<?=$HTTP_HOST;?>/images/app_form_edit.png" align="absmiddle" border="0" alt="Edit"> Edit</a></div></div>
        <!--<div style="float:left;"><div class="icon">
        	<a id="btn_delete" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/app_form_delete.png" align="absmiddle" border="0"> Delete</a></div></div>
        -->
				<div style="float:left;">
        <div class="icon"><a id="btn_new" href="javascript:void(0)">
        	<img src="http://<?=$HTTP_HOST;?>/images/app_form_add.png" align="absmiddle" border="0" alt="New"> New</a>
  			</div></div>
        <?PHP
  		}
  		?>
  		</div>	
  	</div>
  </div>
	
	<?
	$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);
	
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
      //Tgl Agenda : sysdate ---------------------------------------------------
      $sql = "select to_char(sysdate,'dd/mm/yyyy') as tgl from dual ";
      $DB->parse($sql);
      $DB->execute();
      $row = $DB->nextrow();
      $ld_tgl_klaim 	 = $row["TGL"];	
      $ld_tgl_lapor 	 = $row["TGL"];
  		$ld_tgl_kejadian = $row["TGL"];
  		$ls_status_submit_agenda = "T";			
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
									(select nvl(flag_agenda_12,'T') from sijstk.pn_kode_sebab_klaim where kode_sebab_klaim = a.kode_sebab_klaim) flag_agenda_12,
                  to_char(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim,
                  to_char(a.tgl_lapor,'dd/mm/yyyy') tgl_lapor,     
                  a.kanal_pelayanan, a.flag_rtw, a.keterangan,               
    							a.kode_pelaporan, nvl(a.flag_rtw,'T') flag_rtw,
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
                  a.nom_upah_terakhir, to_char(a.blth_upah_terakhir,'dd/mm/yyyy') blth_upah_terakhir, a.kode_tempat_perawatan, a.kode_berobat_jalan, 
                  a.kode_ppk, (select nama_faskes from sijstk.tc_faskes where kode_faskes = a.kode_ppk) nama_ppk,
									a.nama_faskes_reimburse, a.kode_kondisi_terakhir, 
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
									nvl(status_cek_amalgamasi,'T') status_cek_amalgamasi,
									a.negara_penempatan, a.tipe_negara_kejadian, a.flag_meninggal_cuti,
					case when a.tgl_kejadian >='22-FEB-2023' then 'Y'
					else 'T' end aktif_flag_meninggal_cuti
  						from sijstk.pn_klaim a
            	where kode_klaim = :kode_klaim";
      //echo $sql;
      $proc = $DB->parse($sql);
	  oci_bind_by_name($proc, ':kode_klaim', $_GET['dataid'], 100);
	  // 20-03-2024 penyesuaian bind variable
      $DB->execute();
      $row = $DB->nextrow();
      $ls_kode_klaim 						 		= $row['KODE_KLAIM'];		
      $ls_no_klaim 									= $row['NO_KLAIM'];
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
      $ls_jenis_identitas 					= $row['JENIS_IDENTITAS'];
      $ls_kode_kantor_tk 						= $row['KODE_KANTOR_TK'];
      $ls_kode_tipe_klaim 					= $row['KODE_TIPE_KLAIM'];
      $ls_nama_tipe_klaim						= $row['NAMA_TIPE_KLAIM'];
      $ls_kode_sebab_klaim 					= $row['KODE_SEBAB_KLAIM'];
      $ls_nama_sebab_klaim 					= $row['NAMA_SEBAB_KLAIM'];
			$ls_keyword_sebab_klaim				= $row['KEYWORD_SEBAB_KLAIM'];
			$ls_flag_agenda_12 						= $row['FLAG_AGENDA_12'];
  		$ls_jenis_klaim 							= $row['JENIS_KLAIM'];
      $ld_tgl_klaim 								= $row['TGL_KLAIM'];
      $ld_tgl_lapor 								= $row['TGL_LAPOR'];
  		$ld_tgl_kejadian							= $row['TGL_KEJADIAN'];
  		$ls_status_kepesertaan 				= $row['STATUS_KEPESERTAAN'];
    	$ls_kode_perlindungan					= $row['KODE_PERLINDUNGAN'];
  		$ld_tgl_awal_perlindungan			= $row['TGL_AWAL_PERLINDUNGAN'];
  		$ld_tgl_akhir_perlindungan 		= $row['TGL_AKHIR_PERLINDUNGAN'];
  		$ls_ket_masa_perlindungan	 		= $row['KET_MASA_PERLINDUNGAN'];
			$ls_negara_penempatan	 				= $row['NEGARA_PENEMPATAN'];
			$ls_tipe_negara_kejadian			= $row['TIPE_NEGARA_KEJADIAN'];		
      $ls_keterangan 								= $row['KETERANGAN'];	    
      $ls_kode_pelaporan 						= $row['KODE_PELAPORAN'];
      $ls_kanal_pelayanan 					= $row['KANAL_PELAYANAN'];
      $ls_flag_rtw 									= $row['FLAG_RTW'];
      $ls_kode_klaim_induk 					= $row['KODE_KLAIM_INDUK'];
      $ls_kode_klaim_anak 					= $row['KODE_KLAIM_ANAK'];
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
			$ld_blth_upah_terakhir				= $row['BLTH_UPAH_TERAKHIR'];
  		$ls_kode_tempat_perawatan			= $row['KODE_TEMPAT_PERAWATAN'];
  		$ls_kode_berobat_jalan 				= $row['KODE_BEROBAT_JALAN'];
      $ls_kode_ppk									= $row['KODE_PPK'];
			$ls_nama_ppk									= $row['NAMA_PPK'];
  		$ls_nama_faskes_reimburse			= $row['NAMA_FASKES_REIMBURSE'];
  		$ls_kode_kondisi_terakhir 		= $row['KODE_KONDISI_TERAKHIR'];
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
			$ls_flag_meninggal_cuti 		= $row['FLAG_MENINGGAL_CUTI'];
			$ls_aktif_flag_meninggal_cuti 		= $row['AKTIF_FLAG_MENINGGAL_CUTI'];

  		if ($ls_kode_perlindungan=="PRA")
  		{
  		 	$ls_nama_perlindungan = "SEBELUM BEKERJA"; 
  		}elseif ($ls_kode_perlindungan=="ONSITE")
  		{
  		 	$ls_nama_perlindungan = "SELAMA BEKERJA"; 
  		}elseif ($ls_kode_perlindungan=="PURNA")
  		{
  		 	$ls_nama_perlindungan = "SETELAH BEKERJA"; 
  		}
									
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
  if($btn_task=="cek_kelayakan")
  {
	$cek_status_klaim 		= $_POST['cek_status_klaim'];
	$sql_status_klaim 		= "select status_klaim from pn.pn_klaim where kode_klaim = :p_kode_klaim";
	$proc = $DB->parse($sql_status_klaim);
	oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
	// 20-03-2024 penyesuaian bind variable
	$DB->execute();
	$row_cek 				= $DB->nextrow();
	$ls_cek_status_agenda 	= $row_cek['STATUS_KLAIM'];

	if($cek_status_klaim != $ls_cek_status_agenda){
		$msg = "Data klaim sudah diproses sampai dengan $ls_cek_status_agenda. Silahkan lakukan pengecekan pada menu PN5030 - DAFTAR KLAIM.";
		$cek_status_klaim = '';
	} else {
    //cek kelayakan ----------------------------------------------------------
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_CEK_KELAYAKAN(:p_kode_klaim,:p_username,:p_sukses,:p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim,100);
    oci_bind_by_name($proc, ":p_username", $username,50);
	// 20-03-2024 penyesuaian bind variable
    oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
    oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ls_sukses = $p_sukses;
		$ls_mess = $p_mess;	
  	
 	  $msg = $ls_mess;
		$task = "edit";   		
    
	}	
	
    echo "<script language=\"JavaScript\" type=\"text/javascript\">";
    echo "window.location.replace('?&task=Edit&mid=$mid&kode_klaim=$ls_kode_klaim&dataid=$ls_kode_klaim&msg=$msg');";
		echo "alert('$msg')";
    echo "</script>";		
  }
	
	if($btn_task=="submit_data_tanpa_otentikasi")
  {
	$cek_status_klaim 		= $_POST['cek_status_klaim'];
	$sql_status_klaim 		= "select status_klaim from pn.pn_klaim where kode_klaim = :p_kode_klaim";
	$proc = $DB->parse($sql_status_klaim);
	oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
	// 20-03-2024 penyesuaian bind variable
	$DB->execute();
	$row_cek 				= $DB->nextrow();
	$ls_cek_status_agenda 	= $row_cek['STATUS_KLAIM'];

	if($cek_status_klaim != $ls_cek_status_agenda){
		//cek apakah sudah ada user lain yang mengupdate status klaim
		$ls_mess = "Data klaim sudah diproses sampai dengan $ls_cek_status_agenda. Silahkan lakukan pengecekan pada menu PN5030 - DAFTAR KLAIM.";
		$cek_status_klaim = '';
		$ls_sukses="-1";
	} else {
   	//submit data klaim --------------------------------------------------------																					  
	$qry = "BEGIN SIJSTK.P_PN_PN5040.X_POST_SUBMIT(:p_kode_klaim,:p_username,:p_sukses,:p_mess);END;";	
			
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim,100);
    oci_bind_by_name($proc, ":p_username", $username,50);
	// 20-03-2024 penyesuaian bind variable
    oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
    oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ls_sukses = $p_sukses;
		$ls_mess = $p_mess;	
	}
   	
		
		if ($ls_sukses=="1")
		{
			// 08102020, penambahan kondisi nvl(status_submit_agenda,'T') = 'Y'  yg bisa membentuk data dokumen digital
			$qry_cek_klaim="
			select kode_perusahaan, kode_segmen, kode_tk, kpj, id_pointer_asal, to_char(tgl_klaim,'YYYY') tahun_saldo_jht, kanal_pelayanan, status_klaim,
			(select count(1) from pn.pn_klaim where kanal_pelayanan in (select KODE from MS.MS_LOOKUP where TIPE = 'KANALKLM' and KATEGORI = 'DOKUMEN_DIGITAL') and kode_klaim=:p_kode_klaim) lapakasik,
			kode_tipe_klaim
			 from pn.pn_klaim where kanal_pelayanan in (select KODE from MS.MS_LOOKUP where TIPE = 'KANALKLM' and KATEGORI = 'DOKUMEN_DIGITAL') and kode_klaim=:p_kode_klaim
			 and nvl(status_submit_agenda,'T') = 'Y' 
			 "
			;
			$proc = $DB->parse($qry_cek_klaim);
			oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
			// 20-03-2024 penyesuaian bind variable
			if($DB->execute()){
				if($row=$DB->nextrow()){
					$cek_klaim=$row['LAPAKASIK']; 
					$ls_kode_perusahaan = $row['KODE_PERUSAHAAN'];
					$ls_kode_segmen = $row['KODE_SEGMEN'];
					$ls_kode_tk = $row['KODE_TK'];  
					$ls_kpj_klaim = $row['KPJ'];  
					$ls_tahun_saldo = $row['TAHUN_SALDO_JHT']; 
					$ls_kode_booking = $row['ID_POINTER_ASAL']; 
					$ls_kode_tipe_klaim = $row['KODE_TIPE_KLAIM'];
					$ls_kanal_pelayanan = $row['KANAL_PELAYANAN'];
					$ls_status_klaim = $row['STATUS_KLAIM'];
					
					// 08102020, mengisi ulang data klaim sesuai data terakhir pada klaim 
					$ls_kpj = $ls_kpj_klaim;
				}
			}

			// generate dokumen digital
			if($cek_klaim > 0) {
				// cek jika kode tipe klaim JHT, maka akan dibentuk dokumen digital
				// 30 = Agenda Manual Reguler
				if ($ls_kanal_pelayanan == "30") {
					if ($ls_kode_tipe_klaim == "JHT01") {
						$qry_sign = "
						BEGIN 
							PN.P_PN_ARSIP_KLAIM_SIGN.X_GET_DTUSER_DOK_TERIMA
							(
							:P_KODE_KLAIM,
							:P_KODE_KANTOR      ,
							:P_NPK              ,
							:P_KODE_USER        ,
							:P_NAMA_USER        ,
							:P_NAMA_JABATAN     ,
							:P_SUKSES           ,
							:P_MESS              
							);
						END;";
						
						$proc = $DB->parse($qry_sign);       
						oci_bind_by_name($proc, ":P_KODE_KLAIM", $ls_kode_klaim, 2);
						oci_bind_by_name($proc, ":P_SUKSES", $p_sukses, 2);
						oci_bind_by_name($proc, ":P_MESS", $p_mess,1000);
						oci_bind_by_name($proc, ":P_KODE_KANTOR", $p_kode_kantor,100);
						oci_bind_by_name($proc, ":P_NPK", $p_npk,100);
						oci_bind_by_name($proc, ":P_KODE_USER", $p_kode_user,100);
						oci_bind_by_name($proc, ":P_NAMA_USER", $p_nama_user,100);
						oci_bind_by_name($proc, ":P_NAMA_JABATAN", $p_nama_jabatan,100);
					
						if ($DB->execute()) {
							$ls_sukses = $p_sukses;
							$ls_mess = $p_mess; 
							$ls_kode_kantor = $p_kode_kantor;
							$ls_npk = $p_npk;
							$ls_nama_jabatan = $p_nama_jabatan;
							$ls_kode_user = $p_kode_user;

						}

						$sqlbucket="select to_char(sysdate, 'yyyymm') blth,
						(select kode_kantor from pn.pn_klaim where kode_klaim = :p_kode_klaim) kode_kantor
						from dual";

						$proc = $DB->parse($sqlbucket);
						oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
						// 20-03-2024 penyesuaian bind variable
						$DB->execute();
						$row = $DB->nextrow();
						$ls_blth = $row["BLTH"];
						$ls_kode_kantor_bucket = $row["KODE_KANTOR"];


						$ls_nama_bucket_storage = "arsip";
						$ls_nama_folder_storage = "$ls_kode_kantor_bucket/$ls_blth/klaim";
						
						// 10122020
						// start Pembentukan Dokumen Kelengkapan Dokumen Persyaratan input Manual
						$sql = "
							select
							PEMILIK,
							MIME_TYPE,
							NAMA_DOKUMEN,
							URL_DOKUMEN,
							INFO_CETAK,
							FLAG_MANDATORY,
              FLAG_EMPTY
							from
							(
								select 
								1 JENIS_DOKUMEN,
								a.NO_URUT,
								(
								select b.NAMA_TK from PN.PN_KLAIM b
								where b.kode_klaim = a.kode_klaim
								) PEMILIK,
								a.MIME_TYPE,
								(
								select c.NAMA_DOKUMEN from PN.PN_KODE_DOKUMEN c
								where c.KODE_DOKUMEN = a.KODE_DOKUMEN
								and rownum = 1
								) NAMA_DOKUMEN,
								(:p_wsIpStorage || a.URL) URL_DOKUMEN,
								:p_username || '/' || 
								(
								select b.KODE_KANTOR from PN.PN_KLAIM b
								where b.kode_klaim = a.kode_klaim
								) || '/' || TO_CHAR (TRUNC (SYSDATE), 'DDMMRRRR') INFO_CETAK,
								case when a.URL is null then 'Y' else 'T' end FLAG_EMPTY,
                a.flag_mandatory
								from PN.PN_KLAIM_DOKUMEN a
								where SYARAT_TAHAP_KE = '1' 
								and KODE_KLAIM = :p_kode_klaim
								UNION ALL
								select 
								2 JENIS_DOKUMEN,
								a.NO_URUT,
								(
								select b.NAMA_TK from PN.PN_KLAIM b
								where b.kode_klaim = a.kode_klaim
								) PEMILIK,
								a.MIME_TYPE,
								a.NAMA_DOKUMEN_TAMBAHAN NAMA_DOKUMEN,
								(:p_wsIpStorage || a.PATH_URL) URL_DOKUMEN,
								:p_username || '/' || 
								(
								select b.KODE_KANTOR from PN.PN_KLAIM b
								where b.kode_klaim = a.kode_klaim
								) || '/' || TO_CHAR (TRUNC (SYSDATE), 'DDMMRRRR') INFO_CETAK,
								'T' flag_mandatory,
                case when a.PATH_URL is null then 'Y' else 'T' end FLAG_EMPTY
								from PN.PN_KLAIM_DOKUMEN_TAMBAHAN a
								where SYARAT_TAHAP_KE = '1' 
								and KODE_KLAIM = :p_kode_klaim
							)
							where   FLAG_MANDATORY = 'Y' or FLAG_EMPTY = 'T'  
							order by JENIS_DOKUMEN asc, NO_URUT asc
						";
								
						function dashesToCamelCase($string) 
						{
							$str = str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($string))));
							//echo $str;
							
							$str[0] = strtolower($str[0]);
							
							return $str;
						}
								
						$proc = $DB->parse($sql);
						$param_bv = array(':p_wsIpStorage' => $wsIpStorage, ':p_username' => $username, ':p_kode_klaim' => $ls_kode_klaim);
						foreach ($param_bv as $key => $value) {
							oci_bind_by_name($proc, $key, $param_bv[$key]);
						}
						// 20-03-2024 penyesuaian bind variable
						if($DB->execute()){ 
							$i = 0;
							$itotal = 0;
							$jdata = array();
							while($data = $DB->nextrow()){
								$hasil = array();
								foreach( $data as $key => $value ){
								$hasil[dashesToCamelCase($key)] = $value;
									// echo $value;
							} 
								$jdata[] = $hasil;
								$i++;
								$itotal++;
							}
						}
						
						$docs=$jdata;
								
						$data_merged = array(
							"chId" => "SMILE", 
							"reqId" => $username, 
							"idDokumen" => $ls_kode_klaim, 
							"kodeJenisDokumen" => "JD101", 
							"kodeDokumen" => "JD101-D1001", 
							"namaBucketTujuan" => $ls_nama_bucket_storage, 
							"namaFolderTujuan" => $ls_nama_folder_storage, 
							"docs" => $docs
						);

						$headers_merged = array(
							'Content-Type: application/json',
							'X-Forwarded-For: ' . $ipfwd
						);
													
						$result_merged = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers_merged, $data_merged); 
						// end Pembetukan Dokumen Kelengkapan Dokumen Persyaratan
							
						$headers = array(
							'Content-Type: application/json',
							'X-Forwarded-For: ' . $ipfwd
						);

						$data_storedocument = array(
							"chId" => "SMILE", 
							"reqId" => $username, 
							"idDokumen" => $ls_kode_klaim,
							"namaBucketTujuan" => $ls_nama_bucket_storage, 
							"namaFolderTujuan" => $ls_nama_folder_storage,
							"docs" => array(
									array(
									// JD101-D1003	TANDA TERIMA AGENDA JHT
									"kodeJenisDokumen" => "JD101", 
									"kodeDokumen" => "JD101-D1003", 
									"urlDokumen" => $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR900101.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26QUSER%3D%27".$username."%27%26QKODEKLAIM%3D%27".$ls_kode_klaim."%27" 
									), 
									// F5 sementara di komen untuk yang diinput manual
									array(
									// JD101-D1002	DOKUMEN F5
									"kodeJenisDokumen" => "JD101", 
									"kodeDokumen" => "JD101-D1002", 
									"urlDokumen" =>  $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DSIR0011a.rdf%26userid%3D%2Fdata%2Freports%2Fchannel%26%26P_KODE_KLAIM%3D%27".$ls_kode_klaim."%27" 
								),
									array(
										// JD101-D1005	SURAT PERNYATAAN NPWP
										"kodeJenisDokumen" => "JD101", 
										"kodeDokumen" => "JD101-D1005",  
										"urlDokumen" =>  $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR900116.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26P_KODE_KLAIM%3D%27".$ls_kode_klaim."%27%26P_KODE_KANTOR%3D%27".$ls_kode_kantor."%27"
									), 
									array(
										// JD101-D1004	HISTORI SALDO
										"kodeJenisDokumen" => "JD101", 
										"kodeDokumen" => "JD101-D1004",   
										"urlDokumen" => $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR900117.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26P_KODE_PERUSAHAAN%3D%27".$ls_kode_perusahaan."%27%26P_KODE_SEGMEN%3D%27".$ls_kode_segmen."%27%26P_KODE_TK%3D%27".$ls_kode_tk."%27%26P_TAHUN%3D%27".$ls_tahun_saldo."%27" 
									),
									array(
									// JD101-D1011	RINCIAN SALDO
									"kodeJenisDokumen" => "JD101", 
									"kodeDokumen" => "JD101-D1011",   
									"urlDokumen" =>   $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DKNR3315.rdf%26userid%3D%2Fdata%2Freports%2Fkn%26P_KODE_SEGMEN%3D%27".$ls_kode_segmen."%27%26P_TAHUN%3D%27".date("Y")."%27%26P_KODE_PERUSAHAAN%3D%27".$ls_kode_perusahaan."%27%26P_KODE_TK%3D%27".$ls_kode_tk."%27%26P_USER%3D%27".$username."%27%26"
								)   
							) 
						);
							
						$result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMultiDocument", $headers,  $data_storedocument);

						$arr_doc_success = $result_storedocument->docSuccess;
						$idArsipx="";
						foreach ($arr_doc_success as $doc) {
						if($doc->kodeDokumen=="JD101-D1003"){
							$idArsipx = $doc->idArsip;
							}
							}

						$data_presign = array(
							"chId" => "SMILE",
							"reqId" => $username,
							"idArsip" => $idArsipx
						);
							
						if ($result_storedocument->ret == "0") 
						{
							$result_presign = api_json_call($wsIpDocument . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
							if ($result_presign->ret == "0") 
							{
								// sign document
								$idArsip = $result_presign->data->idArsip;
								$docSigns = $result_presign->data->docSigns;
								if (ExtendedFunction::count($docSigns) > 0) 
								{
									$newDocSigns = array();
									foreach ($docSigns as $sign) 
									{
										$sign->dataUserSign = array(
											"kodeKantor" =>  $ls_kode_kantor,
											"npk" => $ls_npk,
											"namaJabatan" => $ls_nama_jabatan,
											"petugas" =>  $ls_kode_user
										);  
										$sign->action = "sign";
										array_push($newDocSigns, $sign);
									}

									$data_sign = array(
										"chId" => "SMILE",
										"reqId" => $username,
										"idArsip" => $idArsip,
										"docSigns" => $newDocSigns
									);
									$result_sign = api_json_call($wsIpDocument . "/JSDS/SignDocument", $headers, $data_sign);
								}
							}    
						}
					}
				} else {
					// generate document lapak asik
					$qry_sign = "
						BEGIN 
						PN.P_PN_ARSIP_KLAIM_SIGN.X_GET_DTUSER_DOK_TERIMA
						(
						:P_KODE_KLAIM,
						:P_KODE_KANTOR      ,
						:P_NPK              ,
						:P_KODE_USER        ,
						:P_NAMA_USER        ,
						:P_NAMA_JABATAN     ,
						:P_SUKSES           ,
						:P_MESS              
						);
					END;";
			
					
					$proc = $DB->parse($qry_sign);       
					oci_bind_by_name($proc, ":P_KODE_KLAIM", $ls_kode_klaim, 100);
					// 20-03-2024 penyesuaian bind variable
					oci_bind_by_name($proc, ":P_SUKSES", $p_sukses, 2);
					oci_bind_by_name($proc, ":P_MESS", $p_mess,1000);
					oci_bind_by_name($proc, ":P_KODE_KANTOR", $p_kode_kantor,100);
					oci_bind_by_name($proc, ":P_NPK", $p_npk,100);
					oci_bind_by_name($proc, ":P_KODE_USER", $p_kode_user,100);
					oci_bind_by_name($proc, ":P_NAMA_USER", $p_nama_user,100);
					oci_bind_by_name($proc, ":P_NAMA_JABATAN", $p_nama_jabatan,100);
				
					if ($DB->execute()) {
						$ls_sukses = $p_sukses;
						$ls_mess = $p_mess; 
						$ls_kode_kantor = $p_kode_kantor;
						$ls_npk = $p_npk;
						$ls_nama_jabatan = $p_nama_jabatan;
						$ls_kode_user = $p_kode_user;

					}

					$sqlbucket="select to_char(sysdate, 'yyyymm') blth,
					(select kode_kantor from pn.pn_klaim where kode_klaim = :p_kode_klaim) kode_kantor
					from dual";

					$proc = $DB->parse($sqlbucket);
					oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
					// 20-03-2024 penyesuaian bind variable
					$DB->execute();
					$row = $DB->nextrow();
					$ls_blth = $row["BLTH"];
					$ls_kode_kantor_bucket = $row["KODE_KANTOR"];


					$ls_nama_bucket_storage = "arsip";
					$ls_nama_folder_storage = "$ls_kode_kantor_bucket/$ls_blth/klaim";
					
					// 08102020
					// start Pembentukan Dokumen Kelengkapan Dokumen Persyaratan
					$sql="
					SELECT CASE
                          WHEN a.kode_dokumen = 'D084'
                          THEN
                                 nama
                              || ' '
                              || ', Status Valid Identitas : '
                              || status_valid_identitas
                          ELSE
                              nama
                      END
                          pemilik,
                      a.mime_type,
                      a.nama_dokumen,
                      :p_wsIpStorage || path_url
                          AS url_dokumen,
                         :p_username
                      || '/'
                      || kode_kantor
                      || '/'
                      || TO_CHAR (TRUNC (SYSDATE), 'DDMMRRRR')
                          info_cetak
                 FROM (  SELECT kode_booking,
                                (SELECT kode_kantor
                                   FROM antrian.atr_booking z
                                  WHERE z.kode_booking = y.kode_booking
                                 UNION
                                 SELECT kode_kantor
                                   FROM antrian.atr_booking_hist w
                                  WHERE w.kode_booking = y.kode_booking)
                                    kode_kantor,
                                (SELECT nama
                                   FROM antrian.atr_booking z
                                  WHERE z.kode_booking = y.kode_booking
                                 UNION
                                 SELECT nama
                                   FROM antrian.atr_booking_hist w
                                  WHERE w.kode_booking = y.kode_booking)
                                    nama,
                                DECODE ((SELECT status_valid_identitas
                                           FROM antrian.atr_booking z
                                          WHERE z.kode_booking = y.kode_booking
                                         UNION
                                         SELECT status_valid_identitas
                                           FROM antrian.atr_booking_hist w
                                          WHERE w.kode_booking = y.kode_booking),
                                        'Y', 'YA',
                                        'TIDAK')
                                    status_valid_identitas,
                                kode_dokumen,
                                mime_type,
                                (SELECT nama_dokumen
                                   FROM antrian.atr_kode_dokumen x
                                  WHERE x.kode_dokumen = y.kode_dokumen)
                                    nama_dokumen,
                                (SELECT no_urut
                                   FROM antrian.atr_kode_dokumen x
                                  WHERE x.kode_dokumen = y.kode_dokumen)
                                    no_urut,
                                path_url,
                                (SELECT status_submit_dokumen
                                   FROM antrian.atr_booking z
                                  WHERE z.kode_booking = y.kode_booking
                                 UNION
                                 SELECT status_submit_dokumen
                                   FROM antrian.atr_booking_hist w
                                  WHERE w.kode_booking = y.kode_booking AND ROWNUM = 1)
                                    status_submit_dokumen
                           FROM antrian.atr_booking_dokumen y
                          WHERE kode_booking = :p_kode_booking
                       ORDER BY no_urut ASC) a
                WHERE a.path_url IS NOT NULL
               UNION ALL
               SELECT a.pemilik,
                      a.mime_type,
                      a.nama_dokumen,
                      :p_wsIpStorage || a.path_url
                          AS url_dokumen,
                         :p_username
                      || '/'
                      || kode_kantor
                      || '/'
                      || TO_CHAR (TRUNC (SYSDATE), 'DDMMRRRR')
                          info_cetak
                 FROM (SELECT mime_type,
                              (SELECT nama
                                 FROM antrian.atr_booking z
                                WHERE z.kode_booking = y.kode_booking
                               UNION
                               SELECT nama
                                 FROM antrian.atr_booking_hist w
                                WHERE w.kode_booking = y.kode_booking)
                                  pemilik,
                              (SELECT nama_dokumen
                                 FROM antrian.atr_kode_dokumen x
                                WHERE x.kode_dokumen = y.kode_dokumen)
                                  nama_dokumen,
                              path_url,
                              (SELECT kode_kantor
                                 FROM antrian.atr_booking z
                                WHERE z.kode_booking = y.kode_booking
                               UNION
                               SELECT kode_kantor
                                 FROM antrian.atr_booking_hist w
                                WHERE w.kode_booking = y.kode_booking)
                                  kode_kantor
                         FROM antrian.atr_booking_kartulain_dokumen y
                        WHERE path_url IS NOT NULL AND kode_booking = :p_kode_booking AND length(petugas_rekam) > 8 order by kode_dokumen asc) a
               UNION ALL
               SELECT a.pemilik,
                      a.mime_type,
                      a.nama_dokumen,
                      :p_wsIpStorage || a.path_url
                          AS url_dokumen,
                         :p_username
                      || '/'
                      || kode_kantor
                      || '/'
                      || TO_CHAR (TRUNC (SYSDATE), 'DDMMRRRR')
                          info_cetak
                 FROM (SELECT mime_type,
                              (SELECT nama
                                 FROM antrian.atr_booking z
                                WHERE z.kode_booking = y.kode_booking
                               UNION
                               SELECT nama
                                 FROM antrian.atr_booking_hist w
                                WHERE w.kode_booking = y.kode_booking)
                                  pemilik,
                              nama_dokumen_lainnya
                                  nama_dokumen,
                              path_url,
                              (SELECT kode_kantor
                                 FROM antrian.atr_booking z
                                WHERE z.kode_booking = y.kode_booking
                               UNION
                               SELECT kode_kantor
                                 FROM antrian.atr_booking_hist w
                                WHERE w.kode_booking = y.kode_booking)
                                  kode_kantor
                         FROM antrian.atr_booking_dokumen_lain y
                        WHERE path_url IS NOT NULL AND kode_booking = :p_kode_booking AND length(petugas_rekam) > 8 order by kode_dokumen asc) a
                        UNION ALL
               SELECT a.pemilik,
                      a.mime_type,
                      a.nama_dokumen,
                      :p_wsIpStorage || a.path_url
                          AS url_dokumen,
                         :p_username
                      || '/'
                      || kode_kantor
                      || '/'
                      || TO_CHAR (TRUNC (SYSDATE), 'DDMMRRRR')
                          info_cetak
                 FROM (SELECT mime_type,
                              (SELECT nama
                                 FROM antrian.atr_booking z
                                WHERE z.kode_booking = y.kode_booking
                               UNION
                               SELECT nama
                                 FROM antrian.atr_booking_hist w
                                WHERE w.kode_booking = y.kode_booking)
                                  pemilik,
                              (SELECT nama_dokumen
                                 FROM antrian.atr_kode_dokumen x
                                WHERE x.kode_dokumen = y.kode_dokumen)
                                  nama_dokumen,
                              path_url,
                              (SELECT kode_kantor
                                 FROM antrian.atr_booking z
                                WHERE z.kode_booking = y.kode_booking
                               UNION
                               SELECT kode_kantor
                                 FROM antrian.atr_booking_hist w
                                WHERE w.kode_booking = y.kode_booking)
                                  kode_kantor
                         FROM antrian.atr_booking_kartulain_dokumen y
                        WHERE path_url IS NOT NULL AND kode_booking = :p_kode_booking AND length(petugas_rekam) <= 8 order by kode_dokumen asc) a
                        UNION ALL
               SELECT a.pemilik,
                      a.mime_type,
                      a.nama_dokumen,
                      :p_wsIpStorage || a.path_url
                          AS url_dokumen,
                         :p_username
                      || '/'
                      || kode_kantor
                      || '/'
                      || TO_CHAR (TRUNC (SYSDATE), 'DDMMRRRR')
                          info_cetak
                 FROM (SELECT mime_type,
                              (SELECT nama
                                 FROM antrian.atr_booking z
                                WHERE z.kode_booking = y.kode_booking
                               UNION
                               SELECT nama
                                 FROM antrian.atr_booking_hist w
                                WHERE w.kode_booking = y.kode_booking)
                                  pemilik,
                              nama_dokumen_lainnya
                                  nama_dokumen,
                              path_url,
                              (SELECT kode_kantor
                                 FROM antrian.atr_booking z
                                WHERE z.kode_booking = y.kode_booking
                               UNION
                               SELECT kode_kantor
                                 FROM antrian.atr_booking_hist w
                                WHERE w.kode_booking = y.kode_booking)
                                  kode_kantor
                         FROM antrian.atr_booking_dokumen_lain y
                        WHERE path_url IS NOT NULL AND kode_booking = :p_kode_booking AND length(petugas_rekam) <= 8  order by kode_dokumen asc) a";
							
					function dashesToCamelCase($string) 
					{
						$str = str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($string))));
						//echo $str;
						
						$str[0] = strtolower($str[0]);
						
						return $str;
					}
							
					$proc = $ECDB->parse($sql);
					$param_bv = array(':p_wsIpStorage' => $wsIpStorage, ':p_username' => $username, ':p_kode_booking' => $ls_kode_booking);
					foreach ($param_bv as $key => $value) {
						oci_bind_by_name($proc, $key, $param_bv[$key]);
					}
					// 20-03-2024 penyesuaian bind variable
					if($ECDB->execute()){ 
						$i = 0;
						$itotal = 0;
						$jdata = array();
						while($data = $ECDB->nextrow()){
							$hasil = array();
							foreach( $data as $key => $value ){
							$hasil[dashesToCamelCase($key)] = $value;
								// echo $value;
						} 
							$jdata[] = $hasil;
							$i++;
							$itotal++;
						}
					}
							
					$docs=$jdata;
							
					if ($ls_kode_tipe_klaim == "JHT01") 
					{
						$data_merged = array(
							"chId" => "SMILE", 
							"reqId" => $username, 
							"idDokumen" => $ls_kode_klaim, 
							"kodeJenisDokumen" => "JD101", 
							"kodeDokumen" => "JD101-D1001", 
							"namaBucketTujuan" => $ls_nama_bucket_storage, 
							"namaFolderTujuan" => $ls_nama_folder_storage, 
							"docs" => $docs
						);

						$headers_merged = array(
							'Content-Type: application/json',
							'X-Forwarded-For: ' . $ipfwd
							);
													
						$result_merged = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers_merged, $data_merged); 
						// end Pembetukan Dokumen Kelengkapan Dokumen Persyaratan

						$headers = array(
							'Content-Type: application/json',
								'X-Forwarded-For: ' . $ipfwd
								);

							$data_storedocument = array(
							"chId" => "SMILE", 
							"reqId" => $username, 
							"idDokumen" => $ls_kode_klaim,
							"namaBucketTujuan" => $ls_nama_bucket_storage, 
							"namaFolderTujuan" => $ls_nama_folder_storage,
							"docs" => array(
									array(
										"kodeJenisDokumen" => "JD101", 
										"kodeDokumen" => "JD101-D1003", 
										"urlDokumen" => $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR900101.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26QUSER%3D%27".$username."%27%26QKODEKLAIM%3D%27".$ls_kode_klaim."%27" 
									), 
									array(
									"kodeJenisDokumen" => "JD101", 
									"kodeDokumen" => "JD101-D1002", 
									"urlDokumen" =>  $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$antrian_rpt_user."&password=".$antrian_rpt_pass."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$antrian_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DSIR0011.rdf%26userid%3D%2Fdata%2Freports%2Fchannel%26%26P_KODE_BOOKING%3D%27".$ls_kode_booking."%27%26P_KPJ%3D%27".$ls_kpj."%27" 
									),
									array(
										"kodeJenisDokumen" => "JD101", 
										"kodeDokumen" => "JD101-D1005",  
										"urlDokumen" =>  $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR900116.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26P_KODE_KLAIM%3D%27".$ls_kode_klaim."%27%26P_KODE_KANTOR%3D%27".$ls_kode_kantor."%27"
									), 
									array(
											"kodeJenisDokumen" => "JD101", 
											"kodeDokumen" => "JD101-D1004",   
											"urlDokumen" => $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR900117.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26P_KODE_PERUSAHAAN%3D%27".$ls_kode_perusahaan."%27%26P_KODE_SEGMEN%3D%27".$ls_kode_segmen."%27%26P_KODE_TK%3D%27".$ls_kode_tk."%27%26P_TAHUN%3D%27".$ls_tahun_saldo."%27" 
									),
									array(
									"kodeJenisDokumen" => "JD101", 
									"kodeDokumen" => "JD101-D1011",   
									"urlDokumen" =>   $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DKNR3315.rdf%26userid%3D%2Fdata%2Freports%2Fkn%26P_KODE_SEGMEN%3D%27".$ls_kode_segmen."%27%26P_TAHUN%3D%27".date("Y")."%27%26P_KODE_PERUSAHAAN%3D%27".$ls_kode_perusahaan."%27%26P_KODE_TK%3D%27".$ls_kode_tk."%27%26P_USER%3D%27".$username."%27%26"
								)   
							) 
							);
							
							$result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMultiDocument", $headers,  $data_storedocument);

							$arr_doc_success = $result_storedocument->docSuccess;
							$idArsipx="";
							foreach ($arr_doc_success as $doc) {
							if($doc->kodeDokumen=="JD101-D1003"){
								$idArsipx = $doc->idArsip;
								}
								}
							
							$data_presign = array(
								"chId" => "SMILE",
							"reqId" => $username,
							"idArsip" => $idArsipx
							);
							
							if ($result_storedocument->ret == "0") {
							
								$result_presign = api_json_call($wsIpDocument . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
								if ($result_presign->ret == "0") {
								// sign document
								$idArsip = $result_presign->data->idArsip;
								$docSigns = $result_presign->data->docSigns;
								if (ExtendedFunction::count($docSigns) > 0) {
								$newDocSigns = array();
								foreach ($docSigns as $sign) {
									$sign->dataUserSign = array(
									"kodeKantor" =>  $ls_kode_kantor,
									"npk" => $ls_npk,
									"namaJabatan" => $ls_nama_jabatan,
									"petugas" =>  $ls_kode_user
									);  
								$sign->action = "sign";
								array_push($newDocSigns, $sign);
								}
							
								$data_sign = array(
								"chId" => "SMILE",
								"reqId" => $username,
								"idArsip" => $idArsip,
								"docSigns" => $newDocSigns
								);
								$result_sign = api_json_call($wsIpDocument . "/JSDS/SignDocument", $headers, $data_sign);
								}
							}    
							}
					}else if($ls_kode_tipe_klaim == "JKM01")
					{
							// JD102	DOKUMEN KLAIM JKM
							// JD102-D1008	DOKUMEN KELENGKAPAN PERSYARATAN
							$data_merged = array(
							"chId" => "SMILE", 
							"reqId" => $username, 
							"idDokumen" => $ls_kode_klaim, 
							"kodeJenisDokumen" => "JD102", 
							"kodeDokumen" => "JD102-D1008", 
							"namaBucketTujuan" => $ls_nama_bucket_storage, 
							"namaFolderTujuan" => $ls_nama_folder_storage, 
							"docs" => $docs
						);

						$headers_merged = array(
							'Content-Type: application/json',
							'X-Forwarded-For: ' . $ipfwd
							);
													
						$result_merged = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers_merged, $data_merged); 
						// end Pembetukan Dokumen Kelengkapan Dokumen Persyaratan

						$headers = array(
							'Content-Type: application/json',
								'X-Forwarded-For: ' . $ipfwd
								);

							$data_storedocument = array(
							"chId" => "SMILE", 
							"reqId" => $username, 
							"idDokumen" => $ls_kode_klaim,
							"namaBucketTujuan" => $ls_nama_bucket_storage, 
							"namaFolderTujuan" => $ls_nama_folder_storage,
							"docs" => array(
									array(
									//JD102-D1006	TANDA TERIMA AGENDA JKM
										"kodeJenisDokumen" => "JD102", 
										"kodeDokumen" => "JD102-D1006", 
										"urlDokumen" => $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR900114.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26QUSER%3D%27".$username."%27%26QKODEKLAIM%3D%27".$ls_kode_klaim."%27" 
									), 
									array(
									// DOKUMEN F4
									"kodeJenisDokumen" => "JD102", 
									"kodeDokumen" => "JD102-D1007", 
									"urlDokumen" =>  $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$antrian_rpt_user."&password=".$antrian_rpt_pass."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$antrian_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DSIR0012.rdf%26userid%3D%2Fdata%2Freports%2Fchannel%26%26P_KODE_BOOKING%3D%27".$ls_kode_booking."%27"									
									)  
							) 
							);
							
							$result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMultiDocument", $headers,  $data_storedocument);

							$arr_doc_success = $result_storedocument->docSuccess;
							$idArsipx="";
							foreach ($arr_doc_success as $doc) {
							if($doc->kodeDokumen=="JD102-D1006"){
								$idArsipx = $doc->idArsip;
								}
								}
							
							$data_presign = array(
								"chId" => "SMILE",
							"reqId" => $username,
							"idArsip" => $idArsipx
							);
							
							if ($result_storedocument->ret == "0") {
							
								$result_presign = api_json_call($wsIpDocument . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
								if ($result_presign->ret == "0") {
								// sign document
								$idArsip = $result_presign->data->idArsip;
								$docSigns = $result_presign->data->docSigns;
								if (ExtendedFunction::count($docSigns) > 0) {
								$newDocSigns = array();
								foreach ($docSigns as $sign) {
									$sign->dataUserSign = array(
									"kodeKantor" =>  $ls_kode_kantor,
									"npk" => $ls_npk,
									"namaJabatan" => $ls_nama_jabatan,
									"petugas" =>  $ls_kode_user
									);  
								$sign->action = "sign";
								array_push($newDocSigns, $sign);
								}
							
							$data_sign = array(
								"chId" => "SMILE",
								"reqId" => $username,
								"idArsip" => $idArsip,
							"docSigns" => $newDocSigns
							);
								$result_sign = api_json_call($wsIpDocument . "/JSDS/SignDocument", $headers, $data_sign);
								}
							}    
						}
					}
					else if($ls_kode_tipe_klaim == "JKK01" && $ls_status_klaim=="PENETAPAN")
					{
							// JD108	DOKUMEN KLAIM JKK
							// JD108-D1008	DOKUMEN KELENGKAPAN PERSYARATAN
						// 	$data_merged = array(
						// 	"chId" => "SMILE", 
						// 	"reqId" => $username, 
						// 	"idDokumen" => $ls_kode_klaim, 
						// 	"kodeJenisDokumen" => "JD108", 
						// 	"kodeDokumen" => "JD108-D1008", 
						// 	"namaBucketTujuan" => $ls_nama_bucket_storage, 
						// 	"namaFolderTujuan" => $ls_nama_folder_storage, 
						// 	"docs" => $docs
						// );

						// $headers_merged = array(
						// 	'Content-Type: application/json',
						// 	'X-Forwarded-For: ' . $ipfwd
						// 	);
													
						// $result_merged = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers_merged, $data_merged); 
						// end Pembetukan Dokumen Kelengkapan Dokumen Persyaratan

						$headers = array(
							'Content-Type: application/json',
								'X-Forwarded-For: ' . $ipfwd
								);

							$data_storedocument = array(
							"chId" => "SMILE", 
							"reqId" => $username, 
							"idDokumen" => $ls_kode_klaim,
							"namaBucketTujuan" => $ls_nama_bucket_storage, 
							"namaFolderTujuan" => $ls_nama_folder_storage,
							"docs" => array(
									array(
									//JD108-D1006	TANDA TERIMA AGENDA JKK I
										"kodeJenisDokumen" => "JD108", 
										"kodeDokumen" => "JD108-D1006", 
										"urlDokumen" => $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR900111.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26QUSER%3D%27".$username."%27%26QKODEKLAIM%3D%27".$ls_kode_klaim."%27" 
									),
									array(
										//JD108-D1011	TANDA TERIMA AGENDA JKK II
											"kodeJenisDokumen" => "JD108", 
											"kodeDokumen" => "JD108-D1011", 
											"urlDokumen" => $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR900112.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26QUSER%3D%27".$username."%27%26QKODEKLAIM%3D%27".$ls_kode_klaim."%27" 
									)
							) 
							);
							
							$result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMultiDocument", $headers,  $data_storedocument);

							$arr_doc_success = $result_storedocument->docSuccess;
							$idArsip1="";
							$idArsip2="";
							foreach ($arr_doc_success as $doc) {
								if($doc->kodeDokumen=="JD108-D1006"){
									$idArsip1 = $doc->idArsip;
								}else if($doc->kodeDokumen=="JD108-D1011"){
									$idArsip2 = $doc->idArsip;
								}
							}
							
							$data_presign1 = array(
							"chId" => "SMILE",
							"reqId" => $username,
							"idArsip" => $idArsip1
							);

							$data_presign2 = array(
							"chId" => "SMILE",
							"reqId" => $username,
							"idArsip" => $idArsip2
							);
							
							if ($result_storedocument->ret == "0") {
							
								$result_presign1 = api_json_call($wsIpDocument . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign1);
									if ($result_presign1->ret == "0") {
										// sign document
										$idArsip1 = $result_presign1->data->idArsip;
										$docSigns1 = $result_presign1->data->docSigns;
										if (ExtendedFunction::count($docSigns1) > 0) {
										$newDocSigns1 = array();
										foreach ($docSigns1 as $sign) {
											$sign->dataUserSign = array(
											"kodeKantor" =>  $ls_kode_kantor,
											"npk" => $ls_npk,
											"namaJabatan" => $ls_nama_jabatan,
											"petugas" =>  $ls_kode_user
											);  
										$sign->action = "sign";
										array_push($newDocSigns1, $sign);
										}
									
										$data_sign1 = array(
											"chId" => "SMILE",
											"reqId" => $username,
											"idArsip" => $idArsip1,
										"docSigns" => $newDocSigns1
										);
										$result_sign1 = api_json_call($wsIpDocument . "/JSDS/SignDocument", $headers, $data_sign1);
										}
									}

								$result_presign2 = api_json_call($wsIpDocument . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign2);
									if ($result_presign2->ret == "0") {
										// sign document
										$idArsip2 = $result_presign2->data->idArsip;
										$docSigns2 = $result_presign2->data->docSigns;
										if (ExtendedFunction::count($docSigns2) > 0) {
										$newDocSigns2 = array();
										foreach ($docSigns2 as $sign) {
											$sign->dataUserSign = array(
											"kodeKantor" =>  $ls_kode_kantor,
											"npk" => $ls_npk,
											"namaJabatan" => $ls_nama_jabatan,
											"petugas" =>  $ls_kode_user
											);  
										$sign->action = "sign";
										array_push($newDocSigns2, $sign);
										}
									
										$data_sign2 = array(
											"chId" => "SMILE",
											"reqId" => $username,
											"idArsip" => $idArsip2,
											"docSigns" => $newDocSigns2
										);
										$result_sign1 = api_json_call($wsIpDocument . "/JSDS/SignDocument", $headers, $data_sign2);
										}
									}    	    
						}
					}
					else if($ls_kode_tipe_klaim == "JPN01")
					{
						// cek apakah lumsum atau berkala
						// jika lumsum
						if($ln_cnt_lumpsum > 0)
						{
							// JD103	DOKUMEN KLAIM JPN LUMSUM
								// JD103-D1011	DOKUMEN KELENGKAPAN PERSYARATAN
								$data_merged = array(
								"chId" => "SMILE", 
								"reqId" => $username, 
								"idDokumen" => $ls_kode_klaim, 
								"kodeJenisDokumen" => "JD103", 
								"kodeDokumen" => "JD103-D1011", 
								"namaBucketTujuan" => $ls_nama_bucket_storage, 
								"namaFolderTujuan" => $ls_nama_folder_storage, 
								"docs" => $docs
							);

							$headers_merged = array(
								'Content-Type: application/json',
								'X-Forwarded-For: ' . $ipfwd
								);
														
							$result_merged = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers_merged, $data_merged); 
							// end Pembetukan Dokumen Kelengkapan Dokumen Persyaratan

							$headers = array(
								'Content-Type: application/json',
									'X-Forwarded-For: ' . $ipfwd
									);

								$data_storedocument = array(
								"chId" => "SMILE", 
								"reqId" => $username, 
								"idDokumen" => $ls_kode_klaim,
								"namaBucketTujuan" => $ls_nama_bucket_storage, 
								"namaFolderTujuan" => $ls_nama_folder_storage,
								"docs" => array(
										array(
										//JD103-D1006	TANDA TERIMA AGENDA JPN
											"kodeJenisDokumen" => "JD103", 
											"kodeDokumen" => "JD103-D1006", 
											"urlDokumen" => $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR900101.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26QUSER%3D%27".$username."%27%26QKODEKLAIM%3D%27".$ls_kode_klaim."%27" 
										), 
										array(
										// JD103-D1010	DOKUMEN F7
										"kodeJenisDokumen" => "JD103", 
										"kodeDokumen" => "JD103-D1010", 
										"urlDokumen" =>  $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$antrian_rpt_user."&password=".$antrian_rpt_pass."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$antrian_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DSIR0013.rdf%26userid%3D%2Fdata%2Freports%2Fchannel%26%26P_KODE_BOOKING%3D%27".$ls_kode_booking."%27"									
										),
										array(
										// JD103-D1009	SURAT PERNYATAAN NPWP
										"kodeJenisDokumen" => "JD103", 
										"kodeDokumen" => "JD103-D1009", 
										"urlDokumen" =>  $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR900116.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26P_KODE_KLAIM%3D%27".$ls_kode_klaim."%27%26P_KODE_KANTOR%3D%27".$ls_kode_kantor."%27"
										),
										array(
										// JD103-D1008	HISTORI IURAN JPN
										"kodeJenisDokumen" => "JD103", 
										"kodeDokumen" => "JD103-D1008", 
										"urlDokumen" => $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR900103_ITK.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26QKODETK%3D%27".$ls_kode_tk."%27%26QUSER%3D%27".$username."%27"
										),
										array(
										// JD103-D1007	RINCIAN IURAN JPN
										"kodeJenisDokumen" => "JD103", 
										"kodeDokumen" => "JD103-D1007", 
										"urlDokumen" => $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR900104_ITK.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26QKODETK%3D%27".$ls_kode_tk."%27%26QUSER%3D%27".$username."%27"
										)
								) 
								);
								
								$result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMultiDocument", $headers,  $data_storedocument);

								$arr_doc_success = $result_storedocument->docSuccess;
								$idArsipx="";
								foreach ($arr_doc_success as $doc) {
								if($doc->kodeDokumen=="JD103-D1006"){
									$idArsipx = $doc->idArsip;
									}
									}
								
								$data_presign = array(
									"chId" => "SMILE",
								"reqId" => $username,
								"idArsip" => $idArsipx
								);
								
								if ($result_storedocument->ret == "0") {
								
									$result_presign = api_json_call($wsIpDocument . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
									if ($result_presign->ret == "0") {
									// sign document
									$idArsip = $result_presign->data->idArsip;
									$docSigns = $result_presign->data->docSigns;
									if (ExtendedFunction::count($docSigns) > 0) {
									$newDocSigns = array();
									foreach ($docSigns as $sign) {
										$sign->dataUserSign = array(
										"kodeKantor" =>  $ls_kode_kantor,
										"npk" => $ls_npk,
										"namaJabatan" => $ls_nama_jabatan,
										"petugas" =>  $ls_kode_user
										);  
									$sign->action = "sign";
									array_push($newDocSigns, $sign);
									}
								
								$data_sign = array(
									"chId" => "SMILE",
									"reqId" => $username,
									"idArsip" => $idArsip,
								"docSigns" => $newDocSigns
								);
									$result_sign = api_json_call($wsIpDocument . "/JSDS/SignDocument", $headers, $data_sign);
									}
								}    
							}
						}
						
						// jika berkala
						if($ln_cnt_berkala > 0)
						{
							// JD104	DOKUMEN KLAIM JPN BERKALA
								// JD104-D1012	DOKUMEN KELENGKAPAN PERSYARATAN
								$data_merged = array(
								"chId" => "SMILE", 
								"reqId" => $username, 
								"idDokumen" => $ls_kode_klaim, 
								"kodeJenisDokumen" => "JD104", 
								"kodeDokumen" => "JD104-D1012", 
								"namaBucketTujuan" => $ls_nama_bucket_storage, 
								"namaFolderTujuan" => $ls_nama_folder_storage, 
								"docs" => $docs
							);

							$headers_merged = array(
								'Content-Type: application/json',
								'X-Forwarded-For: ' . $ipfwd
								);
														
							$result_merged = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers_merged, $data_merged); 
							// end Pembetukan Dokumen Kelengkapan Dokumen Persyaratan

							$headers = array(
								'Content-Type: application/json',
									'X-Forwarded-For: ' . $ipfwd
									);

								$data_storedocument = array(
								"chId" => "SMILE", 
								"reqId" => $username, 
								"idDokumen" => $ls_kode_klaim,
								"namaBucketTujuan" => $ls_nama_bucket_storage, 
								"namaFolderTujuan" => $ls_nama_folder_storage,
								"docs" => array(
										array(
										// JD104-D1006	TANDA TERIMA AGENDA JPN
											"kodeJenisDokumen" => "JD104", 
											"kodeDokumen" => "JD104-D1006", 
											"urlDokumen" => $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR900101.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26QUSER%3D%27".$username."%27%26QKODEKLAIM%3D%27".$ls_kode_klaim."%27" 
										), 
										array(
										// JD104-D1011	DOKUMEN F7
										"kodeJenisDokumen" => "JD104", 
										"kodeDokumen" => "JD104-D1011", 
										"urlDokumen" =>  $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$antrian_rpt_user."&password=".$antrian_rpt_pass."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$antrian_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DSIR0013.rdf%26userid%3D%2Fdata%2Freports%2Fchannel%26%26P_KODE_BOOKING%3D%27".$ls_kode_booking."%27"									
										),
										array(
										// JD104-D1010	SURAT PERNYATAAN NPWP
										"kodeJenisDokumen" => "JD104", 
										"kodeDokumen" => "JD104-D1010", 
										"urlDokumen" =>  $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR900116.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26P_KODE_KLAIM%3D%27".$ls_kode_klaim."%27%26P_KODE_KANTOR%3D%27".$ls_kode_kantor."%27"
										),
										array(
										// JD104-D1009	HISTORI IURAN JPN
										"kodeJenisDokumen" => "JD104", 
										"kodeDokumen" => "JD104-D1009", 
										"urlDokumen" => $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR900103_ITK.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26QKODETK%3D%27".$ls_kode_tk."%27%26QUSER%3D%27".$username."%27"
										),
										array(
										// JD104-D1008	RINCIAN IURAN JPN
										"kodeJenisDokumen" => "JD104", 
										"kodeDokumen" => "JD104-D1008", 
										"urlDokumen" => $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR900104_ITK.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26QKODETK%3D%27".$ls_kode_tk."%27%26QUSER%3D%27".$username."%27"
										),
										array(
										// JD104-D1007	SURAT PERNYATAAN KLAIM JPN BERKALA
										"kodeJenisDokumen" => "JD104", 
										"kodeDokumen" => "JD104-D1007", 
										"urlDokumen" => $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR900102.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26QKODEKLAIM%3D%27".$ls_kode_klaim."%27%26QNOKONFIRMASI%3D%270%27"
										)
								) 
								);
								
								$result_storedocument = api_json_call($wsIp . "/JSDS/StoreMultiDocument", $headers,  $data_storedocument);

								$arr_doc_success = $result_storedocument->docSuccess;
								$idArsipx="";
								foreach ($arr_doc_success as $doc) {
								if($doc->kodeDokumen=="JD104-D1006"){
									$idArsipx = $doc->idArsip;
									}
									}
								
								$data_presign = array(
									"chId" => "SMILE",
								"reqId" => $username,
								"idArsip" => $idArsipx
								);
								
								if ($result_storedocument->ret == "0") {
								
									$result_presign = api_json_call($wsIpDocument . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
									if ($result_presign->ret == "0") {
									// sign document
									$idArsip = $result_presign->data->idArsip;
									$docSigns = $result_presign->data->docSigns;
									if (ExtendedFunction::count($docSigns) > 0) {
									$newDocSigns = array();
									foreach ($docSigns as $sign) {
										$sign->dataUserSign = array(
										"kodeKantor" =>  $ls_kode_kantor,
										"npk" => $ls_npk,
										"namaJabatan" => $ls_nama_jabatan,
										"petugas" =>  $ls_kode_user
										);  
									$sign->action = "sign";
									array_push($newDocSigns, $sign);
									}
								
								$data_sign = array(
									"chId" => "SMILE",
									"reqId" => $username,
									"idArsip" => $idArsip,
								"docSigns" => $newDocSigns
								);
									$result_sign = api_json_call($wsIpDocument . "/JSDS/SignDocument", $headers, $data_sign);
									}
								}    
							}
						}
					}
			  }
			}
			
			//end digital arsip
		$msg = "Submit Data berhasil dilakukan, session dilanjutkan..."; 	
      echo "<script language=\"JavaScript\" type=\"text/javascript\">";
      echo "window.location.replace('?&task=Edit&mid=$mid&kode_klaim=$ls_kode_klaim&dataid=$ls_kode_klaim&msg=$msg');";
      echo "</script>";		
		}else
		{
  		$msg = $ls_mess;			 	
      echo "<script language=\"JavaScript\" type=\"text/javascript\">";
      echo "window.location.replace('?&task=Edit&mid=$mid&kode_klaim=$ls_kode_klaim&dataid=$ls_kode_klaim&msg=$msg&ls_error=1');";
			echo "alert('$msg')";
      echo "</script>";			
		}	
	
	// 16072020
	// untuk push notification ke BPJSTKU yang sumber klaimnya dari BPJSTKU
	$DB_EC = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);
	
		
	}

function api_json_call($apiurl, $header, $data) 
{
	$curl = curl_init();

	curl_setopt_array(
	  $curl, 
	  array(
		CURLOPT_URL => $apiurl,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => json_encode($data),
		CURLOPT_HTTPHEADER => $header,
	  )
	);

	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);

	if ($err) {
	  $jdata["ret"] = -1;
	  $jdata["msg"] = "cURL Error #:" . $err;
	  $result = $jdata;
	} else {
	  $result = json_decode($response);
	}

	return $result;
}	
	//end SUBMIT BUTTON ----------------------------------------------------------
	?>
	