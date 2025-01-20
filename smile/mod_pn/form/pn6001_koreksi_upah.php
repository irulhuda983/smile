<?php
$php_file_name='pn6001_koreksi_upah';

$p_task = $_REQUEST["task"];
$p_kode_agenda = $_REQUEST["kd_agenda"];

    $p_kode_klaim = $_REQUEST["kd_klaim"];
    $p_kode_klaim = $ls_kode_klaim == "" ? $p_kode_klaim : $ls_kode_klaim;
    //echo $p_kode_faskes;
    if ($p_kode_klaim != "" && $p_task == "New"){     
        $sql = "
            SELECT  A.*,
                    TO_CHAR(A.TGL_KECELAKAAN, 'DD/MM/YYYY')TGL_KECELAKAANJKK, 
                    B.NAMA_PERUSAHAAN,
                    B.NPP,
                    C.NAMA_DIVISI,
                    D.KODE_KEPESERTAAN,
                    D.KODE_KANTOR,
                    (SELECT NAMA_KANTOR FROM SIJSTK.MS_KANTOR 
                        WHERE KODE_KANTOR = D.KODE_KANTOR 
                    ) NAMA_KANTOR,
                    D.STATUS,
                    DECODE(D.AKTIF,'Y','AKTIF','NONAKTIF') AKTIF,
                    (SELECT NOM_UPAH FROM KN.KN_IURAN_TK
                      WHERE KODE_PERUSAHAAN = A.KODE_PERUSAHAAN
                      AND KODE_TK = A.KODE_TK
                      AND TO_CHAR(BLTH,'MMYYYY') = TO_CHAR(A.TGL_KECELAKAAN, 'MMYYYY')
                     ) NOM_UPAH_KECELAKAAN,
                     SUBSTR (TO_CHAR (A.TGL_KECELAKAAN, 'DDMMRRRR'), 3, 2) BULAN,
                     SUBSTR (TO_CHAR (A.TGL_KECELAKAAN, 'DDMMRRRR'), 5, 4) TAHUN,
                     (SELECT COUNT (1) FROM PN.PN_AGENDA_KOREKSI_KLAIM_UPAH
                      WHERE KODE_KLAIM = A.KODE_KLAIM
                      AND STATUS_APPROVAL = 'T'
                      AND STATUS_BATAL = 'T'
                     ) JML
            FROM PN.PN_KLAIM A, KN.KN_PERUSAHAAN B, KN.KN_DIVISI C, KN.KN_KEPESERTAAN_PRS D
            WHERE A.KODE_PERUSAHAAN = B.KODE_PERUSAHAAN
            AND A.KODE_PERUSAHAAN = C.KODE_PERUSAHAAN
            AND A.KODE_DIVISI = C.KODE_DIVISI
            AND A.KODE_PERUSAHAAN = D.KODE_PERUSAHAAN
            AND A.KODE_DIVISI = D.KODE_DIVISI
            AND A.KODE_KLAIM = '$p_kode_klaim'";
    } 
    else if ($p_kode_agenda != "" || $p_task == "View") { 
        $sql = "
        SELECT A.KODE_AGENDA,
               A.KODE_JENIS_AGENDA,
               --H.NAMA_JENIS_AGENDA,
               A.KODE_JENIS_AGENDA_DETIL,
               --G.NAMA_JENIS_AGENDA_DETIL,
               A.STATUS_AGENDA,
               A.KETERANGAN,
               A.REFERENSI,
               B.KODE_PERUSAHAAN,
               C.NPP,
               C.NAMA_PERUSAHAAN,
               B.KODE_DIVISI,
               D.NAMA_DIVISI,
               E.KODE_KANTOR,
               (SELECT NAMA_KANTOR
                  FROM SIJSTK.MS_KANTOR
                 WHERE KODE_KANTOR = E.KODE_KANTOR)
                  NAMA_KANTOR,
               E.STATUS,
               DECODE (E.AKTIF, 'Y', 'AKTIF', 'NONAKTIF') AKTIF,
               B.KODE_KEPESERTAAN,
               B.KODE_KLAIM,
               B.KODE_TK,
               F.KPJ,
               TO_CHAR(F.TGL_KECELAKAAN, 'DD/MM/YYYY')TGL_KECELAKAANJKK,
               B.NIK NOMOR_IDENTITAS,
               B.NAMA_TK,
               B.KODE_KLAIM,
               B.TGL_TERIMA_DOK_PENDAFTARAN,
               TO_CHAR(TGL_TERIMA_DOK_PENDAFTARAN, 'hh24:mi') WAKTU_DOK_DFTR,
               B.TGL_TERIMA_DOK_UPAH,
               TO_CHAR(TGL_TERIMA_DOK_UPAH, 'hh24:mi') WAKTU_DOK_UPAH,
               B.KODE_SUMBER_DATA,
               B.BLTH_UPAH_KECELAKAAN,
               SUBSTR (TO_CHAR (B.BLTH_UPAH_KECELAKAAN, 'DDMMRRRR'), 3, 2) BULAN,
               SUBSTR (TO_CHAR (B.BLTH_UPAH_KECELAKAAN, 'DDMMRRRR'), 5, 4) TAHUN,
               B.NOM_UPAH_KECELAKAAN,
               B.NAMA_FILE,
               B.DOC_FILE,
               B.KETERANGAN KETERANGAN_KOREKSI
          FROM PN.PN_AGENDA_KOREKSI            A,
               PN.PN_AGENDA_KOREKSI_KLAIM_UPAH B,
               KN.KN_PERUSAHAAN                C,
               KN.KN_DIVISI                    D,
               KN.KN_KEPESERTAAN_PRS           E,
               PN.PN_KLAIM                     F
               -- PN.PN_KODE_JENIS_AGENDA_KOR_DETIL G,
               -- PN.PN_KODE_JENIS_AGENDA_KOREKSI H
         WHERE     A.KODE_AGENDA = B.KODE_AGENDA
               AND B.KODE_PERUSAHAAN = C.KODE_PERUSAHAAN
               AND B.KODE_PERUSAHAAN = D.KODE_PERUSAHAAN
               AND B.KODE_DIVISI = D.KODE_DIVISI
               AND B.KODE_KEPESERTAAN = E.KODE_KEPESERTAAN
               AND B.KODE_KLAIM = F.KODE_KLAIM
               -- AND A.KODE_JENIS_AGENDA = G.KODE_JENIS_AGENDA
               -- AND A.KODE_JENIS_AGENDA_DETIL = G.KODE_JENIS_AGENDA_DETIL
               -- AND A.KODE_JENIS_AGENDA = H.KODE_JENIS_AGENDA
               AND  A.KODE_AGENDA = '$p_kode_agenda'";

    }

    // echo($sql);
            
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_kode_klaim          = $row["KODE_KLAIM"];
    $ls_tgl_kecelakaan      = $row["TGL_KECELAKAANJKK"];
    $ls_kode_tk             = $row["KODE_TK"] ;
    $ls_kpj                 = $row["KPJ"] ;       
    $ls_no_nik              = $row["NOMOR_IDENTITAS"];
    $ls_nama_tk             = $row["NAMA_TK"];
    $ls_kode_perusahaan     = $row["KODE_PERUSAHAAN"];
    $ls_npp                 = $row["NPP"];
    $ls_nama_perusahaan     = $row["NAMA_PERUSAHAAN"];
    $ls_kode_divisi         = $row["KODE_DIVISI"];
    $ls_nama_divisi         = $row["NAMA_DIVISI"];
    $ls_kode_kepesertaan    = $row["KODE_KEPESERTAAN"];
    $ls_status              = $row["STATUS"];
    $ls_aktif               = $row["AKTIF"];
    $ls_kode_kantor         = $row["KODE_KANTOR"];
    $ls_nama_kantor         = $row["NAMA_KANTOR"];
    $ls_kode_agenda         = $row["KODE_AGENDA"];
    $ls_kode_jenis_agenda   = $row["KODE_JENIS_AGENDA"];
    $ls_kode_jenis_agenda_detil = $row["KODE_JENIS_AGENDA_DETIL"];
    $ls_tgl_dok_pendaftaran = $row["TGL_TERIMA_DOK_PENDAFTARAN"];
    $ls_tgl_dok_upah        = $row["TGL_TERIMA_DOK_UPAH"];
    $ls_sumber_data         = $row["KODE_SUMBER_DATA"];
    $ls_upah_terakhir       = $row["NOM_UPAH_KECELAKAAN"];
    $ls_nama_file           = $row["NAMA_FILE"];
    $ls_keterangan_koreksi  = $row["KETERANGAN_KOREKSI"];
    $ls_bulan               = $row["BULAN"];
    $ls_tahun               = $row["TAHUN"];
    $ls_time_dok_dftr       = $row["WAKTU_DOK_DFTR"];
    $ls_time_dok_upah       = $row["WAKTU_DOK_UPAH"];
    //$ls_upah_terakhir       = $row["NOM_UPAH"];
    $ls_agenda_exists       = $row["JML"];
    // $ls_nama_jenis_agenda   = $row["NAMA_JENIS_AGENDA"];
    // $ls_nama_jenis_agenda_detil  = $row["NAMA_JENIS_AGENDA_DETIL"];
    //$ls_detil_status       = $row["STATUS_AGENDA"];
    // $ls_keterangan          = $row['KETERANGAN'];
             
    //}          

