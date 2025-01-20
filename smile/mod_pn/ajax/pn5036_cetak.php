<?
session_start();
include "../../includes/conf_global.php";
require_once "../../includes/header_app_nosql.php";	
require_once "../../includes/fungsi.php";
include "../../includes/fungsi_newrpt.php"; 

$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "New Core System";
$gs_pagetitle = "PN5036 - SURAT TINDAK LANJUT DAN LEMBAR PERNYATAAN KONFIRMASI JKK TAHAP I DAN PENGAJUAN TAHAP I";
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
            function fl_js_set_st_surat_tindaklanjut()
            {
                var form = document.adminForm;
                if (form.st_surat_tindaklanjut.checked)
                {
                    form.st_surat_tindaklanjut.value = "Y";
                } else
                {
                    form.st_surat_tindaklanjut.value = "T";
                }
            }
            function fl_js_set_st_surat_konfirmasi()
            {
                var form = document.adminForm;
                if (form.st_surat_konfirmasi.checked)
                {
                    form.st_surat_konfirmasi.value = "Y";
                } else
                {
                    form.st_surat_konfirmasi.value = "T";
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
                $ld_periode1 	= !isset($_GET['periode1']) ? $_POST['periode1'] : $_GET['periode1'];
                $ld_periode2 = !isset($_GET['periode2']) ? $_POST['periode2'] : $_GET['periode2'];
                $ls_tgl_periode = $ld_periode1 . " s/d " . $ld_periode2;
                $ls_rg_kategori_tglklaim = !isset($_POST['kategoritglklaim']) ? $_GET['kategoritglklaim'] : $_POST['kategoritglklaim'];
                $ls_kode_perusahaan				= !isset($_GET['kode_perusahaan']) ? $_POST['kode_perusahaan'] : $_GET['kode_perusahaan'];
                $ls_jml_klaim = !isset($_GET['jml_klaim']) ? $_POST['jml_klaim'] : $_GET['jml_klaim'];
                $ls_kode_kantor = !isset($_GET['kode_kantor']) ? $_POST['kode_kantor'] : $_GET['kode_kantor'];
				$ls_kode_segmen = !isset($_GET['kode_segmen']) ? $_POST['kode_segmen'] : $_GET['kode_segmen'];

                $ls_sender				= !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];						

                $ls_st_surat_tindaklanjut	= !isset($_GET['st_surat_tindaklanjut']) ? $_POST['st_surat_tindaklanjut'] : $_GET['st_surat_tindaklanjut'];
                $ls_st_surat_konfirmasi	= !isset($_GET['st_surat_konfirmasi']) ? $_POST['st_surat_konfirmasi'] : $_GET['st_surat_konfirmasi'];

                if ($ls_st_surat_tindaklanjut=="on" || $ls_st_surat_tindaklanjut=="ON" || $ls_st_surat_tindaklanjut=="Y")
                {
					$ls_st_surat_tindaklanjut = "Y";
                }else
                {
					$ls_st_surat_tindaklanjut = "T";
                }	

				if ($ls_st_surat_konfirmasi=="on" || $ls_st_surat_konfirmasi=="ON" || $ls_st_surat_konfirmasi=="Y")
                {
					$ls_st_surat_konfirmasi = "Y";
                }else
                {
					$ls_st_surat_konfirmasi = "T";
                }
		
				if(isset($_POST["butcetak_all"]))
				{
					if ($ls_st_surat_tindaklanjut!="T")
					{
						$ls_user_param .= " p_kode_perusahaan='$ls_kode_perusahaan'"; 
						$ls_user_param .= " p_kode_kantor='$ls_kode_kantor'";
						
						$tipe1 = isset($iscetak) ? "PDF" : "PDF";
						$ls_modul1 = "PN";

						if($ls_rg_kategori_tglklaim == "0")
						{
							$ls_nama_rpt1 = "PNR503603.rdf";	// by tgl klaim
						}
						elseif($ls_rg_kategori_tglklaim == "1")
						{
							$ls_nama_rpt1 = "PNR503604.rdf";	// by tgl kejadian
						}
						
						exec_rpt_enc_new(1, $ls_modul1, $ls_nama_rpt1, $ls_user_param, $tipe1);					 
					}		

					if ($ls_st_surat_konfirmasi!="T")
					{
						$ls_user_param .= " p_kode_kantor='$ls_kode_kantor'";
						$ls_user_param .= " p_kode_perusahaan='$ls_kode_perusahaan'";          
						$ls_user_param .= " p_periode1='$ld_periode1'";
						$ls_user_param .= " p_periode2='$ld_periode2'";
					
						$tipe1 = isset($iscetak) ? "PDF" : "PDF";
						$ls_modul1 = "PN";
						
						if($ls_rg_kategori_tglklaim == "0")
						{
							$ls_nama_rpt1 = "PNR503601.rdf";	// by tgl klaim
						}
						elseif($ls_rg_kategori_tglklaim == "1")
						{
							$ls_nama_rpt1 = "PNR503602.rdf";	// by tgl kejadian
						}

						exec_rpt_enc_new(1, $ls_modul1, $ls_nama_rpt1, $ls_user_param, $tipe1);					 
					}
					
					if ($ls_st_surat_tindaklanjut!="T" && $ls_st_surat_konfirmasi!="T")
					{
						//post insert ----------------------------------------------------------
						$qry = "
							BEGIN PN.P_PN_PN5036.X_POST_INSERT 
							(
								'$ls_kode_kantor', 
								'$ls_kode_perusahaan', 
								'$ld_periode1', 
								'$ld_periode2', 
								'$ls_rg_kategori_tglklaim', 
								'$ls_user_login', 
								:p_sukses,
								:p_mess
							);
							END;
						";												 	
						$proc = $DB->parse($qry);		
						oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
						oci_bind_by_name($proc, ":p_mess", $p_mess,1024);
						$DB->execute();				
						$ls_mess = $p_mess;	
					}
					else
					{
						$ls_mess = "Centang tanda checkbox pada Lampiran, kemudian Klik Tombol CETAK untuk mencetak dokumen lampiran.";
					}

					echo "<script language=\"JavaScript\" type=\"text/javascript\">";
					echo "window.location.replace('?task=View&root_sender=pn5036.php&sender=pn5036.php&sender_mid=$mid&kode_perusahaan=$ls_kode_perusahaan&periode1=$ld_periode1&periode2=$ld_periode2&kategoritglklaim=$ls_rg_kategori_tglklaim&jml_klaim=$ls_jml_klaim&kode_kantor=$ls_kode_kantor&kode_segmen=$ls_kode_segmen);";
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
                                    $sql = "SELECT A.NPP,A.NAMA_PERUSAHAAN FROM KN.KN_PERUSAHAAN A WHERE A.KODE_PERUSAHAAN = '$ls_kode_perusahaan' AND ROWNUM=1 ";
                                    $DB->parse($sql);
                                    $DB->execute();
                                    $row = $DB->nextrow();
                                    $ls_npp = $row["NPP"];
                                    $ls_nama_perusahaan = $row["NAMA_PERUSAHAAN"];
                                    ?>
                                    <div id="formKiri">
                                        <fieldset style="width:500px;"><legend style="text-align:left">Parameter</legend>
											<div class="form-row_kiri">
                                                <label>Kode Segmen</label>
                                                <input type="text" id="npp" name="npp" value="<?= $ls_kode_segmen; ?>" size="40" readonly class="disabled">
                                            </div>
                                            <div class="clear"></div>
                                            <div class="form-row_kiri">
                                                <label>NPP</label>
                                                <input type="text" id="npp" name="npp" value="<?= $ls_npp; ?>" size="40" readonly class="disabled">
                                            </div>
                                            <div class="clear"></div>

                                            <div class="form-row_kiri">
                                                <label>Nama Perusahaan</label>
                                                <input type="text" id="nama_perusahaan" name="nama_perusahaan" value="<?= $ls_nama_perusahaan; ?>" size="40" readonly class="disabled">
                                            </div>
                                            <div class="clear"></div>																

                                            <div class="form-row_kiri">
                                                <label>Tgl Kejadian/Klaim</label>
                                                <input type="text" id="tgl_periode" name="tgl_periode" value="<?= $ls_tgl_periode; ?>" size="40" readonly class="disabled">
                                            </div>
                                            <div class="clear"></div>
                                            <div class="form-row_kiri">
                                                <label>Jumlah Klaim</label>
                                                <input type="text" id="jml_klaim" name="jml_klaim" value="<?= $ls_jml_klaim; ?>" size="40" readonly class="disabled">
                                            </div>
                                            <div class="clear"></div>											
                                            <br>

                                                <div class="form-row_kiri">
                                                    <label>Lampiran :</label>						
                                                    <?$ls_st_surat_tindaklanjut = isset($ls_st_surat_tindaklanjut) ? $ls_st_surat_tindaklanjut : "Y";							
                                                    ?>					
                                                    <input type="checkbox" id="st_surat_tindaklanjut" name="st_surat_tindaklanjut" class="cebox" onclick="fl_js_set_st_surat_tindaklanjut();" <?= $ls_st_surat_tindaklanjut == "Y" || $ls_st_surat_tindaklanjut == "ON" || $ls_st_surat_tindaklanjut == "on" ? "checked" : ""; ?>>
                                                        <i><font  color="#009999">Surat Tindak Lanjut JKK Tahap I</font></i>	
                                                </div>											
                                                <div class="clear"></div>	

                                                <div class="form-row_kiri">
                                                    <label  style = "text-align:right;">&nbsp;</label>						
                                                    <? $ls_st_surat_konfirmasi = isset($ls_st_surat_konfirmasi) ? $ls_st_surat_konfirmasi : "Y";?>					
                                                    <input type="checkbox" id="st_surat_konfirmasi" name="st_surat_konfirmasi" class="cebox" onclick="fl_js_set_st_surat_konfirmasi();" <?= $ls_st_surat_konfirmasi == "Y" || $ls_st_surat_konfirmasi == "ON" || $ls_st_surat_konfirmasi == "on" ? "checked" : ""; ?>>
                                                        <i><font  color="#009999">Lembar Pernyataan Konfirmasi JKK Tahap I</font></i>	
                                                </div>								
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
                                            <fieldset><legend style="text-align:left">Daftar Agenda Klaim</legend>
                                                <div id="formsplit">
                                                    <div class="clear"></div>
													<?php
													//query data -----------------------------------------------------------------	
													// berdasarkan tgl klaim
													if($ls_rg_kategori_tglklaim == "0")
													{		
														$sql = "SELECT rownum no,
																		A.*
																	  FROM
																		( SELECT DISTINCT A.*
																		FROM
																		  (SELECT A.*
																		  FROM
																			(SELECT a.kode_perusahaan,
																			  (SELECT prs.NPP
																			  FROM kn.kn_perusahaan prs
																			  WHERE prs.kode_perusahaan = a.kode_perusahaan
																			  AND rownum                =1
																			  ) NPP,
																			  (SELECT prs.NAMA_PERUSAHAAN
																			  FROM kn.kn_perusahaan prs
																			  WHERE prs.kode_perusahaan = a.kode_perusahaan
																			  AND rownum                =1
																			  ) NAMA_PERUSAHAAN,
																			  a.nomor_identitas,
																			  TO_CHAR(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian,
																			  a.petugas_rekam,
																			  a.kode_klaim,
																			  TO_CHAR(a.tgl_klaim,'yyyymmdd') tglklaim,
																			  TO_CHAR(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim,
																			  a.kpj,
																			  DECODE( NVL(a.kode_pointer_asal,'x'),'PROMOTIF',a.nama_pelaksana_kegiatan, (DECODE(a.kode_segmen,'JAKON',
																			  (SELECT no_proyek
																				||'-'
																				||nama_proyek
																			  FROM sijstk.jn_proyek
																			  WHERE kode_proyek = a.kode_proyek
																			  ),a.nama_tk)) ) nama_pengambil_klaim,
																			  (SELECT nama_tipe_klaim
																			  FROM sijstk.pn_kode_tipe_klaim
																			  WHERE kode_tipe_klaim = a.kode_tipe_klaim
																			  )
																			  ||' '
																			  ||a.kode_pointer_asal ket_tipe_klaim,
																			  a.kode_segmen,
																			  a.kode_kantor,
																			  a.status_klaim,
																			  a.kode_tipe_klaim,
																			  a.kode_sebab_klaim
																			FROM sijstk.pn_klaim a
																			WHERE a.tgl_klaim between to_date('$ld_periode1','dd/mm/yyyy') and to_date('$ld_periode2','dd/mm/yyyy')
																			AND NVL(a.status_klaim,'T')         <> 'BATAL'
																			AND NVL(a.status_submit_agenda2,'T') = 'T'
																			AND NVL(a.status_submit_agenda,'T')  = 'T'
																			AND NVL(a.status_kelayakan,'T')      = 'Y'
																			AND NVL(a.status_batal,'T')          = 'T'
																			AND kode_tipe_klaim                  = 'JKK01'
																			AND a.KODE_KLAIM_INDUK              IS NULL
																			AND a.KODE_KANTOR = '$ls_kode_kantor'
																			AND a.KODE_PERUSAHAAN = '$ls_kode_perusahaan'
																			) A
																		  WHERE 1=1
																		  UNION ALL
																		  SELECT A.*
																		  FROM
																			(SELECT a.kode_perusahaan,
																			  (SELECT prs.NPP
																			  FROM kn.kn_perusahaan prs
																			  WHERE prs.kode_perusahaan = a.kode_perusahaan
																			  AND rownum                =1
																			  ) NPP,
																			  (SELECT prs.NAMA_PERUSAHAAN
																			  FROM kn.kn_perusahaan prs
																			  WHERE prs.kode_perusahaan = a.kode_perusahaan
																			  AND rownum                =1
																			  ) NAMA_PERUSAHAAN,
																			  a.nomor_identitas,
																			  TO_CHAR(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian,
																			  a.petugas_rekam,
																			  a.kode_klaim,
																			  TO_CHAR(a.tgl_klaim,'yyyymmdd') tglklaim,
																			  TO_CHAR(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim,
																			  a.kpj,
																			  DECODE( NVL(a.kode_pointer_asal,'x'),'PROMOTIF',a.nama_pelaksana_kegiatan, (DECODE(a.kode_segmen,'JAKON',
																			  (SELECT no_proyek
																				||'-'
																				||nama_proyek
																			  FROM sijstk.jn_proyek
																			  WHERE kode_proyek = a.kode_proyek
																			  ),a.nama_tk)) ) nama_pengambil_klaim,
																			  (SELECT nama_tipe_klaim
																			  FROM sijstk.pn_kode_tipe_klaim
																			  WHERE kode_tipe_klaim = a.kode_tipe_klaim
																			  )
																			  ||' '
																			  ||a.kode_pointer_asal ket_tipe_klaim,
																			  a.kode_segmen,
																			  a.kode_kantor,
																			  a.status_klaim,
																			  a.kode_tipe_klaim,
																			  a.kode_sebab_klaim
																			FROM sijstk.pn_klaim a
																			WHERE a.tgl_klaim between to_date('$ld_periode1','dd/mm/yyyy') and to_date('$ld_periode2','dd/mm/yyyy')
																			AND NVL(a.status_klaim,'T')           <> 'BATAL'
																			AND NVL(a.status_submit_agenda,'T')    = 'Y'
																			AND NVL(a.status_submit_agenda2,'T')   = 'T'
																			AND NVL(a.status_submit_pengajuan,'T') = 'T'
																			AND NVL(a.status_kelayakan,'T')        = 'Y'
																			AND NVL(a.status_batal,'T')            = 'T'
																			AND a.kode_tipe_klaim                  = 'JKK01'
																			AND a.KODE_KLAIM_INDUK                IS NULL
																			AND a.KODE_KANTOR = '$ls_kode_kantor'
																			AND a.KODE_PERUSAHAAN = '$ls_kode_perusahaan'
																			) A
																		  WHERE 1=1
																		  ) A ORDER BY TGLKLAIM ASC
																		) A ";	
																		
																		//echo $sql;
													}
													elseif($ls_rg_kategori_tglklaim == "1")
													{
														$sql = "SELECT rownum no,
																		A.*
																	  FROM
																		( SELECT DISTINCT A.*
																		FROM
																		  (SELECT A.*
																		  FROM
																			(SELECT a.kode_perusahaan,
																			  (SELECT prs.NPP
																			  FROM kn.kn_perusahaan prs
																			  WHERE prs.kode_perusahaan = a.kode_perusahaan
																			  AND rownum                =1
																			  ) NPP,
																			  (SELECT prs.NAMA_PERUSAHAAN
																			  FROM kn.kn_perusahaan prs
																			  WHERE prs.kode_perusahaan = a.kode_perusahaan
																			  AND rownum                =1
																			  ) NAMA_PERUSAHAAN,
																			  a.nomor_identitas,
																			  TO_CHAR(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian,
																			  a.petugas_rekam,
																			  a.kode_klaim,
																			  TO_CHAR(a.tgl_klaim,'yyyymmdd') tglklaim,
																			  TO_CHAR(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim,
																			  a.kpj,
																			  DECODE( NVL(a.kode_pointer_asal,'x'),'PROMOTIF',a.nama_pelaksana_kegiatan, (DECODE(a.kode_segmen,'JAKON',
																			  (SELECT no_proyek
																				||'-'
																				||nama_proyek
																			  FROM sijstk.jn_proyek
																			  WHERE kode_proyek = a.kode_proyek
																			  ),a.nama_tk)) ) nama_pengambil_klaim,
																			  (SELECT nama_tipe_klaim
																			  FROM sijstk.pn_kode_tipe_klaim
																			  WHERE kode_tipe_klaim = a.kode_tipe_klaim
																			  )
																			  ||' '
																			  ||a.kode_pointer_asal ket_tipe_klaim,
																			  a.kode_segmen,
																			  a.kode_kantor,
																			  a.status_klaim,
																			  a.kode_tipe_klaim,
																			  a.kode_sebab_klaim
																			FROM sijstk.pn_klaim a
																			WHERE a.tgl_kejadian between to_date('$ld_periode1','dd/mm/yyyy') and to_date('$ld_periode2','dd/mm/yyyy')
																			AND NVL(a.status_klaim,'T')         <> 'BATAL'
																			AND NVL(a.status_submit_agenda2,'T') = 'T'
																			AND NVL(a.status_submit_agenda,'T')  = 'T'
																			AND NVL(a.status_kelayakan,'T')      = 'Y'
																			AND NVL(a.status_batal,'T')          = 'T'
																			AND kode_tipe_klaim                  = 'JKK01'
																			AND a.KODE_KLAIM_INDUK              IS NULL
																			AND a.KODE_KANTOR = '$ls_kode_kantor'
																			AND a.KODE_PERUSAHAAN = '$ls_kode_perusahaan'
																			) A
																		  WHERE 1=1
																		  UNION ALL
																		  SELECT A.*
																		  FROM
																			(SELECT a.kode_perusahaan,
																			  (SELECT prs.NPP
																			  FROM kn.kn_perusahaan prs
																			  WHERE prs.kode_perusahaan = a.kode_perusahaan
																			  AND rownum                =1
																			  ) NPP,
																			  (SELECT prs.NAMA_PERUSAHAAN
																			  FROM kn.kn_perusahaan prs
																			  WHERE prs.kode_perusahaan = a.kode_perusahaan
																			  AND rownum                =1
																			  ) NAMA_PERUSAHAAN,
																			  a.nomor_identitas,
																			  TO_CHAR(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian,
																			  a.petugas_rekam,
																			  a.kode_klaim,
																			  TO_CHAR(a.tgl_klaim,'yyyymmdd') tglklaim,
																			  TO_CHAR(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim,
																			  a.kpj,
																			  DECODE( NVL(a.kode_pointer_asal,'x'),'PROMOTIF',a.nama_pelaksana_kegiatan, (DECODE(a.kode_segmen,'JAKON',
																			  (SELECT no_proyek
																				||'-'
																				||nama_proyek
																			  FROM sijstk.jn_proyek
																			  WHERE kode_proyek = a.kode_proyek
																			  ),a.nama_tk)) ) nama_pengambil_klaim,
																			  (SELECT nama_tipe_klaim
																			  FROM sijstk.pn_kode_tipe_klaim
																			  WHERE kode_tipe_klaim = a.kode_tipe_klaim
																			  )
																			  ||' '
																			  ||a.kode_pointer_asal ket_tipe_klaim,
																			  a.kode_segmen,
																			  a.kode_kantor,
																			  a.status_klaim,
																			  a.kode_tipe_klaim,
																			  a.kode_sebab_klaim
																			FROM sijstk.pn_klaim a
																			WHERE a.tgl_kejadian between to_date('$ld_periode1','dd/mm/yyyy') and to_date('$ld_periode2','dd/mm/yyyy')
																			AND NVL(a.status_klaim,'T')           <> 'BATAL'
																			AND NVL(a.status_submit_agenda,'T')    = 'Y'
																			AND NVL(a.status_submit_agenda2,'T')   = 'T'
																			AND NVL(a.status_submit_pengajuan,'T') = 'T'
																			AND NVL(a.status_kelayakan,'T')        = 'Y'
																			AND NVL(a.status_batal,'T')            = 'T'
																			AND a.kode_tipe_klaim                  = 'JKK01'
																			AND a.KODE_KLAIM_INDUK                IS NULL
																			AND a.KODE_KANTOR = '$ls_kode_kantor'
																			AND a.KODE_PERUSAHAAN = '$ls_kode_perusahaan'
																			) A
																		  WHERE 1=1
																		  ) A ORDER BY TGLKLAIM ASC
																		) A ";	
													}
													?>
                                                    <table class="table table-striped table-bordered row-border hover" id="mydata_grid" cellspacing="0" width="100%" style="border:1px;">
                                                        <thead>
                                                            <tr style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">
                                                                <th scope="col">No.</th>
                                                                <th scope="col">Kode Klaim</th>
                                                                <th scope="col">KPJ</th>
                                                                <th scope="col">NIK</th>
                                                                <th scope="col">Nama TK</th>
                                                                <th scope="col">Tgl Klaim</th>
                                                                <th scope="col">Tgl Kecelakaan</th>
                                                                <th scope="col">Status Klaim</th>
                                                                <th scope="col">Petugas Rekam</th>																
                                                            </tr>
                                                        </thead>
														<tbody>
															<?php 
																$DB->parse($sql);
																if($DB->execute())
																{ 
																	$i = 0;
																	while($data = $DB->nextrow())
																	{
																		$i++;
																		echo "<tr>
																				<td>$i</td>
																				<td style='text-align:center;'>".$data['KODE_KLAIM']."</td>
																				<td style='text-align:center;'>".$data['KPJ']."</td>
																				<td style='text-align:center;'>".$data['NOMOR_IDENTITAS']."</td>
																				<td style='text-align:left;'>".$data['NAMA_PENGAMBIL_KLAIM']."</td>
																				<td style='text-align:center;'>".$data['TGL_KLAIM']."</td>
																				<td style='text-align:center;'>".$data['TGL_KEJADIAN']."</td>
																				<td style='text-align:center;'>".$data['STATUS_KLAIM']."</td>
																				<td style='text-align:center;'>".$data['PETUGAS_REKAM']."</td>
																			</tr>";
																	}
																}
															?>
														</tbody>
                                                    </table>								
                                                    <div class="clear"></div>
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
