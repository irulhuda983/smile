<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
/* =============================================================================
Ket : Form ini digunakan untuk tab Input Agenda Klaim JHT/JKM
Hist: - 20/07/2017 : Pembuatan Form (Tim SMILE)			
			- 04/11/2019 : pemindahan query ke webservice 						 
------------------------------------------------------------------------------*/
//get data input jht dan jkm ---------------------------------------------------
$ls_jhminput_kode_manfaat 	 				 		= $_POST["DATA_INPUTJHT"]["KODE_MANFAAT"]								=="null" ? "" : $_POST["DATA_INPUTJHT"]["KODE_MANFAAT"];
$ls_jhminput_no_urut 	 				 					= $_POST["DATA_INPUTJHT"]["NO_URUT_MANFAAT"]						=="null" ? "" : $_POST["DATA_INPUTJHT"]["NO_URUT_MANFAAT"];
$ld_jhminput_tgl_saldo_awaltahun 				= $_POST["DATA_INPUTJHT"]["TGL_SALDO_AWALTAHUN"]				=="null" ? "" : $_POST["DATA_INPUTJHT"]["TGL_SALDO_AWALTAHUN"];
$ln_jhminput_nom_saldo_awaltahun 	 			= $_POST["DATA_INPUTJHT"]["NOM_SALDO_AWALTAHUN"]				=="null" ? "" : $_POST["DATA_INPUTJHT"]["NOM_SALDO_AWALTAHUN"];
$ld_jhminput_tgl_pengembangan_estimasi 	= $_POST["DATA_INPUTJHT"]["TGL_PENGEMBANGAN_ESTIMASI"]	=="null" ? "" : $_POST["DATA_INPUTJHT"]["TGL_PENGEMBANGAN_ESTIMASI"];
$ln_jhminput_nom_pengembangan_estimasi 	= $_POST["DATA_INPUTJHT"]["NOM_PENGEMBANGAN_ESTIMASI"]	=="null" ? "" : $_POST["DATA_INPUTJHT"]["NOM_PENGEMBANGAN_ESTIMASI"];
$ln_jhminput_nom_manfaat_sudahdiambil 	= $_POST["DATA_INPUTJHT"]["NOM_MANFAAT_SUDAHDIAMBIL"]		=="null" ? "" : $_POST["DATA_INPUTJHT"]["NOM_MANFAAT_SUDAHDIAMBIL"];
$ln_jhminput_pengambilan_ke 						= $_POST["DATA_INPUTJHT"]["PENGAMBILAN_KE"]							=="null" ? "" : $_POST["DATA_INPUTJHT"]["PENGAMBILAN_KE"];
$ls_jhminput_kode_tipe_penerima 				= $_POST["DATA_INPUTJHT"]["KODE_TIPE_PENERIMA"]					=="null" ? "" : $_POST["DATA_INPUTJHT"]["KODE_TIPE_PENERIMA"];
$ls_jhminput_nama_tipe_penerima 				= $_POST["DATA_INPUTJHT"]["NAMA_TIPE_PENERIMA"]					=="null" ? "" : $_POST["DATA_INPUTJHT"]["NAMA_TIPE_PENERIMA"];
$ls_jhminput_kode_hubungan 							= $_POST["DATA_INPUTJHT"]["KODE_HUBUNGAN"]							=="null" ? "" : $_POST["DATA_INPUTJHT"]["KODE_HUBUNGAN"];
$ls_jhminput_nama_hubungan 							= $_POST["DATA_INPUTJHT"]["NAMA_HUBUNGAN"]							=="null" ? "" : $_POST["DATA_INPUTJHT"]["NAMA_HUBUNGAN"];
$ls_jhminput_ket_hubungan_lainnya				= $_POST["DATA_INPUTJHT"]["KET_HUBUNGAN_LAINNYA"]				=="null" ? "" : $_POST["DATA_INPUTJHT"]["KET_HUBUNGAN_LAINNYA"];
$ls_jhminput_no_urut_keluarga 					= $_POST["DATA_INPUTJHT"]["NO_URUT_KELUARGA"]						=="null" ? "" : $_POST["DATA_INPUTJHT"]["NO_URUT_KELUARGA"];
$ls_jhminput_nomor_identitas 						= $_POST["DATA_INPUTJHT"]["NOMOR_IDENTITAS"]						=="null" ? "" : $_POST["DATA_INPUTJHT"]["NOMOR_IDENTITAS"];
$ls_jhminput_nama_pemohon 							= $_POST["DATA_INPUTJHT"]["NAMA_PEMOHON"]								=="null" ? "" : $_POST["DATA_INPUTJHT"]["NAMA_PEMOHON"];
$ls_jhminput_tempat_lahir 							= $_POST["DATA_INPUTJHT"]["TEMPAT_LAHIR"]								=="null" ? "" : $_POST["DATA_INPUTJHT"]["TEMPAT_LAHIR"];
$ld_jhminput_tgl_lahir 									= $_POST["DATA_INPUTJHT"]["TGL_LAHIR"]									=="null" ? "" : $_POST["DATA_INPUTJHT"]["TGL_LAHIR"];
$ls_jhminput_jenis_kelamin 							= $_POST["DATA_INPUTJHT"]["JENIS_KELAMIN"]							=="null" ? "" : $_POST["DATA_INPUTJHT"]["JENIS_KELAMIN"];
$ls_jhminput_golongan_darah							= $_POST["DATA_INPUTJHT"]["GOLONGAN_DARAH"]							=="null" ? "" : $_POST["DATA_INPUTJHT"]["GOLONGAN_DARAH"];
$ls_jhminput_alamat 										= $_POST["DATA_INPUTJHT"]["ALAMAT"]											=="null" ? "" : $_POST["DATA_INPUTJHT"]["ALAMAT"];
$ls_jhminput_rt 												= $_POST["DATA_INPUTJHT"]["RT"]													=="null" ? "" : $_POST["DATA_INPUTJHT"]["RT"];
$ls_jhminput_rw 												= $_POST["DATA_INPUTJHT"]["RW"]													=="null" ? "" : $_POST["DATA_INPUTJHT"]["RW"];
$ls_jhminput_kode_kelurahan 						= $_POST["DATA_INPUTJHT"]["KODE_KELURAHAN"]							=="null" ? "" : $_POST["DATA_INPUTJHT"]["KODE_KELURAHAN"];
$ls_jhminput_nama_kelurahan 						= $_POST["DATA_INPUTJHT"]["NAMA_KELURAHAN"]							=="null" ? "" : $_POST["DATA_INPUTJHT"]["NAMA_KELURAHAN"];
$ls_jhminput_kode_kecamatan 						= $_POST["DATA_INPUTJHT"]["KODE_KECAMATAN"]							=="null" ? "" : $_POST["DATA_INPUTJHT"]["KODE_KECAMATAN"];
$ls_jhminput_nama_kecamatan 						= $_POST["DATA_INPUTJHT"]["NAMA_KECAMATAN"]							=="null" ? "" : $_POST["DATA_INPUTJHT"]["NAMA_KECAMATAN"];
$ls_jhminput_kode_kabupaten 						= $_POST["DATA_INPUTJHT"]["KODE_KABUPATEN"]							=="null" ? "" : $_POST["DATA_INPUTJHT"]["KODE_KABUPATEN"];
$ls_jhminput_nama_kabupaten 						= $_POST["DATA_INPUTJHT"]["NAMA_KABUPATEN"]							=="null" ? "" : $_POST["DATA_INPUTJHT"]["NAMA_KABUPATEN"];
$ls_jhminput_kode_pos 									= $_POST["DATA_INPUTJHT"]["KODE_POS"]										=="null" ? "" : $_POST["DATA_INPUTJHT"]["KODE_POS"];
$ls_jhminput_telepon_area 							= $_POST["DATA_INPUTJHT"]["TELEPON_AREA"]								=="null" ? "" : $_POST["DATA_INPUTJHT"]["TELEPON_AREA"];
$ls_jhminput_telepon 										= $_POST["DATA_INPUTJHT"]["TELEPON"]										=="null" ? "" : $_POST["DATA_INPUTJHT"]["TELEPON"];
$ls_jhminput_telepon_ext 								= $_POST["DATA_INPUTJHT"]["TELEPON_EXT"]								=="null" ? "" : $_POST["DATA_INPUTJHT"]["TELEPON_EXT"];
$ls_jhminput_handphone 									= $_POST["DATA_INPUTJHT"]["HANDPHONE"]									=="null" ? "" : $_POST["DATA_INPUTJHT"]["HANDPHONE"];
$ls_jhminput_email 											= $_POST["DATA_INPUTJHT"]["EMAIL"]											=="null" ? "" : $_POST["DATA_INPUTJHT"]["EMAIL"];
$ls_jhminput_npwp 											= $_POST["DATA_INPUTJHT"]["NPWP"]												=="null" ? "" : $_POST["DATA_INPUTJHT"]["NPWP"];