?>
<form name="formreg" id="formreg" role="form" method="post" accept-charset="utf-8" enctype="multipart/form-data">
<style>
    .l_frm{width: 200px; height: : 30px; clear: left; float: left;margin-bottom: 2px;text-align:right;}
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
<fieldset><legend><b>Pilih Kode Agenda Klaim</b></legend>
<table border='0' width="100%">
    <tr>
     <td width="50%" valign="top">
    <div class="l_frm" ><label for="kode_klaim">Kode Klaim <span style="color:#ff0000;">&nbsp;*</span> :</label></div>
    <div class="r_frm">
        <input type="text" id="kode_klaim" name="kode_klaim" maxlength="100"  value="<?=$ls_kode_klaim;?>" style="width:150px;background-color:#ffff99; text-align: ;"readonly />
        <a href="#" onclick="fl_js_get_lov_by('KODE_KLAIM')" tabindex="8" <?PHP if($_REQUEST["task"]!="New"){echo "style='pointer-events:none'";}?>/>                            
        <img src="../../images/help.png" alt="Cari Kode Klaim" border="0" align="absmiddle"></a>   
    </div>
    <div class="l_frm" ><label for="kode_klaim">Tgl Kecelakaan <span style="color:#ff0000;">&nbsp;*</span> :</label></div>
    <div class="r_frm">
        <input type="text" id="tgl_kecelakaan" name="tgl_kecelakaan" maxlength="100"  value="<?=$ls_tgl_kecelakaan;?>" style="width:150px;background-color:#e9e9e9; text-align: ;"readonly />
        <input type="hidden" id="agenda_exists" name="agenda_exists" maxlength="100"  value="<?=$ls_agenda_exists;?>" style="width:150px;background-color:#ffff99; text-align: ;"readonly /> 
    </div>
    </td>
   </tr>
