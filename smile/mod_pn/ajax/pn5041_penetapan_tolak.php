<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "PN5002 - TOLAK DAN KEMBALIKAN KE TAHAPAN SEBELUMNYA";

function api_json_call($apiurl, $header, $data) {
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

$ls_kode_klaim	 		          = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ln_no_level	 			          = !isset($_GET['no_level']) ? $_POST['no_level'] : $_GET['no_level'];
$ls_sender 				 	          = !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_mid			          = !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
$ls_root_sender		 	          = !isset($_GET['root_sender']) ? $_POST['root_sender'] : $_GET['root_sender'];
$ls_status_klaim	 	          = !isset($_GET['status_klaim']) ? $_POST['status_klaim'] : $_GET['status_klaim'];
$btn_task 					          = !isset($_GET['btn_task']) ? $_POST['btn_task'] : $_GET['btn_task'];
$ls_kode_pengajuan_siapkerja 	= !isset($_GET['kode_pengajuan_siapkerja']) ? $_POST['kode_pengajuan_siapkerja'] : $_GET['kode_pengajuan_siapkerja'];




if ($ls_kode_klaim!="")
{
  $sql = "select substr(kode_tipe_klaim,1,3) jenis_klaim, status_klaim, kode_pointer_asal, id_pointer_asal, kode_segmen, kode_perlindungan, kode_sebab_klaim from sijstk.pn_klaim ".
			 	 "where kode_klaim = '$ls_kode_klaim' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();		
  $ls_kode_pointer_asal = $row['KODE_POINTER_ASAL'];
  $ls_id_pointer_asal		= $row['ID_POINTER_ASAL'];
	$ls_kode_realisasi		= $row['ID_POINTER_ASAL'];
	$ls_status_klaim			= $row['STATUS_KLAIM'];
	$ls_jenis_klaim				= $row['JENIS_KLAIM'];	
	$ls_kode_segmen				= $row['KODE_SEGMEN'];
	$ls_kode_perlindungan	= $row['KODE_PERLINDUNGAN'];	
  $ls_kode_sebab_klaim	= $row['KODE_SEBAB_KLAIM'];	
}

$ls_alasan_penolakan	= $_POST['alasan_penolakan'];
		
define('debug', false);	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?=$pagetitle;?></title>
  <meta name="Author" content="JroBalian" />
  <link rel="stylesheet" type="text/css" href="<?="http://$HTTP_HOST";?>/style/style.css" />
  <script type="text/javascript" language="JavaScript" src="../../javascript/iframe.js"></script>
  <script type="text/javascript" src="../../javascript/common.js"></script>

  <script type="text/javascript" src="../../javascript/calendar.js"></script>
  <script type="text/javascript" src="../../javascript/common.js"></script>
  <script type="text/javascript" src="../../javascript/treemenu3.js"></script>
  <link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
  <script type="text/javascript"></script>
	
  <script type="text/javascript">
  function refreshRootParent() 
  {																						
    	//if(window.opener.document.getElementById('formreg') != undefined)
			//{																							
      	<?php	
      	if($ls_root_sender!=''){			
      		echo "window.location.replace('../form/$ls_root_sender?mid=$ls_sender_mid');";		
      	}
      	?>	
    	//}		
  }
  function refreshParent() 
  {																						
    <?php	
    if($ls_sender!=''){			
    	echo "window.location.replace('$ls_sender?task=edit&kode_klaim=$ls_kode_klaim&dataid=$ls_kode_klaim&sender=$ls_sender&activetab=2');";		
    }
    ?>
  }	
  </script>
							
  <script language="JavaScript">    
  	function fl_js_val_simpan()
  	{
      var form = document.fpop;
      if(form.alasan_penolakan.value==""){
        alert('Alasa Penolakan tidak boleh kosong...!!!');
        form.alasan_penolakan.focus();															
      }else
  		{
         form.btn_task.value="simpan";
         form.submit(); 		 
  		}								 
  	}				
  </script>															
</head>
<body>
  <!--[if lte IE 6]>
  <div id="clearie6"></div>
  <![endif]-->	
	<?
	if($btn_task=="simpan")
	{       
			//jika status akhir PENETAPAN maka
			if ($ls_status_klaim=="PENETAPAN")
			{
  			if ($ls_jenis_klaim=="JKK")
				{
  				//jika tki onsite maka kembalikan data penetapan menjadi agenda ------
					//selain itu kembalikan data penetapan menjadi agenda tahap II  ------	  				
      		if ($ls_kode_segmen=="TKI" && $ls_kode_perlindungan=="ONSITE")
					{
        		$sql = "update sijstk.pn_klaim set ".
                   "    status_submit_agenda     = 'T', ".
                   "    tgl_submit_agenda        = null, ".
                   "    petugas_submit_agenda    = null, ".
    							 "    status_submit_pengajuan  = 'T', ".
                   "    tgl_submit_pengajuan  	 = null, ".
                   "    petugas_submit_pengajuan = null, ".
    							 "    status_submit_agenda2 	 = 'T', ".
                   "    tgl_submit_agenda2    	 = null, ".
                   "    petugas_submit_agenda2 	 = null, ".
                   "		status_klaim					 	 = 'AGENDA', ".
                   "    tgl_ubah           	 		 = sysdate, ".
                   "    petugas_ubah       	 		 = '$username' ".
                   "where kode_klaim = '$ls_kode_klaim' ";
            $DB->parse($sql);
            $DB->execute();					
					}else
					{
            //04092023, [Problem ID : 1238] - PN5001 - JKK PMI - Tidak Ada Button Submit PENETAPAN
            if($ls_kode_segmen=="TKI" && $ls_kode_perlindungan=="PRA" && $ls_kode_sebab_klaim == "SKK11")
            {
              $sql = "update sijstk.pn_klaim set ".
              "    status_submit_agenda     = 'T', ".
              "    tgl_submit_agenda        = null, ".
              "    petugas_submit_agenda    = null, ".
              "    status_submit_pengajuan  = 'T', ".
              "    tgl_submit_pengajuan  	 = null, ".
              "    petugas_submit_pengajuan = null, ".
              "    status_submit_agenda2 	 = 'T', ".
              "    tgl_submit_agenda2    	 = null, ".
              "    petugas_submit_agenda2 	 = null, ".
              "		status_klaim					 	 = 'AGENDA', ".
              "    tgl_ubah           	 		 = sysdate, ".
              "    petugas_ubah       	 		 = '$username' ".
              "where kode_klaim = '$ls_kode_klaim' ";
              $DB->parse($sql);
              $DB->execute();					
            }else{
              $sql = "update sijstk.pn_klaim set ".
              "    status_submit_agenda2 	 = 'T', ".
              "    tgl_submit_agenda2    	 = null, ".
              "    petugas_submit_agenda2 	 = null, ".
              "		status_klaim					 	 = 'AGENDA_TAHAP_II', ".
              "    tgl_ubah           	 		 = sysdate, ".
              "    petugas_ubah       	 		 = '$username' ".
              "where kode_klaim = '$ls_kode_klaim' ";
              $DB->parse($sql);
              $DB->execute();	
            }
					}										
    			$ls_ket_submit = "PENOLAKAN DAN PENGEMBALIAN AGENDA DENGAN ALASAN : ".$ls_alasan_penolakan;				
				}else
				{

          if($ls_jenis_klaim=="JKP"){
            //pembatalan klaim --------------------------------------------------
            $qry = "update pn.pn_klaim set status_batal = 'Y', tgl_batal=sysdate, petugas_batal = '$username', status_klaim = 'BATAL'
            where kode_klaim = '$ls_kode_klaim' ";											 	
            $DB->parse($qry);				
            $DB->execute();		

            $headers = array(
              'Content-Type: application/json',
              'X-Forwarded-For: ' . $ipfwd
            );

            $data_send_batal_klaim = array(
              'chId'              => 'SMILE',
              'reqId'             => $username,
              "kodePengajuan"		  => $ls_kode_pengajuan_siapkerja,
              "keteranganBatal"   => "Dibatalkan",
              "petugasRekam"		  => $username
            );

            $result_batal_klaim = api_json_call($wsIp . "/JSKlaimJKP/BatalPengajuanKlaimJkp", $headers,  $data_send_batal_klaim);

            $sql = "SELECT A.FLAG_CEK_MENUNGGAK_IURAN from SIAPKERJA.SK_KLAIM_PENGAJUAN A WHERE A.KODE_KLAIM = '$ls_kode_klaim' ";											 	
            $ECDB->parse($sql);
            $ECDB->execute();
            $row = $ECDB->nextrow();		
            $ls_flag_cek_menunggak_iuran = $row['FLAG_CEK_MENUNGGAK_IURAN'];
            
            if($ls_flag_cek_menunggak_iuran != 'Y'){
              $data_send = array(
                'chId'              => 'SMILE',
                'reqId'             => $username,
                "kodePengajuan"		  => $ls_kode_pengajuan_siapkerja,
                "statusPengajuan"	  => "KLJKP005",
                "keterangan"		    => $ls_alasan_penolakan,
                "petugasRekam"		  => $username
              );
      
              $result = api_json_call($wsIp . "/JSKlaimJKP/UpdateClaimStatus", $headers,  $data_send);
            }

          } else {
            //kembalikan data penetapan menjadi agenda ---------------------------		  				
            $sql = "update sijstk.pn_klaim set ".
                  "    status_submit_agenda     = 'T', ".
                  "    tgl_submit_agenda        = null, ".
                  "    petugas_submit_agenda    = null, ".
                  "    status_submit_pengajuan  = 'T', ".
                  "    tgl_submit_pengajuan  	 = null, ".
                  "    petugas_submit_pengajuan = null, ".
                  "    status_submit_agenda2 	 = 'T', ".
                  "    tgl_submit_agenda2    	 = null, ".
                  "    petugas_submit_agenda2 	 = null, ".
                  "		status_klaim					 	 = 'AGENDA', ".
                  "    tgl_ubah           	 		 = sysdate, ".
                  "    petugas_ubah       	 		 = '$username' ".
                  "where kode_klaim = '$ls_kode_klaim' ";
            $DB->parse($sql);
            $DB->execute();											
            $ls_ket_submit = "PENOLAKAN DAN PENGEMBALIAN AGENDA DENGAN ALASAN : ".$ls_alasan_penolakan;
          }  

				}
			}else if ($ls_status_klaim=="PENGAJUAN_TAHAP_I")
			{
  			//kembalikan data pengajuan jkk tahap I menjadi agenda jkk tahap I  ----  				
    		$sql = "update sijstk.pn_klaim set ".
               "    status_submit_agenda     = 'T', ".
               "    tgl_submit_agenda        = null, ".
               "    petugas_submit_agenda    = null, ".
							 "		status_klaim					 	 = 'AGENDA_TAHAP_I', ".
               "    tgl_ubah           	 		 = sysdate, ".
               "    petugas_ubah       	 		 = '$username' ".
               "where kode_klaim = '$ls_kode_klaim' ";
        $DB->parse($sql);
        $DB->execute();											
  			$ls_ket_submit = "PENOLAKAN DAN PENGEMBALIAN AGENDA JKK TAHAP I DENGAN ALASAN : ".$ls_alasan_penolakan;			
			}
			
      //generate aktivitas klaim -----------------------------------------------
      if ($ls_ket_submit!="")
			{
    		$qry = "BEGIN SIJSTK.P_PN_PN5040.X_INSERT_AKTIVITAS('$ls_kode_klaim', 'PENOLAKAN', substr(upper('$ls_ket_submit'),1,300), '$username',:p_sukses,:p_mess);END;";											 	
        $proc = $DB->parse($qry);				
        oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
        oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
        $DB->execute();				
        $ls_sukses = $p_sukses;
    		$ls_mess = $p_mess;	  			
			}
			
      echo "<script language=\"JavaScript\" type=\"text/javascript\">";
      echo "refreshRootParent();";
      echo "</script>";								            
	} //end if(isset($_POST['simpan'])) 
	?>	
  <form name="fpop" method="post" action="<?=$PHP_SELF;?>">
  	<!--
		<div id="header-popup">	
  		<h3><?=$gs_pagetitle;?></h3>
  	</div>
  	
  	<div id="container-popup">
  	<div id="clearie6"></div>
		-->
		
    <table class="captionform">
      <style>
        #header-caption2 {position:absolute;top:0;left:0;width:98%;height:27px;background:-webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
        #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 6px 1px 1px 1px;height:23px;border-bottom:1px solid #6997ff;padding-left:1px;border-top-right-radius:1px;border-top-left-radius:1px;}
      </style>
      <tr><td id="header-caption2" colspan="3"><h3><?=$gs_pagetitle;?></h3></td></tr>	
      <tr><td colspan="3"></br></td></tr>	
  	</table>		
											
		<div id="formframe" style="width:900px;">
			<span id="dispError1" style="display:none;color:red"></span>
			<input type="hidden" id="st_errval1" name="st_errval1">		
      <input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
			<input type="hidden" id="root_sender" name="root_sender" value="<?=$ls_root_sender;?>">
			<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
			<input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">
			<input type="hidden" id="status_klaim" name="status_klaim" value="<?=$ls_status_klaim;?>">
			<input type="hidden" id="no_level" name="no_level" value="<?=$ln_no_level;?>">
			<input type="hidden" id="btn_task" name="btn_task" value="">
      <input type="hidden" id="kode_pengajuan_siapkerja" name="kode_pengajuan_siapkerja" value="<?=$ls_kode_pengajuan_siapkerja;?>"> 
			
			<div id="formKiri" style="width:900px;">
				<fieldset style="width:820px;"><legend>&nbsp;</legend>
									
          <div class="form-row_kiri">
          <label style = "text-align:right;">Alasan Penolakan &nbsp; *</label>
          	<textarea cols="255" rows="2" style="width:500px;background-color:#ffff99" id="alasan_penolakan" name="alasan_penolakan" tabindex="1" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_keterangan;?></textarea>   					
          </div>														
          <div class="clear"></div>
															
				</fieldset>	
										 	
        <div id="buttonbox" style="width:820px;text-align:center;">        			 
          <input type="button" class="btn green" id="simpan" name="simpan" value="               TOLAK               " onClick="if(confirm('Apakah anda yakin akan melakukan penolakan dan pengembalian data agenda ..?')) fl_js_val_simpan();;">
          <input type="button" class="btn green" id="close" name="close" onclick="refreshParent();" value="               TUTUP               " />       					
        </div>					 
			</div>	 
  	</div>
		
    <?
    if (isset($msg))		
    {
    ?>
      <fieldset>
      <?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
      <?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
      </fieldset>		
    <?
    }
    ?>  
	</form>
</body>
</html>				