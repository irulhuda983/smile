<?php
$php_file_name='pn6001_koreksi_perlindungan_pmi';

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
               /*--H.NAMA_JENIS_AGENDA,*/
               A.KODE_JENIS_AGENDA_DETIL,
               /*--G.NAMA_JENIS_AGENDA_DETIL,*/
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
               TO_CHAR(B.TGL_AWAL_PRA, 'DD/MM/YYYY') TGL_AWAL_PRA,
               TO_CHAR(B.TGL_AKHIR_PRA, 'DD/MM/YYYY') TGL_AKHIR_PRA,
               TO_CHAR(B.TGL_AWAL_ONSITE, 'DD/MM/YYYY') TGL_AWAL_ONSITE,
               TO_CHAR(B.TGL_AKHIR_ONSITE, 'DD/MM/YYYY') TGL_AKHIR_ONSITE,
               TO_CHAR(B.TGL_AWAL_PASKA, 'DD/MM/YYYY') TGL_AWAL_PASKA,
               TO_CHAR(B.TGL_AKHIR_PASKA, 'DD/MM/YYYY') TGL_AKHIR_PASKA,
               TO_CHAR(B.TGL_AWAL_PRA_BARU, 'DD/MM/YYYY') TGL_AWAL_PRA_BARU,
               TO_CHAR(B.TGL_AKHIR_PRA_BARU, 'DD/MM/YYYY') TGL_AKHIR_PRA_BARU,
               TO_CHAR(B.TGL_AWAL_ONSITE_BARU, 'DD/MM/YYYY') TGL_AWAL_ONSITE_BARU,
               TO_CHAR(B.TGL_AKHIR_ONSITE_BARU, 'DD/MM/YYYY') TGL_AKHIR_ONSITE_BARU,
               TO_CHAR(B.TGL_AWAL_PASKA_BARU, 'DD/MM/YYYY') TGL_AWAL_PASKA_BARU,
               TO_CHAR(B.TGL_AKHIR_PASKA_BARU, 'DD/MM/YYYY') TGL_AKHIR_PASKA_BARU,
               B.JENIS_KOREKSI,
               B.NAMA_FILE,
               B.DOC_FILE,
               B.KETERANGAN KETERANGAN_KOREKSI
          FROM PN.PN_AGENDA_KOREKSI            A,
               PN.PN_AGENDA_KOREKSI_KLAIM_PMI B,
               KN.KN_PERUSAHAAN                C,
               KN.KN_DIVISI                    D,
               KN.KN_KEPESERTAAN_PRS           E,
               PN.PN_KLAIM                     F
               /*-- PN.PN_KODE_JENIS_AGENDA_KOR_DETIL G,
               -- PN.PN_KODE_JENIS_AGENDA_KOREKSI H */
         WHERE     A.KODE_AGENDA = B.KODE_AGENDA
               AND B.KODE_PERUSAHAAN = C.KODE_PERUSAHAAN
               AND B.KODE_PERUSAHAAN = D.KODE_PERUSAHAAN
               AND B.KODE_DIVISI = D.KODE_DIVISI
               AND B.KODE_KEPESERTAAN = E.KODE_KEPESERTAAN
               AND B.KODE_KLAIM = F.KODE_KLAIM
               /* AND A.KODE_JENIS_AGENDA = G.KODE_JENIS_AGENDA
               -- AND A.KODE_JENIS_AGENDA_DETIL = G.KODE_JENIS_AGENDA_DETIL
               -- AND A.KODE_JENIS_AGENDA = H.KODE_JENIS_AGENDA */
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
    $ls_kode_jenis_agenda       = $row["KODE_JENIS_AGENDA"];
    $ls_kode_jenis_agenda_detil = $row["KODE_JENIS_AGENDA_DETIL"];
    $ls_nama_file           = $row["NAMA_FILE"];
    $ls_keterangan_koreksi  = $row["KETERANGAN_KOREKSI"];
    $ls_agenda_exists       = $row["JML"];
    $ls_tgl_awal_pra_baru        = $row["TGL_AWAL_PRA_BARU"];
    $ls_tgl_akhir_pra_baru       = $row["TGL_AKHIR_PRA_BARU"];
    $ls_tgl_awal_onsite_baru     = $row["TGL_AWAL_ONSITE_BARU"];
    $ls_tgl_akhir_onsite_baru    = $row["TGL_AKHIR_ONSITE_BARU"];
    $ls_tgl_awal_paska_baru      = $row["TGL_AWAL_PASKA_BARU"];
    $ls_tgl_akhir_paska_baru     = $row["TGL_AKHIR_PASKA_BARU"];
    $ls_tgl_awal_pra        = $row["TGL_AWAL_PRA"];
    $ls_tgl_akhir_pra       = $row["TGL_AKHIR_PRA"];
    $ls_tgl_awal_onsite     = $row["TGL_AWAL_ONSITE"];
    $ls_tgl_akhir_onsite    = $row["TGL_AKHIR_ONSITE"];
    $ls_tgl_awal_paska      = $row["TGL_AWAL_PASKA"];
    $ls_tgl_akhir_paska     = $row["TGL_AKHIR_PASKA"];
    $ls_jenis_koreksi       = $row["JENIS_KOREKSI"];
	$ls_jenis_koreksi       = $ls_jenis_koreksi == "" ? "PRA" : $ls_jenis_koreksi;
    // $ls_nama_jenis_agenda   = $row["NAMA_JENIS_AGENDA"];
    // $ls_nama_jenis_agenda_detil  = $row["NAMA_JENIS_AGENDA_DETIL"];
    //$ls_detil_status       = $row["STATUS_AGENDA"];
    // $ls_keterangan          = $row['KETERANGAN'];        
    //}
    
    if ($p_kode_klaim != "" && $p_task == "New"){    
        $sql = "
        select  to_char(max(tgl_awal_pra), 'dd-mm-yyyy') as tgl_awal_pra, 
                to_char(max(tgl_akhir_pra), 'dd-mm-yyyy') as tgl_akhir_pra, 
                to_char(max(tgl_awal_onsite), 'dd-mm-yyyy') as tgl_awal_onsite,
                to_char(max(tgl_akhir_onsite), 'dd-mm-yyyy') as tgl_akhir_onsite,
                to_char(max(tgl_awal_paska), 'dd-mm-yyyy') as tgl_awal_paska,
                to_char(max(tgl_akhir_paska), 'dd-mm-yyyy') as tgl_akhir_paska
        from    (  
        select  null as tgl_awal_pra, 
                null as tgl_akhir_pra, 
                tgl_efektif as tgl_awal_onsite, 
                tgl_expired as tgl_akhir_onsite,
                (tgl_expired + 1) as tgl_awal_paska, 
                tgl_grace as tgl_akhir_paska
        from    kn.kn_kepesertaan_tk 
        where   kode_kepesertaan = '$ls_kode_kepesertaan' 
                and kode_tk = '$ls_kode_tk' 
                and status = 'TKI'
                and no_mutasi = (
                select  max(no_mutasi) 
                from    kn.kn_kepesertaan_tk 
                where   kode_kepesertaan = '$ls_kode_kepesertaan' 
                        and kode_tk = '$ls_kode_tk'
                        and status = 'TKI')
        union all                
        select  tgl_efektif as tgl_awal_pra, 
                tgl_expired as tgl_akhir_pra, 
                null as tgl_awal_onsite, 
                null as tgl_akhir_onsite,
                null as tgl_awal_paska, 
                null as tgl_akhir_paska
        from    kn.kn_kepesertaan_tk 
        where   kode_kepesertaan = '$ls_kode_kepesertaan' 
                and kode_tk = '$ls_kode_tk' 
                and status = 'CTKI'
                and no_mutasi = (
                select  max(no_mutasi) 
                from    kn.kn_kepesertaan_tk 
                where   kode_kepesertaan = '$ls_kode_kepesertaan' 
                        and kode_tk = '$ls_kode_tk'
                        and status = 'CTKI')) ";
        $DB->parse($sql);
        $DB->execute();
        $row = $DB->nextrow();
        $ls_tgl_awal_pra        = $row["TGL_AWAL_PRA"];
        $ls_tgl_akhir_pra       = $row["TGL_AKHIR_PRA"];
        $ls_tgl_awal_onsite     = $row["TGL_AWAL_ONSITE"];
        $ls_tgl_akhir_onsite    = $row["TGL_AKHIR_ONSITE"];
        $ls_tgl_awal_paska      = $row["TGL_AWAL_PASKA"];
        $ls_tgl_akhir_paska     = $row["TGL_AKHIR_PASKA"];
    }

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
            <div class="l_frm" ><label for="tgl_awal_pra">Tanggal Pra:<span style="color:#ff0000;">&nbsp;*</span></label></div>
            <div class="r_frm">
                <input type="text" id="tgl_awal_pra" name="tgl_awal_pra" value="<?=$ls_tgl_awal_pra?>" size="15" required style="background-color:#e9e9e9;" readonly="readonly">
                <input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tgl_awal_pra', 'dd-mm-y');" src="../../images/calendar.gif" / disabled>
                s/d
                <input type="text" id="tgl_akhir_pra" name="tgl_akhir_pra" value="<?=$ls_tgl_akhir_pra?>" size="15" required style="background-color:#e9e9e9;" readonly="readonly">
                <input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tgl_akhir_pra', 'dd-mm-y');" src="../../images/calendar.gif" / disabled>
            </div>
            <div class="l_frm" ><label for="tgl_awal_onsite">Tanggal Onsite:<span style="color:#ff0000;">&nbsp;*</span></label></div>
            <div class="r_frm">
                <input type="text" id="tgl_awal_onsite" name="tgl_awal_onsite" value="<?=$ls_tgl_awal_onsite?>" size="15" required style="background-color:#e9e9e9;" readonly="readonly">
                <input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tgl_awal_onsite', 'dd-mm-y');" src="../../images/calendar.gif" / disabled>
                s/d
                <input type="text" id="tgl_akhir_onsite" name="tgl_akhir_onsite" value="<?=$ls_tgl_akhir_onsite?>" size="15" required style="background-color:#e9e9e9;" readonly="readonly">
                <input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tgl_akhir_onsite', 'dd-mm-y');" src="../../images/calendar.gif" / disabled>
            </div>
            <div class="l_frm" ><label for="tgl_awal_paska">Tanggal Paska:<span style="color:#ff0000;">&nbsp;*</span></label></div>
            <div class="r_frm">
                <input type="text" id="tgl_awal_paska" name="tgl_awal_paska" value="<?=$ls_tgl_awal_paska?>" size="15" required style="background-color:#e9e9e9;" readonly="readonly">
                <input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tgl_awal_paska', 'dd-mm-y');" src="../../images/calendar.gif" / disabled>
                s/d
                <input type="text" id="tgl_akhir_paska" name="tgl_akhir_paska" value="<?=$ls_tgl_akhir_paska?>" size="15" required style="background-color:#e9e9e9;" readonly="readonly">
                <input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tgl_akhir_paska', 'dd-mm-y');" src="../../images/calendar.gif" / disabled>
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
<fieldset><legend><b>Koreksi Data Perlindungan</b></legend>
<table border='0' width="100%">
    <tr>
        <td width="100%" valign="top">
            <div class="l_frm" ><label for="tgl_awal_pra_baru"></label></div>
            <div class="r_frm" style="height: 24px;">
                <input type="radio" name="jenis_koreksi" value="PRA" <?=$ls_jenis_koreksi == "PRA" ? "checked" : ""?> <?PHP if(($p_task=="Edit" ) || $p_task=="View") {echo "disabled";}?>><span style="margin-top: -2px;">Pra</span> &nbsp;
                <input type="radio" name="jenis_koreksi" value="ONSITE" <?=$ls_jenis_koreksi == "ONSITE" ? "checked" : ""?> <?PHP if(($p_task=="Edit" ) || $p_task=="View") {echo "disabled";}?>><span>Onsite</span>
            </div>
            <div class="l_frm" id="d_tgl_pra_1"><label for="tgl_awal_pra_baru">Tanggal Pra:<span style="color:#ff0000;">&nbsp;*</span></label></div>
            <div class="r_frm" id="d_tgl_pra_2">
                <input type="text" id="tgl_awal_pra_baru" name="tgl_awal_pra_baru" value="<?=$ls_tgl_awal_pra_baru?>" size="15" required style="background-color:#ffff99;" onblur="convert_date(tgl_awal_pra_baru);" <?PHP if(($p_task!="Edit" ) && $p_task!="New") {echo "readonly";}?>>
                <input id="btn_tgl_awal_pra" class="btn_tgl_pra" type="image" align="top" onclick="return showCalendar('tgl_awal_pra_baru', 'dd-mm-y');" src="../../images/calendar.gif" / <?PHP if(($p_task=="Edit" ) || $p_task=="View") {echo "disabled";}?>>
                s/d
                <input type="text" id="tgl_akhir_pra_baru" name="tgl_akhir_pra_baru" value="<?=$ls_tgl_akhir_pra_baru?>" size="15" required style="background-color:#ffff99;" onblur="convert_date(tgl_akhir_pra_baru);" <?PHP if(($p_task!="Edit" ) && $p_task!="New") {echo "readonly";}?>>
                <input id="btn_tgl_akhir_pra" class="btn_tgl_pra" type="image" align="top" onclick="return showCalendar('tgl_akhir_pra_baru', 'dd-mm-y');" src="../../images/calendar.gif" / <?PHP if(($p_task=="Edit" ) || $p_task=="View") {echo "disabled";}?>>
            </div>
            <div class="l_frm" id="d_tgl_onsite_1"><label for="tgl_awal_onsite_baru">Tanggal On Site:<span style="color:#ff0000;">&nbsp;*</span></label></div>
            <div class="r_frm" id="d_tgl_onsite_2">
                <input type="text" id="tgl_awal_onsite_baru" name="tgl_awal_onsite_baru" value="<?=$ls_tgl_awal_onsite_baru?>" size="15" required style="background-color:#ffff99;" onblur="convert_date(tgl_awal_onsite_baru);" <?PHP if(($p_task!="Edit" ) && $p_task!="New") {echo "readonly";}?>>
                <input id="btn_tgl_awal_onsite" class="btn_tgl_onsite" type="image" align="top" onclick="return showCalendar('tgl_awal_onsite_baru', 'dd-mm-y');" src="../../images/calendar.gif" / <?PHP if(($p_task=="Edit" ) || $p_task=="View") {echo "disabled";}?>>
                s/d
                <input type="text" id="tgl_akhir_onsite_baru" name="tgl_akhir_onsite_baru" value="<?=$ls_tgl_akhir_onsite_baru?>" size="15" required style="background-color:#ffff99;" onblur="convert_date(tgl_akhir_onsite_baru); hitungTglPaska();" <?PHP if(($p_task!="Edit" ) && $p_task!="New") {echo "readonly";}?>>
                <input id="btn_tgl_akhir_onsite" class="btn_tgl_onsite" type="image" align="top" onclick="return showCalendar('tgl_akhir_onsite_baru', 'dd-mm-y');" src="../../images/calendar.gif" / <?PHP if(($p_task=="Edit" ) || $p_task=="View") {echo "disabled";}?>>
            </div>
            <div class="l_frm" id="d_tgl_paska_1"><label for="tgl_awal_paska_baru">Tanggal Paska:<span style="color:#ff0000;">&nbsp;</span></label></div>
            <div class="r_frm" id="d_tgl_paska_2">
                <input type="text" id="tgl_awal_paska_baru" name="tgl_awal_paska_baru" value="<?=$ls_tgl_awal_paska_baru?>" size="15" required style="background-color:#e9e9e9;" readonly="readonly">
                <input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tgl_awal_paska_baru', 'dd-mm-y');" src="../../images/calendar.gif" / <?PHP if(($p_task=="Edit" ) || $p_task=="View" || $p_task=="New") {echo "disabled";}?>>
                s/d
                <input type="text" id="tgl_akhir_paska_baru" name="tgl_akhir_paska_baru" value="<?=$ls_tgl_akhir_paska_baru?>" size="15" required style="background-color:#e9e9e9;" readonly="readonly">
                <input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tgl_akhir_paska_baru', 'dd-mm-y');" src="../../images/calendar.gif" / <?PHP if(($p_task=="Edit" ) || $p_task=="View" || $p_task=="New") {echo "disabled";}?>>
            </div>
            <div class="l_frm" ><label for="keterangan_koreksi">Keterangan Koreksi:<span style="color:#ff0000;width: 300px">&nbsp;*</span></label></div>   
            <div class="r_frm">
            <textarea id="keterangan_koreksi" maxlength="300" name="keterangan_koreksi" style="width:265px;" rows="2" <?=$i_readonly;?> <?PHP if(($p_task=="Edit" ) || $p_task=="View" || $p_task=="New") {echo "";}?>><?=$ls_keterangan_koreksi;?></textarea> 
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
    if ($("input[name='jenis_koreksi']:checked").val() == 'PRA') {
        $('#d_tgl_pra_1').show();
        $('#d_tgl_pra_2').show();
        $('#d_tgl_onsite_1').hide();
        $('#d_tgl_onsite_2').hide();
        $('#d_tgl_paska_1').hide();
        $('#d_tgl_paska_2').hide();
    }
    else if ($("input[name='jenis_koreksi']:checked").val() == 'ONSITE') {
        $('#d_tgl_pra_1').hide();
        $('#d_tgl_pra_2').hide();
        $('#d_tgl_onsite_1').show();
        $('#d_tgl_onsite_2').show();
        $('#d_tgl_paska_1').show();
        $('#d_tgl_paska_2').show();
    }
});