</table>
</fieldset>
<br>
<fieldset><legend><b>Informasi Peserta</b></legend>
<table border='0' width="100%">
    <tr>
        <td width="50%" valign="top">
            <div class="l_frm" ><label for="kpj">No. Peserta<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
            <div class="r_frm">
                <input type="hidden" id="kode_tk" name="kode_tk" maxlength="100"  value="<?=$ls_kode_tk;?>" style="width:150px;background-color:#ffff99" readonly class="disabled" />
                <input type="text" id="kpj" name="kpj" maxlength="100"  value="<?=$ls_kpj;?>" style="width:150px;background-color:#e9e9e9" readonly class="disabled" />
            </div>
            <div class="l_frm" ><label for="no_nik">NIK :</label></div>
            <div class="r_frm">
                <input type="text" id="no_nik" name="no_nik" maxlength="100"  value="<?=$ls_no_nik;?>" style="width:150px;background-color:#e9e9e9" readonly  />
            </div>
            <div class="l_frm" ><label for="nama_tk">Nama Peserta :</label></div>
            <div class="r_frm">
                <input type="text" id="nama_tk" name="nama_tk" maxlength="100"  value="<?=$ls_nama_tk;?>" style="width:265px;background-color:#e9e9e9" readonly  />
            </div>
        </td>
        <td valign="top">
            <div class="l_frm" ><label for="npp">NPP<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
            <div class="r_frm">
                <input type="hidden" id="kode_perusahaan" name="kode_perusahaan" maxlength="100"  value="<?=$ls_kode_perusahaan;?>" style="width:200px;background-color:#ffff99" readonly class="disabled" />
                <input type="hidden" id="kode_kepesertaan" name="kode_kepesertaan" maxlength="100"  value="<?=$ls_kode_kepesertaan;?>" style="width:200px;background-color:#ffff99" readonly class="disabled" />
                <input type="text" id="npp" name="npp" maxlength="100"  value="<?=$ls_npp;?>" style="width:80px; text-align: center; background-color:#e9e9e9" readonly class="disabled" /> - 
                <input type="text" id="nama_perusahaan" name="nama_perusahaan" maxlength="100"  value="<?=$ls_nama_perusahaan;?>" style="width:265px;background-color:#e9e9e9" readonly />
            </div>
            <!-- <div class="l_frm" ><label for="nama_perusahaan">Nama Perusahaan :</label></div>
            <div class="r_frm">
                <input type="text" id="nama_perusahaan" name="nama_perusahaan" maxlength="100"  value="<?=$ls_nama_perusahaan;?>" style="width:265px;background-color:#e9e9e9" readonly />
            </div> -->
            <div class="l_frm" ><label for="kode_divisi">Unit Kerja :</label></div>
            <div class="r_frm">
                <input type="text" id="kode_divisi" name="kode_divisi" maxlength="100"  value="<?=$ls_kode_divisi;?>" style="width:80px;background-color:#e9e9e9; text-align: center;" readonly /> - 
                <input type="text" id="nama_divisi" name="nama_divisi" maxlength="100"  value="<?=$ls_nama_divisi;?>" style="width:265px;background-color:#e9e9e9" readonly />
            </div>
            <div class="l_frm" ><label for="status_keps">Status Kepesertaan :</label></div>
            <div class="r_frm">
                <input type="text" id="status" name="status" maxlength="100"  value="<?=$ls_status;?>" style="width:120px;background-color:#e9e9e9;" readonly /> - 
                <input type="text" id="aktif" name="aktif" maxlength="100"  value="<?=$ls_aktif;?>" style="width:80px;background-color:#e9e9e9; text-align: center;" readonly />
            </div>
            <div class="l_frm" ><label for="kantor">Kantor :</label></div>
            <div class="r_frm">
                <input type="text" id="kode_kantor" name="kode_kantor" maxlength="100"  value="<?=$ls_kode_kantor;?>" style="width:30px;background-color:#e9e9e9; text-align: center;" readonly /> - 
                <input type="text" id="nama_kantor" name="nama_kantor" maxlength="100"  value="<?=$ls_nama_kantor;?>" style="width:250px;background-color:#e9e9e9" readonly />
            </div>
        </td>
    </tr>
