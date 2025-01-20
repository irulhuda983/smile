<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
//get data from ajax -----------------------------------------------------------
//note: cara utk get data dari beberapa array data
//$ls_kode_kantor = $_POST["INFO_KLAIM"]["KODE_KANTOR"] =="null" ? "" : $_POST["INFO_KLAIM"]["KODE_KANTOR"];
//end note ---------------------------------------------------------------------
$ls_kode_form  		 			    = $_GET["kode_form"];
$ls_kode_klaim  		 			  = $_POST["KODE_KLAIM"]  						 =="null" ? "" : $_POST["KODE_KLAIM"];
$ls_kode_kantor 					 	= $_POST["KODE_KANTOR"] 						 =="null" ? "" : $_POST["KODE_KANTOR"];
$ls_nama_kantor 					 	= $_POST["NAMA_KANTOR"] 						 =="null" ? "" : $_POST["NAMA_KANTOR"];
$ls_kode_segmen 					 	= $_POST["KODE_SEGMEN"] 						 =="null" ? "" : $_POST["KODE_SEGMEN"];
$ls_nama_segmen 					 	= $_POST["NAMA_SEGMEN"] 						 =="null" ? "" : $_POST["NAMA_SEGMEN"];
$ls_kode_tipe_klaim 			 	= $_POST["KODE_TIPE_KLAIM"] 				 =="null" ? "" : $_POST["KODE_TIPE_KLAIM"];
$ls_nama_tipe_klaim 			 	= $_POST["NAMA_TIPE_KLAIM"]  				 =="null" ? "" : $_POST["NAMA_TIPE_KLAIM"];
$ls_jenis_klaim 					 	= $_POST["JENIS_KLAIM"]  						 =="null" ? "" : $_POST["JENIS_KLAIM"];
$ls_kode_sebab_klaim 			 	= $_POST["KODE_SEBAB_KLAIM"]  			 =="null" ? "" : $_POST["KODE_SEBAB_KLAIM"];
$ls_keyword_sebab_klaim 	 	= $_POST["KEYWORD_SEBAB_KLAIM"]  		 =="null" ? "" : $_POST["KEYWORD_SEBAB_KLAIM"];
$ls_nama_sebab_klaim 			 	= $_POST["NAMA_SEBAB_KLAIM"]  			 =="null" ? "" : $_POST["NAMA_SEBAB_KLAIM"];
$ld_tgl_lapor 						 	= $_POST["TGL_LAPOR"]  							 =="null" ? "" : $_POST["TGL_LAPOR"];
$ld_tgl_kejadian 					 	= $_POST["TGL_KEJADIAN"]  					 =="null" ? "" : $_POST["TGL_KEJADIAN"];
$ls_kpj 									 	= $_POST["KPJ"]  										 =="null" ? "" : $_POST["KPJ"];
$ls_nama_tk 							 	= $_POST["NAMA_TK"]  								 =="null" ? "" : $_POST["NAMA_TK"];
$ls_kode_tk 							 	= $_POST["KODE_TK"]  								 =="null" ? "" : $_POST["KODE_TK"];
$ls_nomor_identitas 			 	= $_POST["NOMOR_IDENTITAS"]  				 =="null" ? "" : $_POST["NOMOR_IDENTITAS"];
$ls_no_proyek 						  = $_POST["NO_PROYEK"]  							 =="null" ? "" : $_POST["NO_PROYEK"];
$ls_nama_proyek 					  = $_POST["NAMA_PROYEK"]  						 =="null" ? "" : $_POST["NAMA_PROYEK"];
$ls_kode_proyek 					  = $_POST["KODE_PROYEK"]  						 =="null" ? "" : $_POST["KODE_PROYEK"];
$ls_nama_pelaksana_kegiatan = $_POST["NAMA_PELAKSANA_KEGIATAN"]  =="null" ? "" : $_POST["NAMA_PELAKSANA_KEGIATAN"];
$ls_tipe_pelaksana_kegiatan = $_POST["TIPE_PELAKSANA_KEGIATAN"]  =="null" ? "" : $_POST["TIPE_PELAKSANA_KEGIATAN"];
$ls_nama_kegiatan 					= $_POST["NAMA_KEGIATAN"]  					 =="null" ? "" : $_POST["NAMA_KEGIATAN"];
$ls_npp 										= $_POST["NPP"]  										 =="null" ? "" : $_POST["NPP"];
$ls_nama_perusahaan 				= $_POST["NAMA_PERUSAHAAN"]  				 =="null" ? "" : $_POST["NAMA_PERUSAHAAN"];
$ls_kode_perusahaan 				= $_POST["KODE_PERUSAHAAN"]  				 =="null" ? "" : $_POST["KODE_PERUSAHAAN"];
$ls_kode_divisi 						= $_POST["KODE_DIVISI"]  						 =="null" ? "" : $_POST["KODE_DIVISI"];
$ls_nama_divisi 						= $_POST["NAMA_DIVISI"]  						 =="null" ? "" : $_POST["NAMA_DIVISI"];
$ls_keterangan 							= $_POST["KETERANGAN"]  						 =="null" ? "" : $_POST["KETERANGAN"];
$ls_kode_pointer_asal 			= $_POST["KODE_POINTER_ASAL"]  			 =="null" ? "" : $_POST["KODE_POINTER_ASAL"];
$ls_jenis_kelamin 					= $_POST["JENIS_KELAMIN"]  					 =="null" ? "" : $_POST["JENIS_KELAMIN"];	
$ls_nama_jenis_kelamin 			= $ls_jenis_kelamin  								 =="L" ? "LAKI-LAKI" : ($ls_jenis_kelamin=="P" ? "PEREMPUAN" : "");
$ls_tempat_lahir 						= $_POST["TEMPAT_LAHIR"]  					 =="null" ? "" : $_POST["TEMPAT_LAHIR"];	
$ld_tgl_lahir 							= $_POST["TGL_LAHIR"]  							 =="null" ? "" : $_POST["TGL_LAHIR"];
$ls_nama_ibu_kandung 				= $_POST["NAMA_IBU_KANDUNG"]  			 =="null" ? "" : $_POST["NAMA_IBU_KANDUNG"];
$ls_alamat_domisili 				= $_POST["ALAMAT_DOMISILI"]  				 =="null" ? "" : $_POST["ALAMAT_DOMISILI"];
$ls_kode_kantor_tk 					= $_POST["KODE_KANTOR_TK"]  				 =="null" ? "" : $_POST["KODE_KANTOR_TK"];
$ls_kode_kepesertaan 				= $_POST["KODE_KEPESERTAAN"]  			 =="null" ? "" : $_POST["KODE_KEPESERTAAN"];
$ln_no_mutasi_tk 						= $_POST["NO_MUTASI_TK"]  					 =="null" ? "" : $_POST["NO_MUTASI_TK"];
$ld_tgl_kepesertaan 				= $_POST["TGL_KEPESERTAAN"]  				 =="null" ? "" : $_POST["TGL_KEPESERTAAN"];
$ls_ket_masa_perlindungan 	= $_POST["KET_MASA_PERLINDUNGAN"]  	 =="null" ? "" : $_POST["KET_MASA_PERLINDUNGAN"];
$ls_kode_na 								= $_POST["KODE_NA"]  								 =="null" ? "" : $_POST["KODE_NA"];						
$ls_kode_perlindungan 			= $_POST["KODE_PERLINDUNGAN"]  			 =="null" ? "" : $_POST["KODE_PERLINDUNGAN"];
$ls_nama_perlindungan 			= $ls_kode_perlindungan  						 =="PRA" ? "SEBELUM BEKERJA" : ($ls_kode_perlindungan=="ONSITE" ? "SELAMA BEKERJA" : ($ls_kode_perlindungan=="PURNA" ? "SETELAH BEKERJA" : ""));
$ld_tgl_efektif 						= $_POST["TGL_EFEKTIF"]  						 =="null" ? "" : $_POST["TGL_EFEKTIF"];
$ld_tgl_expired 						= $_POST["TGL_EXPIRED"]  						 =="null" ? "" : $_POST["TGL_EXPIRED"];
$ld_tgl_grace 							= $_POST["TGL_GRACE"]  							 =="null" ? "" : $_POST["TGL_GRACE"];	
$ls_negara_penempatan 			= $_POST["NEGARA_PENEMPATAN"]  			 =="null" ? "" : $_POST["NEGARA_PENEMPATAN"];
$ld_tgl_klaim 							= $_POST["TGL_KLAIM"]  							 =="null" ? "" : $_POST["TGL_KLAIM"];
$ls_status_klaim 						= $_POST["STATUS_KLAIM"]  					 =="null" ? "" : $_POST["STATUS_KLAIM"];
$ls_status_kelayakan 				= $_POST["STATUS_KELAYAKAN"]  			 =="null" ? "" : $_POST["STATUS_KELAYAKAN"];	
$ls_status_submit_agenda 		= $_POST["STATUS_SUBMIT_AGENDA"]  	 =="null" ? "" : $_POST["STATUS_SUBMIT_AGENDA"];
$ls_status_submit_penetapan = $_POST["STATUS_SUBMIT_PENETAPAN"]  =="null" ? "" : $_POST["STATUS_SUBMIT_PENETAPAN"];
$ld_tgl_aktif 							= $_POST["TGL_AKTIF"]  							 =="null" ? "" : $_POST["TGL_AKTIF"];
$ld_tgl_na 									= $_POST["TGL_NA"]  								 =="null" ? "" : $_POST["TGL_NA"];
$ls_status_kepesertaan 			= $_POST["STATUS_KEPESERTAAN"]  		 =="null" ? "" : $_POST["STATUS_KEPESERTAAN"];
$ld_tgl_awal_perlindungan 	= $_POST["TGL_AWAL_PERLINDUNGAN"]  	 =="null" ? "" : $_POST["TGL_AWAL_PERLINDUNGAN"];
$ld_tgl_akhir_perlindungan 	= $_POST["TGL_AKHIR_PERLINDUNGAN"]   =="null" ? "" : $_POST["TGL_AKHIR_PERLINDUNGAN"];
$ls_flag_meninggal 					= $_POST["FLAG_MENINGGAL"]  				 =="null" ? "" : $_POST["FLAG_MENINGGAL"];
$ls_flag_partial 						= $_POST["FLAG_PARTIAL"]  					 =="null" ? "" : $_POST["FLAG_PARTIAL"];
$ls_flag_agenda_12 					= $_POST["FLAG_AGENDA_12"]  				 =="null" ? "" : $_POST["FLAG_AGENDA_12"];
$ls_jenis_identitas 				= $_POST["JENIS_IDENTITAS"]  				 =="null" ? "" : $_POST["JENIS_IDENTITAS"];
$ls_id_pointer_asal 				= $_POST["ID_POINTER_ASAL"]  				 =="null" ? "" : $_POST["ID_POINTER_ASAL"];
$ls_no_penetapan 						= $_POST["NO_PENETAPAN"]  					 =="null" ? "" : $_POST["NO_PENETAPAN"];
$ld_tgl_penetapan 					= $_POST["TGL_PENETAPAN"]   				 =="null" ? "" : $_POST["TGL_PENETAPAN"];
$ld_tgl_rekam								= $_POST["TGL_REKAM"]   				 		 =="null" ? "" : $_POST["TGL_REKAM"];
$ls_petugas_rekam						= $_POST["PETUGAS_REKAM"]   				 =="null" ? "" : $_POST["PETUGAS_REKAM"];
$ls_flag_rtw  					  	= $_POST["FLAG_RTW"]   				 =="null" ? "" : $_POST["FLAG_RTW"];
//end get data from ajax -------------------------------------------------------
?>

