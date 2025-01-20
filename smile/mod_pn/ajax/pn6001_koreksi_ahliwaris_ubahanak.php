<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SMILE";
$gs_pagetitle = "PN601 - KOREKSI DATA PENERIMA MANFAAT (ANAK)";

$ls_kode_tk	 		 					= !isset($_GET['kode_tk']) ? $_POST['kode_tk'] : $_GET['kode_tk'];
$ls_kode_klaim						= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ln_no_urut_keluarga			= !isset($_GET['no_urut_keluarga']) ? $_POST['no_urut_keluarga'] : $_GET['no_urut_keluarga'];
$ls_kode_penerima_berkala	= !isset($_GET['kode_penerima_berkala']) ? $_POST['kode_penerima_berkala'] : $_GET['kode_penerima_berkala'];
$ls_kode_agenda           = !isset($_GET['kode_agenda']) ? $_POST['kode_agenda'] : $_GET['kode_agenda'];

$ls_root_sender 				 	= !isset($_GET['root_sender']) ? $_POST['root_sender'] : $_GET['root_sender'];
$ls_sender 				 				= !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_activetab 			= !isset($_GET['sender_activetab']) ? $_POST['sender_activetab'] : $_GET['sender_activetab'];
$ls_sender_mid 						= !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
$ls_task 				 			 		= !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];
$btn_task 					 			= !isset($_GET['btn_task']) ? $_POST['btn_task'] : $_GET['btn_task'];

if ($ls_kode_tk!="")
{
  $sql = "select no_urut_keluarga, nama_lengkap,jenis_kelamin,kode_penerima_berkala from sijstk.pn_klaim_penerima_berkala ".
			 	 "where kode_klaim = '$ls_kode_klaim' and kode_penerima_berkala = 'TK' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();		
  $ls_nama_tk = $row['NAMA_LENGKAP'];
	$ls_jenis_kelamin_tk = $row['JENIS_KELAMIN'];
	$ln_no_urut_tk = $row['NO_URUT_KELUARGA'];
}

$ln_jpn_no_urut_anak1			= $_POST['jpn_no_urut_anak1'];
$ln_jpn_no_urut_anak2			= $_POST['jpn_no_urut_anak2'];
			
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
							
  <script language="JavaScript">    
  	function fl_js_val_simpan()
  	{
      var form = window.document.fpop;
      //alert(form.jpn_no_urut_anak2.value);
      if ((form.jpn_no_urut_anak1.value == form.jpn_no_urut_anak2.value))
			{
        alert('Anak I dan II tidak boleh sama, perbaiki data input...!!!');
        form.jpn_no_urut_anak2.focus();	
			}else
  		{
         form.btn_task.value="simpan";
         form.submit(); 		 
  		}								 
  	}
		
    function refreshParent() 
    {
    	if(window.opener.document.getElementById('formreg') != undefined){																							
    	<?php	
    	if($ls_sender!=''){			
    		echo "window.opener.reloadForm();"; 
    	}
    	?>	
    	}            
    }						
  </script>															