</table>
</fieldset>
<br>
<fieldset><legend><b>Koreksi Upah</b></legend>
<table border='0' width="100%">
    <tr>
        <td width="100%" valign="top">
            <div class="l_frm" ><label for="tgl_dok_pendaftaran">Tanggal Terima Dokumen Pendaftaran:<span style="color:#ff0000;">&nbsp;*</span></label></div>
            <div class="r_frm">
                <input type="text" id="tgl_dok_pendaftaran" name="tgl_dok_pendaftaran" value="<?=$ls_tgl_dok_pendaftaran?>" size="15" required style="background-color:#ffff99;" readonly="readonly">
                <input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tgl_dok_pendaftaran', 'dd-mm-y');" src="../../images/calendar.gif" / <?PHP if(($p_task=="Edit" ) || $p_task=="View") {echo "disabled";}?>> - 
                <select size="1" id="time_dok_dftr" name="time_dok_dftr" value="<?=$ls_time_dok_dftr;?>" style="background-color:#ffff99; width:80px;" >
                    <option value="">-- time --</option>
                    <? 
                    $sql = "SELECT TO_CHAR(TRUNC(SYSDATE)+ 
                                           NUMTODSINTERVAL (LEVEL-1,'minute'),'hh24:mi') WAKTU
                            FROM DUAL
                            CONNECT BY LEVEL <= (24*60)";
                    $DB->parse($sql);
                    $DB->execute();
                    while($row = $DB->nextrow())
                    {
                    echo "<option ";
                    if ($row["WAKTU"]==$ls_time_dok_dftr && strlen($ls_time_dok_dftr)==strlen($row["WAKTU"])){ echo " selected"; }
                    echo " value=\"".$row["WAKTU"]."\">".$row["WAKTU"]."</option>";
                    }
                    ?>
                  </select>
            </div>
            <div class="l_frm" ><label for="tgl_dok_upah">Tanggal Terima Dokumen Upah:<span style="color:#ff0000;">&nbsp;*</span></label></div>
            <div class="r_frm">
                <input type="text" id="tgl_dok_upah" name="tgl_dok_upah" value="<?=$ls_tgl_dok_upah?>" size="15" required style="background-color:#ffff99;" readonly="readonly">
                <input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tgl_dok_upah', 'dd-mm-y');" src="../../images/calendar.gif" / <?PHP if(($p_task=="Edit" ) || $p_task=="View") {echo "disabled";}?>>
                - 
                <select size="1" id="time_dok_upah" name="time_dok_upah" value="<?=$ls_time_dok_upah;?>" style="background-color:#ffff99; width:80px;" >
                    <option value="">-- time --</option>
                    <? 
                    $sql = "SELECT TO_CHAR(TRUNC(SYSDATE)+ 
                                           NUMTODSINTERVAL (LEVEL-1,'minute'),'hh24:mi') WAKTU
                            FROM DUAL
                            CONNECT BY LEVEL <= (24*60)";
                    $DB->parse($sql);
                    $DB->execute();
                    while($row = $DB->nextrow())
                    {
                    echo "<option ";
                    if ($row["WAKTU"]==$ls_time_dok_upah && strlen($ls_time_dok_upah)==strlen($row["WAKTU"])){ echo " selected"; }
                    echo " value=\"".$row["WAKTU"]."\">".$row["WAKTU"]."</option>";
                    }
                    ?>
                  </select>
            </div>
            <div class="l_frm" ><label for="sumber_data">Sumber Data:<span style="color:#ff0000;">&nbsp;*</span></label></div>
            <div class="r_frm">
                <select size="1" id="sumber_data" name="sumber_data" value="<?=$ls_sumber_data;?>" style="background-color:#ffff99; width:80px;" >
                    <option value="">-- pilih --</option>
                    <? 
                    $sql = "SELECT * FROM MS.MS_LOOKUP where TIPE = 'KLMKORSBR' ORDER BY SEQ ASC";
                    $DB->parse($sql);
                    $DB->execute();
                    while($row = $DB->nextrow())
                    {
                    echo "<option ";
                    if ($row["KODE"]==$ls_sumber_data && strlen($ls_sumber_data)==strlen($row["KODE"])){ echo " selected"; }
                    echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
                    }
                    ?>
                  </select>

                <!-- <select size="1" id="sumber_data" name="sumber_data" value="<?=$ls_sumber_data;?>" style="background-color:#ffff99; width: 160px" >
                    <option value="">-- pilih --</option>
                    <option value="SIPP" <?php echo $ls_sumber_data=='SIPP'?'selected':'';?>>SIPP</option>
                    <option value="Manual" <?php echo $ls_sumber_data=='Manual'?'selected':'';?>>Manual</option>
                    <option value="Email" <?php echo $ls_sumber_data=='Email'?'selected':'';?>>Email</option>
                    <option value="Telepon" <?php echo $ls_sumber_data=='Telepon'?'selected':'';?>>Telepon</option>
                    <option value="SMS" <?php echo $ls_sumber_data=='SMS'?'selected':'';?>>SMS</option>
                    <option value="Media Lain" <?php echo $ls_sumber_data=='Media Lain'?'selected':'';?>>Media Lain</option>
                  </select> -->

            </div>
             <div class="l_frm" ><label for="blth_upah">BLTH Upah Kecelakaan:<span style="color:#ff0000;">&nbsp;*</span></label></div>
            <div class="r_frm">
                 <select size="1" id="bulan" name="bulan" value="<?=$ls_bulan;?>" style="background-color:#e9e9e9; width:120px;" readonly="true"> 
                    <option disabled value="">-- pilih --</option>
                    <? 
                    $sql = "SELECT * FROM SIJSTK.MS_LOOKUP WHERE TIPE = 'BULAN'
                            AND AKTIF = 'Y'
                            ORDER BY KODE ASC";
                    $DB->parse($sql);
                    $DB->execute();
                    while($row = $DB->nextrow())
                    {
                    echo "<option disabled";
                    if ($row["KODE"]==$ls_bulan && strlen($ls_bulan)==strlen($row["KODE"])){ echo " selected"; }
                    echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
                    }
                    ?>
                  </select>  
                  <!-- <input type="text" id="bulan" name="bulan" maxlength="100"  value="<?=$ls_bulan;?>" style="width:120px;background-color:#ffff99; text-align: center;" readonly autocomplete="off" /> -->
                  - 
                  <input type="text" id="tahun" name="tahun" maxlength="100"  value="<?=$ls_tahun;?>" style="width:80px;background-color:#e9e9e9; text-align: center;" readonly autocomplete="off" />
            </div>
            <div class="l_frm" ><label for="upah_terakhir">Upah BLTH Kecelakaan:<span style="color:#ff0000;width: 300px">&nbsp;*</span></label></div>
            <div class="r_frm">
                <input id="upah_terakhir" name="upah_terakhir" value="<?=number_format((float)$ls_upah_terakhir,2,".",",");?>" onblur="this.value=format_uang(this.value);" size="25" style="border-width: 1;text-align:right;background-color:#e9e9e9"  maxlength="20" autocomplete="off" readonly >
            </div>
            <div class="l_frm" ><label for="keterangan_koreksi">Keterangan Koreksi:<span style="color:#ff0000;width: 300px">&nbsp;*</span></label></div>   
            <div class="r_frm">
            <textarea id="keterangan_koreksi" maxlength="300" name="keterangan_koreksi" style="background-color:#ffff99;width:265px;" rows="2" <?=$i_readonly;?>><?=$ls_keterangan_koreksi;?></textarea> 
			<? 
			$sql = "SELECT klm.TGL_KECELAKAAN, korklm.TGL_TERIMA_DOK_PENDAFTARAN,
            CASE
              WHEN to_date(to_char(klm.TGL_KECELAKAAN,'dd-mm-yyyy') || ' ' || (
              select SUBSTR(keterangan,9,6) || ':00' from MS.MS_LOOKUP a where a.TIPE = 'KLMJAMKERJ'
                and a.KODE = klm.KODE_JAM_KECELAKAAN),'dd-mm-yyyy hh24:mi:ss') < to_date(to_char(korklm.TGL_TERIMA_DOK_PENDAFTARAN,'dd-mm-yyyy HH24'),'dd-mm-yyyy hh24:mi:ss') THEN 1
              ELSE
              0
            END TGL_KECELAKAAN_PENDAFTARAN
            FROM PN.PN_KLAIM klm, PN.PN_AGENDA_KOREKSI_KLAIM_UPAH korklm
            WHERE klm.KODE_KLAIM = korklm.KODE_KLAIM
                                AND korklm.KODE_AGENDA = '$p_kode_agenda' AND ROWNUM=1";
			
			$DB->parse($sql);
			$DB->execute();
			if($row = $DB->nextrow())
				$cek_tgl_kecelakaan_pendaftaran = $row['TGL_KECELAKAAN_PENDAFTARAN'];	

			if($cek_tgl_kecelakaan_pendaftaran == "1"){
				echo "<br>";
				echo '<span style="color:red;font-weight:bold;">Tanggal terima dokumen pendaftaran lebih besar dari tanggal kecelakaan.</span>';
			}
			?>
            </div>
        </td>
        <td>
        </td>
    </tr>
