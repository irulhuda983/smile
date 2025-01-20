<?php
$php_file_name='pn6001_koreksi_ahliwaris';

$p_task = $_REQUEST["task"];
$p_form_id = $_REQUEST["form"];
$p_kode_agenda = $_REQUEST["kd_agenda"];
$ls_kode_koreksi_awaris    = !isset($_GET['kd_koreksi_awaris']) ? $_POST['kd_koreksi_awaris'] : $_GET['kd_koreksi_awaris'];
$msg    = !isset($_GET['msg']) ? $_POST['msg'] : $_GET['msg'];
//$ls_kode_koreksi_awaris    = $_REQUEST["kd_koreksi_awaris"];


    $p_kode_klaim = $_REQUEST["kd_klaim"];
    $p_kode_klaim = $ls_kode_klaim == "" ? $p_kode_klaim : $ls_kode_klaim;
    //echo $p_kode_klaim;
    if ($p_kode_klaim != "" && $p_task == "New"){     
        $sql = "
             SELECT    A.KODE_KLAIM,
                       A.KODE_KANTOR,
                       (SELECT NAMA_KANTOR
                          FROM MS.MS_KANTOR
                         WHERE KODE_KANTOR = A.KODE_KANTOR)
                          NAMA_KANTOR,
                       (SELECT NAMA_SEGMEN
                          FROM KN.KN_KODE_SEGMEN
                         WHERE KODE_SEGMEN = A.KODE_SEGMEN)
                          NAMA_SEGMEN,
                       A.KODE_PERUSAHAAN,
                       B.NAMA_PERUSAHAAN,
                       B.NPP,
                       A.KODE_DIVISI,
                       (SELECT NAMA_DIVISI
                          FROM KN.KN_DIVISI
                         WHERE     KODE_PERUSAHAAN = A.KODE_PERUSAHAAN
                               AND KODE_DIVISI = A.KODE_DIVISI)
                          NAMA_DIVISI,
                       A.KODE_TK,
                       A.NAMA_TK,
                       (SELECT DECODE (JENIS_KELAMIN,
                                       'L', 'LAKI-LAKI',
                                       'P', 'PEREMPUAN',
                                       '')
                          FROM KN.VW_KN_TK TK
                         WHERE TK.KODE_TK = A.KODE_TK AND ROWNUM = 1)
                          JENIS_KELAMIN,
                       A.KPJ,
                       A.NOMOR_IDENTITAS,
                       A.KODE_TIPE_KLAIM,
                       (SELECT NAMA_TIPE_KLAIM
                          FROM PN.PN_KODE_TIPE_KLAIM
                         WHERE KODE_TIPE_KLAIM = A.KODE_TIPE_KLAIM)
                          NAMA_TIPE_KLAIM,
                       A.KODE_SEBAB_KLAIM,
                       (SELECT NAMA_SEBAB_KLAIM
                          FROM PN.PN_KODE_SEBAB_KLAIM
                         WHERE KODE_SEBAB_KLAIM = A.KODE_SEBAB_KLAIM)
                          NAMA_SEBAB_KLAIM,
                       TO_CHAR (A.TGL_KLAIM, 'DD/MM/RRRR')     TGL_KLAIM,
                       TO_CHAR (A.TGL_LAPOR, 'DD/MM/RRRR')     TGL_LAPOR,
                       TO_CHAR (A.TGL_KEJADIAN, 'DD/MM/RRRR')  TGL_KEJADIAN,
                       TO_CHAR (A.TGL_KEMATIAN, 'DD/MM/RRRR')  TGL_KEMATIAN,
                       TO_CHAR (A.TGL_PENETAPAN, 'DD/MM/RRRR') TGL_PENETAPAN,
                       A.NO_PENETAPAN,
                       A.STATUS_KLAIM,
                       '$ls_kode_koreksi_awaris' KODE_KOREKSI_AWARIS,
                       ''                        STATUS_CERAI,
                       ''                        TGL_CERAI,
                       NVL((SELECT TO_CHAR (TGL_KONDISI_TERAKHIR, 'dd/mm/rrrr')
                          FROM PN.PN_KLAIM_PENERIMA_BERKALA
                         WHERE KODE_KLAIM = A.KODE_KLAIM AND KODE_PENERIMA_BERKALA = 'TK'),
                         TO_CHAR (A.TGL_KONDISI_TERAKHIR, 'dd/mm/rrrr')
                       )TGL_KONDISI_TERAKHIR,
                       NVL((SELECT NAMA_KONDISI_TERAKHIR
                          FROM PN.PN_KODE_KONDISI_TERAKHIR
                          WHERE KODE_KONDISI_TERAKHIR =
                                  (SELECT KODE_KONDISI_TERAKHIR
                                     FROM PN.PN_KLAIM_PENERIMA_BERKALA
                                    WHERE     KODE_KLAIM = A.KODE_KLAIM
                                          AND KODE_PENERIMA_BERKALA = 'TK')
                        ), (SELECT NAMA_KONDISI_TERAKHIR
                            FROM PN.PN_KODE_KONDISI_TERAKHIR
                            WHERE KODE_KONDISI_TERAKHIR = A.KODE_KONDISI_TERAKHIR)
                       )NAMA_KONDISI_TERAKHIR,
                       (SELECT KODE_PENERIMA_BERKALA
                          FROM PN.PN_KLAIM_BERKALA X
                         WHERE     KODE_KLAIM = A.KODE_KLAIM
                               AND STATUS_BATAL = 'T'
                               AND NO_KONFIRMASI =
                                      (SELECT MIN (NO_KONFIRMASI)
                                         FROM PN.PN_KLAIM_BERKALA
                                        WHERE     KODE_KLAIM = X.KODE_KLAIM
                                              AND STATUS_BATAL = 'T'))
                          KODE_PENERIMA_BERKALA
                FROM PN.PN_KLAIM A, KN.KN_PERUSAHAAN B
                WHERE     A.KODE_PERUSAHAAN = B.KODE_PERUSAHAAN
                AND A.KODE_KLAIM = '$p_kode_klaim'";
    } 
    else if ($p_kode_agenda != "" || $p_task == "View") { 
        $sql = "
          SELECT   A.KODE_KLAIM,
			       A.KODE_KANTOR,
			       (SELECT NAMA_KANTOR FROM MS.MS_KANTOR WHERE KODE_KANTOR = A.KODE_KANTOR)NAMA_KANTOR,
			       (SELECT NAMA_SEGMEN FROM KN.KN_KODE_SEGMEN WHERE KODE_SEGMEN = A.KODE_SEGMEN)NAMA_SEGMEN,
			       A.KODE_PERUSAHAAN,
			       B.NAMA_PERUSAHAAN,
			       B.NPP,
			       A.KODE_DIVISI,
			       (SELECT NAMA_DIVISI FROM KN.KN_DIVISI WHERE KODE_PERUSAHAAN = A.KODE_PERUSAHAAN
			        AND KODE_DIVISI = A.KODE_DIVISI
			        )NAMA_DIVISI,
			       A.KODE_TK,
				   (SELECT DECODE(JENIS_KELAMIN, 'L','LAKI-LAKI','P','PEREMPUAN','') FROM KN.VW_KN_TK TK WHERE TK.KODE_TK = A.KODE_TK AND ROWNUM=1) JENIS_KELAMIN,
			       A.NAMA_TK,
			       A.KPJ,
			       A.NOMOR_IDENTITAS,
			       A.KODE_TIPE_KLAIM,
			       (SELECT NAMA_TIPE_KLAIM FROM PN.PN_KODE_TIPE_KLAIM WHERE KODE_TIPE_KLAIM = A.KODE_TIPE_KLAIM)NAMA_TIPE_KLAIM,
			       A.KODE_SEBAB_KLAIM,
			       (SELECT NAMA_SEBAB_KLAIM FROM PN.PN_KODE_SEBAB_KLAIM WHERE KODE_SEBAB_KLAIM = A.KODE_SEBAB_KLAIM)NAMA_SEBAB_KLAIM,
			       TO_CHAR (A.TGL_KLAIM, 'DD/MM/RRRR')TGL_KLAIM,
			       TO_CHAR (A.TGL_LAPOR, 'DD/MM/RRRR')TGL_LAPOR,
			       TO_CHAR (A.TGL_KEJADIAN, 'DD/MM/RRRR')TGL_KEJADIAN,
                   --TO_CHAR (A.TGL_KONDISI_TERAKHIR, 'DD/MM/RRRR')TGL_KONDISI_TERAKHIR,
                   TO_CHAR (A.TGL_KEMATIAN, 'DD/MM/RRRR')TGL_KEMATIAN,
			       TO_CHAR (A.TGL_PENETAPAN, 'DD/MM/RRRR')TGL_PENETAPAN,
			       A.NO_PENETAPAN,
			       A.STATUS_KLAIM,
			       C.KODE_AGENDA,
                   C.KODE_JENIS_AGENDA,
                   C.KODE_JENIS_AGENDA_DETIL,
                   C.STATUS_AGENDA,
                   C.KETERANGAN,
                   D.KODE_KOREKSI_AWARIS,
                   D.STATUS_SUBMIT_KOREKSI,
                   D.STATUS_APPROVAL,
                   D.STATUS_CERAI,                  
                   TO_CHAR (D.TGL_CERAI, 'DD/MM/RRRR')TGL_CERAI,
                    NVL((SELECT TO_CHAR (TGL_KONDISI_TERAKHIR, 'dd/mm/rrrr')
                          FROM PN.PN_KLAIM_PENERIMA_BERKALA
                         WHERE KODE_KLAIM = A.KODE_KLAIM AND KODE_PENERIMA_BERKALA = 'TK'),
                         TO_CHAR (A.TGL_KONDISI_TERAKHIR, 'dd/mm/rrrr')
                       )TGL_KONDISI_TERAKHIR,
                       NVL((SELECT NAMA_KONDISI_TERAKHIR
                          FROM PN.PN_KODE_KONDISI_TERAKHIR
                          WHERE KODE_KONDISI_TERAKHIR =
                                  (SELECT KODE_KONDISI_TERAKHIR
                                     FROM PN.PN_KLAIM_PENERIMA_BERKALA
                                    WHERE     KODE_KLAIM = A.KODE_KLAIM
                                          AND KODE_PENERIMA_BERKALA = 'TK')
                        ), (SELECT NAMA_KONDISI_TERAKHIR
                            FROM PN.PN_KODE_KONDISI_TERAKHIR
                            WHERE KODE_KONDISI_TERAKHIR = A.KODE_KONDISI_TERAKHIR)
                       )NAMA_KONDISI_TERAKHIR,
                       (SELECT KODE_PENERIMA_BERKALA
                          FROM PN.PN_KLAIM_BERKALA X
                         WHERE     KODE_KLAIM = A.KODE_KLAIM
                               AND STATUS_BATAL = 'T'
                               AND NO_KONFIRMASI =
                                      (SELECT MIN (NO_KONFIRMASI)
                                         FROM PN.PN_KLAIM_BERKALA
                                        WHERE     KODE_KLAIM = X.KODE_KLAIM
                                              AND STATUS_BATAL = 'T'))
                          KODE_PENERIMA_BERKALA
			 FROM  PN.PN_KLAIM           A,
			       KN.KN_PERUSAHAAN      B,
			       PN.PN_AGENDA_KOREKSI  C,
			       PN.PN_AGENDA_KOREKSI_AWARIS D
			 WHERE A.KODE_PERUSAHAAN = B.KODE_PERUSAHAAN
			 AND   A.KODE_KLAIM = C.REFERENSI
			 AND   C.KODE_AGENDA = D.KODE_AGENDA
			 AND C.KODE_AGENDA = '$p_kode_agenda'";

    }

     //echo($sql);
            
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_kode_klaim          = $row["KODE_KLAIM"];
    $ls_kode_koreksi_awaris = $row["KODE_KOREKSI_AWARIS"];
    $ls_st_submit_koreksi   = $row["STATUS_SUBMIT_KOREKSI"];
    $ls_st_approval         = $row["STATUS_APPROVAL"];
    $ls_kode_tk             = $row["KODE_TK"] ;
    $ls_kpj                 = $row["KPJ"] ;       
    $ls_no_nik              = $row["NOMOR_IDENTITAS"];
    $ls_nama_tk             = $row["NAMA_TK"];
	$ls_jenis_kelamin		= $row["JENIS_KELAMIN"];
    $ls_kode_perusahaan     = $row["KODE_PERUSAHAAN"];
    $ls_npp                 = $row["NPP"];
    $ls_nama_perusahaan     = $row["NAMA_PERUSAHAAN"];
    $ls_kode_divisi         = $row["KODE_DIVISI"];
    $ls_nama_divisi         = $row["NAMA_DIVISI"];
    $ls_kode_kantor         = $row["KODE_KANTOR"];
    $ls_nama_kantor         = $row["NAMA_KANTOR"];
    $ls_kode_segmen         = $row["NAMA_SEGMEN"];
    $ls_tipe_klaim          = $row["KODE_TIPE_KLAIM"];
    $ls_nama_tipe_klaim     = $row["NAMA_TIPE_KLAIM"];
    $ls_sebab_klaim         = $row["KODE_SEBAB_KLAIM"];
    $ls_nama_sebab_klaim    = $row["NAMA_SEBAB_KLAIM"];
    $ls_status_klaim        = $row["STATUS_KLAIM"];
    $ls_tgl_klaim           = $row["TGL_KLAIM"];
    $ls_tgl_lapor           = $row["TGL_LAPOR"];
    $ls_tgl_kejadian        = $row["TGL_KEJADIAN"];
    $ls_tgl_kondisi_terakhir= $row["TGL_KONDISI_TERAKHIR"];
    $ls_tgl_kematian        = $row["TGL_KEMATIAN"];
    $ls_tgl_penetapan       = $row["TGL_PENETAPAN"];
    $ls_tgl_cerai           = $row["TGL_CERAI"];
    $ls_status_cerai        = $row["STATUS_CERAI"];
    $ls_no_penetapan        = $row["NO_PENETAPAN"];
    //$ls_kode_kondisi_terakhir = $row["KODE_KONDISI_TERAKHIR"];
    $ls_kondisi_terakhir    = $row["NAMA_KONDISI_TERAKHIR"];
    $ls_kd_penerima_berkala = $row["KODE_PENERIMA_BERKALA"];
    $ls_kode_agenda         = $row["KODE_AGENDA"];
    $ls_kode_jenis_agenda   = $row["KODE_JENIS_AGENDA"];
    $ls_kode_jenis_agenda_detil = $row["KODE_JENIS_AGENDA_DETIL"];