Date.prototype.addDays = function(days) {
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() + days);
    return date;
}

Date.prototype.formatDate = function(format){
    if (format == 'dd/MM/yyyy') {
        var date = new Date(this.valueOf());
        var day = date.getDate().toString();
        var month = (date.getMonth() + 1).toString();
        var year = date.getFullYear().toString();

        var dateString = day.padStart(2, '0') + '/' + month.padStart(2, '0') + '/' + year;

        return dateString;
    }
}

$('input[type=radio][name=jenis_koreksi]').change(function() {
    if (this.value == 'PRA') {
        $('#d_tgl_pra_1').show();
        $('#d_tgl_pra_2').show();
        $('#d_tgl_onsite_1').hide();
        $('#d_tgl_onsite_2').hide();
        $('#d_tgl_paska_1').hide();
        $('#d_tgl_paska_2').hide();
    }
    else if (this.value == 'ONSITE') {
        $('#d_tgl_pra_1').hide();
        $('#d_tgl_pra_2').hide();
        $('#d_tgl_onsite_1').show();
        $('#d_tgl_onsite_2').show();
        $('#d_tgl_paska_1').show();
        $('#d_tgl_paska_2').show();
    }
});

$( ".btn_tgl_pra" ).blur(function() {
	var tgl_awal_pra_baru  = $('#tgl_awal_pra_baru').val();
    var tgl_akhir_pra_baru  = $('#tgl_akhir_pra_baru').val();

    if (tgl_awal_pra_baru != "" || tgl_awal_pra_baru != null)  {
        var arr_tgl_awal_pra_baru = tgl_awal_pra_baru.split('/');
        if (arr_tgl_awal_pra_baru.length === 3)
        {
            var date_tgl_awal_pra_baru_text = arr_tgl_awal_pra_baru[1] + '/' + arr_tgl_awal_pra_baru[0] + '/' + arr_tgl_awal_pra_baru[2];
            var set_tgl_awal_pra_baru = new Date(date_tgl_awal_pra_baru_text);
        }
    }

    if (tgl_akhir_pra_baru != "" || tgl_akhir_pra_baru != null)  {
        var arr_tgl_akhir_pra_baru = tgl_akhir_pra_baru.split('/');
        if (arr_tgl_akhir_pra_baru.length === 3)
        {
            var date_tgl_akhir_pra_baru_text = arr_tgl_akhir_pra_baru[1] + '/' + arr_tgl_akhir_pra_baru[0] + '/' + arr_tgl_akhir_pra_baru[2];
            var set_tgl_akhir_pra_baru = new Date(date_tgl_akhir_pra_baru_text);
        }
    }

    if(set_tgl_awal_pra_baru > set_tgl_akhir_pra_baru)
    {
        alert("Tanggal akhir pra harus lebih besar dari tanggal awal pra!");
        $('#tgl_akhir_pra_baru').val('');
    }
});

