<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
/* =============================================================================
Ket : Form ini digunakan untuk tab Input Agenda Klaim JHT
Hist: - 20/07/2017 : Pembuatan Form (Tim SIJSTK)			 
			- 22/10/2019 : Sentralisasi Rekening dan Mengalihkan direct conn ke ws
									 	 ws JSPN5040/GetDataByKodeKlaim DATA_INPUTJHT			 
------------------------------------------------------------------------------*/

//get data input jht -----------------------------------------------------------
$ls_jhtinput_kode_manfaat 	 				 		= $_POST["KODE_MANFAAT"]  						 	=="null" ? "" : $_POST["KODE_MANFAAT"];
$ls_jhtinput_no_urut 	 				 					= $_POST["NO_URUT_MANFAAT"]  						=="null" ? "" : $_POST["NO_URUT_MANFAAT"];
$ld_jhtinput_tgl_saldo_awaltahun 				= $_POST["TGL_SALDO_AWALTAHUN"]  				=="null" ? "" : $_POST["TGL_SALDO_AWALTAHUN"];
$ln_jhtinput_nom_saldo_awaltahun 	 			= $_POST["NOM_SALDO_AWALTAHUN"]  				=="null" ? "" : $_POST["NOM_SALDO_AWALTAHUN"];
$ld_jhtinput_tgl_pengembangan_estimasi 	= $_POST["TGL_PENGEMBANGAN_ESTIMASI"]  	=="null" ? "" : $_POST["TGL_PENGEMBANGAN_ESTIMASI"];
$ln_jhtinput_nom_pengembangan_estimasi 	= $_POST["NOM_PENGEMBANGAN_ESTIMASI"]  	=="null" ? "" : $_POST["NOM_PENGEMBANGAN_ESTIMASI"];
$ln_jhtinput_nom_manfaat_sudahdiambil 	= $_POST["NOM_MANFAAT_SUDAHDIAMBIL"]  	=="null" ? "" : $_POST["NOM_MANFAAT_SUDAHDIAMBIL"];	
$ln_jhtinput_pengambilan_ke 						= $_POST["PENGAMBILAN_KE"]  						=="null" ? "" : $_POST["PENGAMBILAN_KE"];
$ls_jhtinput_kode_tipe_penerima 				= $_POST["KODE_TIPE_PENERIMA"]  				=="null" ? "" : $_POST["KODE_TIPE_PENERIMA"];
$ls_jhtinput_nama_tipe_penerima 				= $_POST["NAMA_TIPE_PENERIMA"]					=="null" ? "" : $_POST["NAMA_TIPE_PENERIMA"];
$ls_jhtinput_kode_hubungan 							= $_POST["KODE_HUBUNGAN"]  						 	=="null" ? "" : $_POST["KODE_HUBUNGAN"];
$ls_jhtinput_nama_hubungan 							= $_POST["NAMA_HUBUNGAN"]								=="null" ? "" : $_POST["NAMA_HUBUNGAN"];
$ls_jhtinput_ket_hubungan_lainnya				= $_POST["KET_HUBUNGAN_LAINNYA"]  			=="null" ? "" : $_POST["KET_HUBUNGAN_LAINNYA"];
$ls_jhtinput_no_urut_keluarga 					= $_POST["NO_URUT_KELUARGA"]  					=="null" ? "" : $_POST["NO_URUT_KELUARGA"];
$ls_jhtinput_nomor_identitas 						= $_POST["NOMOR_IDENTITAS"]  						=="null" ? "" : $_POST["NOMOR_IDENTITAS"];
$ls_jhtinput_nama_pemohon 							= $_POST["NAMA_PEMOHON"]  						 	=="null" ? "" : $_POST["NAMA_PEMOHON"];
$ls_jhtinput_tempat_lahir 							= $_POST["TEMPAT_LAHIR"]  						 	=="null" ? "" : $_POST["TEMPAT_LAHIR"];
$ld_jhtinput_tgl_lahir 									= $_POST["TGL_LAHIR"]  						 			=="null" ? "" : $_POST["TGL_LAHIR"];
$ls_jhtinput_jenis_kelamin 							= $_POST["JENIS_KELAMIN"]  						 	=="null" ? "" : $_POST["JENIS_KELAMIN"];
$ls_jhtinput_golongan_darah							= $_POST["GOLONGAN_DARAH"]  						=="null" ? "" : $_POST["GOLONGAN_DARAH"];
$ls_jhtinput_alamat 										= $_POST["ALAMAT"]  						 				=="null" ? "" : $_POST["ALAMAT"];
$ls_jhtinput_rt 												= $_POST["RT"]  						 						=="null" ? "" : $_POST["RT"];
$ls_jhtinput_rw 												= $_POST["RW"]  						 						=="null" ? "" : $_POST["RW"];
$ls_jhtinput_kode_kelurahan 						= $_POST["KODE_KELURAHAN"]  						=="null" ? "" : $_POST["KODE_KELURAHAN"];
$ls_jhtinput_nama_kelurahan 						= $_POST["NAMA_KELURAHAN"]  						=="null" ? "" : $_POST["NAMA_KELURAHAN"];
$ls_jhtinput_kode_kecamatan 						= $_POST["KODE_KECAMATAN"]  						=="null" ? "" : $_POST["KODE_KECAMATAN"];
$ls_jhtinput_nama_kecamatan 						= $_POST["NAMA_KECAMATAN"]  						=="null" ? "" : $_POST["NAMA_KECAMATAN"];
$ls_jhtinput_kode_kabupaten 						= $_POST["KODE_KABUPATEN"]  						=="null" ? "" : $_POST["KODE_KABUPATEN"];
$ls_jhtinput_nama_kabupaten 						= $_POST["NAMA_KABUPATEN"]  						=="null" ? "" : $_POST["NAMA_KABUPATEN"];
$ls_jhtinput_kode_pos 									= $_POST["KODE_POS"]  						 			=="null" ? "" : $_POST["KODE_POS"];
$ls_jhtinput_telepon_area 							= $_POST["TELEPON_AREA"]  						 	=="null" ? "" : $_POST["TELEPON_AREA"];
$ls_jhtinput_telepon 										= $_POST["TELEPON"]  						 				=="null" ? "" : $_POST["TELEPON"];
$ls_jhtinput_telepon_ext 								= $_POST["TELEPON_EXT"]  						 		=="null" ? "" : $_POST["TELEPON_EXT"];
$ls_jhtinput_handphone 									= $_POST["HANDPHONE"]  						 			=="null" ? "" : $_POST["HANDPHONE"];
$ls_jhtinput_email 											= $_POST["EMAIL"]  						 					=="null" ? "" : $_POST["EMAIL"];
$ls_jhtinput_npwp 											= $_POST["NPWP"]  						 					=="null" ? "" : $_POST["NPWP"];

