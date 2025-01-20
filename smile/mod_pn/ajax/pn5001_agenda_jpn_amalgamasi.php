<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "PN5001 - AMALGAMASI JP";

$ls_kode_tk	 		 					= !isset($_GET['kode_tk']) ? $_POST['kode_tk'] : $_GET['kode_tk'];
$ls_kode_klaim						= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];

$ls_root_sender 				 	= !isset($_GET['root_sender']) ? $_POST['root_sender'] : $_GET['root_sender'];
$ls_sender 				 				= !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_activetab 			= !isset($_GET['sender_activetab']) ? $_POST['sender_activetab'] : $_GET['sender_activetab'];
$ls_sender_mid 						= !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
$ls_task 				 			 		= !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];
$btn_task 					 			= !isset($_GET['btn_task']) ? $_POST['btn_task'] : $_GET['btn_task'];

if ($ls_kode_tk!="")
{
  $sql = "select kpj, nomor_identitas, nama_lengkap, tempat_lahir, to_char(tgl_lahir,'dd/mm/yyyy') tgl_lahir 
          from sijstk.pn_klaim_tkgabung
          where kode_klaim = '$ls_kode_klaim'
          and kode_tk = '$ls_kode_tk' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();		
  $ls_nomor_identitas = $row['NOMOR_IDENTITAS'];
	$ls_nama_lengkap = $row['NAMA_LENGKAP'];
	$ls_tempat_lahir = $row['TEMPAT_LAHIR'];
	$ld_tgl_lahir = $row['TGL_LAHIR'];
	$ls_kpj = $row['KPJ'];
}

$ls_search_kpj  = $_POST['search_kpj'];
$ls_search_nomor_identitas  = !isset($_POST['search_nomor_identitas']) ? $ls_nomor_identitas : $_POST['search_nomor_identitas'];
//$ls_search_nama_lengkap 		= !isset($_POST['search_nama_lengkap']) ? $ls_nama_lengkap : $_POST['search_nama_lengkap'];
//$ls_search_tempat_lahir 		= !isset($_POST['search_tempat_lahir']) ? $ls_tempat_lahir : $_POST['search_tempat_lahir'];
$ld_search_tgl_lahir 				= !isset($_POST['search_tgl_lahir']) ? $ld_tgl_lahir : $_POST['search_tgl_lahir'];

//update status cek amalgamasi jp ----------------------------------------------
$qry = "update sijstk.pn_klaim ".
       "set status_cek_amalgamasi    = 'Y', ".
       "    tgl_ubah           			 = sysdate, ".
       "    petugas_ubah       			 = '$username' ".
       "where kode_klaim = '$ls_kode_klaim' ";