</head>
<body>
	<div id="header-popup">	
		<h3><?=$gs_pagetitle;?></h3>
	</div> 
	
	<div id="container-popup">
	<!--[if lte IE 6]>
	<div id="clearie6"></div>
	<![endif]-->	
	
	<?

	if($btn_task=="simpan")
	{   
     $sql1 = "INSERT INTO PN.PN_AGENDA_KOREKSI_AWARIS_DETIL
              (
                 SELECT '$ls_kode_agenda',
                           KODE_KLAIM,
                           (select nvl(max(no_mutasi),0)+1 from pn.pn_agenda_koreksi_awaris_detil where kode_agenda = '$ls_kode_agenda'),
                           SYSDATE,
                           SYSDATE,
                           '',
                           'Y',
                           'T',
                           NO_URUT_KELUARGA,
                           KODE_HUBUNGAN,
                           NAMA_LENGKAP,
                           NO_KARTU_KELUARGA,
                           NOMOR_IDENTITAS,
                           TEMPAT_LAHIR,
                           TGL_LAHIR,
                           JENIS_KELAMIN,
                           GOLONGAN_DARAH,
                           STATUS_KAWIN,
                           ALAMAT,
                           RT,
                           RW,
                           KODE_KELURAHAN,
                           KODE_KECAMATAN,
                           KODE_KABUPATEN,
                           KODE_POS,
                           TELEPON_AREA,
                           TELEPON,
                           TELEPON_EXT,
                           FAX_AREA,
                           FAX,
                           HANDPHONE,
                           EMAIL,
                           NPWP,
                           NAMA_PENERIMA,
                           BANK_PENERIMA,
                           NO_REKENING_PENERIMA,
                           NAMA_REKENING_PENERIMA,
                           KODE_BANK_PEMBAYAR,
                           KPJ_TERTANGGUNG,
                           PEKERJAAN,
                           KODE_KONDISI_TERAKHIR,
                           TGL_KONDISI_TERAKHIR,
                           STATUS_LAYAK,
                           --'A1',
                           KODE_PENERIMA_BERKALA,
                           KETERANGAN,
                           SYSDATE,
                           '$USER',
                           '',
                           '',
                           JENIS_IDENTITAS,
                           STATUS_VALID_IDENTITAS,
                           KODE_BANK_PENERIMA,
                           ID_BANK_PENERIMA,
                           STATUS_VALID_REKENING_PENERIMA,
                           STATUS_REKENING_SENTRAL,
                           KANTOR_REKENING_SENTRAL,
                           METODE_TRANSFER,
                           KODE_NEGARA,
                           ''
                    FROM  PN.PN_KLAIM_PENERIMA_BERKALA
                    WHERE KODE_KLAIM = '$ls_kode_klaim'
                    AND KODE_HUBUNGAN in ('A','1','2','3')
                    --AND NVL(STATUS_LAYAK,'T')='Y'
                );";
      $sql2 = "     UPDATE PN.PN_AGENDA_KOREKSI_AWARIS_DETIL
                    SET    KODE_PENERIMA_BERKALA_BARU = 'A1'
                    WHERE KODE_KLAIM = '$ls_kode_klaim'
                    AND   KODE_AGENDA = '$ls_kode_agenda'
                    AND NO_URUT_KELUARGA = '$ln_jpn_no_urut_anak1';

                    UPDATE PN.PN_AGENDA_KOREKSI_AWARIS_DETIL
                    SET    KODE_PENERIMA_BERKALA_BARU = 'A2'
                    WHERE KODE_KLAIM = '$ls_kode_klaim'
                    AND   KODE_AGENDA = '$ls_kode_agenda'
                    AND NO_URUT_KELUARGA = '$ln_jpn_no_urut_anak2';
                ";
      $sql = "
            BEGIN
                DELETE PN.PN_AGENDA_KOREKSI_AWARIS_DETIL WHERE KODE_AGENDA = '$ls_kode_agenda';
                $sql1
                $sql2
            EXCEPTION
            WHEN OTHERS THEN
            ROLLBACK;
            RAISE;
            END;";
      // print_r($sql);
      // die();
      $DB->parse($sql);
      if($DB->execute()){
        $msg = "Data berhasil tersimpan, session dilanjutkan...";
        $task = "edit"; 
        $ls_hiddenid = $ln_no_urut_keluarga;
        $editid = $ln_no_urut_keluarga; 
        $ls_error = 1;
      }else{
        $ls_error = -1;
        $msg = "Data gagal disimpan. .!!!!";
      }
      
  	

  		echo "<script language=\"JavaScript\" type=\"text/javascript\">";						
  		echo "refreshParent();";
  		echo "</script>";

  		echo "<script language=\"JavaScript\" type=\"text/javascript\">";           
      echo "window.location.replace('?task=edit&kode_agenda=$ls_kode_agenda&kode_tk=$ls_kode_tk&kode_klaim=$ls_kode_klaim&no_urut_keluarga=$ln_no_urut_keluarga&root_sender=$ls_root_sender&sender=$ls_sender&sender_activetab=$ls_sender_activetab&mid=$ls_sender_mid&msg=$msg');";
      echo "</script>";
      //echo "window.location.replace(window.location.href.replace('#', ''));";
      //var new_location = window.location.href.replace("#", "");
    
	} //end if(isset($_POST['simpan']))
		
	?>	
	
  <form name="fpop" method="post" action="<?=$PHP_SELF;?>">	
		<?
    $sql = "SELECT COUNT(1) AS ADA FROM PN.PN_AGENDA_KOREKSI_AWARIS_DETIL
                      WHERE KODE_AGENDA = '$ls_kode_agenda'";
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ln_exist = $row["ADA"];
    //echo $ln_exist;
    //ambil nama anak pertama ------------------------------------------------------
    if($ln_exist == 0){
      $sql = "select no_urut_keluarga from sijstk.pn_klaim_penerima_berkala a ". 
           "where kode_klaim = '$ls_kode_klaim' ". 
           "and kode_penerima_berkala = 'A1' ". 
           "and rownum = 1";
    }else{
      $sql = "select no_urut_keluarga from pn.pn_agenda_koreksi_awaris_detil a ". 
           "where kode_klaim = '$ls_kode_klaim' ".
           "and kode_agenda = '$ls_kode_agenda' ". 
           "and kode_penerima_berkala_baru = 'A1' ". 
           "and rownum = 1";
    }
    //echo $sql;
    //die();
  
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ln_jpn_no_urut_anak1 = $row["NO_URUT_KELUARGA"];
    
    				
    //ambil nama anak kedua --------------------------------------------------------
    if($ln_exist == 0){
      $sql = "select no_urut_keluarga from sijstk.pn_klaim_penerima_berkala a ". 
           "where kode_klaim = '$ls_kode_klaim' ". 
           "and kode_penerima_berkala = 'A2' ". 
           "and rownum = 1";
    }else{
      $sql = "select no_urut_keluarga from pn.pn_agenda_koreksi_awaris_detil a ". 
           "where kode_klaim = '$ls_kode_klaim' ".
           "and kode_agenda = '$ls_kode_agenda' ".  
           "and kode_penerima_berkala_baru = 'A2' ". 
           "and rownum = 1";
    }
    //echo $sql;
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ln_jpn_no_urut_anak2 = $row["NO_URUT_KELUARGA"];	
		
		?>									
		<div id="formframe" style="width:650px;">
			<span id="dispError1" style="display:none;color:red"></span>
			<input type="hidden" id="st_errval1" name="st_errval1">		
      <input type="hidden" id="kode_tk" name="kode_tk" value="<?=$ls_kode_tk;?>">
			<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
      <input type="hidden" id="kode_agenda" name="kode_agenda" value="<?=$ls_kode_agenda;?>">
			<input type="hidden" id="btn_task" name="btn_task" value=""> 
      <input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
    	<input type="hidden" id="root_sender" name="root_sender" value="<?=$ls_root_sender;?>">
			<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
      <input type="hidden" id="sender_activetab" name="sender_activetab" value="<?=$ls_sender_activetab;?>">
      <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">

			
			<div id="formKiri" style="width:650px;">				
				<fieldset style="width:600px;"><legend >Ubah Anak Yang Akan Menerima Manfaat Pensiun:</legend>
          <div class="form-row_kiri">
          <label style = "text-align:right;">Anak Penerima I</label>
          <!-- <input type="text" id="jpn_no_urut_anak1" name="jpn_no_urut_anak1" value="<?=$ln_jpn_no_urut_anak1;?>"> --> 
           <input type="hidden" id="kode_agenda" name="kode_agenda" value="<?=$ls_kode_agenda;?>">		 	    				
            <select size="1" id="jpn_no_urut_anak1" name="jpn_no_urut_anak1" value="<?=$ln_jpn_no_urut_anak1;?>" tabindex="1" class="select_format" style="width:300px;" >
            <option value="">-- Pilih --</option>
            <?					 	 

              $sql = "select no_urut_keluarga, nama_lengkap from sijstk.pn_klaim_penerima_berkala ".
                     "where kode_klaim = '$ls_kode_klaim' ".
                     "and kode_hubungan in ('A','1','2','3') ".
                     "and nvl(status_layak,'T')='Y' ".
                     "order by tgl_lahir ";
              $DB->parse($sql);
              $DB->execute();
              while($row = $DB->nextrow())
              {
                echo "<option ";
                if ($row["NO_URUT_KELUARGA"]==$ln_jpn_no_urut_anak1 && strlen($ln_jpn_no_urut_anak1)==strlen($row["NO_URUT_KELUARGA"])){ echo " selected"; }
                echo " value=\"".$row["NO_URUT_KELUARGA"]."\">".$row["NAMA_LENGKAP"]."</option>";
              }
            ?>
            </select>
          </div>																																													
        	<div class="clear"></div>

          <div class="form-row_kiri">
          <label style = "text-align:right;">Anak Penerima II</label>		
           <!-- <input type="hidden" id="jpn_no_urut_anak2" name="jpn_no_urut_anak2" value="<?=$ln_jpn_no_urut_anak2;?>">  --> 	    				
            <select size="1" id="jpn_no_urut_anak2" name="jpn_no_urut_anak2" value="<?=$ln_jpn_no_urut_anak2;?>" tabindex="2" class="select_format" style="width:300px;" >
            <option value="">-- Pilih --</option>
            <?					 	 
              $sql = "select no_urut_keluarga, nama_lengkap from sijstk.pn_klaim_penerima_berkala ".
									 	 "where kode_klaim = '$ls_kode_klaim' ".
										 "and kode_hubungan in ('A','1','2','3') ".
										 "and nvl(status_layak,'T')='Y' ".
										 "order by tgl_lahir ";
              $DB->parse($sql);
              $DB->execute();
              while($row = $DB->nextrow())
              {
                echo "<option ";
                if ($row["NO_URUT_KELUARGA"]==$ln_jpn_no_urut_anak2 && strlen($ln_jpn_no_urut_anak2)==strlen($row["NO_URUT_KELUARGA"])){ echo " selected"; }
                echo " value=\"".$row["NO_URUT_KELUARGA"]."\">".$row["NAMA_LENGKAP"]."</option>";
              }
            ?>
            </select>
          </div>																																													
        	<div class="clear"></div>
																			
				</fieldset>
								
        <? 					
        if(!empty($ls_kode_klaim))
        {
        ?>			 	
          <div id="buttonbox" style="width:620px;text-align:center;">       			 
          <?if ($ls_task == "edit" || $ls_task == "new")
					{
  					?>
  					<input type="button" class="btn green" id="simpan" name="simpan" value="            SIMPAN           " onClick="if(confirm('Apakah anda yakin akan menyimpan data..?')) fl_js_val_simpan();">
						<?
					}
					?>
					<input type="button" class="btn green" id="close" name="close" onclick="window.close();" value="           TUTUP           " />       					
          </div>							 			 
        <? 					
        }
        ?>	
				
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
			</div>	 
  	</div>
	  
	</form>
</body>
</html>				