?>
<form name="formreg" id="formreg" role="form" method="post" accept-charset="utf-8" enctype="multipart/form-data">
<style>
    .l_frm{width: 150px; height: : 30px; clear: left; float: left;margin-bottom: 2px;text-align:right;}
    .r_frm{float: left;margin-bottom: 2px;}
    .r_frm input,.r_frm select {
        border-radius: 2px; 
        -moz-border-radius: 2px; 
        -webkit-border-radius: 2px; 
        border: 1px solid #585858;
    }
</style>
<div style="width: 100%;">
<br>
<fieldset  style="width: 1150px"><legend><b>Pilih Kode Agenda Klaim</b></legend>
<table border='0' width="100%">
    <tr>
     <td width="50%" valign="top">
    <div class="l_frm" ><label for="kode_koreksi_awaris">Jenis Koreksi <span style="color:#ff0000;">&nbsp;*</span> :</label></div>
   	<div class="r_frm"> 
        <!-- <select size="1" id="kode_koreksi_awaris" name="kode_koreksi_awaris" value="<?=$ls_kode_koreksi_awaris;?>" style="background-color:#ffff99; width:180px;" >
            <option value="">-- pilih --</option>
            <option value="KP01">Tambah Ahli Waris</option>
            <option value="KP02">Ubah Ahli Waris</option>
        </select> -->
        <!-- <select size="1" id="kode_koreksi_awaris" name="kode_koreksi_awaris" <?PHP if($p_task!="New"){echo "disabled";}?> class="select_format" style="width:180px;background-color:#ffff99;">
            <?
            switch($ls_kode_koreksi_awaris){
                case 'KP01' : $kode_koreksi1="selected"; break;
                case 'KP02' : $kode_koreksi2="selected"; break;
                case 'KP03' : $kode_koreksi3="selected"; break;                      
            }
            ?>
            <option value="">--- Pilih ---</option>
            <option value="KP01" <?PHP if($ls_kode_koreksi_awaris=='KP01'){echo "selected";}?> <?=$kode_koreksi1;?>>Tambah Ahli Waris</option>
            <option value="KP02" <?PHP if($ls_kode_koreksi_awaris=='KP02'){echo "selected";}?> <?=$kode_koreksi2;?>>Ubah Anak I/II</option> 
            <option value="KP03" <?PHP if($ls_kode_koreksi_awaris=='KP03'){echo "selected";}?> <?=$kode_koreksi3;?>>Tambah Anak Usia <= 300 hari</option>                     
        </select> -->
        <!-- <input type="text" id="kode_koreksi_awarisx" name="kode_koreksi_awarisx" value="<?=$ls_kode_koreksi_awaris;?>"> -->
        <input type = "hidden" id = "kode_koreksi_awaris2" name = "kode_koreksi_awaris2" value="<?=$ls_kode_koreksi_awaris;?>">
        <select size="1" id="kode_koreksi_awaris" name="kode_koreksi_awaris" value="<?=$ls_kode_koreksi_awaris;?>" style="background-color:#ffff99; width:200px;" <?PHP if($p_task!="New"){echo "disabled";}?> >
                    <option value="">-- Pilih --</option> 
                    <? 
                    $sql = "SELECT * FROM (
                            -- SELECT 'xxx' KODE, '---Pilih---' KETERANGAN FROM MS.MS_LOOKUP WHERE AKTIF = 'Y' AND TIPE = 'KOR_AWARIS'
                            -- UNION
                            SELECT KODE, KETERANGAN FROM MS.MS_LOOKUP WHERE AKTIF = 'Y' AND TIPE = 'KOR_AWARIS'
                            )
                            ORDER BY KETERANGAN ASC";
                    //echo $sql;
                    $DB->parse($sql);
                    $DB->execute();
                    while($row = $DB->nextrow())
                    {
                    echo "<option ";
                    if ($row["KODE"]==$ls_kode_koreksi_awaris && strlen($ls_kode_koreksi_awaris)==strlen($row["KODE"])){ echo " selected"; }
                    echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
                    }
                    ?>
                  </select>
    </div>
    <div class="l_frm" ><label for="kode_klaim">Kode Klaim <span style="color:#ff0000;">&nbsp;*</span> :</label></div>
    <div class="r_frm">
        <input type="text" id="kode_klaim" name="kode_klaim" maxlength="100"  value="<?=$ls_kode_klaim;?>" style="width:150px;background-color:#ffff99; text-align: ;"readonly />
        <a href="#" onclick="fl_js_get_lov_by2('KODE_KLAIM')" tabindex="8" <?PHP if($_REQUEST["task"]!="New"){echo "style='pointer-events:none'";}?>/>                            
        <img src="../../images/help.png" alt="Cari Kode Klaim" border="0" align="absmiddle"></a>   
    </div>
    </td>
   </tr>