$( ".btn_tgl_onsite" ).blur(function() {
    var tgl_awal_onsite_baru  = $('#tgl_awal_onsite_baru').val();
    var tgl_akhir_onsite_baru  = $('#tgl_akhir_onsite_baru').val();

    if (tgl_awal_onsite_baru != "" || tgl_awal_onsite_baru != null)  {
        var arr_tgl_awal_onsite_baru = tgl_awal_onsite_baru.split('/');
        if (arr_tgl_awal_onsite_baru.length === 3)
        {
            var date_tgl_awal_onsite_baru_text = arr_tgl_awal_onsite_baru[1] + '/' + arr_tgl_awal_onsite_baru[0] + '/' + arr_tgl_awal_onsite_baru[2];
            var set_tgl_awal_onsite_baru = new Date(date_tgl_awal_onsite_baru_text);
        }
    }

    if (tgl_akhir_onsite_baru != "" || tgl_akhir_onsite_baru != null)  {
        var arr_tgl_akhir_onsite_baru = tgl_akhir_onsite_baru.split('/');
        if (arr_tgl_akhir_onsite_baru.length === 3)
        {
            var date_tgl_akhir_onsite_baru_text = arr_tgl_akhir_onsite_baru[1] + '/' + arr_tgl_akhir_onsite_baru[0] + '/' + arr_tgl_akhir_onsite_baru[2];
            var set_tgl_akhir_onsite_baru = new Date(date_tgl_akhir_onsite_baru_text);
        }
    }

    if(set_tgl_awal_onsite_baru > set_tgl_akhir_onsite_baru)
    {
        alert("Tanggal akhir onsite harus lebih besar dari tanggal awal onsite!");
        $('#tgl_akhir_onsite_baru').val('');
        $('#tgl_awal_paska_baru').val('');
        $('#tgl_akhir_paska_baru').val('');
    }

    if ($(this).attr('id') == 'btn_tgl_akhir_onsite') {
        hitungTglPaska();
    }
    
});