<div class="div-row">
	<div class="div-col" style="width:49%; max-height: 100%;">
    <div class="div-row">
      <div class="div-col" style="width: 100%">
        <fieldset style="min-height:422px;"><legend>Informasi Klaim</legend>
          <div class="form-row_kiri">
          <label style = "text-align:right;">Kantor &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; *</label>
            <input type="text" value="<?=$ls_kode_kantor;?>" id="kode_kantor" name="kode_kantor" readonly class="disabled" style="width:55px;">
            <input type="text" value="<?=$ls_nama_kantor;?>" id="nama_kantor" name="nama_kantor" readonly class="disabled" style="width:225px;">																				 								
          </div>
					<div class="clear"></div>
											  
          <div class="form-row_kiri">
          <label style = "text-align:right;">Segmentasi Keps &nbsp; *</label>
            <input type="text" value="<?=$ls_kode_segmen;?>" id="kode_segmen" name="kode_segmen" readonly class="disabled" style="width:55px;">
            <input type="text" value="<?=$ls_nama_segmen;?>" id="nama_segmen" name="nama_segmen" readonly class="disabled" style="width:225px;">
          </div>
          <div class="clear"></div>
											  
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Tipe Klaim &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
            <input type="text" value="<?=$ls_kode_tipe_klaim;?>" id="kode_tipe_klaim" name="kode_tipe_klaim" style="width:55px;" readonly class="disabled">
            <input type="text" value="<?=$ls_nama_tipe_klaim;?>" id="nama_tipe_klaim" name="nama_tipe_klaim" style="width:205px;" readonly class="disabled">						
            <input type="hidden" value="<?=$ls_jenis_klaim;?>" id="jenis_klaim" name="jenis_klaim">
          </div>																																																					
          <div class="clear"></div>
					
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Sebab Klaim &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
            <input type="text" value="<?=$ls_kode_sebab_klaim;?>" id="kode_sebab_klaim" name="kode_sebab_klaim" style="width:55px;" readonly class="disabled">
            <input type="text" value="<?=$ls_keyword_sebab_klaim;?>" id="keyword_sebab_klaim" name="keyword_sebab_klaim" style="width:30px;" readonly class="disabled">
            <input type="text" value="<?=$ls_nama_sebab_klaim;?>" id="nama_sebab_klaim" name="nama_sebab_klaim" style="width:150px;" readonly class="disabled">	
          </div>																																																						
          <div class="clear"></div>		
											
          <div class="form-row_kiri">
          <label style = "text-align:right;">Tgl Lapor *</label>
            <input type="text" value="<?=$ld_tgl_lapor;?>" id="tgl_lapor" name="tgl_lapor" onblur="convert_date(tgl_lapor);" style="width:220px;" readonly class="disabled">
          </div>	
          <div class="clear"></div>
											
          <span id="span_tgl_kejadian" style="display:none;">
            <div class="form-row_kiri">
            <label style = "text-align:right;">Tgl Kejadian *</label>	 
              <input type="text" value="<?=$ld_tgl_kejadian;?>" id="tgl_kejadian" name="tgl_kejadian" onblur="convert_date(tgl_kejadian);" style="width:220px;" readonly class="disabled">
            </div>																																																								
            <div class="clear"></div>																																																	
          </span>
          </br>
											          
          <span id="span_kpj" style="display:block;">						
            <div class="form-row_kiri">
            <label  style = "text-align:right;">No Referensi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
              <input type="text" value="<?=$ls_kpj;?>" id="kpj" name="kpj" style="width:80px;" readonly class="disabled">
              <input type="text" value="<?=$ls_nama_tk;?>" id="nama_tk" name="nama_tk" style="width:180px;" readonly class="disabled">
              <input type="hidden" value="<?=$ls_kode_tk;?>" id="kode_tk" name="kode_tk">
            </div>																																											
            <div class="clear"></div>
            <div class="form-row_kiri">
            <label  style = "text-align:right;">NIK &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            	<input type="text" value="<?=$ls_nomor_identitas;?>" id="nomor_identitas" name="nomor_identitas" style="width:290px;" readonly class="disabled">
            </div>																																										
            <div class="clear"></div>									
          </span>
					
          <span id="span_proyek" style="display:none;">						
            <div class="form-row_kiri">
            <label  style = "text-align:right;">No Proyek &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
              <input type="text" value="<?=$ls_no_proyek;?>" id="no_proyek" name="no_proyek" style="width:85px;" readonly class="disabled">
              <input type="text" value="<?=$ls_nama_proyek;?>" id="nama_proyek" name="nama_proyek" style="width:175px;" readonly class="disabled">
              <input type="hidden" value="<?=$ls_kode_proyek;?>" id="kode_proyek" name="kode_proyek">			
            </div>																																										
            <div class="clear"></div>						
          </span>
						
          <span id="span_kegiatan_tambahan" style="display:none;">						
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Pelaksana Kegiatan &nbsp;</label>
              <input type="text" value="<?=$ls_nama_pelaksana_kegiatan;?>" id="nama_pelaksana_kegiatan" name="nama_pelaksana_kegiatan" style="width:290px;" class="disabled">
              <input type="hidden" value="<?=$ls_tipe_pelaksana_kegiatan;?>" id="tipe_pelaksana_kegiatan" name="tipe_pelaksana_kegiatan">
            </div>																																									
            <div class="clear"></div>	
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Kegiatan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
            	<input type="text" value="<?=$ls_nama_kegiatan;?>" id="nama_kegiatan" name="nama_kegiatan" style="width:290px;" readonly class="disabled">			
            </div>																																									
            <div class="clear"></div>
          </span>
		
          <div class="form-row_kiri">
          <label  style = "text-align:right;">NPP &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </label>
            <input type="text" value="<?=$ls_npp;?>" id="npp" name="npp" style="width:80px;" readonly class="disabled">
            <input type="text" value="<?=$ls_nama_perusahaan;?>" id="nama_perusahaan" name="nama_perusahaan" style="width:180px;" readonly class="disabled">
            <input type="hidden" value="<?=$ls_kode_perusahaan;?>" id="kode_perusahaan" name="kode_perusahaan">				
          </div>																	
          <div class="clear"></div>
					
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Unit &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </label>
            <input type="text" value="<?=$ls_kode_divisi;?>" id="kode_divisi" name="kode_divisi" style="width:60px;" readonly class="disabled">
            <input type="text" value="<?=$ls_nama_divisi;?>" id="nama_divisi" name="nama_divisi" style="width:180px;" readonly class="disabled">				
          </div>																													
          <div class="clear"></div>
					
          <div class="form-row_kiri">
          <label style = "text-align:right;">Keterangan&nbsp;</label>
          	<textarea cols="255" rows="2" id="keterangan" name="keterangan" tabindex="31" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" style="width:280px;background-color:#F5F5F5" readonly><?=$ls_keterangan;?></textarea>   					
          </div>																																																					
          <div class="clear"></div> 
					        
          <div class="form-row_kiri">
          <label  style = "text-align:right;">&nbsp;</label>											
            <input type="checkbox" id="cb_flag_spo" name="cb_flag_spo" class="cebox" disabled <?=$ls_kode_pointer_asal=="SPO" ? "checked" : "";?>>
            <i><font color="#009999"><b>SPO</b></font></i>	
          </div>																																																						
          <div class="clear"></div>	
          
          <div class="form-row_kiri">
          <label  style = "text-align:right;">&nbsp;</label>											
            <input type="checkbox" id="cb_flag_rtw" name="cb_flag_rtw" class="cebox" disabled <?=$ls_flag_rtw=="Y" ? "checked" : "";?>>
            <i><font color="#009999"><b>RTW</b></font></i>	
          </div>																																																						
          <div class="clear"></div>	

        </fieldset>
      </div>
    </div>								
  </div>
  
	<div class="div-col" style="width:1%;">
  </div>
  
	<div class="div-col-right" style="width:50%;">
    <div class="div-row">
      <div class="div-col" style="width: 100%">
        <fieldset><legend>Informasi Tenaga Kerja</legend>
          <div class="div-row">
            <div class="div-col" style="width: 110px">
            <?php if ($ls_kode_pointer_asal =="JMO") { ?>
              <img id="tk_foto" onclick="showfotojmo();" src="../../mod_pn/ajax/pngetfotojmo.php?dataid=<?=$ls_kode_klaim;?>" style="height: 98px !important; width: 95px !important;  border-radius: 6%; border: 1px solid #DBDBDB!important;"/>
            <?php } else { ?>  
							<img id="tk_foto" src="../../mod_kn/ajax/kngetfoto.php?dataid=<?=$ls_nomor_identitas;?>" style="height: 98px !important; width: 95px !important;  border-radius: 6%; border: 1px solid #DBDBDB!important;"/>
            <?php } ?>  
							<!--	 
              <input id="tk_foto" name="tk_foto" type="image" align="center" src="../../images/nopic.png" style="height: 98px !important; width: 95px !important; border-radius: 6%; border: 1px solid #DBDBDB!important;"/>
            	-->
						</div>
            <div class="div-col">						
          		<div class="form-row_kiri">
              <label>Jenis Kelamin</label>
                <input type="text" value="<?=$ls_nama_jenis_kelamin;?>" id="nama_jenis_kelamin" name="nama_jenis_kelamin" style="width:220px;" readonly class="disabled">
                <input type="hidden" value="<?=$ls_jenis_kelamin;?>" id="jenis_kelamin" name="jenis_kelamin">
              </div>																																									
              <div class="clear"></div> 
							    
              <div class="form-row_kiri">
              <label>Tempat & Tgl Lahir</label>
                <input type="text" value="<?=$ls_tempat_lahir;?>" id="tempat_lahir" name="tempat_lahir" style="width:146px;" readonly class="disabled">
                <input type="text" value="<?=$ld_tgl_lahir;?>" id="tgl_lahir" name="tgl_lahir" style="width:65px;" readonly class="disabled">
              </div>																																									
              <div class="clear"></div>      
							      
              <div class="form-row_kiri">
              <label>Nama Ibu Kandung</label>
              	<input type="text" value="<?=$ls_nama_ibu_kandung;?>" id="nama_ibu_kandung" name="nama_ibu_kandung" style="width:220px;" readonly class="disabled">
              </div>																																									
              <div class="clear"></div>            
              <div class="form-row_kiri">
              <label>Alamat</label>
                <textarea cols="255" rows="2" id="alamat_domisili" name="alamat_domisili" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" style="width:220px;height:25px;background-color:#F5F5F5" ><?=$ls_alamat_domisili;?></textarea>
                <input type="hidden" id="handphone" name="handphone" style="width:170px;" readonly class="disabled">
                <input type="hidden" id="email" name="email" style="width:190px;" readonly class="disabled">
              </div>																																									
              <div class="clear"></div>
            </div>
          </div>					
        </fieldset>
      </div>									
    </div>
    
		<div class="div-row">
      <div class="div-col" style="width: 100%">
        <fieldset style="min-height:83px;"><legend>Informasi Kepesertaan</legend>                                
          <span id="span_infokeps_pu" style="display:block;">
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Kode Kepesertaan &nbsp;</label>
              <input type="text" value="<?=$ls_kode_kantor_tk;?>" id="pu_kode_kantor_tk" name="pu_kode_kantor_tk" style="width:30px;" readonly class="disabled">
              <input type="text" value="<?=$ls_kode_kepesertaan;?>" id="pu_kode_kepesertaan" name="pu_kode_kepesertaan" style="width:183px;" readonly class="disabled">
              <input type="text" value="<?=$ln_no_mutasi_tk;?>" id="pu_no_mutasi_tk" name="pu_no_mutasi_tk" style="width:30px;" readonly class="disabled">
            </div>																																									
            <div class="clear"></div>
												       
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Awal Kepesertaan &nbsp;</label>
            	<input type="text" value="<?=$ld_tgl_kepesertaan;?>" id="pu_tgl_kepesertaan" name="pu_tgl_kepesertaan" style="width:260px;" readonly class="disabled">
            </div>																																									
            <div class="clear"></div>	           
            
						<div class="form-row_kiri">
            <label  style = "text-align:right;">Periode Aktif&nbsp;&nbsp;</label>
              <input type="text" value="<?=$ls_ket_masa_perlindungan;?>" id="pu_ket_masa_perlindungan" name="pu_ket_masa_perlindungan" style="width:210px;" readonly class="disabled">
              <input type="text" value="<?=$ls_kode_na;?>" id="pu_kode_na" name="pu_kode_na" style="width:30px;" readonly class="disabled">
            </div>																																									
            <div class="clear"></div>								
          </span>
  
          <span id="span_infokeps_bpu" style="display:none;">
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Tgl Efektif &nbsp;</label>
            	<input type="text" value="<?=$ld_tgl_efektif;?>" id="bpu_tgl_efektif" name="bpu_tgl_efektif" style="width:260px;" readonly class="disabled">
            </div>																																									
            <div class="clear"></div>	 
						        
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Tgl Expired &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            	<input type="text" value="<?=$ld_tgl_expired;?>" id="bpu_tgl_expired" name="bpu_tgl_expired" style="width:260px;" readonly class="disabled">
            </div>																																									
            <div class="clear"></div>	
						       
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Tgl Grace/NA &nbsp;&nbsp;</label>
            	<input type="text" value="<?=$ld_tgl_grace;?>" id="bpu_tgl_grace" name="bpu_tgl_grace" style="width:240px;" readonly class="disabled">
            </div>																																									
            <div class="clear"></div>								
          </span>
					          
          <span id="span_infokeps_tki" style="display:none;">
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Negara Penempatan &nbsp;</label>
            	<input type="text" value="<?=$ls_negara_penempatan;?>" id="tki_negara_penempatan" name="tki_negara_penempatan" style="width:200px;" readonly class="disabled">
            </div>																																									
            <div class="clear"></div>	 
						    
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Perlindungan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            	<input type="text" value="<?=$ls_nama_perlindungan;?>" id="tki_nama_perlindungan" name="tki_nama_perlindungan" style="width:200px;" readonly class="disabled">
            </div>																																									
            <div class="clear"></div>	
						         
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Periode &nbsp;&nbsp;</label>
            	<input type="text" value="<?=$ls_ket_masa_perlindungan;?>" id="tki_ket_masa_perlindungan" name="tki_ket_masa_perlindungan" style="width:200px;" readonly class="disabled">
            </div>																																									
            <div class="clear"></div>								
          </span>
					        
          <span id="span_infokeps_jakon" style="display:none;">
            <br/>
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Masa Perlindungan &nbsp;&nbsp;</label>
            	<input type="text" value="<?=$ls_ket_masa_perlindungan;?>" id="jakon_ket_masa_perlindungan" name="jakon_ket_masa_perlindungan" style="width:200px;" readonly class="disabled">
            </div>																																									
            <div class="clear"></div>								
          </span>	
        </fieldset>
      </div>									
    </div>
					  
    <div class="div-row">
      <div class="div-col" style="width: 100%">
        <fieldset style="height:150px;"><legend>Status Klaim</legend>
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Kode Klaim</label>
            
            <?
						if ($ls_kode_form == "PN5069") //hanya utk di daftar klaim
						{
  						?>
  						<input type="text" value="<?=$ls_kode_klaim;?>" id="kode_klaim_display" name="kode_klaim_display" style="width:175px;" readonly class="disabled">
							<a href="#" onClick="fl_js_callform_switch_kodeklaim();" title="Klik Untuk Switch Kode Klaim"><img src="../../images/switcher.png" border="0" alt="entry" align="absmiddle" /></a>	
  						&nbsp;&nbsp;
							<?
						}else
						{
						 	?>
							<input type="text" value="<?=$ls_kode_klaim;?>" id="kode_klaim_display" name="kode_klaim_display" style="width:200px;" readonly class="disabled">
							<?	 
						}
						?>
						&nbsp;<a href="#" onClick="fl_js_show_daftarcekkelayakan();"><img src="../../images/indent_right.gif" border="0" alt="Tambah" align="absmiddle" />Kelayakan</a>
          </div>																																									
          <div class="clear"></div>
						        
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Tgl Klaim &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <input type="text" value="<?=$ld_tgl_klaim;?>" id="tgl_klaim" name="tgl_klaim" style="width:200px;" readonly class="disabled">
            &nbsp;<a href="#" onClick="fl_js_show_daftaraktivitas();"><img src="../../images/indent_right.gif" border="0" alt="Tambah" align="absmiddle" />Aktivitas</a>
          </div>																																								
          <div class="clear"></div>

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Tgl Rekam&nbsp;&nbsp;&nbsp;</label>
            <input type="text" value="<?=$ld_tgl_rekam;?>" id="tgl_rekam" name="tgl_rekam" style="width:61px;" readonly class="disabled">
            <input type="text" value="<?=$ls_petugas_rekam;?>" id="petugas_rekam" name="petugas_rekam" style="width:130px;" readonly class="disabled">
						&nbsp;<a href="#" onClick="fl_js_show_daftarcatatan();"><img src="../../images/indent_right.gif" border="0" alt="Tambah" align="absmiddle" />Catatan</a>
          </div>																																								
          <div class="clear"></div>
										
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Penetapan Klaim&nbsp;&nbsp;&nbsp;</label>
            <input type="text" value="<?=$ld_tgl_penetapan;?>" id="tgl_penetapan" name="tgl_penetapan" style="width:61px;" readonly class="disabled">
            <input type="text" value="<?=$ls_no_penetapan;?>" id="no_penetapan" name="no_penetapan" style="width:130px;" readonly class="disabled">
						&nbsp;<a href="#" onClick="fl_js_show_daftarapproval();"><img src="../../images/indent_right.gif" border="0" alt="Tambah" align="absmiddle" />Persetujuan</a>
          </div>																																								
          <div class="clear"></div>
										         
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Status Klaim &nbsp;&nbsp;</label>
            <input type="text" value="<?=$ls_status_klaim;?>" id="status_klaim" name="status_klaim" style="width:140px;" readonly class="disabled">					
            <input type="checkbox" id="cb_status_kelayakan" name="cb_status_kelayakan" class="cebox" disabled <?=$ls_status_kelayakan=="Y" ? "checked" : "";?>>
            <i><font color="#009999"><b>Layak</b></font></i>
						&nbsp;&nbsp;&nbsp;<a href="#" onClick="fl_js_show_cetak();"><img src="../../images/printer.png" border="0" alt="Tambah" align="absmiddle" />&nbsp;Cetak</a>
						
						<input type="hidden" value="<?=$ls_status_submit_agenda;?>" id="status_submit_agenda" name="status_submit_agenda">
          	<input type="hidden" value="<?=$ls_status_submit_penetapan;?>" id="status_submit_penetapan" name="status_submit_penetapan">
            <input type="hidden" value="<?=$ls_kode_kantor_tk;?>" id="kode_kantor_tk" name="kode_kantor_tk">
            <input type="hidden" value="<?=$ls_kode_kepesertaan;?>" id="kode_kepesertaan" name="kode_kepesertaan">
            <input type="hidden" value="<?=$ln_no_mutasi_tk;?>" id="no_mutasi_tk" name="no_mutasi_tk">
            <input type="hidden" value="<?=$ld_tgl_kepesertaan;?>" id="tgl_kepesertaan" name="tgl_kepesertaan">
            <input type="hidden" value="<?=$ld_tgl_aktif;?>" id="tgl_aktif" name="tgl_aktif">
            <input type="hidden" value="<?=$ld_tgl_efektif;?>" id="tgl_efektif" name="tgl_efektif">
            <input type="hidden" value="<?=$ld_tgl_expired;?>" id="tgl_expired" name="tgl_expired">
            <input type="hidden" value="<?=$ld_tgl_grace;?>" id="tgl_grace" name="tgl_grace">
            <input type="hidden" value="<?=$ld_tgl_na;?>" id="tgl_na" name="tgl_na">
            <input type="hidden" value="<?=$ls_kode_na;?>" id="kode_na" name="kode_na">
            <input type="hidden" value="<?=$ls_negara_penempatan;?>" id="negara_penempatan" name="negara_penempatan">
            <input type="hidden" value="<?=$ls_status_kepesertaan;?>" id="status_kepesertaan" name="status_kepesertaan">
            <input type="hidden" value="<?=$ls_kode_perlindungan;?>" id="kode_perlindungan" name="kode_perlindungan">
            <input type="hidden" value="<?=$ls_nama_perlindungan;?>" id="nama_perlindungan" name="nama_perlindungan">
            <input type="hidden" value="<?=$ls_ket_masa_perlindungan;?>" id="ket_masa_perlindungan" name="ket_masa_perlindungan">									
            <input type="hidden" value="<?=$ld_tgl_awal_perlindungan;?>" id="tgl_awal_perlindungan" name="tgl_awal_perlindungan">
            <input type="hidden" value="<?=$ld_tgl_akhir_perlindungan;?>" id="tgl_akhir_perlindungan" name="tgl_akhir_perlindungan">
            <input type="hidden" value="<?=$ls_flag_meninggal;?>" id="flag_meninggal" name="flag_meninggal">
            <input type="hidden" value="<?=$ls_flag_partial;?>" id="flag_partial" name="flag_partial">
            <input type="hidden" value="<?=$ls_flag_agenda_12;?>" id="flag_agenda_12" name="flag_agenda_12">
            <input type="hidden" value="<?=$ls_jenis_identitas;?>" id="jenis_identitas" name="jenis_identitas">
            <input type="hidden" id="status_valid_identitas" name="status_valid_identitas">
            <input type="hidden" value="<?=$ls_kode_pointer_asal;?>" id="kode_pointer_asal" name="kode_pointer_asal">
            <input type="hidden" value="<?=$ls_id_pointer_asal;?>" id="id_pointer_asal" name="id_pointer_asal">	
          </div>																																									
          <div class="clear"></div>									
        </fieldset>
      </div>									
    </div>
													
  </div>									
