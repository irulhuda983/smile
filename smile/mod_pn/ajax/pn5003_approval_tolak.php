<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "PN5003 - TOLAK DAN KEMBALIKAN KE TAHAPAN SEBELUMNYA";

$ls_kode_klaim	 		= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ln_no_level	 			= !isset($_GET['no_level']) ? $_POST['no_level'] : $_GET['no_level'];
$ls_sender 				 	= !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_mid			= !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
$ls_root_sender		 	= !isset($_GET['root_sender']) ? $_POST['root_sender'] : $_GET['root_sender'];
$ls_status_klaim	 	= !isset($_GET['status_klaim']) ? $_POST['status_klaim'] : $_GET['status_klaim'];
$btn_task 					= !isset($_GET['btn_task']) ? $_POST['btn_task'] : $_GET['btn_task'];

if ($ls_kode_klaim!="")
{
  $sql = "select substr(kode_tipe_klaim,1,3) jenis_klaim, status_klaim, kode_pointer_asal, id_pointer_asal from sijstk.pn_klaim ".
			 	 "where kode_klaim = '$ls_kode_klaim' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();		
  $ls_kode_pointer_asal = $row['KODE_POINTER_ASAL'];
  $ls_id_pointer_asal		= $row['ID_POINTER_ASAL'];
	$ls_kode_realisasi		= $row['ID_POINTER_ASAL'];
	$ls_status_klaim			= $row['STATUS_KLAIM'];
	$ls_jenis_klaim				= $row['JENIS_KLAIM'];		
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
			//window.close();		
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
			if ($ls_status_klaim=="PERSETUJUAN" && $ln_no_level!="")
			{
        $sql = "select max(no_level) as v_max_level from sijstk.pn_klaim_approval ".
      			 	 "where kode_klaim = '$ls_kode_klaim' and no_level < '$ln_no_level' ".
							 "and nvl(status_approval,'T')='Y' ";
        $DB->parse($sql);
        $DB->execute();
        $row = $DB->nextrow();		
        $ln_level_sebelumnya = $row['V_MAX_LEVEL'];

				if ($ln_level_sebelumnya == "")
				{
				 	 //kembalikan ke penetapan -------------------------------------------	  				
      		$sql = "update sijstk.pn_klaim set ".
                 "    status_submit_penetapan  = 'T', ".
                 "    tgl_submit_penetapan     = null, ".
                 "    petugas_submit_penetapan = null, ".                 
                 "		status_klaim					 	 = 'PENETAPAN', ".
                 "    tgl_ubah           	 		 = sysdate, ".
                 "    petugas_ubah       	 		 = '$username' ".
                 "where kode_klaim = '$ls_kode_klaim' ";
          $DB->parse($sql);
          $DB->execute();	
					//reset data approval ---------------------------------------------
      		$sql = "delete from sijstk.pn_klaim_approval where kode_klaim = '$ls_kode_klaim' ";
          $DB->parse($sql);
          $DB->execute();			
																			
    			$ls_ket_submit = "PENOLAKAN DAN PENGEMBALIAN DATA PENETAPAN DENGAN ALASAN : ".$ls_alasan_penolakan;				
				}else
				{
				 	//kembalikan ke persetujuan level sebelumnya -------------------------  				
      		$sql3 = "update sijstk.pn_klaim_approval set ".
                 "    status_approval  = 'T', ".
                 "    tgl_approval     = null, ".
                 "    petugas_approval = null, ".             
                 "    tgl_ubah         = sysdate, ".
                 "    petugas_ubah     = '$username' ".
                 "where kode_klaim = '$ls_kode_klaim' ".
								 "and no_level = '$ln_level_sebelumnya' ";
          $DB->parse($sql3);
          $DB->execute();											
    			$ls_ket_submit = "PENOLAKAN DAN PENGEMBALIAN DATA PERSETUJUAN KE LEVEL SEBELUMNYA (LEVEL ".$ln_level_sebelumnya.") DENGAN ALASAN : ".$ls_alasan_penolakan;					 
				}
			}
			
      //generate aktivitas klaim -----------------------------------------------
      if ($ls_ket_submit!="")
			{
  			$sql = "select nvl(max(no_urut),0)+1 as v_nourut from sijstk.pn_klaim_aktivitas ".
        		 	 "where kode_klaim = '$ls_kode_klaim' ";
        $DB->parse($sql);
        $DB->execute();
        $row = $DB->nextrow();
        $ln_no_urut = $row["V_NOURUT"];	
        
        $sql = "insert into sijstk.pn_klaim_aktivitas ( ".
               "	kode_klaim, no_urut, kode_aktivitas, tgl_mulai, tgl_akhir, status_aktivitas, keterangan, tgl_rekam, petugas_rekam) ". 
               "values ( ".
               "	'$ls_kode_klaim', '$ln_no_urut', 'PENOLAKAN', sysdate, sysdate, 'TERBUKA', substr(upper('$ls_ket_submit'),1,300), sysdate, '$username' ".  
               ") ";
        $DB->parse($sql);
        $DB->execute();
        
        $sql = "update sijstk.pn_klaim_aktivitas a set status_aktivitas = 'SELESAI',tgl_akhir = sysdate,tgl_ubah = sysdate,petugas_ubah='$username' ".
               "where kode_klaim = '$ls_kode_klaim' ".
               "and no_urut in ".
               "( ".
               "     select max(no_urut) from sijstk.pn_klaim_aktivitas ".
               "     where kode_klaim = a.kode_klaim ".
               "     and no_urut < '$ln_no_urut' ".  
               "     ) ";
        $DB->parse($sql);
        $DB->execute();
        //end generate aktivitas klaim -------------------------------------------
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
			
			<div id="formKiri" style="width:900px;">
				<fieldset style="width:820px;"><legend>&nbsp;</legend>
									
          <div class="form-row_kiri">
          <label style = "text-align:right;">Alasan Penolakan &nbsp; *</label>
          	<textarea cols="255" rows="2" style="width:500px;background-color:#ffff99" id="alasan_penolakan" name="alasan_penolakan" tabindex="1" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_keterangan;?></textarea>   					
          </div>														
          <div class="clear"></div>
															
				</fieldset>	
										 	
        <div id="buttonbox" style="width:820px;text-align:center;">        			 
          <input type="button" class="btn green" id="simpan" name="simpan" value="               TOLAK               " onClick="if(confirm('Apakah anda yakin akan melakukan penolakan dan pengembalian data penetapan ..?')) fl_js_val_simpan();;">
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