$DB->parse($qry);
$DB->execute();
			
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
    function fl_js_set_status_valid(v_i)
    {
    	var form = document.fpop;
			var n_d_status_valid = 'd_status_valid'+v_i;								
			
    	if (document.getElementById(n_d_status_valid).checked)
    	{
				document.getElementById(n_d_status_valid).value = 'Y';
    	}
    	else
    	{
    		document.getElementById(n_d_status_valid).value = 'T';
    	}									
    }		
		
  	function fl_js_val_simpan()
  	{
      var form = window.document.fpop;
      
      form.btn_task.value="simpan";
      form.submit(); 		 						 
  	}
		
    function refreshParent() 
    {
    	if(window.opener.document.getElementById('formreg') != undefined){																							
    	<?php	
    	if($ls_sender!=''){			
    		echo "window.opener.location.replace('../form/$ls_sender?task=Edit&mid=$ls_sender_mid&dataid=$ls_kode_klaim&kode_klaim=$ls_kode_klaim&activetab=$ls_sender_activetab');";		
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
		//insert data tk amalgamasi ------------------------------------------------
    $ln_panjang = $_POST['d_kounter_dtl'];
    for($i=0;$i<=$ln_panjang-1;$i++)
    {		 	           												 		        					      
			$ls_d_jp_kpj						= $_POST['d_jp_kpj'.$i];
			$ls_d_jp_nik						= $_POST['d_jp_nik'.$i];
      $ls_d_jp_nama_tk				= $_POST['d_jp_nama_tk'.$i];
			$ls_d_jp_tempat_lahir		= $_POST['d_jp_tempat_lahir'.$i];
			$ld_d_jp_tgl_lahir			= $_POST['d_jp_tgl_lahir'.$i];
			$ls_d_jp_kode_tk				= $_POST['d_jp_kode_tk'.$i];
			$ls_d_status_valid			= $_POST['d_status_valid'.$i];
			
      if ($ls_d_status_valid=="on" || $ls_d_status_valid=="ON" || $ls_d_status_valid=="Y")
      {
      	$ls_d_status_valid = "Y";
      }else
      {
      	$ls_d_status_valid = "T";	 
      }			
      
      if ($ls_d_jp_kode_tk!="")
      {
				$sql = "insert into sijstk.pn_klaim_tkgabung ( ".
               "		kode_klaim, kode_tk, kpj, nomor_identitas, nama_lengkap, tempat_lahir,  ".
               "		tgl_lahir, kode_tk_gabung, tgl_rekam, petugas_rekam) ". 
               "values ( ".
               "    '$ls_kode_klaim', '$ls_d_jp_kode_tk', '$ls_d_jp_kpj','$ls_d_jp_nik', '$ls_d_jp_nama_tk', '$ls_d_jp_tempat_lahir', ". 
               "    to_date('$ld_d_jp_tgl_lahir','dd/mm/yyyy'), null, sysdate, '$username' ". 
							 ") ";
        $DB->parse($sql);
        $DB->execute();
      }							
    }     			
		//end proses amalgamasi ----------------------------------------------------
		
    //post proses ---------------------------------------------------------- 
    $qry = "BEGIN SIJSTK.P_PN_PN5001.X_POST_UPDATE('$ls_kode_klaim','$username',:p_sukses,:p_mess);END;";											 	
    $proc = $DB->parse($qry);				
    oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
    oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ls_sukses = $p_sukses;	
    $ls_mess = $p_mess;	
    
    $msg = "Proses amalgamasi berhasil dilakukan, session dilanjutkan...";
    $task = "edit";	
    $ls_hiddenid = $ls_kode_tk;
    $editid = $ls_kode_tk;		
    
    echo "<script language=\"JavaScript\" type=\"text/javascript\">";						
    echo "refreshParent();";
    echo "</script>";
    
    echo "<script language=\"JavaScript\" type=\"text/javascript\">";						
    echo "window.location.replace('?task=edit&kode_tk=$ls_kode_tk&kode_klaim=$ls_kode_klaim&no_urut_keluarga=$ln_no_urut_keluarga&root_sender=$ls_root_sender&sender=$ls_sender&sender_activetab=$ls_sender_activetab&mid=$ls_sender_mid&msg=$msg');";
    echo "</script>";					            
	} //end if(isset($_POST['simpan']))	
	?>	
	
  <form name="fpop" method="post" action="<?=$PHP_SELF;?>">							
		<div id="formframe" style="width:850px;">
			<span id="dispError1" style="display:none;color:red"></span>
			<input type="hidden" id="st_errval1" name="st_errval1">		
      <input type="hidden" id="kode_tk" name="kode_tk" value="<?=$ls_kode_tk;?>">
			<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
			<input type="hidden" id="btn_task" name="btn_task" value=""> 
      <input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
    	<input type="hidden" id="root_sender" name="root_sender" value="<?=$ls_root_sender;?>">
			<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
      <input type="hidden" id="sender_activetab" name="sender_activetab" value="<?=$ls_sender_activetab;?>">
      <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">	
			
			<div id="formKiri" style="width:850px;">
				<fieldset style="width:820px;"><legend >Data TK</legend>	
          <div class="form-row_kiri">
          <label style = "text-align:right;">NIK &nbsp;&nbsp;&nbsp;&nbsp;</label>		 	    				
          	<input type="text" id="nomor_identitas" name="nomor_identitas" value="<?=$ls_nomor_identitas;?>" size="40" readonly class="disabled">		   
          </div>																																														
          <div class="form-row_kanan">
          <label style = "text-align:right;">Tempat Lahir</label>		 	    				
          	<input type="text" id="tempat_lahir" name="tempat_lahir" value="<?=$ls_tempat_lahir;?>" size="40" readonly class="disabled">		   
          </div>
        	<div class="clear"></div>			
											
          <div class="form-row_kiri">
          <label style = "text-align:right;">Nama Lengkap</label>		 	    				
          	<input type="text" id="nama_lengkap" name="nama_lengkap" value="<?=$ls_nama_lengkap;?>" size="40" readonly class="disabled">		   
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">Tgl Lahir</label>		 	    				
          	<input type="text" id="tgl_lahir" name="tgl_lahir" value="<?=$ld_tgl_lahir;?>"  size="40" readonly class="disabled">   
          </div>																																																				
        	<div class="clear"></div>	

          <div class="form-row_kiri">
          <label style = "text-align:right;">No. Referensi &nbsp;</label>		 	    				
          	<input type="text" id="kpj" name="kpj" value="<?=$ls_kpj;?>" size="35" readonly class="disabled">		   
          </div>																																														
        	<div class="clear"></div>	
																										
				</fieldset>
				
				</br>
							
				<fieldset style="width:820px;"><legend >Pencairan Data TK untuk Kartu Lainnya</legend>			
          <div class="form-row_kiri">
          <label style = "text-align:right;">NIK &nbsp;&nbsp;&nbsp;&nbsp;</label>		 	    				
          	<input type="text" id="search_nomor_identitas" name="search_nomor_identitas" value="<?=$ls_search_nomor_identitas;?>" tabindex="1" size="40">		
						<input type="hidden" id="search_nama_lengkap" name="search_nama_lengkap" value="<?=$ls_search_nama_lengkap;?>" tabindex="2" size="40"> 
						<input type="hidden" id="search_tempat_lahir" name="search_tempat_lahir" value="<?=$ls_search_tempat_lahir;?>" tabindex="3" size="40">  
          </div>																																														
        	<div class="clear"></div>			

          <div class="form-row_kiri">
          <label style = "text-align:right;">No Referensi</label>		 	    				
          	<input type="text" id="search_kpj" name="search_kpj" value="<?=$ls_search_kpj;?>" tabindex="2" size="40">		   
          </div>																																														
        	<div class="clear"></div>	
										
          <div class="form-row_kiri">
          <label style = "text-align:right;">Tgl Lahir</label>		 	    				
          	<input type="text" id="search_tgl_lahir" name="search_tgl_lahir" value="<?=$ld_search_tgl_lahir;?>" tabindex="4" size="20">
						<input id="btn_search_tgl_lahir" type="image" align="top" onclick="return showCalendar('search_tgl_lahir', 'dd-mm-y');" src="../../images/calendar.gif" />
						<input type="submit" class="btn green" id="btntampilkan" name="btntampilkan" value="      CARI      " >		   
          </div>																																														
        	<div class="clear"></div>
																					
				</fieldset>
				
				</br>
				
        <fieldset style="width:820px;"><legend>Hasil Pencarian :</legend>
        	</br>
          <table id="tblrincian1" width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
            <tbody>	
            <tr>
            	<th colspan="7"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
            </tr>	
            <tr>
              <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No. Referensi</th>
    					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">NIK</th>
              <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama TK</th>
    					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tempat Lahir</th>
    					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tgl Lahir</th>
              <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Kode TK</th>
    					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Action</th>                  		
            </tr>
            <tr>
            	<th colspan="7"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
            </tr>
              <?						
              if ($ls_kode_tk!="")
              {			
                if ($ls_search_nomor_identitas!="")
								{
								 	 $ls_filter_nomor_identitas = " and a.nomor_identitas = '$ls_search_nomor_identitas' "; 	 
								}
								
                //if ($ls_search_nama_lengkap!="")
								//{
								// 	 $ls_filter_nama_lengkap = " and a.nama_lengkap = '$ls_search_nama_lengkap' "; 	 
								//}

                //if ($ls_search_tempat_lahir!="")
								//{
								// 	 $ls_filter_tempat_lahir = " and a.tempat_lahir = '$ls_search_tempat_lahir' "; 	 
								//}

                if ($ls_search_kpj!="")
								{
								 	 $ls_filter_kpj = " and a.kpj = '$ls_search_kpj' "; 	 
								}
																
                if ($ld_search_tgl_lahir!="")
								{
								 	 $ls_filter_tgl_lahir = " and to_char(a.tgl_lahir,'yyyymmdd') = to_char(to_date('$ld_search_tgl_lahir','dd/mm/yyyy'),'yyyymmdd') "; 	 
								}
																																
								$sql = "select kode_tk, kpj, nomor_identitas, nama_tk, tempat_lahir, to_char(tgl_lahir,'dd/mm/yyyy') tgl_lahir ".
                       "from sijstk.vw_kn_tk a ".
                       "where 1 = 1 ".
                       $ls_filter_kpj.
                       $ls_filter_tgl_lahir.
                       $ls_filter_nomor_identitas.
											 "and exists ".
                       "( ".
                       "    select null from sijstk.kn_kepesertaan_tk_prg where kode_kepesertaan = a.kode_kepesertaan and kd_prg=4 ".
                       ") ".
											 "and not exists( ".
											 " select null from sijstk.pn_klaim_tkgabung where kode_klaim = '$ls_kode_klaim' and kode_tk = a.kode_tk ".
											 ") ";        
      					//echo $sql;
								$DB->parse($sql);
                $DB->execute();							              					
                $i=0;		
                $ln_dtl =0;										
                while ($row = $DB->nextrow())
                {
                ?>
                  <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>	
                    <td>								
                      <input type="hidden" id="d_jp_kpj<?=$i;?>" name="d_jp_kpj<?=$i;?>" value="<?=$row['KPJ'];?>" size="14" maxlength="15">
      								<input type="hidden" id="d_jp_nik<?=$i;?>" name="d_jp_nik<?=$i;?>" value="<?=$row['NOMOR_IDENTITAS'];?>">
      								<input type="hidden" id="d_jp_nama_tk<?=$i;?>" name="d_jp_nama_tk<?=$i;?>" value="<?=$row['NAMA_TK'];?>">
      								<input type="hidden" id="d_jp_tempat_lahir<?=$i;?>" name="d_jp_tempat_lahir<?=$i;?>" value="<?=$row['TEMPAT_LAHIR'];?>">
      								<input type="hidden" id="d_jp_tgl_lahir<?=$i;?>" name="d_jp_tgl_lahir<?=$i;?>" value="<?=$row['TGL_LAHIR'];?>">
      								<input type="hidden" id="d_jp_kode_tk<?=$i;?>" name="d_jp_kode_tk<?=$i;?>" value="<?=$row['KODE_TK'];?>">
      								<?=$row['KPJ'];?>
      							</td>
      							<td style="text-align:center;"><?=$row['NOMOR_IDENTITAS'];?></td>							
                    <td style="text-align:center;"><?=$row['NAMA_TK'];?></td>
      							<td style="text-align:center;"><?=$row['TEMPAT_LAHIR'];?></td>
      							<td style="text-align:center;"><?=$row['TGL_LAHIR'];?></td>
      							<td style="text-align:center;"><?=$row['KODE_TK'];?></td>																
                    <td style="text-align:center;">	
        							<?	
											if (
												 	($row['NOMOR_IDENTITAS'] == $ls_nomor_identitas) &&
													($row['NAMA_TK'] == $ls_nama_lengkap) &&
													($row['TEMPAT_LAHIR'] == $ls_tempat_lahir) &&
													($row['TGL_LAHIR'] == $ld_tgl_lahir)
											)
											{
											 	$ls_status_valid = "Y";	
											}else
											{
											 	$ls_status_valid = "T";	
											}		
																					
        							if ($ls_status_valid =="Y")
        							{
      								 	?>
                    		<input type="checkbox" class="cebox" id="d_status_valid<?=$i;?>" name="d_status_valid<?=$i;?>" value="<?=$ls_status_valid;?>" onclick="fl_js_set_status_valid(<?=$i;?>)" <?=$ls_status_valid=="Y" || $ls_status_valid=="ON" || $ls_status_valid=="on" ? "checked" : "";?>>
      									Valid
												<?
      								}else
      								{
      								 	?>
                    		<input type="checkbox" disabled class="cebox" id="dcb_status_valid<?=$i;?>" name="dcb_status_valid<?=$i;?>" value="<?=$ls_status_valid;?>" <?=$ls_status_valid=="Y" || $ls_status_valid=="ON" || $ls_status_valid=="on" ? "checked" : "";?>>
      									<input type="hidden" id="d_status_valid<?=$i;?>" name="d_status_valid<?=$i;?>" value="<?=$ls_status_valid;?>">
      									Valid
												<?								
      								}
      								?>																            	
                    </td>																									
                  </tr>
                  <?								    							
                  $i++;//iterasi i
                }	//end while
                $ln_dtl=$i;
              }						
              ?>									             																
            </tbody>
            <tr>
              <td style="text-align:center" colspan="7"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></b>
                <input type="hidden" id="d_kounter_dtl" name="d_kounter_dtl" value="<?=$ln_dtl;?>">
                <input type="hidden" id="d_count_dtl" name="d_count_dtl" value="<?=$ln_countdtl;?>">
                <input type="hidden" name="d_showmessage" style="border-width: 0;text-align:right" readonly size="5">					
              </td>
            </tr>																					
          </table>
          </br>
        </fieldset>
				
        <? 					
        if(!empty($ls_kode_klaim))
        {
        ?>			 	
          <div id="buttonbox" style="width:820px;text-align:center;">       			 
          <?if ($ls_task == "edit" || $ls_task == "new")
					{
  					?>
  					<input type="button" class="btn green" id="simpan" name="simpan" value="         AMALGAMASI JP           " onClick="if(confirm('Apakah anda yakin akan melakukan proses amalgamasi..?')) fl_js_val_simpan();">
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