</div>	

<script language="javascript">				
	//function tambahan untuk informasi klaim ------------------------------------
  function fl_js_set_span_kpj_proyek() 
  { 
    var v_kode_pointer_asal = window.document.getElementById('kode_pointer_asal').value;
    var v_kode_segmen = window.document.getElementById('kode_segmen').value;
    
    if (v_kode_segmen =="JAKON")
    {
      window.document.getElementById("span_proyek").style.display = 'block';
      window.document.getElementById("span_kpj").style.display = 'none';
      window.document.getElementById("span_kegiatan_tambahan").style.display = 'none';
      window.document.getElementById("kpj").value = "";
      window.document.getElementById("kode_kantor_tk").value = "";
    }else
    {
      if (v_kode_pointer_asal !="" && v_kode_pointer_asal=="PROMOTIF" ) //data bersumber dari modul lain (promotif/preventif)
      {
        window.document.getElementById("span_proyek").style.display = 'none';
        window.document.getElementById("span_kpj").style.display = 'none';
        window.document.getElementById("span_kegiatan_tambahan").style.display = 'block';	
      }else
      {
        window.document.getElementById("span_proyek").style.display = 'none';
        window.document.getElementById("span_kpj").style.display = 'block';
        window.document.getElementById("span_kegiatan_tambahan").style.display = 'none';
        window.document.getElementById("kode_proyek").value = "";
        window.document.getElementById("no_proyek").value = "";
        window.document.getElementById("nama_proyek").value = "";						 				 		 
      }			 		 
    } 
		
		if (v_kode_segmen =="PU")
    {
		 	window.document.getElementById("span_infokeps_pu").style.display = 'block';
			window.document.getElementById("span_infokeps_bpu").style.display = 'none';
			window.document.getElementById("span_infokeps_tki").style.display = 'none';
			window.document.getElementById("span_infokeps_jakon").style.display = 'none'; 
		}else if (v_kode_segmen =="BPU")
		{
		 	window.document.getElementById("span_infokeps_pu").style.display = 'none';
			window.document.getElementById("span_infokeps_bpu").style.display = 'block';
			window.document.getElementById("span_infokeps_tki").style.display = 'none';
			window.document.getElementById("span_infokeps_jakon").style.display = 'none'; 
		}else if (v_kode_segmen =="TKI")
		{
		  window.document.getElementById("span_infokeps_pu").style.display = 'none';
			window.document.getElementById("span_infokeps_bpu").style.display = 'none';
			window.document.getElementById("span_infokeps_tki").style.display = 'block';
			window.document.getElementById("span_infokeps_jakon").style.display = 'none';
		}else if (v_kode_segmen =="JAKON")
		{
		  window.document.getElementById("span_infokeps_pu").style.display = 'none';
			window.document.getElementById("span_infokeps_bpu").style.display = 'none';
			window.document.getElementById("span_infokeps_tki").style.display = 'none';
			window.document.getElementById("span_infokeps_jakon").style.display = 'block';
		}else
		{
		  window.document.getElementById("span_infokeps_pu").style.display = 'block';
			window.document.getElementById("span_infokeps_bpu").style.display = 'none';
			window.document.getElementById("span_infokeps_tki").style.display = 'none';
			window.document.getElementById("span_infokeps_jakon").style.display = 'none';
		}
  }		

  function fl_js_set_span_tgl_kejadian() 
  {     			
    var v_jenis_klaim = window.document.getElementById('jenis_klaim').value;
    var v_flag_meninggal = window.document.getElementById('flag_meninggal').value;
    
    //tgl kejadian hanya bisa diisi hanya utk klaim JKK/JKM/JHM/Meninggal ------
    if (v_jenis_klaim == "JKK" || v_jenis_klaim == "JKM" || v_jenis_klaim == "JHM" || v_flag_meninggal == "Y")
    {
     	window.document.getElementById("span_tgl_kejadian").style.display = 'block';
    }else
    {
     	window.document.getElementById("span_tgl_kejadian").style.display = 'none';	 
    }	
  }
	
	function fl_js_show_daftarcekkelayakan()
	{		 
	 	var c_kode_klaim = window.document.getElementById('kode_klaim_display').value;
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_daftarcekkelayakan.php?&kode_klaim='+c_kode_klaim+'&mid='+c_mid+'','',980,550,'yes');
	}

	function fl_js_show_daftaraktivitas()
	{		 
	 	var c_kode_klaim = window.document.getElementById('kode_klaim_display').value;
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_daftaraktivitas.php?&kode_klaim='+c_kode_klaim+'&mid='+c_mid+'','',980,550,'yes');
	}

	function fl_js_show_daftarcatatan()
	{		 
	 	var c_kode_klaim = window.document.getElementById('kode_klaim_display').value;
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_daftarcatatan.php?&kode_klaim='+c_kode_klaim+'&mid='+c_mid+'','',980,550,'yes');
	}
		
	function fl_js_show_daftarapproval()
	{		 
	 	var c_kode_klaim = window.document.getElementById('kode_klaim_display').value;
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_daftarapproval.php?&kode_klaim='+c_kode_klaim+'&mid='+c_mid+'','',980,550,'yes');
	}

	function fl_js_show_cetak()
	{		 
	 	var c_kode_klaim = window.document.getElementById('kode_klaim_display').value;
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5001_cetak.php?&kode_klaim='+c_kode_klaim+'&mid='+c_mid+'','',980,550,'yes');
	}
  function showfotojmo()
	{
    	window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pngetfotojmo.php?dataid=<?=$ls_kode_klaim;?>');
  }
	
	$(document).ready(function(){
		fl_js_set_span_kpj_proyek();
		fl_js_set_span_tgl_kejadian();
	});		
	//end function tambahan untuk informasi klaim --------------------------------	
</script>