if ($ls_jhtinput_jenis_kelamin == "L")
{
 	$ls_jhtinput_nama_jenis_kelamin = "LAKI-LAKI";   	 
}else if ($ls_jhtinput_jenis_kelamin == "P")
{
 	$ls_jhtinput_nama_jenis_kelamin = "PEREMPUAN";   	 
}else
{
 	$ls_jhtinput_nama_jenis_kelamin = "";   	 
}
//end get data input jht -------------------------------------------------------
?>

<div class="div-row">
  <div class="div-col" style="width:49%; max-height: 100%;">
    <div class="div-row">
      <div class="div-col" style="width: 100%">
        <fieldset style="min-height:461px;"><legend>Saldo JHT</legend>
          <div class="form-row_kiri">
            <label  style = "text-align:right;">Saldo Awal Tahun &nbsp;</label>
            <input type="text" id="jhtinput_tgl_saldo_awaltahun" name="jhtinput_tgl_saldo_awaltahun" value="<?=$ld_jhtinput_tgl_saldo_awaltahun;?>" style="width:80px;" readonly class="disabled">
            <input type="text" id="jhtinput_nom_saldo_awaltahun" name="jhtinput_nom_saldo_awaltahun" value="<?=number_format((float)$ln_jhtinput_nom_saldo_awaltahun,2,".",",");?>" maxlength="20" readonly class="disabled" style="width:150px;text-align:right;">                					
          </div>																																																				
          <div class="clear"></div>

          <div class="form-row_kiri">
            <label  style = "text-align:right;">Saldo JHT Estimasi &nbsp;</label>
            <input type="text" id="jhtinput_tgl_pengembangan_estimasi" name="jhtinput_tgl_pengembangan_estimasi" value="<?=$ld_jhtinput_tgl_pengembangan_estimasi;?>" style="width:80px;" readonly class="disabled">
            <input type="text" id="jhtinput_nom_pengembangan_estimasi" name="jhtinput_nom_pengembangan_estimasi" value="<?=number_format((float)$ln_jhtinput_nom_pengembangan_estimasi,2,".",",");?>" maxlength="20" readonly class="disabled" style="width:150px;text-align:right;">                					
          </div>	
          <div class="clear"></div>
          
          <br></br>
          
          <div class="form-row_kiri">
            <label  style = "text-align:right;">Saldo JHT sdh Diambil</label>
            <input type="text" id="jhtinput_nom_manfaat_sudahdiambil" name="jhtinput_nom_manfaat_sudahdiambil" value="<?=number_format((float)$ln_jhtinput_nom_manfaat_sudahdiambil,2,".",",");?>" maxlength="20" readonly class="disabled" style="width:220px;text-align:right;">                					
          </div>	
          <div class="clear"></div>

          <div class="form-row_kiri">
            <label  style = "text-align:right;">Pengambilan JHT ke- &nbsp;</label>
            <input type="text" id="jhtinput_pengambilan_ke" name="jhtinput_pengambilan_ke" value="<?=number_format((float)$ln_jhtinput_pengambilan_ke,0,".",",");?>" maxlength="20" readonly class="disabled" style="width:220px;text-align:right;">                					
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
        <fieldset>
          <legend>Penerima Manfaat</legend>
          <div class="form-row_kiri">
            <label  style = "text-align:right;">Tipe Penerima &nbsp;*</label>
						<input type="text" id="jhtinput_nama_tipe_penerima" name="jhtinput_nama_tipe_penerima" value = "<?=$ls_jhtinput_nama_tipe_penerima;?>" readonly class="disabled" style="width:270px;">
						<input type="hidden" id="jhtinput_kode_tipe_penerima" name="jhtinput_kode_tipe_penerima" value = "<?=$ls_jhtinput_kode_tipe_penerima;?>">
						<input type="hidden" id="jhtinput_kode_tipe_penerima_old" name="jhtinput_kode_tipe_penerima_old" value="<?=$ls_jhtinput_kode_tipe_penerima;?>">
            <input type="hidden" id="jhtinput_kode_manfaat" name="jhtinput_kode_manfaat" value="<?=$ls_jhtinput_kode_manfaat;?>">
            <input type="hidden" id="jhtinput_no_urut" name="jhtinput_no_urut" value="<?=$ls_jhtinput_no_urut;?>">
          </div>		    																																				
          <div class="clear"></div>
          
          <span id="span_jhtinput_kode_hubungan" style="display:none;">
            <div class="form-row_kiri">
              <label style = "text-align:right;">Ahli Waris *</label>
							<input type="text" id="jhtinput_nama_hubungan" name="jhtinput_nama_hubungan" value = "<?=$ls_jhtinput_nama_hubungan;?>" readonly class="disabled" style="width:270px;">
							<input type="hidden" id="jhtinput_kode_hubungan" name="jhtinput_kode_hubungan" value = "<?=$ls_jhtinput_kode_hubungan;?>">
            	<input type="hidden" id="jhtinput_no_urut_keluarga" name="jhtinput_no_urut_keluarga" value="<?=$ls_jhtinput_no_urut_keluarga;?>">        				
            </div>																																									
            <div class="clear"></div>
          </span>
      
          <span id="span_jhtinput_ket_hubungan_lainnya" style="display:none;">
            <div class="form-row_kiri">
              <label style = "text-align:right;">&nbsp; *</label>
							<input type="text" id="jhtinput_ket_hubungan_lainnya" name="jhtinput_ket_hubungan_lainnya" value="<?=$ls_jhtinput_ket_hubungan_lainnya;?>" readonly class="disabled" style="width:270px;">
						</div>																																									
            <div class="clear"></div>			
          </span>				
          
          <div class="form-row_kiri">
            <label style = "text-align:right;">Nama &nbsp;&nbsp;*</label>
            <input type="text" id="jhtinput_nama_pemohon" name="jhtinput_nama_pemohon" value="<?=$ls_jhtinput_nama_pemohon;?>" style="width:270px;" readonly class="disabled">
          </div>	
          <div class="clear"></div>

          <div class="form-row_kiri">
            <label style = "text-align:right;">Tempat & Tgl Lahir &nbsp; *</label>
						<input type="text" id="jhtinput_tempat_lahir" name="jhtinput_tempat_lahir" value="<?=$ls_jhtinput_tempat_lahir;?>" style="width:164px;" readonly class="disabled">
            <input type="text" id="jhtinput_tgl_lahir" name="jhtinput_tgl_lahir" value="<?=$ld_jhtinput_tgl_lahir;?>" style="width:82px;" readonly class="disabled">		
          </div>																													
          <div class="clear"></div>

          <div class="form-row_kiri">
            <label style = "text-align:right;">Jenis Kelamin *</label>
						<input type="text" id="jhtinput_nama_jenis_kelamin" name="jhtinput_nama_jenis_kelamin" value="<?=$ls_jhtinput_nama_jenis_kelamin;?>" style="width:100px;" readonly class="disabled">
						&nbsp;
            Gol. Darah
						<input type="text" id="jhtinput_golongan_darah" name="jhtinput_golongan_darah" value="<?=$ls_jhtinput_golongan_darah;?>" style="width:60px;" readonly class="disabled">
          </div>																																									
          <div class="clear"></div>
				
          </br>

          <div class="form-row_kiri">
            <label style = "text-align:right;">Alamat &nbsp;&nbsp;&nbsp;&nbsp;*</label>		 	    				
            <textarea cols="255" id="jhtinput_alamat" name="jhtinput_alamat" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" readonly class="disabled" style="width:239px;height:18px;background-color:#F5F5F5;"><?=$ls_jhtinput_alamat;?></textarea>
					</div>																																										
          <div class="clear"></div>

          <div class="form-row_kiri">
            <label style = "text-align:right;">RT/RW &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;</label>		 	    				
            <input type="text" id="jhtinput_rt" name="jhtinput_rt" value="<?=$ls_jhtinput_rt;?>" style="width:133px;" readonly class="disabled">
            /
            <input type="text" id="jhtinput_rw" name="jhtinput_rw" value="<?=$ls_jhtinput_rw;?>" style="width:80px;" readonly class="disabled">		
          </div>																																										
          <div class="clear"></div>

          <div class="form-row_kiri">
            <label style = "text-align:right;">Kelurahan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>		 	    				
            <input type="text" id="jhtinput_nama_kelurahan" name="jhtinput_nama_kelurahan" value="<?=$ls_jhtinput_nama_kelurahan;?>" style="width:239px;" readonly class="disabled">
            <input type="hidden" id="jhtinput_kode_kelurahan" name="jhtinput_kode_kelurahan" value="<?=$ls_jhtinput_kode_kelurahan;?>">      
          </div>																																																	
          <div class="clear"></div>
										       
          <div class="form-row_kiri">
            <label style = "text-align:right;">Kecamatan &nbsp;&nbsp;&nbsp;</label>		 	    				
            <input type="text" id="jhtinput_nama_kecamatan" name="jhtinput_nama_kecamatan" value="<?=$ls_jhtinput_nama_kecamatan;?>" style="width:239px;" readonly class="disabled">
            <input type="hidden" id="jhtinput_kode_kecamatan" name="jhtinput_kode_kecamatan" value="<?=$ls_jhtinput_kode_kecamatan;?>">			
          </div>
          <div class="clear"></div>
              
          <div class="form-row_kiri">
            <label style = "text-align:right;">Kabupaten &nbsp;</label>		 	    				
            <input type="text" id="jhtinput_nama_kabupaten" name="jhtinput_nama_kabupaten" value="<?=$ls_jhtinput_nama_kabupaten;?>" style="width:239px;" readonly class="disabled">
            <input type="hidden" id="jhtinput_kode_kabupaten" name="jhtinput_kode_kabupaten" value="<?=$ls_jhtinput_kode_kabupaten;?>">      
            <input type="hidden" id="jhtinput_kode_propinsi" name="jhtinput_kode_propinsi" value="<?=$ls_jhtinput_kode_propinsi;?>">
            <input type="hidden" id="jhtinput_nama_propinsi" name="jhtinput_nama_propinsi" value="<?=$ls_jhtinput_nama_propinsi;?>">
          </div>
          <div class="clear"></div>	
					
          <div class="form-row_kiri">
            <label style = "text-align:right;">Kode Pos &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label> 	    				
            <input type="text" id="jhtinput_kode_pos" name="jhtinput_kode_pos" value="<?=$ls_jhtinput_kode_pos;?>" style="width:206px;" readonly class="disabled">
          </div>																																																	
          <div class="clear"></div>
					                                                          
          </br>

          <div class="form-row_kiri">
            <label style = "text-align:right;">Email &nbsp;&nbsp;</label>		 	    				
            <input type="text" id="jhtinput_email" name="jhtinput_email" value="<?=$ls_jhtinput_email;?>" style="width:270px;" readonly class="disabled">
          </div>
          <div class="clear"></div>

          <div class="form-row_kiri">
            <label style = "text-align:right;">No. Telp &nbsp;</label>	    				
            <input type="text" id="jhtinput_telepon_area" name="jhtinput_telepon_area" value="<?=$ls_jhtinput_telepon_area;?>" style="width:40px;" readonly class="disabled">
            <input type="text" id="jhtinput_telepon" name="jhtinput_telepon" value="<?=$ls_jhtinput_telepon;?>" style="width:148px;" readonly class="disabled">
            &nbsp;ext.
            <input type="text" id="jhtinput_telepon_ext" name="jhtinput_telepon_ext" value="<?=$ls_jhtinput_telepon_ext;?>" style="width:40px;" readonly class="disabled">
          </div>
          <div class="clear"></div>					          
      
          <div class="form-row_kiri">
            <label style = "text-align:right;">Handphone &nbsp;&nbsp;</label>		 	    				
            <input type="text" id="jhtinput_handphone" name="jhtinput_handphone" value="<?=$ls_jhtinput_handphone;?>" style="width:270px;" readonly class="disabled">
          </div>																																																																																														
          <div class="clear"></div>
					
          <div class="form-row_kiri">
            <label style = "text-align:right;">No. Identitas &nbsp;</label>
            <input type="text" id="jhtinput_nomor_identitas" name="jhtinput_nomor_identitas" value="<?=$ls_jhtinput_nomor_identitas;?>" style="width:270px;" readonly class="disabled">
          </div>																																									
          <div class="clear"></div>      

          <div class="form-row_kiri">
            <label style = "text-align:right;">NPWP &nbsp; </label>
            <input type="text" id="jhtinput_npwp" name="jhtinput_npwp" value="<?=$ls_jhtinput_npwp;?>" style="width:240px;" readonly class="disabled">
          </div>																																									
          <div class="clear"></div>      
						
          <br></br>																					
        </fieldset>	
      </div>
    </div>
  </div>