$ld_jhminput_tgl_kematian  							= $_POST["DATA_INPUTJKM"]["TGL_KEMATIAN"]								=="null" ? "" : $_POST["DATA_INPUTJKM"]["TGL_KEMATIAN"];
$ls_jhminput_ket_tambahan								= $_POST["DATA_INPUTJKM"]["KET_TAMBAHAN"]								=="null" ? "" : $_POST["DATA_INPUTJKM"]["KET_TAMBAHAN"];

if ($ls_jhminput_jenis_kelamin == "L")
{
 	$ls_jhminput_nama_jenis_kelamin = "LAKI-LAKI";   	 
}else if ($ls_jhminput_jenis_kelamin == "P")
{
 	$ls_jhminput_nama_jenis_kelamin = "PEREMPUAN";   	 
}else
{
 	$ls_jhminput_nama_jenis_kelamin = "";   	 
}
//end get data input jht dan jkm -----------------------------------------------

?>	
<div class="div-row">
  <div class="div-col" style="width:49%; max-height: 100%;">
    <div class="div-row">
      <div class="div-col" style="width: 100%">
        <fieldset><legend>Input Data Klaim JHT/JKM</legend>
          </br></br>
          
          <div class="form-row_kiri">
          <label style = "text-align:right;">Tgl Kematian &nbsp;</label>
          	<input type="text" id="jhminput_tgl_kematian" name="jhminput_tgl_kematian" value="<?=$ld_jhminput_tgl_kematian;?>" style="width:230px;" readonly class="disabled">      	
          </div> 	
          <div class="clear"></div>
          
          <div class="form-row_kiri">
          <label style = "text-align:right;">Keterangan &nbsp;</label>
          	<textarea cols="255" rows="1" id="jhminput_ket_tambahan" name="jhminput_ket_tambahan" onchange="trimLength(this, 300)" readonly class="disabled" style="width:230px;background-color:#F5F5F5;"><?=$ls_jhminput_ket_tambahan;?></textarea>	   							
          </div>			
          <div class="clear"></div>			
					
					</br></br>				
        </fieldset>	
        
        <fieldset style="height:310px;"><legend>Saldo JHT</legend>
          </br></br>
          
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Saldo Awal Tahun &nbsp;</label>
            <input type="text" id="jhminput_tgl_saldo_awaltahun" name="jhminput_tgl_saldo_awaltahun" value="<?=$ld_jhminput_tgl_saldo_awaltahun;?>" style="width:70px;" readonly class="disabled">
            <input type="text" id="jhminput_nom_saldo_awaltahun" name="jhminput_nom_saldo_awaltahun" value="<?=number_format((float)$ln_jhminput_nom_saldo_awaltahun,2,".",",");?>" readonly class="disabled" style="width:150px;text-align:right;">                					
          </div>			
          <div class="clear"></div>
          
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Saldo JHT Estimasi &nbsp;</label>
            <input type="text" id="jhminput_tgl_pengembangan_estimasi" name="jhminput_tgl_pengembangan_estimasi" value="<?=$ld_jhminput_tgl_pengembangan_estimasi;?>" style="width:70px;" readonly class="disabled">
            <input type="text" id="jhminput_nom_pengembangan_estimasi" name="jhminput_nom_pengembangan_estimasi" value="<?=number_format((float)$ln_jhminput_nom_pengembangan_estimasi,2,".",",");?>" readonly class="disabled" style="width:150px;text-align:right;">                					
          </div>			
          <div class="clear"></div>
          
          </br></br>
          
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Saldo JHT sdh Diambil</label>
          	<input type="text" id="jhminput_nom_manfaat_sudahdiambil" name="jhminput_nom_manfaat_sudahdiambil" value="<?=number_format((float)$ln_jhminput_nom_manfaat_sudahdiambil,2,".",",");?>" readonly class="disabled" style="width:200px;text-align:right;">                					
          </div>				
          <div class="clear"></div>
          
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Pengambilan JHT ke- &nbsp;</label>
          	<input type="text" id="jhminput_pengambilan_ke" name="jhminput_pengambilan_ke" value="<?=number_format((float)$ln_jhminput_pengambilan_ke,0,".",",");?>" readonly class="disabled" style="width:200px;text-align:right;">                					
          </div>		
          <div class="clear"></div>	
					
					</br></br>							
        </fieldset>	
							
      </div>
    </div>
  </div>

  <div class="div-col" style="width:1%;">
  </div>

  <div class="div-col-right" style="width:50%;">
    <div class="div-row">
      <div class="div-col" style="width: 100%">
        <fieldset><legend>Penerima Manfaat :</legend>
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Tipe Penerima &nbsp;*</label>
							<input type="text" id="jhminput_nama_tipe_penerima" name="jhminput_nama_tipe_penerima" value = "<?=$ls_jhminput_nama_tipe_penerima;?>" readonly class="disabled" style="width:270px;">
							<input type="hidden" id="jhminput_kode_tipe_penerima" name="jhminput_kode_tipe_penerima" value = "<?=$ls_jhminput_kode_tipe_penerima;?>">
							<input type="hidden" id="jhminput_kode_tipe_penerima_old" name="jhminput_kode_tipe_penerima_old" value="<?=$ls_jhminput_kode_tipe_penerima;?>">
              <input type="hidden" id="jhminput_kode_manfaat" name="jhminput_kode_manfaat" value="<?=$ls_jhminput_kode_manfaat;?>">
              <input type="hidden" id="jhminput_no_urut" name="jhminput_no_urut" value="<?=$ls_jhminput_no_urut;?>"> 
            </div>				
            <div class="clear"></div>
            
            <span id="span_jhminput_kode_hubungan" style="display:none;">
              <div class="form-row_kiri">
              <label style = "text-align:right;">Ahli Waris *</label>		 	    				
                <input type="text" id="jhminput_nama_hubungan" name="jhminput_nama_hubungan" value = "<?=$ls_jhminput_nama_hubungan;?>" readonly class="disabled" style="width:270px;">
								<input type="hidden" id="jhminput_kode_hubungan" name="jhminput_kode_hubungan" value = "<?=$ls_jhminput_kode_hubungan;?>">
								<input type="hidden" id="jhminput_no_urut_keluarga" name="jhminput_no_urut_keluarga" value="<?=$ls_jhminput_no_urut_keluarga;?>">        				
              </div>																																									
              <div class="clear"></div>
            </span>
        
            <span id="span_jhminput_ket_hubungan_lainnya" style="display:none;">
              <div class="form-row_kiri">
              <label style = "text-align:right;">&nbsp; *</label>		
                <input type="text" id="jhminput_ket_hubungan_lainnya" name="jhminput_ket_hubungan_lainnya" value="<?=$ls_jhminput_ket_hubungan_lainnya;?>" style="width:270px;" readonly class="disabled">
              </div>																																									
              <div class="clear"></div>			
            </span>
        
            <div class="form-row_kiri">
            <label style = "text-align:right;">Nama &nbsp;&nbsp;*</label>
              <input type="text" id="jhminput_nama_pemohon" name="jhminput_nama_pemohon" value="<?=$ls_jhminput_nama_pemohon;?>" style="width:270px;" readonly class="disabled">
            </div>										
            <div class="clear"></div>												
                      
            <div class="form-row_kiri">
            <label style = "text-align:right;">Tempat, Tgl Lahir &nbsp; *</label>	    				
              <input type="text" id="jhminput_tempat_lahir" name="jhminput_tempat_lahir" value="<?=$ls_jhminput_tempat_lahir;?>" readonly class="disabled" style="width:164px">
              <input type="text" id="jhminput_tgl_lahir" name="jhminput_tgl_lahir" value="<?=$ld_jhminput_tgl_lahir;?>" readonly class="disabled" style="width:82px">			
            </div>																																													
            <div class="clear"></div>
        
            <div class="form-row_kiri">
              <label style = "text-align:right;">Jenis Kelamin *</label>
  						<input type="text" id="jhminput_nama_jenis_kelamin" name="jhminput_nama_jenis_kelamin" value="<?=$ls_jhminput_nama_jenis_kelamin;?>" style="width:100px;" readonly class="disabled">
  						&nbsp;
              Gol. Darah
  						<input type="text" id="jhminput_golongan_darah" name="jhminput_golongan_darah" value="<?=$ls_jhminput_golongan_darah;?>" style="width:60px;" readonly class="disabled">
            </div>																																									
            <div class="clear"></div>
                                
            </br>
            
            <div class="form-row_kiri">
              <label style = "text-align:right;">Alamat &nbsp;&nbsp;&nbsp;&nbsp;*</label>		 	    				
              <textarea cols="255" id="jhminput_alamat" name="jhminput_alamat" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" readonly class="disabled" style="width:239px;height:18px;background-color:#F5F5F5;"><?=$ls_jhminput_alamat;?></textarea>
  					</div>																																										
            <div class="clear"></div>

            <div class="form-row_kiri">
              <label style = "text-align:right;">RT/RW &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;</label>		 	    				
              <input type="text" id="jhminput_rt" name="jhminput_rt" value="<?=$ls_jhminput_rt;?>" style="width:133px;" readonly class="disabled">
              /
              <input type="text" id="jhminput_rw" name="jhminput_rw" value="<?=$ls_jhminput_rw;?>" style="width:80px;" readonly class="disabled">		
            </div>																																										
            <div class="clear"></div>
        
            <div class="form-row_kiri">
              <label style = "text-align:right;">Kelurahan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>		 	    				
              <input type="text" id="jhminput_nama_kelurahan" name="jhminput_nama_kelurahan" value="<?=$ls_jhminput_nama_kelurahan;?>" style="width:239px;" readonly class="disabled">
              <input type="hidden" id="jhminput_kode_kelurahan" name="jhminput_kode_kelurahan" value="<?=$ls_jhminput_kode_kelurahan;?>">      
            </div>																																																	
            <div class="clear"></div>
  										       
            <div class="form-row_kiri">
              <label style = "text-align:right;">Kecamatan &nbsp;&nbsp;&nbsp;</label>		 	    				
              <input type="text" id="jhminput_nama_kecamatan" name="jhminput_nama_kecamatan" value="<?=$ls_jhminput_nama_kecamatan;?>" style="width:239px;" readonly class="disabled">
              <input type="hidden" id="jhminput_kode_kecamatan" name="jhminput_kode_kecamatan" value="<?=$ls_jhminput_kode_kecamatan;?>">			
            </div>
            <div class="clear"></div>
                
            <div class="form-row_kiri">
              <label style = "text-align:right;">Kabupaten &nbsp;</label>		 	    				
              <input type="text" id="jhminput_nama_kabupaten" name="jhminput_nama_kabupaten" value="<?=$ls_jhminput_nama_kabupaten;?>" style="width:239px;" readonly class="disabled">
              <input type="hidden" id="jhminput_kode_kabupaten" name="jhminput_kode_kabupaten" value="<?=$ls_jhminput_kode_kabupaten;?>">      
              <input type="hidden" id="jhminput_kode_propinsi" name="jhminput_kode_propinsi" value="<?=$ls_jhminput_kode_propinsi;?>">
              <input type="hidden" id="jhminput_nama_propinsi" name="jhminput_nama_propinsi" value="<?=$ls_jhminput_nama_propinsi;?>">
            </div>
            <div class="clear"></div>	
  					
            <div class="form-row_kiri">
              <label style = "text-align:right;">Kode Pos &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label> 	    				
              <input type="text" id="jhminput_kode_pos" name="jhminput_kode_pos" value="<?=$ls_jhminput_kode_pos;?>" style="width:206px;" readonly class="disabled">
            </div>																																																	
            <div class="clear"></div>
					                                                         
            </br>
                
            <div class="form-row_kiri">
            <label style = "text-align:right;">Email &nbsp;&nbsp;</label>		 	    				
              <input type="text" id="jhminput_email" name="jhminput_email" value="<?=$ls_jhminput_email;?>" style="width:270px;" readonly class="disabled">
            </div>
            <div class="clear"></div>
          
            <div class="form-row_kiri">
            <label style = "text-align:right;">No. Telp &nbsp;</label>	    				
              <input type="text" id="jhminput_telepon_area" name="jhminput_telepon_area" value="<?=$ls_jhminput_telepon_area;?>" style="width:40px;" readonly class="disabled">
              <input type="text" id="jhminput_telepon" name="jhminput_telepon" value="<?=$ls_jhminput_telepon;?>" style="width:148px;" readonly class="disabled">
              &nbsp;ext.
              <input type="text" id="jhminput_telepon_ext" name="jhminput_telepon_ext" value="<?=$ls_jhminput_telepon_ext;?>" style="width:40px;" readonly class="disabled">
            </div>
            <div class="clear"></div>
        
            <div class="form-row_kiri">
            <label style = "text-align:right;">Handphone &nbsp;&nbsp;</label>		 	    				
              <input type="text" id="jhminput_handphone" name="jhminput_handphone" value="<?=$ls_jhminput_handphone;?>" style="width:270px;" readonly class="disabled">
            </div>																																																																																														
            <div class="clear"></div>
        
            <div class="form-row_kiri">
            <label style = "text-align:right;">No. Identitas &nbsp;</label>
              <input type="text" id="jhminput_nomor_identitas" name="jhminput_nomor_identitas" value="<?=$ls_jhminput_nomor_identitas;?>" style="width:270px;" readonly class="disabled">
            </div>																																									
            <div class="clear"></div>
        
            <div class="form-row_kiri">
            <label style = "text-align:right;">NPWP &nbsp; *</label>
              <input type="text" id="jhminput_npwp" name="jhminput_npwp" value="<?=$ls_jhminput_npwp;?>" style="width:240px;" readonly class="disabled">
            </div>																																									
            <div class="clear"></div>			
				</fieldset>	
      </div>
    </div>
  </div>