</table>
</fieldset>

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

$( "#btn_tgl" ).blur(function() {
	var tgl_kecelakaan  = $('#tgl_kecelakaan').val();
    var tgl_dok_pendaftaran  = $('#tgl_dok_pendaftaran').val();
	
	if (tgl_kecelakaan != "" || tgl_kecelakaan != null)  {
		var arr_tgl_kecelakaan = tgl_kecelakaan.split('/');
		if (arr_tgl_kecelakaan.length === 3)
		{
			var date_tgl_kecelakaan_text = arr_tgl_kecelakaan[1] + '/' + arr_tgl_kecelakaan[0] + '/' + arr_tgl_kecelakaan[2];
			var set_tgl_kecelakaan = new Date(date_tgl_kecelakaan_text);
			set_tgl_kecelakaan.setHours(0, 0, 0, 0); 
		}
	}
	
	if (tgl_dok_pendaftaran != "" || tgl_dok_pendaftaran != null)  {
		var arr_tgl_dok_pendaftaran = tgl_dok_pendaftaran.split('/');
		if (arr_tgl_dok_pendaftaran.length === 3)
		{
			var date_tgl_dok_pendaftaran_text = arr_tgl_dok_pendaftaran[1] + '/' + arr_tgl_dok_pendaftaran[0] + '/' + arr_tgl_dok_pendaftaran[2];
			var set_tgl_dok_pendaftaran = new Date(date_tgl_dok_pendaftaran_text);
			set_tgl_dok_pendaftaran.setHours(0, 0, 0, 0); 
		}
	}
	
	if(set_tgl_kecelakaan <= set_tgl_dok_pendaftaran)
	{
		alert("Tanggal terima dokumen pendaftaran lebih besar dari tanggal kecelakaan!");
	}
});

 function fl_ls_get_lov_by_selected(lov, obj) {
        if (lov == "KODE_KLAIM") {
            if (obj.KODE_KLAIM != '') {
                var new_location = window.location.href.replace("#", "") + '&kd_klaim=' + obj.KODE_KLAIM;
                window.location.replace(new_location);
                // cek_tk_aktif();

            } else {
                alert("Kode Klaim tidak ditemukan, pilih No Klaim yang akan diubah !");
            }
        } 
    }

    function fl_js_get_lov_by(lov) { 
        if (lov == "KODE_KLAIM") {
            var params = "p=pn6001_koreksi_upah.php&a=formreg";
            NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_kor_upah_lov_klaim.php?'+params,'',800,500,1);
        } 
    }