</table>
</fieldset>
<br>

<fieldset  style="width: 1150px"><legend><b>Informasi Agenda Klaim</b></legend>
<table border='0' width="100%">
    <tr>
        <td width="50%" valign="top">
        	<div class="l_frm" ><label for="kantor">Kantor :</label></div>
             <div class="r_frm">
                <input type="text" id="kode_kantor" name="kode_kantor" maxlength="100"  value="<?=$ls_kode_kantor;?>" style="width:30px;background-color:#e9e9e9; text-align: center;" readonly /> - 
                <input type="text" id="nama_kantor" name="nama_kantor" maxlength="100"  value="<?=$ls_nama_kantor;?>" style="width:200px;background-color:#e9e9e9" readonly />
            </div>
            <div class="l_frm" ><label for="kode_segmen">Segmentasi Keps :</label></div>
             <div class="r_frm">
                <input type="text" id="kode_segmen" name="kode_segmen" maxlength="100"  value="<?=$ls_kode_segmen;?>" style="width:230px;background-color:#e9e9e9;" readonly /> 
            </div>
            <div class="l_frm" ><label for="tipe_klaim">Tipe Klaim :</label></div>
             <div class="r_frm">
                <input type="text" id="tipe_klaim" name="tipe_klaim" maxlength="100"  value="<?=$ls_tipe_klaim;?>" style="width:50px;background-color:#e9e9e9; text-align: center;" readonly /> - 
                <input type="text" id="nama_tipe_klaim" name="nama_tipe_klaim" maxlength="100"  value="<?=$ls_nama_tipe_klaim;?>" style="width:200px;background-color:#e9e9e9" readonly />
            </div>
            <div class="l_frm" ><label for="sebab_klaim">Sebab Klaim :</label></div>
             <div class="r_frm">
                <input type="text" id="sebab_klaim" name="sebab_klaim" maxlength="100"  value="<?=$ls_sebab_klaim;?>" style="width:50px;background-color:#e9e9e9; text-align: center;" readonly /> - 
                <input type="text" id="nama_sebab_klaim" name="nama_sebab_klaim" maxlength="100"  value="<?=$ls_nama_sebab_klaim;?>" style="width:200px;background-color:#e9e9e9" readonly />
            </div>
            <div class="l_frm" ><label for="kpj">No. Peserta :</label></div>
            <div class="r_frm">
                <input type="hidden" id="kode_tk" name="kode_tk" maxlength="100"  value="<?=$ls_kode_tk;?>" style="width:150px;background-color:#ffff99" readonly class="disabled" />
                <input type="text" id="kpj" name="kpj" maxlength="100"  value="<?=$ls_kpj;?>" style="width:90px;background-color:#e9e9e9" readonly class="disabled" /> - 
                 <input type="text" id="nama_tk" name="nama_tk" maxlength="100"  value="<?=$ls_nama_tk;?>" style="width:230px;background-color:#e9e9e9" readonly  />
            </div>
			<div class="l_frm" ><label for="jenis_kelamin">Jenis Kelamin :</label></div>
            <div class="r_frm">
                <input type="text" id="jenis_kelamin" name="jenis_kelamin" maxlength="100"  value="<?=$ls_jenis_kelamin;?>" style="width:150px;background-color:#e9e9e9" readonly  />
            </div>
            <div class="l_frm" ><label for="no_nik">NIK :</label></div>
            <div class="r_frm">
                <input type="text" id="no_nik" name="no_nik" maxlength="100"  value="<?=$ls_no_nik;?>" style="width:150px;background-color:#e9e9e9" readonly  />
            </div>
            <div class="l_frm" ><label for="npp">NPP :</label></div> 
            <div class="r_frm">
                <input type="hidden" id="kode_perusahaan" name="kode_perusahaan" maxlength="100"  value="<?=$ls_kode_perusahaan;?>" style="width:200px;background-color:#ffff99" readonly class="disabled" />
                <input type="hidden" id="kode_kepesertaan" name="kode_kepesertaan" maxlength="100"  value="<?=$ls_kode_kepesertaan;?>" style="width:200px;background-color:#ffff99" readonly class="disabled" />
                <input type="text" id="npp" name="npp" maxlength="100"  value="<?=$ls_npp;?>" style="width:80px; text-align: center; background-color:#e9e9e9" readonly class="disabled" /> - 
                <input type="text" id="nama_perusahaan" name="nama_perusahaan" maxlength="100"  value="<?=$ls_nama_perusahaan;?>" style="width:265px;background-color:#e9e9e9" readonly />
            </div>
            <div class="l_frm" ><label for="kode_divisi">Unit Kerja :</label></div>
            <div class="r_frm">
                <input type="text" id="kode_divisi" name="kode_divisi" maxlength="100"  value="<?=$ls_kode_divisi;?>" style="width:80px;background-color:#e9e9e9; text-align: center;" readonly /> - 
                <input type="text" id="nama_divisi" name="nama_divisi" maxlength="100"  value="<?=$ls_nama_divisi;?>" style="width:265px;background-color:#e9e9e9" readonly />
            </div>
        </td>
        <td valign="top">
            <div class="l_frm" ><label for="tgl_klaim">Tgl Klaim :</label></div>
            <div class="r_frm">
                <input type="text" id="tgl_klaim" name="tgl_klaim" maxlength="100"  value="<?=$ls_tgl_klaim;?>" style="width:150px;background-color:#e9e9e9;" readonly /> 
            </div>
            <div class="l_frm" ><label for="tgl_lapor">Tgl Lapor :</label></div>
            <div class="r_frm">
                <input type="text" id="tgl_lapor" name="tgl_lapor" maxlength="100"  value="<?=$ls_tgl_lapor;?>" style="width:150px;background-color:#e9e9e9;" readonly /> 
            </div>
            <div class="l_frm" ><label for="tgl_kejadian">Tgl Kejadian :</label></div>
            <div class="r_frm">
                <input type="text" id="tgl_kejadian" name="tgl_kejadian" maxlength="100"  value="<?=$ls_tgl_kejadian;?>" style="width:150px;background-color:#e9e9e9;" readonly /> 
            </div>

            <div class="l_frm" ><label for="tgl_penetapan">Tgl Penetapan :</label></div>
            <div class="r_frm">
                <input type="text" id="tgl_penetapan" name="tgl_penetapan" maxlength="100"  value="<?=$ls_tgl_penetapan;?>" style="width:150px;background-color:#e9e9e9;" readonly /> 
            </div>
            <div class="l_frm" ><label for="no_penetapan">No Penetapan :</label></div>
            <div class="r_frm">
                <input type="text" id="no_penetapan" name="no_penetapan" maxlength="100"  value="<?=$ls_no_penetapan;?>" style="width:170px;background-color:#e9e9e9;" readonly /> 
            </div>
            <div class="l_frm" ><label for="status_klaim">Status Klaim :</label></div>
            <div class="r_frm">
                <input type="text" id="status_klaim" name="status_klaim" maxlength="100"  value="<?=$ls_status_klaim;?>" style="width:190px;background-color:#e9e9e9;" readonly /> 
            </div>

            <div class="l_frm" ><label for="kondisi_terakhir">Kondisi Terakhir :</label></div>
            <div class="r_frm">
                <!-- <input type="text" id="kode_kondisi_terakhir" name="kode_kondisi_terakhir" maxlength="100"  value="<?=$ls_kode_kondisi_terakhir;?>" style="width:40px;background-color:#e9e9e9;" readonly /> -->
                <input type="text" id="kondisi_terakhir" name="kondisi_terakhir" maxlength="100"  value="<?=$ls_kondisi_terakhir;?>" style="width:150px;background-color:#e9e9e9;" readonly /> 
            </div>
            <div class="l_frm" ><label for="tgl_kondisi_terakhir">Tgl Kondisi Terakhir :</label></div>
            <div class="r_frm">
                <input type="text" id="tgl_kondisi_terakhir" name="tgl_kondisi_terakhir" maxlength="100"  value="<?=$ls_tgl_kondisi_terakhir;?>" style="width:150px;background-color:#e9e9e9;" readonly /> 
            </div>

            <div class="l_frm" ><label for="kd_penerima_berkala">Kode Penerima Berkala :</label></div>
            <div class="r_frm">
                <input type="text" id="kd_penerima_berkala" name="kd_penerima_berkala" maxlength="100"  value="<?=$ls_kd_penerima_berkala;?>" style="width:190px;background-color:#e9e9e9;" readonly /> 
            </div>
            <? if($ls_kode_koreksi_awaris == "KP03"){?>
            <div class="l_frm" ><label for="div_st_cerai">Status Cerai :</label></div>
            <div class="r_frm">
					<br/>
					<fieldset <?PHP if($p_task != "New" || $ls_status_cerai == "Y"){echo "disabled";}?>><legend><b>Status Cerai</b></legend>
					<table border='0' width="100%">
					    <tr>
					        <td width="50%" valign="top">
					        	<div class="l_frm" ><label for="status_cerai">Status Cerai :</label></div>
					             <div class="r_frm">
					                <input type="checkbox" id="cb_status_cerai" name="cb_status_cerai" class="cebox" <?=$ls_status_cerai=="Y" ||$ls_status_cerai=="ON" ||$ls_status_cerai=="on" ? "checked" : "";?> onClick="fl_js_set_flag_cerai();"><i>Cerai</i>
					                <input type="hidden" id="status_cerai" name="status_cerai" value="<?=$ls_status_cerai;?>" >
					            </div>
					            <div class="l_frm" ><label for="kode_segmen">Tanggal Cerai :</label></div>
					             <div class="r_frm">
					             	<input id="tgl_cerai" name="tgl_cerai" value="<?=$ls_tgl_cerai;?>" size="15" maxlength="10" onblur="convert_date(tgl_cerai);" readonly class="disabled" >
					                <input id="btn_tgl_cerai" type="image" align="top" onclick="return showCalendar('tgl_cerai', 'dd-mm-y');" src="../../images/calendar.gif" />
					            </div>
					        </td>
					    </tr>
					</table>
					</fieldset>
					
            </div>
            <?}?>
        </td>
    </tr>