</div>

<script language="JavaScript">    
  function fl_js_jhminput_kode_tipe_penerima() 
  { 
		var	v_kode_tipe_penerima = window.document.getElementById('jhminput_kode_tipe_penerima').value;
							
  	if (v_kode_tipe_penerima =="AW") //ahli waris ----------------------------
    {
      window.document.getElementById("span_jhminput_kode_hubungan").style.display = 'block';	
    }else
    {
      window.document.getElementById("span_jhminput_kode_hubungan").style.display = 'none';
    }
  }
	
  function fl_js_jhminput_ahliwaris_lain() 
  { 
		var	v_kode_tipe_penerima = window.document.getElementById('jhminput_kode_tipe_penerima').value;
		var	v_kode_hubungan = window.document.getElementById('jhminput_kode_hubungan').value;
    
    if (v_kode_tipe_penerima =="AW" && v_kode_hubungan=="L") //Ahli Waris Lainnya ---------
    {
		 	window.document.getElementById("span_jhminput_ket_hubungan_lainnya").style.display = 'block'; 
    }else
    {
      window.document.getElementById("span_jhminput_ket_hubungan_lainnya").style.display = 'none'; 	
    } 	
  }
	
	$(document).ready(function(){
		fl_js_jhminput_kode_tipe_penerima();
		fl_js_jhminput_ahliwaris_lain();
	});							
</script>