</div>

<script language="JavaScript">    
  function fl_js_jhtinput_kode_tipe_penerima() 
  { 
		var	v_kode_tipe_penerima = window.document.getElementById('jhtinput_kode_tipe_penerima').value;
							
  	if (v_kode_tipe_penerima =="AW") //ahli waris ----------------------------
    {
      window.document.getElementById("span_jhtinput_kode_hubungan").style.display = 'block';	
    }else
    {
      window.document.getElementById("span_jhtinput_kode_hubungan").style.display = 'none';
			window.document.getElementById("span_jhtinput_ket_hubungan_lainnya").style.display = 'none';	
    }
  }
	
  function fl_js_jhtinput_ahliwaris_lain() 
  { 
		var	v_kode_tipe_penerima = window.document.getElementById('jhtinput_kode_tipe_penerima').value;
		var	v_kode_hubungan = window.document.getElementById('jhtinput_kode_hubungan').value;
    
    if (v_kode_tipe_penerima =="AW" && v_kode_hubungan=="L") //Ahli Waris Lainnya ---------
    {
		 	window.document.getElementById("span_jhtinput_ket_hubungan_lainnya").style.display = 'block'; 
    }else
    {
      window.document.getElementById("span_jhtinput_ket_hubungan_lainnya").style.display = 'none'; 	
    } 	
  }
	
	$(document).ready(function(){
		fl_js_jhtinput_kode_tipe_penerima();
		fl_js_jhtinput_ahliwaris_lain();
	});							
</script>