window.reloadFaskes=function(p_data){
    var new_location = window.location.href.replace("#", "") + '&kd_klaim=' +p_data;
    window.location.replace(new_location);
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

function get_upah()
    {
      if($('#bulan').val()!="" && $('#tahun').val() !=""){
      	 var blth = $('#bulan').val()+$('#tahun').val();
	      //alert(blth);
	      $.ajax({
	        type: 'POST',
	        url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_koreksi_upah_action.php?'+Math.random(),
	        data: { TYPE:'GET_UPAH', KODE_KEPESERTAAN:$('#kode_kepesertaan').val(), KODE_TK:$('#kode_tk').val(), BLTH:blth},
	        //
	        async: false,
	        success: function(data) {           
	           //preload(false); 
	              jdata = JSON.parse(data);
	              //console.log(data);
	              //console.log(JSON.parse(data));
	                  if(jdata.ret == '0' && jdata.count == '1'){ 
	                    $('#upah_terakhir').val(numeral(jdata.data[0].NOM_UPAH).format('0,0.00'));
	                  } else {
	                    alert("Nilai Upah pada BLTH "+blth+" tidak ditemukan");
	                    $('#upah_terakhir').val('0.00');
	                  }

	        	} 
	    	});
      }
     
    } 

 function isValid() {
        var kd_klaim             = $('#kode_klaim').val();
        var tgl_dok_pendaftaran  = $('#tgl_dok_pendaftaran').val();
        var tgl_dok_upah         = $('#tgl_dok_upah').val();
        var time_dok_dftr        = $('#time_dok_dftr').val();
        var time_dok_upah        = $('#time_dok_upah').val();
        var sumber_data          = $('#sumber_data').val();
        var upah_terakhir        = $('#upah_terakhir').val();
        var bulan                = $('#bulan').val();
        var tahun                = $('#tahun').val();
		var keterangan_koreksi   = $('#keterangan_koreksi').val();
        var file                 = $('#datafile').val();

        if (kd_klaim== "" || kd_klaim== null)  {
            return { val : false, msg : "Kode Klaim masih kosong, harap lengkapi data input !" };
        } else if (tgl_dok_pendaftaran == "" || tgl_dok_pendaftaran == null) {
            return { val : false, msg : "Tanggal Terima Dokumen Pendaftaran masih kosong, harap lengkapi data input !" };
        } else if (time_dok_dftr == "" || time_dok_dftr == null) {
            return { val : false, msg : "Waktu Terima Dokumen Pendaftaran masih kosong, harap lengkapi data input !" };
        } else if (tgl_dok_upah == "" || tgl_dok_upah== null) {
            return { val : false, msg : "Tanggal Terima Dokumen Upah masih kosong, harap lengkapi data input !" };
        } else if (time_dok_upah == "" || time_dok_upah== null) {
            return { val : false, msg : "Waktu Terima Dokumen Upah masih kosong, harap lengkapi data input !" };
        } else if (sumber_data == "" || sumber_data== null) {
            return { val : false, msg : "Sumber Data masih kosong, harap lengkapi data input !" };
        } else if (upah_terakhir == "" || upah_terakhir == '0.00' || upah_terakhir == '0') {
            return { val : false, msg : "Upah Terakhir Saat Kecelakaan masih kosong, harap lengkapi data input !" };
        } else if (bulan == "" || bulan == null) {
            return { val : false, msg : "Bulan BLTH Upah Terakhir masih kosong, harap lengkapi data input !" };
        } else if (tahun == "" || tahun == null) {
            return { val : false, msg : "Tahun BLTH Upah Terakhir masih kosong, harap lengkapi data input !" };
        } else if (keterangan_koreksi == "" || keterangan_koreksi == null) {
            return { val : false, msg : "Keterangan koreksi masih kosong, harap lengkapi data input !" };
        } 
        else if (file == "" || file == null) {
            return { val : false, msg : "Berita Acara masih kosong, harap lengkapi data input !" };
        }   
        return { val : true, msg : "Valid"} ;

    }
</script>   