</table>
</fieldset>

<?
	if($p_kode_agenda != ""){
?>
<div id="formKiri" style="width:1210px;">
	<fieldset style="width: 1150px" ><legend>Koreksi Ahli Waris</legend>
    <table id="mydata1" width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>
        <tr>
        	<th colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
        </tr>
        <tr>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;" colspan="4">&nbsp;</th>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;" colspan="3">Kondisi Akhir</th>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">&nbsp;</th>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">&nbsp;</th>
        </tr>
        <tr>
        	<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:100px;">Koreksi</th>
          
        	<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:120px;">Hubungan</th>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:200px;">Nama Lengkap</th>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:80px;">Tgl Lahir</th>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:80px;">Jns Kelamin</th>
					<th colspan="2"><hr></hr></th>
          <?if ($ls_st_submit_koreksi=='Y'){?>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:50px;">Eligible</th>
	      	<?}?>
		   <? if  ($ls_st_submit_koreksi == 'T') {?>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:100px;">Action</th>
			<?}?>
        </tr>																										
        <tr>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;" colspan="5">&nbsp;</th>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">Status</th>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:100px;">Sejak</th>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">&nbsp;</th>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:100px;">&nbsp;</th>
        </tr>
        <tr>
        <th colspan="9"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        </tr>													
        <?
        if ($ls_st_approval == 'Y'){
          $sql = "SELECT * FROM (
                 select 
                 a.kode_klaim, a.kode_penerima_berkala,a.kode_hubungan,
                     (select nama_hubungan from sijstk.kn_kode_hubungan_tk where kode_hubungan = a.kode_hubungan) nama_hubungan,
                     a.no_urut_keluarga, a.nama_lengkap, a.no_kartu_keluarga, a.nomor_identitas,
                     a.tempat_lahir, to_char(a.tgl_lahir,'dd/mm/yyyy') tgl_lahir, a.jenis_kelamin,
                     decode(a.jenis_kelamin,'P','PEREMPUAN','L','LAKI-LAKI',a.jenis_kelamin) nama_jenis_kelamin,
                     a.golongan_darah, a.status_kawin, a.alamat,
                     a.rt, a.rw, a.kode_kelurahan,
                     a.kode_kecamatan, a.kode_kabupaten, a.kode_pos,
                     a.telepon_area, a.telepon, a.telepon_ext,
                     a.fax_area, a.fax, a.handphone,
                     a.email, a.npwp, a.nama_penerima,
                     a.bank_penerima, a.no_rekening_penerima, a.nama_rekening_penerima,
                     a.kode_bank_pembayar, a.kpj_tertanggung, a.pekerjaan,
                     a.kode_kondisi_terakhir,
                     (select nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir where kode_kondisi_terakhir = a.kode_kondisi_terakhir) nama_kondisi_terakhir,
                     to_char(a.tgl_kondisi_terakhir,'dd/mm/yyyy') tgl_kondisi_terakhir,
                     nvl(a.status_layak,'T') status_layak, a.keterangan,
                     'T' edit_awaris
                 from pn.pn_klaim_penerima_berkala a
                 where a.kode_klaim = '$ls_kode_klaim'
                 and a.kode_hubungan <> 'T'
                 )
              order by no_urut_keluarga
                 ";
        }	else{	
          $sql = "SELECT * FROM (
        		select 
                    a.kode_klaim, a.kode_penerima_berkala,a.kode_hubungan, 
                    (select nama_hubungan from sijstk.kn_kode_hubungan_tk where kode_hubungan = a.kode_hubungan) nama_hubungan,
                    a.no_urut_keluarga, a.nama_lengkap, a.no_kartu_keluarga, a.nomor_identitas, 
                    a.tempat_lahir, to_char(a.tgl_lahir,'dd/mm/yyyy') tgl_lahir, a.jenis_kelamin, 
                    decode(a.jenis_kelamin,'P','PEREMPUAN','L','LAKI-LAKI',a.jenis_kelamin) nama_jenis_kelamin,
                    a.golongan_darah, a.status_kawin, a.alamat, 
                    a.rt, a.rw, a.kode_kelurahan, 
                    a.kode_kecamatan, a.kode_kabupaten, a.kode_pos, 
                    a.telepon_area, a.telepon, a.telepon_ext, 
                    a.fax_area, a.fax, a.handphone, 
                    a.email, a.npwp, a.nama_penerima, 
                    a.bank_penerima, a.no_rekening_penerima, a.nama_rekening_penerima, 
                    a.kode_bank_pembayar, a.kpj_tertanggung, a.pekerjaan, 
                    a.kode_kondisi_terakhir, 
                    (select nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir where kode_kondisi_terakhir = a.kode_kondisi_terakhir) nama_kondisi_terakhir,
                    to_char(a.tgl_kondisi_terakhir,'dd/mm/yyyy') tgl_kondisi_terakhir, 
                    nvl(a.status_layak,'T') status_layak, a.keterangan,
                    flag_koreksi edit_awaris
                from pn.pn_agenda_koreksi_awaris_detil a
                where a.kode_klaim = '$ls_kode_klaim'
                and a.kode_agenda = '$p_kode_agenda'
				and a.kode_hubungan <> 'T'
				-- and a.flag_koreksi = 'T'
    --             and a.kode_penerima_berkala_baru is not null
				-- UNION
			   )
              order by no_urut_keluarga ";
        }
        //echo $sql;
        $DB->parse($sql);
        $DB->execute();
        $i=0;
        $ln_dtl = 0;
        while ($row = $DB->nextrow())
        {
        ?>
        <?echo "<tr bgcolor=#".($i%2 ? "ffffff" : "f3f3f3").">";?>
          
          <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
							<?=($row["EDIT_AWARIS"]=="Y" ? "<img src=../../images/file_apply.gif>" : "")?>
		  </td>
		 					
          <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["NAMA_HUBUNGAN"];?></td>	
          <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["NAMA_LENGKAP"];?></td>
          <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["TGL_LAHIR"];?></td>
          <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["NAMA_JENIS_KELAMIN"];?></td>
          <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["NAMA_KONDISI_TERAKHIR"];?></td>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["TGL_KONDISI_TERAKHIR"];?></td>
           <?if ($ls_st_submit_koreksi=='Y'){?>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
							<?=($row["STATUS_LAYAK"]=="Y" ? "<img src=../../images/file_apply.gif>" : "")?>
					</td>
		      <?}?>
          <td style="text-align:center;">   
          <? if (($row["EDIT_AWARIS"] == 'Y') && ($_REQUEST["form"] != 'tdl') && ($ls_st_submit_koreksi == 'T') && ($ls_kode_koreksi_awaris == 'KP01'||$ls_kode_koreksi_awaris == 'KP03')) {?>     	
            <a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_koreksi_ahliwarisentry.php?task=edit&kode_agenda=<?=$ls_kode_agenda;?>&kode_tk=<?=$ls_kode_tk;?>&kode_klaim=<?=$ls_kode_klaim;?>&no_urut_keluarga=<?=$row["NO_URUT_KELUARGA"];?>&kd_koreksi_awaris=<?=$ls_kode_koreksi_awaris;?>&st_cerai='+$('#status_cerai').val()+'&tgl_cerai='+$('#tgl_cerai').val()+'&root_sender=pn6001.php&sender=pn6001_koreksi_ahliwaris.php&sender_activetab=2&sender_mid=<?=$mid;?>','Ubah Data Keluarga',880,620,'no');"><img src="../../images/app_form_edit.png" border="0" alt="Ubah Divisi" align="absmiddle" />&nbsp;Ubah</a>
            <?}?>
          </td>
           
        </tr>
        <?
        //hitung total									    							
        $i++;//iterasi i							
        }	//end while
        $ln_dtl=$i;				
        ?>									             																
      </tbody>
      <tr>
      	<td colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td>	
      </tr>											
      <tr>
        <td colspan="2"style="text-align:left;" >
					<!-- <a href="#" onClick="if(confirm('Apakah anda yakin akan mengambil data ahli waris..?')) fl_js_val_jp_ambildata_ahliwaris();"><img src="../../images/application_get.png" border="0" alt="Tambah" align="absmiddle" />&nbsp;Ambil Data Keluarga</a> -->	
				</td>
				<td style="text-align:left" colspan="4"><b><i>&nbsp;<i></b>
          <input type="hidden" id="kounter_dtl" name="kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="count_dtl" name="count_dtl" value="<?=$ln_countdtl;?>">
          <input type="hidden" name="showmessage" style="border-width: 0;text-align:right" readonly size="5">					
        </td>  
        <td colspan="1" style="text-align:left;" >
						
        </td>
        <? if (($_REQUEST["form"] != 'tdl') && ($ls_st_submit_koreksi == 'T')) {
              if($ls_kode_koreksi_awaris == 'KP01' || $ls_kode_koreksi_awaris == 'KP03'){
        ?>        
        <td style="text-align:center">	
        	   <a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_koreksi_ahliwarisentry.php?task=new&kode_tk=<?=$ls_kode_tk;?>&kode_agenda=<?=$ls_kode_agenda;?>&kode_klaim=<?=$ls_kode_klaim;?>&kd_koreksi_awaris=<?=$ls_kode_koreksi_awaris;?>&st_cerai='+$('#status_cerai').val()+'&tgl_cerai='+$('#tgl_cerai').val()+'&sender=pn6001_koreksi_ahliwaris.php&sender_mid=<?=$mid;?>','Entry Data Ahli Waris',880,620,'no');"><img src="../../images/plus.png" border="0" alt="Tambah" align="absmiddle" />&nbsp;Tambah Data</a>
        </td> 
        <?}
          if($ls_kode_koreksi_awaris == 'KP02'){
        ?>
        <td style="text-align:center">
            <a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_koreksi_ahliwaris_ubahanak.php?task=new&kode_tk=<?=$ls_kode_tk;?>&kode_agenda=<?=$ls_kode_agenda;?>&kode_klaim=<?=$ls_kode_klaim;?>&sender=pn6001_koreksi_ahliwaris.php&sender_mid=<?=$mid;?>','Ubah Data Anak',650,400,'no');"><img src="../../images/application_form_edit.png" border="0" alt="Tambah" align="absmiddle" />&nbsp;Ubah Anak I/II</a> 
        </td>
        <?}
        }?>
        											
      </tr>
    </table>					
	</fieldset>	 