function hitungTglPaska(){
    var tgl_akhir_onsite_baru = $('#tgl_akhir_onsite_baru').val();

    var arr_tgl_akhir_onsite_baru = tgl_akhir_onsite_baru.split('/');
    if (arr_tgl_akhir_onsite_baru.length === 3)
    {
        var date_tgl_akhir_onsite_baru_text = arr_tgl_akhir_onsite_baru[1] + '/' + arr_tgl_akhir_onsite_baru[0] + '/' + arr_tgl_akhir_onsite_baru[2];
        var set_tgl_akhir_onsite_baru = new Date(date_tgl_akhir_onsite_baru_text);

        var set_tgl_awal_paska_baru = set_tgl_akhir_onsite_baru.addDays(1);
        var set_tgl_akhir_paska_baru = set_tgl_akhir_onsite_baru.addDays(30);
        
        $('#tgl_awal_paska_baru').val(set_tgl_awal_paska_baru.formatDate('dd/MM/yyyy'));
        $('#tgl_akhir_paska_baru').val(set_tgl_akhir_paska_baru.formatDate('dd/MM/yyyy'));
    }
}

function fl_ls_get_lov_by_selected(lov, obj) {
    if (lov == "KODE_KLAIM") {
        if (obj.KODE_KLAIM != '') {
            var new_location = window.location.href.replace("#", "") + '&kd_klaim=' + obj.KODE_KLAIM;
            window.location.replace(new_location);

        } else {
            alert("Kode Klaim tidak ditemukan, pilih No Klaim yang akan diubah !");
        }
    } 
}

