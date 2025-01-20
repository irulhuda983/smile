<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
/* ============================================================================
Ket : Form ini digunakan untuk tab Input Data TK Jakon
Hist: - 18/03/2018 : Pembuatan Form (Tim SMILE)	
			- 04/11/2019 : pemindahan query sql ke webservice				 						 
-----------------------------------------------------------------------------*/
$ls_tkjakon_kode_klaim	 	 		= $_POST["KODE_KLAIM"]  		=="null" ? "" : $_POST["KODE_KLAIM"];
$ls_tkjakon_kode_proyek 	 	  = $_POST["KODE_PROYEK"]  		=="null" ? "" : $_POST["KODE_PROYEK"];
$ls_tkjakon_kode_tk 					= $_POST["KODE_TK"]  				=="null" ? "" : $_POST["KODE_TK"];
$ls_tkjakon_nama_tk 	 				= $_POST["NAMA_TK"]  				=="null" ? "" : $_POST["NAMA_TK"];
$ls_tkjakon_kpj 							= $_POST["KPJ"]  						=="null" ? "" : $_POST["KPJ"];
$ls_tkjakon_nomor_identitas		= $_POST["NOMOR_IDENTITAS"] =="null" ? "" : $_POST["NOMOR_IDENTITAS"];
$ls_tkjakon_jenis_identitas		= $_POST["JENIS_IDENTITAS"] =="null" ? "" : $_POST["JENIS_IDENTITAS"];
$ls_tkjakon_alamat_domisili		= $_POST["ALAMAT_DOMISILI"] =="null" ? "" : $_POST["ALAMAT_DOMISILI"];
$ld_tkjakon_tgl_lahir					= $_POST["TGL_LAHIR"]  			=="null" ? "" : $_POST["TGL_LAHIR"];
$ls_tkjakon_kode_pekerjaan		= $_POST["KODE_PEKERJAAN"]  =="null" ? "" : $_POST["KODE_PEKERJAAN"];
$ls_tkjakon_nama_pekerjaan		= "";

//get list  Jenis Pekerjaan ----------------------------------------------------
if ($ls_tkjakon_kode_klaim!="" && $ls_tkjakon_kode_pekerjaan!="")
{
  $ipDev  	= "";
	global $wsIp;
  $ipDev  	= $wsIp."/JSPN5040/LovKodePekerjaanProyek";
  $url    	= $ipDev;
  $chId   	= 'CORE';
  $username = $_SESSION["USER"];
  
  // set HTTP header -----------------------------------------------------
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );
  
  // set POST params -----------------------------------------------------
  $data = array(
    'chId'				=>$chId,
    'reqId'				=>$username,
    'PAGE'				=>1,
    'NROWS'				=>10000,
    'C_COLNAME'		=>"",
    'C_COLVAL'		=>"",
    'C_COLNAME2'	=>"",
    'C_COLVAL2'		=>"",
    'O_COLNAME'		=>"",
    'O_MODE'			=>"ASC"
  );

  // Open connection -----------------------------------------------------
  $ch = curl_init();
  
  // Set the url, number of POST vars, POST data -------------------------
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  
  // Execute post --------------------------------------------------------
  $result = curl_exec($ch);
  $resultArray_Pkj = json_decode(utf8_encode($result));
	
	//ambil nama pekerjaan ----------------------------
  for($i=0;$i<=($resultArray_Pkj->TOTAL_REC)-1;$i++){
    if ($resultArray_Pkj->DATA[$i]->KODE_PEKERJAAN==$ls_tkjakon_kode_pekerjaan && 
			 	strlen($ls_tkjakon_kode_pekerjaan)==strlen($resultArray_Pkj->DATA[$i]->KODE_PEKERJAAN))
		{
      $ls_tkjakon_nama_pekerjaan = $resultArray_Pkj->DATA[$i]->NAMA_PEKERJAAN;																	
    }
  }
}
//end get list Jenis Pekerjaan -------------------------------------------------
?>
<fieldset><legend>Tenaga Kerja Jasa Konstruksi</legend>
  </br>
  <div class="form-row_kiri">
  <label style = "text-align:right;">Nama &nbsp;&nbsp;*</label>
    <input type="text" id="tkjakon_nama_tk" name="tkjakon_nama_tk" value="<?=$ls_tkjakon_nama_tk;?>" style="width:270px;" readonly class="disabled">
    <input type="hidden" id="tkjakon_kode_tk" name="tkjakon_kode_tk" value="<?=$ls_tkjakon_kode_tk;?>">
  </div>	
  <div class="clear"></div>
  
  <div class="form-row_kiri">
  <label style = "text-align:right;">No. Identitas &nbsp;</label>
    <input type="text" id="tkjakon_jenis_identitas" name="tkjakon_jenis_identitas" value="<?=$ls_tkjakon_jenis_identitas;?>" style="width:76px;" readonly class="disabled">					
    <input type="text" id="tkjakon_nomor_identitas" name="tkjakon_nomor_identitas" value="<?=$ls_tkjakon_nomor_identitas;?>" style="width:185px;" readonly class="disabled">
  </div>																																									
  <div class="clear"></div>
  
  <div class="form-row_kiri">
  <label style = "text-align:right;">Tgl Lahir</label>	 
  	<input type="text" id="tkjakon_tgl_lahir" name="tkjakon_tgl_lahir" value="<?=$ld_tkjakon_tgl_lahir;?>" style="width:270px;" readonly class="disabled">                     												   							
  </div>																																																								
  <div class="clear"></div>
  
  <div class="form-row_kiri">
  <label style = "text-align:right;">Alamat Domisili &nbsp;&nbsp;&nbsp;&nbsp;</label>		 	    				
  	<textarea cols="255" rows="1" id="tkjakon_alamat_domisili" name="tkjakon_alamat_domisili" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" readonly class="disabled" style="width:270px;background-color:#F5F5F5"><?=$ls_tkjakon_alamat_domisili;?></textarea> 
  </div>																																										
  <div class="clear"></div>
  
  <div class="form-row_kiri">
  <label  style = "text-align:right;">Jenis Pekerjaan &nbsp;</label>
  	<input type="text" id="tkjakon_nama_pekerjaan" name="tkjakon_nama_pekerjaan" value="<?=$ls_tkjakon_nama_pekerjaan;?>" style="width:260px;" readonly class="disabled">
    <input type="hidden" id="tkjakon_kode_pekerjaan" name="tkjakon_kode_pekerjaan" value="<?=$ls_tkjakon_kode_pekerjaan;?>">
  </div>		    																																				
  <div class="clear"></div>
  
  </br>		 
</fieldset>	