</div>

<!-- <div id="formKiri" style="width: 100%">
<?
    if($btn_task=="upload")
    {     
        $ls_nama_file               = $_FILES['datafile']['name'];
            $ls_nama_file           = stripslashes($ls_nama_file);
            $ls_nama_file           = str_replace("'","",$ls_nama_file);
            $ls_mime_type           = $_FILES['datafile']['type'];
            $ext                    = end(explode(".", $_FILES['datafile']['name']));
            $jml_ext                = strlen($ext)+1;
            $FILENAME               = substr(pathinfo($_FILES['datafile']['name'], PATHINFO_FILENAME),0,(50-$jml_ext)).'.'.$ext;
            if(!empty($_FILES['datafile']['tmp_name']) 
              && file_exists($_FILES['datafile']['tmp_name'])) {
             // if($ls_nama_file !=""){
                $DOC_FILE= file_get_contents($_FILES['datafile']['tmp_name']);
                $sql_upload = "UPDATE PN.PN_AGENDA_KOREKSI_AWARIS
                               SET  NAMA_FILE  = '$ls_nama_file',
                                    MIME_TYPE  = '$ls_mime_type',
                                    DOC_FILE   = EMPTY_BLOB(),
                                    tgl_ubah   = sysdate
                               WHERE   KODE_AGENDA  = '$ls_kode_agenda';
                               RETURNING
                               DOC_FILE INTO :LOB_A";

                $stmt   = oci_parse($DB->conn, $sql_upload);
                $myLOB  = oci_new_descriptor($DB->conn, OCI_D_LOB);
                oci_bind_by_name($stmt, ":LOB_A", $myLOB, -1, OCI_B_BLOB);
                oci_execute($stmt, OCI_DEFAULT)
                or die ("Unable to execute query\n");
                if ( !$myLOB->save($DOC_FILE)) {
                    $STATUS_UPLOAD = false;
                    oci_rollback($DB->conn);
                } else {
                    $STATUS_UPLOAD=oci_commit($DB->conn);
                }
                              // Free resources
                oci_free_statement($stmt);
                $myLOB->free();
                 
                if($STATUS_UPLOAD){
                   $msg = "Dokumen berhasil diupload, session dilanjutkan..."; 
                    echo "<script language=\"JavaScript\" type=\"text/javascript\">";             
                    echo "reloadFormx();";
                    echo "</script>";  
                } else{
                  $msg = "Dokumen gagal diupload!!!!";   
                }
            } else{
                  $msg = "Gagal. Dokumen kosong!!!!...  Silahkan Pilih file";   
            }

                            
        //$msg = "Dokumen berhasil diupload, session dilanjutkan...";     

        echo "<script language=\"JavaScript\" type=\"text/javascript\">";                       
        echo "reloadFormx();";
        echo "</script>";                             
    } //end if(isset($_POST['simpan']))  
    ?>               
  <fieldset><legend>Upload File :</legend>
    <table border='0' width="100%">
    <tr>
        <td width="100%" valign="top">
            <div class="l_frm" ><label for="datafile">Berita Acara<span style="color:#ff0000;">&nbsp;*</span> :</label></div>   
            <div class="r_frm">
                <?PHP if($ls_st_submit_koreksi == 'Y') { ?>
                    <input type="button" class="btn green" id="btn_download" name="btn_download" value="Download" style="width:180px;" onclick="window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_koreksi_upah_download_file.php?&kode_agenda=<?=$p_kode_agenda;?>&TYPE=download')">
                <?
                }else{
                ?>
                    <input type="file" id="datafile" name="datafile" size="40" style="width:265px;background-color:#ffff99" accept=".doc, .docx, .pdf, .png, .jpg, .jpeg" value="<?=$ls_nama_file;?>" />
                <?
                }
                ?>
                <input type="hidden" id="btn_task_upload" name="btn_task_upload" value=""> 
                <input type="button" class="btn green" id="btn_upload" name="btn_upload" value="Upload" style="width:180px;" onClick="if(confirm('Apakah anda yakin akan mengupload dokumen..?')) fl_js_val_upload();">
            </div>


        </td>
        <td>
        </td>
    </tr>