function fl_js_get_lov_by(lov) { 
    if (lov == "KODE_KLAIM") {
        var params = "p=pn6001_koreksi_perlindungan_pmi.php&a=formreg";
        NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_kor_perlindungan_pmi_lov_klaim.php?'+params,'',800,500,1);
    } 
}

 function isValid() {
        var kd_klaim                = $('#kode_klaim').val() == null ? "" : $('#kode_klaim').val();
        var tgl_awal_pra_baru       = $('#tgl_awal_pra_baru').val() == null ? "" : $('#tgl_awal_pra_baru').val();
        var tgl_akhir_pra_baru      = $('#tgl_akhir_pra_baru').val() == null ? "" : $('#tgl_akhir_pra_baru').val();
        var tgl_awal_onsite_baru    = $('#tgl_awal_onsite_baru').val() == null ? "" : $('#tgl_awal_onsite_baru').val();
        var tgl_akhir_onsite_baru   = $('#tgl_akhir_onsite_baru').val() == null ? "" : $('#tgl_akhir_onsite_baru').val();
        var keterangan_koreksi      = $('#keterangan_koreksi').val();
        var file                    = $('#datafile').val();

        if (kd_klaim == "")  {
            return { val : false, msg : "Kode Klaim masih kosong, harap lengkapi data input !" };
        } else if (tgl_awal_onsite_baru == ""  && tgl_akhir_onsite_baru == "" && tgl_awal_pra_baru == "" && tgl_akhir_pra_baru == "") {
            return { val : false, msg : "Tanggal Awal dan Tanggal Akhir untuk koreksi tanggal Pra atau tanggal Onsite masih kosong, harap lengkapi data input !" };
        } else if (tgl_awal_onsite_baru != "" && tgl_akhir_onsite_baru == "") {
            return { val : false, msg : "Tanggal Akhir Onsite masih kosong, harap lengkapi data input !" };
        } else if (tgl_awal_onsite_baru == "" && tgl_akhir_onsite_baru != "") {
            return { val : false, msg : "Tanggal Awal Onsite masih kosong, harap lengkapi data input !" };
        } else if (tgl_awal_pra_baru != "" && tgl_akhir_pra_baru == "") {
            return { val : false, msg : "Tanggal Akhir Pra masih kosong, harap lengkapi data input !" };
        } else if (tgl_awal_pra_baru == "" && tgl_akhir_pra_baru != "") {
            return { val : false, msg : "Tanggal Awal Pra masih kosong, harap lengkapi data input !" };
        } else if (keterangan_koreksi == "" || keterangan_koreksi == null) {
            return { val : false, msg : "Keterangan koreksi masih kosong, harap lengkapi data input !" };
        } else if (file == "" || file == null) {
            return { val : false, msg : "Berita Acara masih kosong, harap lengkapi data input !" };
        }   
        return { val : true, msg : "Valid"} ;

    }
</script>   