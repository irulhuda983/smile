<?
session_start();
include "../../includes/conf_global.php";
require_once "../../includes/header_app_nosql.php";	
require_once "../../includes/fungsi.php";
include "../../includes/fungsi_newrpt.php"; 

$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "New Core System";
$gs_pagetitle = "PN5037 - SURAT KETERANGAN INFORMASI SALDO JAMINAN HARI TUA";
?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?= $pagetitle; ?></title>
        <meta name="Author" content="JroBalian" />
        <link rel="stylesheet" type="text/css" href="<?= "http://$HTTP_HOST"; ?>/style/style.css" />
		<link rel="stylesheet" type="text/css" href="<?= "http://$HTTP_HOST"; ?>/style/jquery.dataTables.min.css" />
		<style>
			#mydata_grid {
			  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			  border-collapse: collapse;
			  width: 100%;
			}

			#mydata_grid td, #mydata_grid th {
			  border: 1px solid #ddd;
			  padding: 8px;
			}

			#mydata_grid tr:nth-child(even){background-color: #f2f2f2;}

			#mydata_grid tr:hover {background-color: #ddd;}

			#mydata_grid th {
			  padding-top: 12px;
			  padding-bottom: 12px;
			  text-align: left;
			  background-color: #ebebeb;
			  border: 1px solid #dddddd;
			  color: #000;
			}
		</style>
        <script type="text/javascript" language="JavaScript" src="../../javascript/iframe.js"></script>
        <script type="text/javascript" src="../../javascript/common.js"></script>
		<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
		
        <script language="JavaScript">
            function fl_js_set_st_surat_keterangan_jht()
            {
                var form = document.adminForm;
                if (form.st_surat_keterangan_jht.checked)
                {
                    form.st_surat_keterangan_jht.value = "Y";
                } else
                {
                    form.st_surat_keterangan_jht.value = "T";
                }
            }
        </script>									
    </head>
    <body>
        <div id="header-popup">	
            <h3><?= $gs_pagetitle; ?></h3>
        </div>

        <div id="container-popup">
            <!--[if lte IE 6]>
            <div id="clearie6"></div>
            <![endif]-->	
            <form name="adminForm" id="adminForm" method="post" >
                <?
				$ls_user_login = $_SESSION["USER"];
                $ls_kode_kantor = !isset($_GET['kode_kantor']) ? $_POST['kode_kantor'] : $_GET['kode_kantor'];
				$ls_kode_segmen = !isset($_GET['kode_segmen']) ? $_POST['kode_segmen'] : $_GET['kode_segmen'];
				$ls_kode_tk				= !isset($_GET['kode_tk']) ? $_POST['kode_tk'] : $_GET['kode_tk'];
				$ls_kode_kelayakan = !isset($_GET['kode_kelayakan']) ? $_POST['kode_kelayakan'] : $_GET['kode_kelayakan'];

                $ls_sender				= !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];						

                $ls_st_surat_keterangan_jht	= !isset($_GET['st_surat_keterangan_jht']) ? $_POST['st_surat_keterangan_jht'] : $_GET['st_surat_keterangan_jht'];

                if ($ls_st_surat_keterangan_jht=="on" || $ls_st_surat_keterangan_jht=="ON" || $ls_st_surat_keterangan_jht=="Y")
                {
					$ls_st_surat_keterangan_jht = "Y";
                }else
                {
					$ls_st_surat_keterangan_jht = "T";
                }	
		
				if(isset($_POST["butcetak_all"]))
				{
					if ($ls_st_surat_keterangan_jht !="T")
					{
						$ls_user_param .= " P_KODE_AGENDA_KELAYAKAN='$ls_kode_kelayakan'"; 
						
						$tipe1 = isset($iscetak) ? "PDF" : "PDF";
						$ls_modul1 = "PN";

						$ls_nama_rpt1 = "PNR503701.rdf";
						
						exec_rpt_enc_new(1, $ls_modul1, $ls_nama_rpt1, $ls_user_param, $tipe1);					 
					}		
					
					if ($ls_st_surat_keterangan_jht =="T" )
					{
						$ls_mess = "Centang tanda checkbox pada Lampiran, kemudian Klik Tombol CETAK untuk mencetak dokumen lampiran.";
					}

					echo "<script language=\"JavaScript\" type=\"text/javascript\">";
					echo "window.location.replace('?task=View&root_sender=pn5037.php&sender=pn5037.php&sender_mid=$mid&kode_tk=$ls_kode_tk&kode_kantor=$ls_kode_kantor&kode_segmen=$ls_kode_segmen&kode_kelayakan=$ls_kode_kelayakan);";
					echo "</script>";									
                } 								         		 
                ?>				

                <table class="captionentry">
                    <tr> 
                        <td align="left"></td>						 
                    </tr>
                </table>								
                <div id="formframe" style="background:#FFF !important">
                    <span id="dispError" style="display:none;color:red"></span>
                    <input type="hidden" id="st_errval" name="st_errval">
                        <span id="dispError1" style="display:none;color:red"></span>
                        <input type="hidden" id="st_errval1" name="st_errval1">					
                            <span id="dispError2" style="display:none;color:red"></span>
                            <input type="hidden" id="st_errval2" name="st_errval2">
                                <input type="hidden" id="kode_klaim" name="kode_klaim" value="<?= $ls_kode_klaim; ?>" size="40" readonly class="disabled">
                                    <input type="hidden" id="no_penetapan" name="no_penetapan" value="<?= $ls_no_penetapan; ?>" size="50"/>
                                    <input type="hidden" id="kd_prg" name="kd_prg" value="<?= $ls_kd_prg; ?>" size="50"/>
                                    <input type="hidden" id="sender" name="sender" value="<?= $ls_sender; ?>" size="50"/>
                                    <input type="hidden" id="blth_proses" name="blth_proses" value="<?= $ld_blth_proses; ?>" size="50"/>						
                                    <?php
                                    $sql = "
											select a.KODE_AGENDA_KELAYAKAN, a.KODE_TK, (select kpj from kn.kn_tk where kode_tk = a.kode_tk) KPJ 
											from pn.PN_AGENDA_KLAIM_KELAYAKAN a
											where a.KODE_AGENDA_KELAYAKAN = '$ls_kode_kelayakan'
											";
                                    $DB->parse($sql);
                                    $DB->execute();
                                    $row = $DB->nextrow();
                                    $ls_kode_agenda_kelayakan = $row["KODE_AGENDA_KELAYAKAN"];
                                    $ls_kpj = $row["KPJ"];
                                    ?>
                                    <div id="formKiri">
                                        <fieldset style="width:500px;"><legend style="text-align:left">Parameter</legend>
                                            <div class="form-row_kiri">
                                                <label>Kode Kelayakan</label>
                                                <input type="text" id="npp" name="npp" value="<?= $ls_kode_agenda_kelayakan; ?>" size="40" readonly class="disabled">
                                            </div>
                                            <div class="clear"></div>

                                            <div class="form-row_kiri">
                                                <label>Nomor Referensi</label>
                                                <input type="text" id="kpj" name="kpj" value="<?= $ls_kpj; ?>" size="40" readonly class="disabled">
                                            </div>
                                            <div class="clear"></div>																											
                                            <br>

                                                <div class="form-row_kiri">
                                                    <label>Lampiran :</label>						
                                                    <?$ls_st_surat_keterangan_jht = isset($ls_st_surat_keterangan_jht) ? $ls_st_surat_keterangan_jht : "Y";							
                                                    ?>					
                                                    <input type="checkbox" id="st_surat_keterangan_jht" name="st_surat_keterangan_jht" class="cebox" onclick="fl_js_set_st_surat_keterangan_jht();" <?= $ls_st_surat_keterangan_jht == "Y" || $ls_st_surat_keterangan_jht == "ON" || $ls_st_surat_keterangan_jht == "on" ? "checked" : ""; ?>>
                                                        <i><font  color="#009999">Surat Keterangan Informasi Saldo JHT</font></i>	
                                                </div>											
                                                <div class="clear"></div>								
                                        </fieldset>
                                        <br>
										<fieldset style="width:500px;"><legend>&nbsp;</legend>
                                            <!--<input type="submit" class="btn green" id="butcetak" name="butcetak" value="          CETAK       " />-->
											<input type="submit" class="btn green" id="butcetak_all" name="butcetak_all" value="          CETAK       " />
											<br>
											<span style="color:red;font-style: italic;">
											<?php 
												echo $ls_mess;
											?>
											</span>
										</fieldset>
										<br>
                                                    <div class="clear"></div>

                                                    <div style="background: #F2F2F2;margin-top:2px;padding:10px 20px;border:1px solid #ececec;text-align:left;">
                                                        <span style="background: #FF0; border: 1px solid #CCC;"><i><b>Keterangan:</b></i></span>
                                                        <li style="margin-left:15px;">Centang tanda checkbox pada Lampiran, kemudian Klik Tombol <font color="#ff0000"> CETAK</font> untuk mencetak dokumen lampiran</li>	
                                                    </div>																																																											
                                            </fieldset>
                                    </div>
                                    </fieldset>	
									<br>
									<br>
									<br>									
                                    </div>
                                    </div>													 							

                                    </br>
                                    <?
                                    if (isset($msg))		
                                    {
                                    ?>
                                    <fieldset>
                                        <?= $ls_error == 1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>"; ?>
                                        <?= $ls_error == 1 ? "<font color=#ff0000>" . $msg . "</font>" : "<font color=#007bb7>" . $msg . "</font>"; ?>
                                    </fieldset>		
                                    <?
                                    }
                                    ?>

                                    <?
                                    $othervar = "kode_klaim=".$ls_kode_klaim."&sender=".$ls_sender."";
                                    echo f_draw_pager($url, $total_pages, $_GET['page'],$othervar); 
                                    ?>	

                                    <script type="text/javascript">
									
									$(document).ready(function() {
										$('#mydata_grid').DataTable( {
											"pagingType": "full_numbers"
										} );
									} );
									</script>												
                                    </form>	

                                    <div id="clear-bottom-popup"></div>
                                    </div> 

                                    <div id="footer-popup">
                                        <p class="lft"></p>
                                        <p class="rgt">New Core System</p>
                                    </div>

                                    </body>
                                    </html>						
