<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
include "../../includes/fungsi_newrpt.php";

$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "New Core System";
$gs_pagetitle = "Cetak Pembayaran Jaminan Koreksi";
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
        
				<script language="JavaScript">			 
          function gantiId(theForm) {
            window.location.replace('?cbulanke='+nu+'');
          }	
      		function confirmDelete(delUrl) {
            if (confirm("Are you sure you want to delete this record")) {
              document.location = delUrl;
            }
          }
    			function confirmapproval(apvUrl) {
      			if (confirm("Anda yakin menyimpan data?")) {
      				 window.document.location = apvUrl;
      			}		 
    			}												
    		</script>
        <script language="JavaScript">
          function fl_js_set_st_kwitansi()
          {
          	var form = document.adminForm;
          	if (form.st_kwitansi.checked)
          	{
          		form.st_kwitansi.value = "Y";
          	}
          	else
          	{
          		form.st_kwitansi.value = "T";
          	}	
          }
          function fl_js_set_st_spb()
          {
          	var form = document.adminForm;
          	if (form.st_spb.checked)
          	{
          		form.st_spb.value = "Y";
          	}
          	else
          	{
          		form.st_spb.value = "T";
          	}	
          }
          function fl_js_set_st_voucher()
          {
          	var form = document.adminForm;
          	if (form.st_voucher.checked)
          	{
          		form.st_voucher.value = "Y";
          	}
          	else
          	{
          		form.st_voucher.value = "T";
          	}	
          }
          function fl_js_set_st_bp21()
          {
          	var form = document.adminForm;
          	if (form.st_bp21.checked)
          	{
          		form.st_bp21.value = "Y";
          	}
          	else
          	{
          		form.st_bp21.value = "T";
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
    		<form name="adminForm" id="adminForm" method="post" action="<?=$PHP_SELF;?>">
          <?
          $ls_sender				= !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
					$ls_jenis_laporan	= !isset($_GET['jenis_laporan']) ? $_POST['jenis_laporan'] : $_GET['jenis_laporan'];
          if ($ls_jenis_laporan=="")
          {
           $ls_jenis_laporan = "lap1";
          }							
          $ls_kode_pembayaran	= !isset($_GET['kode_pembayaran']) ? $_POST['kode_pembayaran'] : $_GET['kode_pembayaran'];
         /* $sql = 	"select ".
                  "    a.kode_pembayaran, a.kode_klaim, a.kode_tipe_penerima, d.nama_tipe_penerima, a.kd_prg, ".  
                  "    b.nama_rekening_penerima, b.bank_penerima, b.no_rekening_penerima, ".
                  "    c.no_penetapan, a.no_pointer, nvl(a.flag_pph_progresif,'T') flag_pph_progresif ".  
                  "from sijstk.pn_klaim_pembayaran a, sijstk.pn_klaim_penerima_manfaat b, sijstk.pn_klaim c, sijstk.pn_kode_tipe_penerima d ".
                  "where a.kode_klaim = b.kode_klaim and a.kode_tipe_penerima = b.kode_tipe_penerima ". 
                  "and a.kode_klaim = c.kode_klaim and a.kode_tipe_penerima = d.kode_tipe_penerima ".
                  "and a.kode_pembayaran = '$ls_kode_pembayaran' ";
				  */
				  
		  //diupdate untuk akomodir cetak kwitansi/spb/voucher JPN
		  $sql = "select 
                      a.kode_pembayaran, a.kode_klaim, a.kode_tipe_penerima, d.nama_tipe_penerima, a.kd_prg,   
                      b.nama_rekening_penerima, b.bank_penerima, b.no_rekening_penerima, 
                      c.kode_agenda no_penetapan, a.no_pointer, nvl(a.flag_pph_progresif,'T') flag_pph_progresif   
                  from pn.pn_agenda_koreksi_klaim_bayar a, pn.pn_agenda_koreksi_klaim_trm b, pn.pn_agenda_koreksi_klaim c, sijstk.pn_kode_tipe_penerima d 
                  where a.kode_klaim = b.kode_klaim and a.kode_tipe_penerima = b.kode_tipe_penerima  
                  and a.kode_klaim = c.kode_klaim and a.kode_tipe_penerima = d.kode_tipe_penerima 
                  and a.kode_pembayaran = '$ls_kode_pembayaran' 
                   "; 
          $DB->parse($sql);
          $DB->execute();
          $row = $DB->nextrow();
					$ls_kode_pembayaran			= $row["KODE_PEMBAYARAN"];
					$ls_kode_klaim 					= $row["KODE_KLAIM"];
					$ls_kode_tipe_penerima	= $row["KODE_TIPE_PENERIMA"];	
          $ls_tipe_penerima				= $row["NAMA_TIPE_PENERIMA"];
					$ls_kd_prg							= $row["KD_PRG"];
          $ls_nm_rek_penerima			= $row["NAMA_REKENING_PENERIMA"];
          $ls_bank_penerima				= $row["BANK_PENERIMA"];
          $ls_no_rek_penerima			= $row["NO_REKENING_PENERIMA"];
					$ls_no_penetapan				=	$row["NO_PENETAPAN"]; 
					$ls_no_pointer					=	$row["NO_POINTER"];  
					$ls_flag_pph_progresif	=	$row["FLAG_PPH_PROGRESIF"];
					
					$ls_st_kwitansi	= !isset($_GET['st_kwitansi']) ? $_POST['st_kwitansi'] : $_GET['st_kwitansi'];
					$ls_st_spb	= !isset($_GET['st_spb']) ? $_POST['st_spb'] : $_GET['st_spb'];
					$ls_st_voucher	= !isset($_GET['st_voucher']) ? $_POST['st_voucher'] : $_GET['st_voucher'];
					$ls_st_bp21	= !isset($_GET['st_bp21']) ? $_POST['st_bp21'] : $_GET['st_bp21'];
					
          if ($ls_st_kwitansi=="on" || $ls_st_kwitansi=="ON" || $ls_st_kwitansi=="Y")
          {
          	$ls_st_kwitansi = "Y";
          }else
          {
          	$ls_st_kwitansi = "T";
          }						
																			
          if ($ls_st_spb=="on" || $ls_st_spb=="ON" || $ls_st_spb=="Y")
          {
          	$ls_st_spb = "Y";
          }else
          {
          	$ls_st_spb = "T";
          }	
          if ($ls_st_voucher=="on" || $ls_st_voucher=="ON" || $ls_st_voucher=="Y")
          {
          	$ls_st_voucher = "Y";
          }else
          {
          	$ls_st_voucher = "T";
          }	
          if ($ls_st_bp21=="on" || $ls_st_bp21=="ON" || $ls_st_bp21=="Y")
          {
          	$ls_st_bp21 = "Y";
          }else
          {
          	$ls_st_bp21 = "T";
          }	
			
					if(isset($_POST["butcetak_all"]))
          {		
          	$ls_user_param .= " qkode_pembayaran='$ls_kode_pembayaran'";
          	$ls_user_param .= " qkode_klaim='$ls_kode_klaim'";
						
            $sql = 	"select to_char(sysdate,'yyyymmdd') as vtgl, replace('$ls_tipe_penerima',' ','XXX') tipe_penerima, ".
								 		"		to_char(to_date('$ld_blth_proses','dd/mm/yyyy'),'yyyymmdd') as vblth_proses ".	
										"from dual ";
            $DB->parse($sql);
            $DB->execute();
            $row = $DB->nextrow();
            $ls_lap_tipe_penerima 	= $row["TIPE_PENERIMA"];
						$ls_lap_blth_proses 	= $row["VBLTH_PROSES"];			
          	$ls_user_param .= " qtipe_penerima='$ls_lap_tipe_penerima'";          
          	$ls_user_param .= " qtgl='$ld_tglcetak'";
          	$ls_user_param .= " qblth_proses='$ls_lap_blth_proses'";

          	if ($ls_st_bp21!="T")
          	{
							 $ls_user_param .= " qkodepointer_asal='JM09'"; 
							 $ls_user_param .= " qidpointer_asal='$ls_kode_pembayaran'";
							 
               $tipe4 = isset($iscetak) ? "PDF" : "PDF";
               $ls_modul4 = "LK";
							 
							 if ($ls_flag_pph_progresif=="Y")
							 {
							 		$ls_nama_rpt4  .= "TAXR301408.rdf";
							 }else
							 {
          	 	 	  $ls_nama_rpt4  .= "PNRPP0205012.rdf";
							 }
							 exec_rpt_enc_new(1, $ls_modul4, $ls_nama_rpt4, $ls_user_param, $tipe4);								 							 
          	}									
          	if ($ls_st_voucher!="T" && $ls_no_pointer!="")
          	{
							 $ls_user_param .= " qpointer='PN01'"; 
							 $ls_user_param .= " qiddokumen_induk='$ls_kode_klaim'"; 
							 $ls_user_param .= " qiddokumen='$ls_no_pointer'";
						 	 $ls_user_param .= " quser_cetak='$username'"; 							  	 
							 
               $tipe3 = isset($iscetak) ? "PDF" : "PDF";
               $ls_modul3 = "LK";
		
          	 	 $ls_nama_rpt3  .= "PNRPP0205013.rdf";
							 exec_rpt_enc_new(1, $ls_modul3, $ls_nama_rpt3, $ls_user_param, $tipe3);									 						 
						}
          	if ($ls_st_spb!="T")
          	{
               $tipe2 = isset($iscetak) ? "PDF" : "PDF";
               $ls_modul2 = "PN";
		
          	 	 $ls_nama_rpt2 .= "PNRPP0205011.rdf";	
							 exec_rpt_enc_new(1, $ls_modul2, $ls_nama_rpt2, $ls_user_param, $tipe2);								 						 
          	}						
          	if ($ls_st_kwitansi!="T")
          	{
               $tipe1 = isset($iscetak) ? "PDF" : "PDF";
               $ls_modul1 = "PN";
		
          	 	 $ls_nama_rpt1 .= "PNRPP0205010.rdf";	
							 exec_rpt_enc_new(1, $ls_modul1, $ls_nama_rpt1, $ls_user_param, $tipe1);					 
          	}							 
						          	
          	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
          	echo "window.location.replace('?kode_pembayaran=$ls_kode_pembayaran&jenis_laporan=$ls_jenis_laporan&st_kwitansi=$ls_st_kwitansi&st_spb=$ls_st_spb&st_voucher=$ls_st_voucher&st_bp21=$ls_st_bp21&sender=$ls_sender');";
          	echo "</script>";									
          } 								         		 
          ?>				
					
    			<table class="captionentry">
      			<tr> 
        		 <td align="left">No. Penetapan : <b><?=$ls_no_penetapan;?></b> </td>						 
      			</tr>
    			</table>								
        	<div id="formframe">
        		<span id="dispError" style="display:none;color:red"></span>
        		<input type="hidden" id="st_errval" name="st_errval">
        		<span id="dispError1" style="display:none;color:red"></span>
        		<input type="hidden" id="st_errval1" name="st_errval1">					
        		<span id="dispError2" style="display:none;color:red"></span>
        		<input type="hidden" id="st_errval2" name="st_errval2">
						<input type="hidden" id="kode_pembayaran" name="kode_pembayaran" value="<?=$ls_kode_pembayaran;?>" size="40" readonly class="disabled">
        		<input type="hidden" id="no_penetapan" name="no_penetapan" value="<?=$ls_no_penetapan;?>" size="50"/>
        		<input type="hidden" id="kd_prg" name="kd_prg" value="<?=$ls_kd_prg;?>" size="50"/>
						<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>" size="50"/>
						<input type="hidden" id="blth_proses" name="blth_proses" value="<?=$ld_blth_proses;?>" size="50"/>						
									
  					<div id="formKiri">
    					<fieldset style="width:500px;"><legend>Parameter</legend>
        				<div class="form-row_kiri">
        				<label>Kode Klaim</label>
        					<input type="text" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>" size="40" readonly class="disabled">
        				</div>
        				<div class="clear"></div>
															
        				<div class="form-row_kiri">
        				<label>Tipe Penerima</label>
        					<input type="text" id="tipe_penerima" name="tipe_penerima" value="<?=$ls_tipe_penerima;?>" size="40" readonly class="disabled">
        				</div>
        				<div class="clear"></div>																

        				<div class="form-row_kiri">
        				<label>Transfer ke Bank</label>
        					<input type="text" id="bank_penerima" name="bank_penerima" value="<?=$ls_bank_penerima;?>" size="40" readonly class="disabled">
        				</div>
        				<div class="clear"></div>

        				<div class="form-row_kiri">
        				<label>No Rekening</label>
        					<input type="text" id="no_rek_penerima" name="no_rek_penerima" value="<?=$ls_no_rek_penerima;?>" size="40" readonly class="disabled">
        				</div>
        				<div class="clear"></div>
								
        				<div class="form-row_kiri">
        				<label>A/N</label>
        					<input type="text" id="nm_rek_penerima" name="nm_rek_penerima" value="<?=$ls_nm_rek_penerima;?>" size="40" readonly class="disabled">
        				</div>
        				<div class="clear"></div>
																
								<br>
								
    						<div class="form-row_kiri">
        				<label>Lampiran :</label>						
        					<?$ls_st_kwitansi = isset($ls_st_kwitansi) ? $ls_st_kwitansi : "Y";							
									?>					
    							<input type="checkbox" id="st_kwitansi" name="st_kwitansi" class="cebox" onclick="fl_js_set_st_kwitansi();" <?=$ls_st_kwitansi=="Y" ||$ls_st_kwitansi=="ON" ||$ls_st_kwitansi=="on" ? "checked" : "";?>>
        					<i><font  color="#009999">Kwitansi</font></i>	
    						</div>											
    						<div class="clear"></div>	
								
    						<div class="form-row_kiri">
        				<label  style = "text-align:right;">&nbsp;</label>						
        					<? $ls_st_spb = isset($ls_st_spb) ? $ls_st_spb : "Y";?>					
    							<input type="checkbox" id="st_spb" name="st_spb" class="cebox" onclick="fl_js_set_st_spb();" <?=$ls_st_spb=="Y" ||$ls_st_spb=="ON" ||$ls_st_spb=="on" ? "checked" : "";?>>
        					<i><font  color="#009999">Surat Perintah Bayar</font></i>	
    						</div>											
    						<div class="clear"></div>	

    						<div class="form-row_kiri">
        				<label  style = "text-align:right;">&nbsp;</label>						
        					<? $ls_st_voucher = isset($ls_st_voucher) ? $ls_st_voucher : "Y";?>					
    							<input type="checkbox" id="st_voucher" name="st_voucher" class="cebox" onclick="fl_js_set_st_voucher();" <?=$ls_st_voucher=="Y" ||$ls_st_voucher=="ON" ||$ls_st_voucher=="on" ? "checked" : "";?>>
        					<i><font  color="#009999">Voucher</font></i>	
    						</div>											
    						<div class="clear"></div>

    						<div class="form-row_kiri">
        				<label  style = "text-align:right;">&nbsp;</label>						
        					<? $ls_st_bp21 = isset($ls_st_bp21) ? $ls_st_bp21 : "Y";?>					
    							<input type="checkbox" id="st_bp21" name="st_bp21" class="cebox" onclick="fl_js_set_st_bp21();" <?=$ls_st_bp21=="Y" ||$ls_st_bp21=="ON" ||$ls_st_bp21=="on" ? "checked" : "";?>>
        					<i><font  color="#009999">Bukti Potong PPh21</font></i>	
    						</div>											
    						<div class="clear"></div>
																																																								  																																								
    					</fieldset>
  						
    					<fieldset style="width:500px;"><legend>&nbsp;</legend>
    						<!--<input type="submit" class="btn green" id="butcetak" name="butcetak" value="          CETAK       " />-->
								<input type="submit" class="btn green" id="butcetak_all" name="butcetak_all" value="          CETAK       " />    																
    					</fieldset>								
  					</div>
        	</div>													 							
												
    			</br>
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
							
    			<?
    			$othervar = "kode_pembayaran=".$ls_kode_pembayaran."&sender=".$ls_sender."";
    			echo f_draw_pager($url, $total_pages, $_GET['page'],$othervar); 
    			?>						
				</form>	
				
				<div id="clear-bottom-popup"></div>
				</div> 

      	<div id="footer-popup">
        <p class="lft"></p>
        <p class="rgt">New Core System</p>
      	</div>

	</body>
	</html>						
	