</table>
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
  </fieldset>
    </br>
    </br>
</div> -->

<?}?>

<table class="table responsive-table" id="mydata1" cellspacing="0" width="100%">
    <thead>
      <tr>                                                               
        <th scope="col" width="5%" class="align-center" style="vertical-align: middle;"></th> 
        <th scope="col" width="20%" class="align-center" style="vertical-align: middle;"></th>
        <th scope="col" width="12%" class="align-center" style="vertical-align: middle;"></th>
        <th scope="col" width="15%" class="align-center" style="vertical-align: middle;"></th>
        <th scope="col" width="10%" class="align-center" style="vertical-align: middle;"></th>
        <th scope="col" width="8%" class="align-center" style="vertical-align: middle;"></th>
         <th scope="col" width="5%" class="align-center" style="vertical-align: middle;"></th>
         <th scope="col" width="7%" class="align-center" style="vertical-align: middle;"></th>
        <th scope="col" width="5%" class="align-center" style="vertical-align: middle;"></th>
        <th scope="col" width="18%" class="align-center" style="vertical-align: middle;"></th>
        <th scope="col" width="10%" class="align-center" style="vertical-align: middle;"></th>
      </tr>
    </thead>     
</table>
</div>
</form>
<script type="text/javascript">
$(document).ready(function(){ 
   
});     

 function reset_form() {
       // $('#kode_klaim').val('');
       // reloadForm();
    }
 function fl_ls_get_lov_by_selected(lov, kod , obj) {
        if (lov == "KODE_KLAIM") {
            if (obj.KODE_KLAIM != '') {
                var new_location = window.location.href.replace("#", "") + '&kd_klaim=' + obj.KODE_KLAIM + '&kd_koreksi_awaris=' + obj.KODE;
                window.location.replace(new_location);
                // cek_tk_aktif();

            } else {
                alert("Kode Klaim tidak ditemukan, pilih No Klaim yang akan diubah !");
            }
        } 
    }

    function fl_ls_get_lov_by_selected2(kode_klaim, kode_kor_awaris) {
       //alert(kode_kor_awaris);
            if (kode_klaim != '' && kode_kor_awaris !='') {
                var new_loc = window.location.href.replace("kd_klaim", "temp");
                console.log(new_loc);
                var new_location = new_loc.replace("#", "") + '&kd_klaim=' + kode_klaim + '&kd_koreksi_awaris=' + kode_kor_awaris;
                window.location.replace(new_location);
                // cek_tk_aktif();

            } else {
                alert("Kode Klaim tidak ditemukan, pilih No Klaim yang akan diubah !");
            }
       
    }

    function fl_js_get_lov_by(lov) { 
        if (lov == "KODE_KLAIM") {
            // var params = "p=pn6001_koreksi_ahliwaris.php&a=formreg&kode_jenis_koreksi='+$('#kode_koreksi_awaris').val()+'";
            NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_kor_ahliwaris_lov_klaim.php?p=pn6001_koreksi_ahliwaris.php&a=formreg&kode_jenis_koreksi='+$('#kode_koreksi_awaris').val()+'','',800,500,1);
        } 
    }
    function fl_js_get_lov_by2(lov) { 
        if (lov == "KODE_KLAIM") {
            // var params = "p=pn6001_koreksi_ahliwaris.php&a=formreg&kode_jenis_koreksi='+$('#kode_koreksi_awaris').val()+'";
            NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_kor_ahliwaris_lov_klaim2.php?p=pn6001_koreksi_ahliwaris.php&a=formreg&b=kode_klaim&c='+$('#kode_koreksi_awaris').val()+'','',800,500,1);
        } 
    }


