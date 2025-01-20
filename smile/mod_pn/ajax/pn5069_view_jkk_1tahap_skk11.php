<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
/* ============================================================================
Ket : Form ini digunakan untuk tab Input jkk Klaim JKK TKI Gagal Berangkat
      (tanpa melalui pengajuan dan jkk tahap 2, langsung ke penetapan)
Hist: - 22/01/2018 : Pembuatan Form (Tim SMILE)
			- 04/11/2019 : pemindahan query sql ke webservice								 						 
-----------------------------------------------------------------------------*/
$ld_jkk_tgl_gagal_berangkat	= $_POST["TGL_KEJADIAN"]  =="null" ? "" : $_POST["TGL_KEJADIAN"];
$ls_jkk_ket_tambahan				= $_POST["KET_TAMBAHAN"]	=="null" ? "" : $_POST["KET_TAMBAHAN"];
$ls_jkk_kode_klaim					= $_POST["KODE_KLAIM"]		=="null" ? "" : $_POST["KODE_KLAIM"];
$ls_jkk_nama_ket_tambahan		= "";

//get list penyebab gagal berangkat --------------------------------------------
if ($ls_jkk_kode_klaim!="" && $ls_jkk_ket_tambahan!="")
{
  $ipDev  	= "";
	global $wsIp;
  $ipDev  	= $wsIp."/MS1001/LovMsLookup";
  $url    	= $ipDev;
  $chId   	= 'SMILE';
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
    'TIPE'				=>"TKIGGLBRKT",
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
  $resultArray_LovJkkKetTambahan = json_decode(utf8_encode($result));
	
  for($i=0;$i<=($resultArray_LovJkkKetTambahan->TOTAL_REC)-1;$i++){
    if ($resultArray_LovJkkKetTambahan->DATA[$i]->KODE==$ls_jkk_ket_tambahan 
			 	&& strlen($ls_jkk_ket_tambahan)==strlen($resultArray_LovJkkKetTambahan->DATA[$i]->KODE))
		{
      $ls_jkk_nama_ket_tambahan = $resultArray_LovJkkKetTambahan->DATA[$i]->KETERANGAN;																	
    }
  }				
}
//end get list penyebab gagal berangkat ----------------------------------------				
?>

<fieldset><legend>Informasi Gagal Berangkat</legend>
  </br>	
												
  <div class="form-row_kiri">
  <label style = "text-align:right;">Penyebab Gagal *</label>		 	    				
    <input type="text" id="jkk_nama_ket_tambahan" name="jkk_nama_ket_tambahan" value="<?=$ls_jkk_nama_ket_tambahan;?>" style="width:320px;" readonly class="disabled"> 
    <input type="hidden" id="jkk_ket_tambahan" name="jkk_ket_tambahan" value="<?=$ls_jkk_ket_tambahan;?>">
  </div>																																												
  <div class="clear"></div>		
  
  <div class="form-row_kiri">
  <label style = "text-align:right;">Tgl Gagal Berangkat</label>
  	<input type="text" id="jkk_tgl_gagal_berangkat" name="jkk_tgl_gagal_berangkat" value="<?=$ld_jkk_tgl_gagal_berangkat;?>" style="width:320px;" readonly class="disabled">     	   							
  </div>	
  <div class="clear"></div>
  
  </br>																								  
</fieldset>						