function reloadForm(){
    var new_location = window.location.href.replace("#", "");
    window.location.replace(new_location);
    //NewWindow.close();
}

function reloadFormx(){
    var new_location = window.location.href.replace("#", "");
    window.location.replace(new_location,+'&msg=upload');
}

function fl_js_val_numeric(v_field_id){
    var c_val = window.document.getElementById(v_field_id).value;
    //alert (c_id);
    var number=/^[0-9]+$/;
    if ((c_val!='') && (!c_val.match(number))){
        document.getElementById(v_field_id).value = '';         
        window.document.getElementById(v_field_id).focus();
        alert("Harus berisikan angka, tidak boleh alphabet atau karakter lainnya...! ");
        window.document.getElementById(v_field_id).value = '';         
        return false;         
    }   
}

function fl_js_val_upload()
    {
      var form = window.document.formreg;
      // if(form.datafile.value==""){
      //   alert('Dokumen Kosong...!!!');
      //   form.datafile.focus();
      //       }else
      //   {
         form.btn_task_upload.value="upload";
         form.submit();          
        //}                                
    } 

function fl_js_set_flag_cerai(){
	var checkBox = document.getElementById("cb_status_cerai");
	 if (checkBox.checked == true){
	 	$('#status_cerai').val('Y');
	 }
	 else{
	 	$('#status_cerai').val('T');
	 	$('#tgl_cerai').val('');
	 }
}

 function isValid() {
        var kd_klaim             = $('#kode_klaim').val();
        var kode_koreksi_awaris  = $('#kode_koreksi_awaris').val();
        var file                 = $('#datafile').val();
        var st_cerai 			       = $('#status_cerai').val();
        var tgl_cerai            = $('#tgl_cerai').val();

        var fileUp = $("#datafile")[0].files[0] == undefined ? { name: "", size: "" } : $("#datafile")[0].files[0];
        //var fileName = fileUp.name;
        var fileSize = fileUp.size;

        //console.log(fileSize);
        
        var maxSize = 500000;

        if (kd_klaim== "" || kd_klaim== null)  {
            return { val : false, msg : "Kode Klaim masih kosong, harap lengkapi data input !" };
        } 
        else if (kode_koreksi_awaris == "" || kode_koreksi_awaris == null) {
            return { val : false, msg : "Jenis Koreksi masih kosong, harap lengkapi data input !" };
        }  
        else if (st_cerai == "Y" && tgl_cerai == "") {
            return { val : false, msg : "Tanggal cerai masih Kosong, harap lengkapi data input !" };
        }   
        else if (file == "" || file == null) {
            return { val : false, msg : "Berita Acara masih kosong, harap lengkapi data input !" };
        }
        else if (fileSize > maxSize)  {
            return { val : false, msg : 'Size file terlalu besar! Maksimal size file adalah ' + maxSize + ' Byte !' };
        }   
        return { val : true, msg : "Valid"} ;

    }
</script>   