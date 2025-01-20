<?php
$php_file_name='pn6001_faskes';
if(trim($_REQUEST['kdf'])!='')
{
    $sql="select * from tc.tc_faskes where kode_faskes='{$_REQUEST['KODE_FASKES']}'";
    if($DB->parse($sql))
        if($DB->execute())
            if($row = $DB->nextrow())
            {
                
            }
}

$p_task = $_REQUEST["task"];
$p_kode_agenda = $_REQUEST["kd_agenda"];


if ($p_kode_agenda != "") {
        $sql = "
        SELECT  A.KODE_AGENDA,
                A.KODE_JENIS_AGENDA,
                A.KODE_JENIS_AGENDA_DETIL,
                B.KODE_FASKES
        FROM    PN.PN_AGENDA_KOREKSI A, TC.TC_FASKES_HIST B
        WHERE   A.KODE_AGENDA = B.KODE_AGENDA
        AND     A.KODE_AGENDA = '$p_kode_agenda'";
        //echo $sql;

        $DB->parse($sql);
        $DB->execute();
        $row = $DB->nextrow();
        $ls_kode_agenda                    = $row["KODE_AGENDA"];
        $ls_kode_jenis_agenda              = $row["KODE_JENIS_AGENDA"];
        $ls_kode_jenis_agenda_detil        = $row["KODE_JENIS_AGENDA_DETIL"];
        $ls_kode_faskes                    = $row["KODE_FASKES"];

        $sql_agn = "SELECT MAX(KODE_AGENDA) AS KODE_AGENDA_LAST FROM TC.TC_FASKES_HIST A
                WHERE KODE_FASKES = '$ls_kode_faskes'";

        $DB->parse($sql_agn);
        $DB->execute();
        $row = $DB->nextrow();
        $ls_kode_agenda_last         = $row["KODE_AGENDA_LAST"];
       
    }

    // if($ls_kode_agenda_last == 0){
    //     $ls_kode_agenda_last = $p_kode_agenda;
    // }
  
    $p_kode_faskes = $_REQUEST["kdf"];
    $p_kode_faskes = $ls_kode_faskes == "" ? $p_kode_faskes : $ls_kode_faskes;
    //echo $p_kode_faskes;
    if (($p_kode_faskes != "" && $p_task == "New") ||($ls_kode_agenda_last == $p_kode_agenda && $p_kode_agenda != "")){     
    $sql = "
          SELECT    KODE_FASKES,
                    KODE_TIPE,
                    KODE_KANTOR,
                    (SELECT NAMA_KANTOR FROM SIJSTK.MS_KANTOR
                        WHERE KODE_KANTOR = A.KODE_KANTOR
                    ) NAMA_KANTOR,
                    KODE_PEMBINA,
                    NAMA_FASKES,
                    ALAMAT,
                    RT,
                    RW,
                    KODE_KELURAHAN,
                    (SELECT NAMA_KELURAHAN FROM SIJSTK.MS_KELURAHAN
                        WHERE KODE_KELURAHAN = A.KODE_KELURAHAN
                    )NAMA_KELURAHAN,
                    KODE_KECAMATAN,
                    (SELECT NAMA_KECAMATAN FROM SIJSTK.MS_KECAMATAN
                        WHERE KODE_KECAMATAN = A.KODE_KECAMATAN
                    )NAMA_KECAMATAN,
                    KODE_KABUPATEN,
                    (SELECT NAMA_KABUPATEN FROM SIJSTK.MS_KABUPATEN
                        WHERE KODE_KABUPATEN = A.KODE_KABUPATEN
                    )NAMA_KABUPATEN,
                    KODE_POS,
                    NAMA_PIC,
                    HANDPHONE_PIC,
                    TGL_NONAKTIF,
                    TGL_AKTIF,
                    KODE_STATUS,
                    KODE_JENIS,
                    KODE_JENIS_DETIL,
                    NO_IJIN_PRAKTEK,
                    NPWP,
                    MAX_TERTANGGUNG,
                    FLAG_UMUM,
                    FLAG_GIGI,
                    FLAG_SALIN,
                    FLAG_REG_WEBSITE,
                    KETERANGAN,
                    NAMA_PEMILIK,
                    ALAMAT_PEMILIK,
                    RT_PEMILIK,
                    RW_PEMILIK,
                    KODE_KELURAHAN_PEMILIK,
                    (SELECT NAMA_KELURAHAN FROM SIJSTK.MS_KELURAHAN
                        WHERE KODE_KELURAHAN = A.KODE_KELURAHAN_PEMILIK
                    )NAMA_KELURAHAN_PEMILIK,
                    KODE_KECAMATAN_PEMILIK,
                    (SELECT NAMA_KECAMATAN FROM SIJSTK.MS_KECAMATAN
                        WHERE KODE_KECAMATAN = A.KODE_KECAMATAN_PEMILIK
                    )NAMA_KECAMATAN_PEMILIK,
                    KODE_KABUPATEN_PEMILIK,
                    (SELECT NAMA_KABUPATEN FROM SIJSTK.MS_KABUPATEN
                        WHERE KODE_KABUPATEN = A.KODE_KABUPATEN_PEMILIK
                    )NAMA_KABUPATEN_PEMILIK,
                    KODE_POS_PEMILIK,
                    TELEPON_AREA_PEMILIK,
                    TELEPON_PEMILIK,
                    TELEPON_EXT_PEMILIK,
                    FAX_AREA_PEMILIK,
                    FAX_PEMILIK,
                    EMAIL_PEMILIK,
                    KODE_KEPEMILIKAN,
                    KODE_METODE_PEMBAYARAN,
                    KODE_BANK_PEMBAYARAN,
                    NAMA_REKENING_PEMBAYARAN,
                    NO_REKENING_PEMBAYARAN,
                    TELEPON_AREA_PIC,
                    TELEPON_PIC,
                    TELEPON_EXT_PIC,
                    NO_FASKES,
                    KELAS_PERAWATAN,
                    BAGIAN_PIC,
                    TGL_SUBMIT,
                    PETUGAS_SUBMIT,
                    TGL_REKAM,
                    PETUGAS_REKAM,
                    TGL_UBAH,
                    PETUGAS_UBAH,
                    LATITUDE,
                    LONGITUDE,
                    KODE_KANTOR || ' - ' || (
                    SELECT
                        NAMA_KANTOR
                    FROM
                        SIJSTK.MS_KANTOR
                    WHERE
                        KODE_KANTOR = A.KODE_KANTOR ) AS KODE_NAMA_KACAB,
                    KODE_PIC_CABANG,
                    KODE_PIC_CABANG || ' - ' || (SELECT NAMA_USER FROM MS.SC_USER A WHERE KODE_PIC_CABANG = A.KODE_USER) AS KODE_USER_NAMA,
                    KONTAK_PIC_CABANG
            FROM TC.TC_FASKES A
            WHERE KODE_FASKES = '$p_kode_faskes'";
        } 
        else if ($p_task == "View" && $ls_kode_agenda_last != $p_kode_agenda) { 
            $sql_agn = "SELECT KODE_AGENDA FROM(
                            SELECT ROWNUM,A.* FROM TC.TC_FASKES_hist A
                            WHERE KODE_FASKES = '$ls_kode_faskes'
                            AND KODE_AGENDA > '$p_kode_agenda'
                            ORDER BY KODE_AGENDA ASC
                        )WHERE ROWNUM = 1 ";

            $DB->parse($sql_agn);
            $DB->execute();
            $row = $DB->nextrow();
            $ls_kode_agenda_vw         = $row["KODE_AGENDA"];


          $sql = "
          SELECT    KODE_FASKES,
                    KODE_TIPE,
                    KODE_KANTOR,
                    (SELECT NAMA_KANTOR FROM SIJSTK.MS_KANTOR
                        WHERE KODE_KANTOR = A.KODE_KANTOR
                    ) NAMA_KANTOR,
                    KODE_PEMBINA,
                    NAMA_FASKES,
                    ALAMAT,
                    RT,
                    RW,
                    KODE_KELURAHAN,
                    (SELECT NAMA_KELURAHAN FROM SIJSTK.MS_KELURAHAN
                        WHERE KODE_KELURAHAN = A.KODE_KELURAHAN
                    )NAMA_KELURAHAN,
                    KODE_KECAMATAN,
                    (SELECT NAMA_KECAMATAN FROM SIJSTK.MS_KECAMATAN
                        WHERE KODE_KECAMATAN = A.KODE_KECAMATAN
                    )NAMA_KECAMATAN,
                    KODE_KABUPATEN,
                    (SELECT NAMA_KABUPATEN FROM SIJSTK.MS_KABUPATEN
                        WHERE KODE_KABUPATEN = A.KODE_KABUPATEN
                    )NAMA_KABUPATEN,
                    KODE_POS,
                    NAMA_PIC,
                    HANDPHONE_PIC,
                    TGL_NONAKTIF,
                    TGL_AKTIF,
                    KODE_STATUS,
                    KODE_JENIS,
                    KODE_JENIS_DETIL,
                    NO_IJIN_PRAKTEK,
                    NPWP,
                    MAX_TERTANGGUNG,
                    FLAG_UMUM,
                    FLAG_GIGI,
                    FLAG_SALIN,
                    FLAG_REG_WEBSITE,
                    KETERANGAN,
                    NAMA_PEMILIK,
                    ALAMAT_PEMILIK,
                    RT_PEMILIK,
                    RW_PEMILIK,
                    KODE_KELURAHAN_PEMILIK,
                    (SELECT NAMA_KELURAHAN FROM SIJSTK.MS_KELURAHAN
                        WHERE KODE_KELURAHAN = A.KODE_KELURAHAN_PEMILIK
                    )NAMA_KELURAHAN_PEMILIK,
                    KODE_KECAMATAN_PEMILIK,
                    (SELECT NAMA_KECAMATAN FROM SIJSTK.MS_KECAMATAN
                        WHERE KODE_KECAMATAN = A.KODE_KECAMATAN_PEMILIK
                    )NAMA_KECAMATAN_PEMILIK,
                    KODE_KABUPATEN_PEMILIK,
                    (SELECT NAMA_KABUPATEN FROM SIJSTK.MS_KABUPATEN
                        WHERE KODE_KABUPATEN = A.KODE_KABUPATEN_PEMILIK
                    )NAMA_KABUPATEN_PEMILIK,
                    KODE_POS_PEMILIK,
                    TELEPON_AREA_PEMILIK,
                    TELEPON_PEMILIK,
                    TELEPON_EXT_PEMILIK,
                    FAX_AREA_PEMILIK,
                    FAX_PEMILIK,
                    EMAIL_PEMILIK,
                    KODE_KEPEMILIKAN,
                    KODE_METODE_PEMBAYARAN,
                    KODE_BANK_PEMBAYARAN,
                    NAMA_REKENING_PEMBAYARAN,
                    NO_REKENING_PEMBAYARAN,
                    TELEPON_AREA_PIC,
                    TELEPON_PIC,
                    TELEPON_EXT_PIC,
                    NO_FASKES,
                    KELAS_PERAWATAN,
                    BAGIAN_PIC,
                    TGL_SUBMIT,
                    PETUGAS_SUBMIT,
                    TGL_REKAM,
                    PETUGAS_REKAM,
                    TGL_UBAH,
                    PETUGAS_UBAH,
                    LATITUDE,
                    LONGITUDE,
                    KODE_KANTOR || ' - ' || (
                    SELECT
                        NAMA_KANTOR
                    FROM
                        SIJSTK.MS_KANTOR
                    WHERE
                        KODE_KANTOR = A.KODE_KANTOR ) AS KODE_NAMA_KACAB,
                    KODE_PIC_CABANG,
                    KODE_PIC_CABANG || ' - ' || (SELECT NAMA_USER FROM MS.SC_USER A WHERE KODE_PIC_CABANG = A.KODE_USER) AS KODE_USER_NAMA,
                    KONTAK_PIC_CABANG
            FROM TC.TC_FASKES_HIST A
            WHERE KODE_AGENDA = '$ls_kode_agenda_vw'";
         }

            //echo($sql);
            
            $DB->parse($sql);
            $DB->execute();
            $row = $DB->nextrow();
            $ls_kode_faskes         = $row["KODE_FASKES"];
            $ls_kode_tipe           = $row["KODE_TIPE"] ;       
            $ls_kd_kantor           = $row["KODE_KANTOR"];
            $ls_nama_kantor         = $row["NAMA_KANTOR"];
            $ls_kode_pembina        = $row["KODE_PEMBINA"];
            $ls_nama_faskes         = $row["NAMA_FASKES"];
            $ls_alamat              = $row["ALAMAT"];
            $ls_rt                  = $row["RT"] ;       
            $ls_rw                  = $row["RW"];
            $ls_kode_kelurahan      = $row["KODE_KELURAHAN"];
            $ls_kode_kecamatan      = $row["KODE_KECAMATAN"];
            $ls_kode_kabupaten      = $row["KODE_KABUPATEN"];
            $ls_nama_kelurahan      = $row["NAMA_KELURAHAN"];
            $ls_nama_kecamatan      = $row["NAMA_KECAMATAN"];
            $ls_nama_kabupaten      = $row["NAMA_KABUPATEN"];
            $ls_kode_pos            = $row["KODE_POS"];
            $ls_nama_pic            = $row["NAMA_PIC"] ;       
            $ls_handphone_pic       = $row["HANDPHONE_PIC"];
            $ls_tgl_nonaktif        = $row["TGL_NONAKTIF"];
            $ls_tgl_aktif           = $row["TGL_AKTIF"];
            $ls_kode_status         = $row["KODE_STATUS"];
            $ls_kode_jenis          = $row["KODE_JENIS"] ;       
            $ls_kode_jenis_detil    = $row["KODE_JENIS_DETIL"];
            $ls_no_ijin_praktek     = $row["NO_IJIN_PRAKTEK"];
            $ls_npwp                = $row["NPWP"];
            $ls_max_tertanggung     = $row["MAX_TERTANGGUNG"];
            $ls_keterangan          = $row["KETERANGAN"] ;       
            $ls_nama_pemilik        = $row["NAMA_PEMILIK"];
            $ls_alamat_pemilik      = $row["ALAMAT_PEMILIK"];
            $ls_rt_pemilik          = $row["RT_PEMILIK"];
            $ls_rw_pemilik          = $row["RW_PEMILIK"];
            $ls_kode_kelurahan_pemilik = $row["KODE_KELURAHAN_PEMILIK"] ;       
            $ls_kode_kecamatan_pemilik = $row["KODE_KECAMATAN_PEMILIK"];
            $ls_kode_kabupaten_pemilik = $row["KODE_KABUPATEN_PEMILIK"];
            $ls_nama_kelurahan_pemilik = $row["NAMA_KELURAHAN_PEMILIK"];
            $ls_nama_kecamatan_pemilik = $row["NAMA_KECAMATAN_PEMILIK"];
            $ls_nama_kabupaten_pemilik = $row["NAMA_KABUPATEN_PEMILIK"];
            $ls_kode_pos_pemilik       = $row["KODE_POS_PEMILIK"];
            $ls_telepon_area_pemilik   = $row["TELEPON_AREA_PEMILIK"];
            $ls_telepon_pemilik        = $row["TELEPON_PEMILIK"] ;       
            $ls_telepon_ext_pemilik    = $row["TELEPON_EXT_PEMILIK"];
            $ls_fax_area_pemilik       = $row["FAX_AREA_PEMILIK"];
            $ls_fax_pemilik            = $row["FAX_PEMILIK"];
            $ls_email_pemilik          = $row["EMAIL_PEMILIK"];
            $ls_kode_kepemilikan       = $row["KODE_KEPEMILIKAN"];
            $ls_kode_metode_byr        = $row["KODE_METODE_PEMBAYARAN"] ;       
            $ls_kode_bank_byr          = $row["KODE_BANK_PEMBAYARAN"];
            $ls_nama_rekening_pembayaran = $row["NAMA_REKENING_PEMBAYARAN"];
            $ls_no_rekening_pembayaran   = $row["NO_REKENING_PEMBAYARAN"];
            $ls_telepon_area_pic       = $row["TELEPON_AREA_PIC"] ;       
            $ls_telepon_pic            = $row["TELEPON_PIC"];
            $ls_telepon_ext_pic        = $row["TELEPON_EXT_PIC"];
            $ls_no_faskes              = $row["NO_FASKES"];
            if ($ls_no_faskes == "" || $ls_no_faskes == NULL){
                $ls_no_faskes = $ls_kode_faskes;
            }else{
                $ls_no_faskes = $ls_no_faskes;
            }
            $ls_kelas_perawatan        = $row["KELAS_PERAWATAN"];
            $ls_bagian_pic             = $row["BAGIAN_PIC"] ;       
            $ls_tgl_submit             = $row["TGL_SUBMIT"];
            $ls_petugas_submit         = $row["PETUGAS_SUBMIT"];
            $ls_tgl_rekam              = $row["TGL_REKAM"];
            $ls_petugas_rekam          = $row["PETUGAS_REKAM"];
            $ls_tgl_ubah               = $row["TGL_UBAH"];
            $ls_petugas_ubah           = $row["PETUGAS_UBAH"]; 
            $ls_latitude            =$row['LATITUDE'];
            $ls_longitude           =$row['LONGITUDE'];
            $kode_nama_kantor       =$row['KODE_NAMA_KACAB'];
            $kode_user_nama         =$row['KODE_USER_NAMA'];
            if ($kode_user_nama == " - "){
                $kode_user_nama = "";
            }else{
                $kode_user_nama = $kode_user_nama;
            }
            $kode_pic_cabang        =$row['KODE_PIC_CABANG'];
            $ls_handphone_pic_kacab =$row['KONTAK_PIC_CABANG'];
    //}          
?>
<style>
    .l_frm{width: 135px; clear: left; float: left;margin-bottom: 2px;text-align: right; margin-right: 2px;}
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
<fieldset><legend><b>Pilih Faskes/BLK</b></legend>
<table border='0' width="100%">
    <tr>
     <td width="50%" valign="top">
    <div class="l_frm" ><label for="kode_faskes">Kode Faskes/BLK<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
    <div class="r_frm">
        <input type="text" id="kode_faskes" name="kode_faskes" maxlength="100"  value="<?=$ls_kode_faskes;?>" style="width:150px;background-color:#ffff99; text-align: ;"readonly />
        <a href="#" onclick="fl_js_get_lov_by('KODE_FASKES')" tabindex="8" <?PHP if($_REQUEST["task"]!="New"){echo "style='pointer-events:none'";}?>/>                            
        <img src="../../images/help.png" alt="Cari Kabupaten" border="0" align="absmiddle"></a>
        <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="text" id="nama_faskes" name="nama_faskes" maxlength="100"  value="<?=$ls_nama_faskes;?>" style="width:200px;background-color:#e9e9e9" <?=$i_readonly;?> /> -->    
    </div>
    </td>
    <td width="50%" valign="top">
    </td>
    <td width="20%" valign="top">
    <div class="l_frm" ><label for="histori_faskes" style="color:#009999;"></label></div>
     <div class="r_frm">
        <a href="#" onclick="open_faskes_hist()" tabindex="8" <?PHP if($_REQUEST["task"]!="View"){echo "hidden";}?>/>                            
            <!-- <img src="../../images/application_view_columns.png" alt="Lihat Histori" border="0" align="absmiddle"> -->
            <font  color="#009999"><i><b>Data Faskes/BLK Sebelumnya</i></font>
        </a>
        <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="text" id="nama_faskes" name="nama_faskes" maxlength="100"  value="<?=$ls_nama_faskes;?>" style="width:200px;background-color:#e9e9e9" <?=$i_readonly;?> /> -->    
    </div>
    </td>
   </tr>
</table>
</fieldset>
<br>
<fieldset><legend><b>Informasi Fasilitas</b></legend>
<table border='0' width="100%">
    <tr>
        <td width="50%" valign="top">
            <div class="l_frm" ><label for="nama_faskes">Nama Faskes/BLK<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
            <div class="r_frm">
                <input type="text" id="nama_faskes" name="nama_faskes" maxlength="100"  value="<?=$ls_nama_faskes;?>" style="width:265px;background-color:#ffff99" <?=$i_readonly;?> />
            </div>
            <div class="l_frm" ><label for="no_faskes">No. Faskes/BLK :</label></div>
            <!-- <div class="r_frm"><?=$ls_no_faskes;?></div> -->
            <div class="r_frm">
                <input type="text" id="no_faskes" name="no_faskes" maxlength="100"  value="<?=$ls_no_faskes;?>" style="width:200px;background-color:#e9e9e9" readonly />
            </div>
            <div class="l_frm" ><label for="kode_tipe">Tipe Faskes/BLK<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
            <div class="r_frm">
                <select size="1" id="kode_tipe" name="kode_tipe" value="<?=$ls_kode_tipe;?>" style="background-color:#ffff99" <?PHP if($p_task=="View"){echo "disabled";}?> >
                    <option value="">-- pilih --</option>
                    <? 
                    $sql = "SELECT KODE_TIPE, NAMA_TIPE FROM TC.TC_KODE_TIPE WHERE NVL(STATUS_NONAKTIF,'T') = 'T' ORDER BY KODE_TIPE";
                    $DB->parse($sql);
                    $DB->execute();
                    while($row = $DB->nextrow())
                    {
                    echo "<option ";
                    if ($row["KODE_TIPE"]==$ls_kode_tipe && strlen($ls_kode_tipe)==strlen($row["KODE_TIPE"])){ echo " selected"; }
                    echo " value=\"".$row["KODE_TIPE"]."\">".$row["NAMA_TIPE"]."</option>";
                    }
                    ?>
                  </select>
            </div>
            <div class="l_frm" ><label for="kode_kantor">Kantor<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
            <!-- <div class="r_frm"><?=$ls_kode_kantor;?></div> -->
            <div class="r_frm">
                <input type="text" id="kode_kantor" name="kode_kantor" maxlength="100"  value="<?=$ls_kd_kantor;?>" style="width:30px;background-color:#ffff99" readonly /> - 
                <input type="text" id="nama_kantor" name="nama_kantor" maxlength="100"  value="<?=$ls_nama_kantor;?>" style="width:170px;background-color:#ffff99" readonly />
            </div>
            <div class="l_frm" ><label for="alamat">Alamat Lengkap<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
            <div class="r_frm"><textarea id="alamat" name="alamat" maxlength="300" style="width:265px;background-color:#ffff99" rows="2" <?=$i_readonly;?>><?=$ls_alamat;?></textarea></div>
            <div class="l_frm" ><label for="rt">RT/ RW :</label></div>
            <div class="r_frm">
                <input type="text" id="rt" name="rt" maxlength="5" style="width:30px;background-color:<?=$i_ocolor;?>" value="<?=$ls_rt;?>" <?=$i_readonly;?> />/ 
                <input type="text" id="rw" name="rw" maxlength="5" style="width:30px;background-color:<?=$i_ocolor;?>"  value="<?=$ls_rw;?>"  <?=$i_readonly;?> />
            </div>
            <div class="l_frm" ><label for="kode_pos">Kode Pos<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
            <div class="r_frm"><input type="text" id="kode_pos" name="kode_pos" style="width:90px;background-color: #ffff99" readonly   value="<?=$ls_kode_pos;?>"/>
                <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn2401_lov_pos.php?p=tc5001.php&a=formreg&b=kode_kelurahan&c=nama_kelurahan&d=kode_kecamatan&e=nama_kecamatan&f=kode_kabupaten&g=nama_kabupaten&h=kode_propinsi&j=nama_propinsi&k=kode_pos','_faskeslovprop',800,500,1)" tabindex="8" <?PHP if($_REQUEST["task"]!="New"){echo "style='pointer-events:none'";}?>/>							
                <img src="../../images/help.png" alt="Cari Kabupaten" border="0" align="absmiddle"></a>	
            </div>
            <input type="hidden" id="kode_kelurahan" name="kode_kelurahan" value="<?=$ls_kode_kelurahan;?>" />
            <input type="hidden" id="kode_kecamatan" name="kode_kecamatan" value="<?=$ls_kode_kecamatan;?>"/>
            <input type="hidden" id="kode_kabupaten" name="kode_kabupaten" value="<?=$ls_kode_kabupaten;?>"/>
            <input type="hidden" id="kode_propinsi" name="kode_propinsi" />
            <input type="hidden" id="nama_propinsi" name="nama_propinsi" />
            <div class="l_frm" ><label for="nama_kelurahan">Kelurahan :</label></div>
            <div class="r_frm"><input type="text" id="nama_kelurahan" name="nama_kelurahan" style="width:200px;background-color:#e9e9e9;" readonly  value="<?=$ls_nama_kelurahan;?>"/></div>
            <div class="l_frm" ><label for="nama_kecamatan">Kecamatan :</label></div>
            <div class="r_frm"><input type="text" id="nama_kecamatan" name="nama_kecamatan" style="width:200px;background-color:#e9e9e9;" readonly  value="<?=$ls_nama_kecamatan;?>"/></div>
            <div class="l_frm" ><label for="nama_kabupaten">Kabupaten :</label></div>
            <div class="r_frm"><input type="text" id="nama_kabupaten" name="nama_kabupaten" style="width:200px;background-color:#e9e9e9;" readonly  value="<?=$ls_nama_kabupaten;?>"/></div>
            <div class="l_frm" ><label for="latitude">Latitude<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
            <div class="r_frm"><input type="text" id="latitude" name="latitude" style="width:200px;background-color:#ffff99" <?=$i_readonly;?> value="<?=$ls_latitude;?>" /></div>
            <div class="l_frm" ><label for="longitude">Longitude<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
            <div class="r_frm"><input type="text" id="longitude" name="longitude" style="width:200px;background-color:#ffff99" <?=$i_readonly;?> value="<?=$ls_longitude;?>" /></div>
        </td>
        <td valign="top">
            <div class="l_frm" ><label for="telp_area_pic">No. Telepon :</label></div>
            <div class="r_frm">
                <input type="text" id="telp_area_pic" name="telp_area_pic" style="width:40px;background-color: <?=$i_ocolor;?>" maxlength="5" onchange="fl_js_val_numeric('telp_area_pic')"  value="<?=$ls_telepon_area_pic;?>"  <?=$i_readonly;?>/> 
                <input type="text" id="telp_pic" name="telp_pic" style="width:170px;background-color: <?=$i_ocolor;?>" maxlength="20" onchange="fl_js_val_numeric('telp_pic')" value="<?=$ls_telepon_pic;?>"  <?=$i_readonly;?>/>
            </div>
            <div class="l_frm" ><label for="telp_ext_pic">Telepon Ext. :</label></div>
            <div class="r_frm"><input type="text" id="telp_ext_pic" name="telp_ext_pic" style="width:100px;background-color: <?=$i_ocolor;?>" onchange="fl_js_val_numeric('telp_ext_pic')" value="<?=$ls_telepon_ext_pic;?>" maxlength="5"  <?=$i_readonly;?>/> </div>
            <div class="l_frm" ><label for="nama_pic">Nama PIC<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
            <div class="r_frm"><input type="text" id="nama_pic" name="nama_pic" maxlength="100" style="width:265px;background-color:#ffff99" value="<?=$ls_nama_pic;?>" <?=$i_readonly;?>/></div>
            
            <div class="l_frm" ><label for="bagian_pic">Bagian PIC<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
            <div class="r_frm"><input type="text" id="bagian_pic" name="bagian_pic" style="width:265px;background-color:#ffff99" maxlength="20" <?=$i_readonly;?> value="<?=$ls_bagian_pic;?>" /></div>
            <div class="l_frm" ><label for="handphone_pic">Handphone PIC<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
            <div class="r_frm"><input type="text" id="handphone_pic" name="handphone_pic" maxlength="20" style="width:140px;background-color:#ffff99" onchange="fl_js_val_numeric('handphone_pic')" value="<?=$ls_handphone_pic;?>" <?=$i_readonly;?>/></div>
            <div class="l_frm" ><label for="no_ijin_praktek">No. Ijin Praktek<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
            <div class="r_frm"><input type="text" id="no_ijin_praktek" name="no_ijin_praktek" maxlength="100" style="width:265px;background-color:#ffff99" value="<?=$ls_no_ijin_praktek;?>" <?=$i_readonly;?>/></div>
            <div class="l_frm" ><label for="npwp">NPWP Faskes/BLK<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
            <div class="r_frm"><input type="text" id="npwp" name="npwp" maxlength="15" style="width:140px;background-color:#ffff99" <?=$i_readonly;?>  value="<?=$ls_npwp;?>"/></div>
            <div class="l_frm" ><label for="kode_jenis">Jenis Faskes/BLK<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
            <div class="r_frm">
                <!-- <select id="kode_jenis" name="kode_jenis" tabindex="15" style="background-color:<?=$i_mcolor;?>" <?=$i_readonly;?> ><?=$kode_jenis;?>
                </select> -->
                <select size="1" id="kode_jenis" name="kode_jenis" value="<?=$ls_kode_jenis;?>" style="background-color:#ffff99" <?PHP if($p_task=="View"){echo "disabled";}?> >
                    <option value="">-- pilih --</option>
                    <? 
                    $sql = "SELECT KODE_JENIS, NAMA_JENIS FROM TC.TC_KODE_JENIS 
                                WHERE NVL(STATUS_NONAKTIF,'T') = 'T' 
                                AND KODE_TIPE = (SELECT KODE_TIPE FROM TC.TC_KODE_TIPE WHERE KODE_TIPE = '$ls_kode_tipe')
                                ORDER BY KODE_JENIS";
                    $DB->parse($sql);
                    $DB->execute();
                    while($row = $DB->nextrow())
                    {
                    echo "<option ";
                    if ($row["KODE_JENIS"]==$ls_kode_jenis && strlen($ls_kode_jenis)==strlen($row["KODE_JENIS"])){ echo " selected"; }
                    echo " value=\"".$row["KODE_JENIS"]."\">".$row["NAMA_JENIS"]."</option>";
                    }
                    ?>
                  </select>
            </div>
            <div class="l_frm"><label for="kode_jenis_detil">Sub Jenis Faskes/BLK* :</label></div>
            <div class="r_frm">
                <!-- <select id="kode_jenis_detil" name="kode_jenis_detil" <?=$i_readonly;?> style="background-color:<?=$i_ocolor;?>">
                </select> -->
                <select size="1" id="kode_jenis_detil" name="kode_jenis_detil" value="<?=$ls_kode_jenis_detil;?>" <?PHP if($p_task=="View"){echo "disabled";}?> >
                    <option value="">-- pilih --</option>
                    <? 
                    $sql = "SELECT KODE_JENIS_DETIL, NAMA_JENIS_DETIL FROM TC.TC_KODE_JENIS_DETIL
                                WHERE NVL(STATUS_NONAKTIF,'T') = 'T'
                                AND KODE_JENIS = (SELECT KODE_JENIS FROM TC.TC_KODE_JENIS WHERE KODE_JENIS = '$ls_kode_jenis') 
                            ORDER BY KODE_JENIS_DETIL";
                    $DB->parse($sql);
                    $DB->execute();
                    while($row = $DB->nextrow())
                    {
                    echo "<option ";
                    if ($row["KODE_JENIS_DETIL"]==$ls_kode_jenis_detil && strlen($ls_kode_jenis_detil)==strlen($row["KODE_JENIS_DETIL"])){ echo " selected"; }
                    echo " value=\"".$row["KODE_JENIS_DETIL"]."\">".$row["NAMA_JENIS_DETIL"]."</option>";
                    }
                    ?>
                  </select>
            </div>
            <div id="div_kelas_perawatan" style="display:none">
            <div class="l_frm"><label for="kelas_perawatan">Kelas Perawatan* :</label></div>
            <div class="r_frm">
            <select id="kelas_perawatan" name="kelas_perawatan" tabindex="15" style="background-color:<?=$i_ocolor;?>" <?=$i_readonly;?> >
                <option value="KELAS 1" <?php echo $ls_kelas_perawatan=='KELAS 1'?'selected':'';?>>KELAS 1</option>
                <option value="KELAS 2" <?php echo $ls_kelas_perawatan=='KELAS 2'?'selected':'';?>>KELAS 2</option>
                <option value="KELAS 3" <?php echo $ls_kelas_perawatan=='KELAS 3'?'selected':'';?>>KELAS 3</option>
            </select>
            </div>
            </div>
            <div class="l_frm" ><label for="keterangan">Keterangan :</label></div>
            <div class="r_frm"><textarea id="keterangan" name="keterangan" rows="2" maxlength="300" style="width:265px;background-color:<?=$i_ocolor;?>" <?=$i_readonly;?> ><?=$ls_keterangan;?></textarea></div>
        </td>
    </tr>
</table>
</fieldset>
<br>
<fieldset><legend><b>Informasi Pemilik</b></legend>
<table border='0' width="100%">
    <tr>
        <td width="50%" valign="top">
            <div class="l_frm" ><label for="nama_pemilik">Nama<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
            <div class="r_frm"><input type="text" id="nama_pemilik" maxlength="100" name="nama_pemilik" style="width:265px;background-color:#ffff99" <?=$i_readonly;?> value="<?=$ls_nama_pemilik;?>" /></div>
            <div class="l_frm" ><label for="alamat_pemilik">Alamat Lengkap<span style="color:#ff0000;">&nbsp;*</span> :</label></div>   
            <div class="r_frm"><textarea id="alamat_pemilik" maxlength="300" name="alamat_pemilik" style="width:265px;background-color:#ffff99" rows="2" <?=$i_readonly;?>><?=$ls_alamat_pemilik;?></textarea></div>
            <div class="l_frm" ><label for="rt_pemilik">RT/ RW :</label></div>
            <div class="r_frm"><input type="text" id="rt_pemilik" maxlength="5" name="rt_pemilik" style="width:30px;background-color:<?=$i_ocolor;?>" <?=$i_readonly;?>  value="<?=$ls_rt_pemilik;?>" />/ <input type="text" id="rw_pemilik" maxlength="5" name="rw_pemilik" style="width:30px;background-color:<?=$i_ocolor;?>" <?=$i_readonly;?>  value="<?=$ls_rw_pemilik;?>" /></div>
            <div class="l_frm" ><label for="kode_pos_pemilik">Kode Pos<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
            <div class="r_frm"><input type="text" id="kode_pos_pemilik" name="kode_pos_pemilik" style="width:90px;background-color:#ffff99" readonly  value="<?=$ls_kode_pos_pemilik;?>"/>
                <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn2401_lov_pos.php?p=tc5001.php&a=formreg&b=kode_kelurahan_pemilik&c=nama_kelurahan_pemilik&d=kode_kecamatan_pemilik&e=nama_kecamatan_pemilik&f=kode_kabupaten_pemilik&g=nama_kabupaten_pemilik&h=kode_propinsi_pemilik&j=nama_propinsi_pemilik&k=kode_pos_pemilik','_faskeslovprop',800,500,1)" <?PHP if($_REQUEST["task"]!="New"){echo "style='pointer-events:none'";}?> />							
                <img src="../../images/help.png" alt="Cari Kabupaten" border="0" align="absmiddle"></a>	
            </div>
                <input type="hidden" id="kode_kelurahan_pemilik" name="kode_kelurahan_pemilik" value="<?=$ls_kode_kelurahan_pemilik;?>" />
                <input type="hidden" id="kode_kecamatan_pemilik" name="kode_kecamatan_pemilik" value="<?=$ls_kode_kecamatan_pemilik;?>" />
                <input type="hidden" id="kode_kabupaten_pemilik" name="kode_kabupaten_pemilik" value="<?=$ls_kode_kabupaten_pemilik;?>" />
                <input type="hidden" id="kode_propinsi_pemilik" name="kode_propinsi_pemilik" />
                <input type="hidden" id="nama_propinsi_pemilik" name="nama_propinsi_pemilik" />
            <div class="l_frm" ><label for="nama_kelurahan_pemilik">Kelurahan :</label></div>
            <div class="r_frm"><input type="text" id="nama_kelurahan_pemilik" name="nama_kelurahan_pemilik" style="width:220px;background-color:#e9e9e9;" readonly value="<?=$ls_nama_kelurahan_pemilik;?>"/></div>
            <div class="l_frm" ><label for="nama_kecamatan_pemilik">Kecamatan :</label></div>
            <div class="r_frm"><input type="text" id="nama_kecamatan_pemilik" name="nama_kecamatan_pemilik" style="width:220px;background-color:#e9e9e9;" readonly value="<?=$ls_nama_kecamatan_pemilik;?>"/></div>
            <div class="l_frm" ><label for="nama_kabupaten_pemilik">Kabupaten :</label></div>
            <div class="r_frm"><input type="text" id="nama_kabupaten_pemilik" name="nama_kabupaten_pemilik" style="width:220px;background-color:#e9e9e9;" readonly value="<?=$ls_nama_kabupaten_pemilik;?>"/></div>
        </td>
        <td valign="top">
            <div class="l_frm" ><label for="telp_area_pemilik">No. Telepon :</label></div>
            <div class="r_frm">
            <input type="text" id="telp_area_pemilik" name="telp_area_pemilik" style="width:40px;background-color:<?=$i_ocolor;?>" maxlength="5" <?=$i_readonly;?>  value="<?=$ls_telepon_area_pemilik;?>" onchange="fl_js_val_numeric('telp_area_pemilik')" /> 
            <input type="text" id="telp_pemilik" name="telp_pemilik" style="width:170px;background-color:<?=$i_ocolor;?>" maxlength="20" <?=$i_readonly;?> value="<?=$ls_telepon_pemilik;?>" onchange="fl_js_val_numeric('telp_pemilik')" />
            </div>
            <div class="l_frm" ><label for="telepon_ext_pemilik">Telepon Ext. :</label></div>
            <div class="r_frm"><input type="text" id="telepon_ext_pemilik" name="telepon_ext_pemilik" style="width:100px;background-color:<?=$i_ocolor;?>" value="<?=$ls_telepon_ext_pemilik;?>" <?=$i_ocolor;?> maxlength="5" onchange="fl_js_val_numeric('telepon_ext_pemilik')" /> </div>
            <div class="l_frm" ><label for="fax_area_pemilik">Fax :</label></div>
            <div class="r_frm"><input type="text" id="fax_area_pemilik" name="fax_area_pemilik" style="width:40px;background-color:<?=$i_ocolor;?>" value="<?=$ls_fax_area_pemilik;?>" maxlength="5" <?=$i_readonly;?> onchange="fl_js_val_numeric('fax_area_pemilik')"/> <input type="text" id="fax_pemilik" name="fax_pemilik" style="width:170px;background-color:<?=$i_ocolor;?>" value="<?=$ls_fax_pemilik;?>" maxlength="20"  <?=$i_readonly;?>/></div>
            <div class="l_frm" ><label for="email_pemilik">Alamat Email :</label></div>
            <div class="r_frm"><input type="text" id="email_pemilik" name="email_pemilik" style="width:265px;background-color:<?=$i_ocolor;?>" <?=$i_readonly;?>  value="<?=$ls_email_pemilik;?>" maxlength="200" onblur="fl_js_val_email('email_pemilik');" /></div>
            <div>&nbsp;</div>
            <div class="l_frm" ><label for="kode_kepemilikan">Tipe Pemilik :</label></div>
            <div class="r_frm">
                <!-- <select id="kode_kepemilikan" name="kode_kepemilikan"  style="background-color:<?=$i_ocolor;?>" <?=$i_readonly;?>><?=$ls_kode_kepemilikan;?>
                </select> -->
                <select size="1" id="kode_kepemilikan" name="kode_kepemilikan" value="<?=$ls_kode_kepemilikan;?>" style="background-color:<?=$i_ocolor;?>" <?PHP if($p_task=="View"){echo "disabled";}?> >
                    <option value="">-- pilih --</option>
                    <? 
                    $sql = "SELECT KODE_KEPEMILIKAN, NAMA_KEPEMILIKAN FROM TC.TC_KODE_KEPEMILIKAN WHERE NVL(STATUS_NONAKTIF,'T') = 'T' ORDER BY KODE_KEPEMILIKAN";
                    $DB->parse($sql);
                    $DB->execute();
                    while($row = $DB->nextrow())
                    {
                    echo "<option ";
                    if ($row["KODE_KEPEMILIKAN"]==$ls_kode_kepemilikan && strlen($ls_kode_kepemilikan)==strlen($row["KODE_KEPEMILIKAN"])){ echo " selected"; }
                    echo " value=\"".$row["KODE_KEPEMILIKAN"]."\">".$row["NAMA_KEPEMILIKAN"]."</option>";
                    }
                    ?>
                  </select>
            </div>
        </td>
    </tr>
</table>
</fieldset>
<br>
<fieldset><legend><b>Informasi Pembayaran</b></legend>
<table border='0' width="100%">
    <tr>
        <td width="50%" valign="top">
            <div class="l_frm" ><label for="paymethod">Metode Pembayaran :</label></div>
            <div class="r_frm">
                <!-- <select id="paymethod" name="paymethod"  style="background-color:<?=$i_ocolor;?>" <?=$i_readonly;?>><?=$kode_metode_pembayaran;?>
                </select> -->
                <select size="1" id="paymethod" name="paymethod" value="<?=$ls_kode_metode_byr;?>" style="background-color:<?=$i_ocolor;?>" <?PHP if($p_task=="View"){echo "disabled";}?> >
                    <option value="">-- pilih --</option>
                    <? 
                    $sql = "SELECT KODE_METODE_PEMBAYARAN, NAMA_METODE_PEMBAYARAN FROM TC.TC_KODE_METODE_PEMBAYARAN WHERE NVL(STATUS_NONAKTIF,'T') = 'T' ORDER BY KODE_METODE_PEMBAYARAN";
                    $DB->parse($sql);
                    $DB->execute();
                    while($row = $DB->nextrow())
                    {
                    echo "<option ";
                    if ($row["KODE_METODE_PEMBAYARAN"]==$ls_kode_metode_byr && strlen($ls_kode_metode_byr)==strlen($row["KODE_METODE_PEMBAYARAN"])){ echo " selected"; }
                    echo " value=\"".$row["KODE_METODE_PEMBAYARAN"]."\">".$row["NAMA_METODE_PEMBAYARAN"]."</option>";
                    }
                    ?>
                  </select>
            </div>
            <div class="l_frm" ><label for="bankcode">Bank Penerima :</label></div>
            <div class="r_frm">
                <!-- <select id="bankcode" name="bankcode" style="background-color:<?=$i_ocolor;?>" <?=$i_readonly;?>><?=$kode_bank_pembayaran;?>
                </select> -->
                <select size="1" id="bankcode" name="bankcode" value="<?=$$ls_kode_bank_byr;?>" style="background-color:<?=$i_ocolor;?>" <?PHP if($p_task=="View"){echo "disabled";}?> >
                    <option value="">-- pilih --</option>
                    <? 
                    $sql = "SELECT KODE_BANK, NAMA_BANK FROM SIJSTK.MS_BANK 
                                WHERE NVL(AKTIF,'Y') = 'Y'
                            ORDER BY KODE_BANK";
                    $DB->parse($sql);
                    $DB->execute();
                    while($row = $DB->nextrow())
                    {
                    echo "<option ";
                    if ($row["KODE_BANK"]==$ls_kode_bank_byr && strlen($ls_kode_bank_byr)==strlen($row["KODE_BANK"])){ echo " selected"; }
                    echo " value=\"".$row["KODE_BANK"]."\">".$row["NAMA_BANK"]."</option>";
                    }
                    ?>
                  </select>
            </div>                        
        </td>
        <td width="50%" valign="top">
            <div class="l_frm" ><label for="norek">No. Rekening :</label></div>
            <div class="r_frm"><input type="text" id="norek" name="norek" maxlength="100" style="width:160px;background-color:<?=$i_ocolor;?>" <?=$i_readonly;?>  value="<?=$ls_no_rekening_pembayaran;?>" onchange="fl_js_val_numeric('norek')" /></div>                        
            <div class="l_frm" ><label for="namarek">Nama Rekening :</label></div>
            <div class="r_frm"><input type="text" id="namarek" name="namarek" maxlength="100" style="width:265px;background-color:<?=$i_ocolor;?>" <?=$i_readonly;?> value="<?=$ls_nama_rekening_pembayaran;?>"  /></div>                        
        </td>
    </tr>
</table>
</fieldset>
<br>
<fieldset><legend><b>PIC Kantor Cabang</b></legend>
    <table border='0' width="100%">
        <tr>
            <td width="50%" valign="top">
                <!-- <div class="f_1" ><label for="kacab">Kantor Cabang<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
                <div class="f_2">
                    <?if($p_task=='New' || $p_task=='Edit') {?>
                    <input type="text" id="kode_nama_kantor" name="kode_nama_kantor" style="width:200px;background-color:#ffff99" readonly  value="<?=$kode_nama_kantor;?>"/>
                    <input type="hidden" id="kode_kacab" name="kode_kacab" maxlength="100"  value="<?=$ls_kd_kantor;?>" style="width:30px;background-color:#ffff99" readonly />
                    <?}else{?>
                    <input type="text" id="kode_nama_kantor" name="kode_nama_kantor" style="width:200px;background-color:#e9e9e9" readonly  value="<?=$kode_nama_kantor;?>"/>
                    <input type="hidden" id="kode_kacab" name="kode_kacab" maxlength="100"  value="<?=$ls_kd_kantor;?>" style="width:30px;background-color:#e9e9e9" readonly />
                    <?}?>
	                <?if($p_task!='View') {?>
                    <a href="#" id="btn_kantor" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn2401_lov_kantor.php?p=pn2401.php&a=formreg&b=kode_kacab&c=kode_nama_kantor','',800,500,1)"> 
                    <img src="../../images/help.png" alt="Cari Kabupaten" border="0" align="absmiddle"></a>
                    <?}?>
                </div> -->
                <?if($p_task=='New' || $p_task=='Edit') {?>
                    <div class="f_1" ><label for="nm_pic_kacab">Nama PIC Kantor Cabang<span style="color:#ff0000;">&nbsp;*</span>:</label></div>
                    <div class="f_2">
                        <input type="text" id="nm_pic_kacab" name="nm_pic_kacab" style="width: 265px; background-color:#ffff99" value="<?=$kode_user_nama;?>" size="20" readonly />
                        <input type="hidden" name="kode_pic_cabang" id="kode_pic_cabang" style="background-color:#ffff99;" tabindex="1"  value="<?=$kode_pic_cabang;?>" readonly/>
                        <input type="hidden" name="kode_kacab" id="kode_kacab" style="background-color:#ffff99;" tabindex="1"  value="<?=$ls_kode_kantor;?>" readonly/>
                        <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn2401_lov_user.php?p=pn2401.php&a=formreg&b=kode_kacab&c=nm_pic_kacab&d=kode_pic_cabang&e='+$('#kode_kantor').val()+'','',800,500,1)">
                        <img src="../../images/help.png" alt="Cari Nama Pic" border="0" align="absmiddle"></a>
                    </div>
                    <br>
                    <div class="f_1" ><label for="handphone_pic_kacab">Handphone PIC Cabang<span style="color:#ff0000;">&nbsp;*</span>:</label></div>
                    <div class="f_2"><input type="text" id="handphone_pic_kacab" name="handphone_pic_kacab" maxlength="20" style="width:140px;background-color:#ffff99" value="<?=$ls_handphone_pic_kacab;?>" <?=$i_readonly;?>/></div>
                <?}else{?>
                    <div class="f_1" ><label for="nm_pic_kacab">Nama PIC Kantor Cabang<span style="color:#e9e9e9;">&nbsp;*</span>:</label></div>
                    <div class="f_2">
                        <input type="text" id="nm_pic_kacab" name="nm_pic_kacab" style="width: 265px; background-color:#e9e9e9" value="<?=$kode_user_nama;?>" size="20" readonly />
                        <input type="hidden" name="kode_pic_cabang" id="kode_pic_cabang" style="background-color:#e9e9e9;" tabindex="1"  value="<?=$kode_pic_cabang;?>" readonly/>
                        <input type="hidden" name="kode_kacab" id="kode_kacab" style="background-color:#e9e9e9;" tabindex="1"  value="<?=$ls_kode_kantor;?>" readonly/>
                        <a href="#" style="display: none;" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn2401_lov_user.php?p=pn2401.php&a=formreg&b=kode_kacab&c=nm_pic_kacab&d=kode_pic_cabang&e='+$('#kode_kantor').val()+'','',800,500,1)">
                        <img src="../../images/help.png" alt="Cari Nama Pic" border="0" align="absmiddle"></a>
                    </div>
                    <br>
                    <div class="f_1" ><label for="handphone_pic_kacab">Handphone PIC Cabang<span style="color:#e9e9e9;">&nbsp;*</span>:</label></div>
                    <div class="f_2"><input type="text" id="handphone_pic_kacab" name="handphone_pic_kacab" maxlength="20" style="width:140px;background-color:#e9e9e9" value="<?=$ls_handphone_pic_kacab;?>" <?=$i_readonly;?>/></div>
                <?}?>
            </td>
        </tr>
    </table>
</fieldset>
<br>
<fieldset><legend><b>Petugas</b></legend>
<table border='0' width="100%">
    <tr>
        <td width="50%" valign="top">
            <div class="l_frm" ><label for="createdby">Petugas Submit :</label></div>
            <div class="r_frm"><input type="text" id="createdby" name="createdby" style="width:140px;background-color:#e9e9e9;" readonly value="<?=$ls_petugas_submit;?>" /></div>
            <div class="l_frm" ><label for="modifiedby">Petugas Ubah :</label></div>
            <div class="r_frm"><input type="text" id="modifiedby" name="modifiedby" style="width:140px;background-color:#e9e9e9;" readonly value="<?=$ls_petugas_ubah;?>" /></div>
            <div class="l_frm" ><label for="rekamby">Petugas Rekam :</label></div>
            <div class="r_frm"><input type="text" id="rekamby" name="rekamby" style="width:140px;background-color:#e9e9e9;" readonly value="<?=$ls_petugas_rekam;?>" /></div>
        </td>
        <td width="50%" valign="top">
            <div class="l_frm" ><label for="createddate">Tanggal Submit :</label></div>
            <div class="r_frm"><input type="text" id="createddate" name="createddate" style="width:140px;background-color:#e9e9e9;" readonly value="<?=$ls_tgl_submit;?>" /></div>
            <div class="l_frm" ><label for="modifiedate">Tanggal Ubah :</label></div>
            <div class="r_frm"><input type="text" id="modifiedate" name="modifiedate" style="width:140px;background-color:#e9e9e9;" readonly value="<?=$ls_tgl_ubah;?>" /></div>
            <div class="l_frm" ><label for="rekamdate">Tanggal Rekam :</label></div>
            <div class="r_frm"><input type="text" id="rekamdate" name="rekamdate" style="width:140px;background-color:#e9e9e9;" readonly value="<?=$ls_tgl_rekam;?>" /></div>
        </td>
    </tr>
</table>
</fieldset>
<br>
<fieldset style="background: #F2F2F2;"><legend style="background: #FF0; border: 1px solid #CCC;">Keterangan:</legend>
    <ol>
        <span><b>langkah-langkah pencarian longitude dan latitude:</b></span>
        <li>Silahkan memastikan komputer Anda terhubung dengan internet.</li>
        <li>Silahkan mengakses google maps atau w3schools untuk mendapatkan titik lokasi Faskes.</li>
        <li>Silahkan menyalin atau memasukkan Latitude dan Longitude pada inputan.</li>
    </ol>
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
<script type="text/javascript">
$(document).ready(function(){ 
    lov_subData('getJenis','getJenis','<?=$ls_kode_tipe;?>','<?=$ls_kode_jenis;?>'); 
    lov_subData('getJenisDetil','getJenisDetil','<?=$ls_kode_jenis;?>','<?=$ls_kode_jenis_detil;?>'); 
    showKelasPerawatan("<?=$ls_kode_jenis;?>");

    $("#kode_tipe").change(function(){
        //alert("ojojijio");
        lov_subData('getJenis','getJenis',this.value,''); 
        setJenisDetil('');
    });
    $("#kode_jenis").change(function(){
        lov_subData('getJenisDetil','getJenisDetil',this.value,''); 
        showKelasPerawatan(this.value);
    });
});

var gw_openwin;
function gw_newWindow(mypage, myname, w, h, scroll) { // confirm(gw_openwin_param1);
    var par ='';
    window.gw_openwin = window.parent.Ext.create('Ext.window.Window', {
        title: myname,
        collapsible: true,
        animCollapse: true,
        maximizable: true,
        closable: true,
        width: w,
        height: h,
        minWidth: w,
        minHeight: h,
        layout: 'fit',
        modal: true,
        html: '<iframe src="' + mypage +'"  height="100%" width="100%" frameborder="0" style="border:0; height:100%; width:100%;scrollbars=no;"></iframe>',
        listeners: {
            close: function () {
                //location.reload();
            },
            destroy: function (wnd, eOpts) {

            }
        }
    });
    window.gw_openwin.show();
}

function lov_subData(par_callFunc,par_ajaxFunc,par_ajaxKey1,par_ajaxKey2,par_ajaxKey3)
{
    preload(true);
    var ajax_Key1 = (par_ajaxKey1 == undefined) ? "":par_ajaxKey1;
    var ajax_Key2 = (par_ajaxKey2 == undefined) ? "":par_ajaxKey2; 
    var ajax_Key3 = (par_ajaxKey3 == undefined) ? "":par_ajaxKey3; 
    $.ajax({
        type: 'GET',
        url: "../ajax/pn2401_lov.php?"+Math.random(),
        data: {f:par_ajaxFunc,key1:ajax_Key1,key2:ajax_Key2,key3:ajax_Key3},
        success: function(ajaxdata){ console.log(ajaxdata);
            preload(false);
            if(par_callFunc=='getStatus') setViewSearch(ajaxdata);
            if(par_callFunc=='getJenis') setJenis(ajaxdata);
            if(par_callFunc=='getJenisDetil') setJenisDetil(ajaxdata);
        },
        error:function(){
            preload(false);
        },
    });
}

 function fl_ls_get_lov_by_selected(lov, obj) {
        if (lov == "KODE_FASKES") {
            if (obj.KODE_FASKES != '') {
                var new_location = window.location.href.replace("#", "") + '&kdf=' + obj.KODE_FASKES;
                window.location.replace(new_location);
                // cek_tk_aktif();

            } else {
                alert("Kode Faskes tidak ditemukan, pilih No Faskes yang akan diubah !");
            }
        } 
    }

    function fl_js_get_lov_by(lov) { 
        if (lov == "KODE_FASKES") {
            var params = "p=pn6001_faskes.php&a=formreg";
            NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_faskes_lov_faskes.php?'+params,'',800,500,1);
        } 
    }

    function open_faskes_hist(lov) { 
        //if (lov == "kode_agenda") {
            var params = "kode_agenda=<?=$ls_kode_agenda?>";
            gw_newWindow('http://<?=$HTTP_HOST;?>/mod_pn/form/pn6001_faskes_hist.php?'+params,'',850,520,1);
        //} 
    }

window.reloadFaskes=function(p_data){
    var new_location = window.location.href.replace("#", "") + '&kdf=' +p_data;
    window.location.replace(new_location);
}

function setJenis(par_data){
    $("#kode_jenis").html(par_data);
}

function setJenisDetil(par_data){
    $("#kode_jenis_detil").html(par_data);
}


function showKelasPerawatan(p_val)
{
    if(p_val=="J02"){
        $("#div_kelas_perawatan").show();
    } else {
        $("#div_kelas_perawatan").hide();
    }
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

function fl_js_val_email(v_field_id)
    {
      var x = window.document.getElementById(v_field_id).value;
      var atpos=x.indexOf("@");
      var dotpos=x.lastIndexOf(".");
      if ((x!='') && (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length))
      {
         document.getElementById(v_field_id).value = '';         
         window.document.getElementById(v_field_id).focus();
         alert("Format Email tidak valid, belum ada (@ DAN .)");         
         return false;  
      }
    } 

 function isValid() {
        var kd_faskes       = $('#kode_faskes').val();
        var nama_faskes     = $('#nama_faskes').val();
        var kd_tipe         = $('#kode_tipe').val();
        var alamat          = $('#alamat').val();
        var kd_pos          = $('#kode_pos').val();
        var nama_pic        = $('#nama_pic').val();
        var bgn_pic         = $('#bagian_pic').val();
        var hp_pic          = $('#handphone_pic').val();
        var no_ijin         = $('#no_ijin_praktek').val();
        var npwp            = $('#npwp').val();
        var kd_jns          = $('#kode_jenis').val();
        var nama_pemilik    = $('#nama_pemilik').val();
        var alamat_pemilik  = $('#alamat_pemilik').val();
        var kd_pos_pemilik  = $('#kode_pos_pemilik').val();
        var latitude  = $('#latitude').val();
        var longitude  = $('#longitude').val();
        var kode_nama_kantor  = $('#kode_nama_kantor').val();
        var nm_pic_kacab  = $('#nm_pic_kacab').val();
        var handphone_pic_kacab  = $('#handphone_pic_kacab').val();

        if (kd_faskes== "" || kd_faskes== null)  {
            return { val : false, msg : "Kode Faskes masih kosong, harap lengkapi data input !" };
        } else if (nama_faskes == "" || nama_faskes == null) {
            return { val : false, msg : "Nama Faskes masih kosong, harap lengkapi data input !" };
        } else if (kd_tipe == "" || kd_tipe== null) {
            return { val : false, msg : "Tipe Faskes masih kosong, harap lengkapi data input !" };
        } else if (alamat == "" || alamat== null) {
            return { val : false, msg : "Alamat Faskes masih kosong, harap lengkapi data input !" };
        } else if (kd_pos == "" || kd_pos == null) {
            return { val : false, msg : "Kode Pos Faskes masih kosong, harap lengkapi data input !" };
        } else if (nama_pic == "" || nama_pic == null) {
            return { val : false, msg : "Nama PIC masih kosong, harap lengkapi data input !" };
        } else if (bgn_pic == "" || bgn_pic == null) {
            return { val : false, msg : "Bagian PIC masih kosong, harap lengkapi data input !" };
        } else if (hp_pic == "" || hp_pic == null) {
            return { val : false, msg : "No Handphone PIC masih kosong, harap lengkapi data input !" };
        } else if (no_ijin == "" || no_ijin== null) {
            return { val : false, msg : "No Ijin Praktek masih kosong, harap lengkapi data input !" };
        } else if (npwp == "" || npwp== null) {
            return { val : false, msg : "NPWP Faskes masih kosong, harap lengkapi data input !" };
        } else if (kd_jns == "" || kd_jns == null) {
            return { val : false, msg : "Kode Jenis Faskes masih kosong, harap lengkapi data input !" };
        } else if (nama_pemilik == "" || nama_pemilik == null) {
            return { val : false, msg : "Nama Pemilik masih kosong, harap lengkapi data input !" };
        } else if (alamat_pemilik == "" || alamat_pemilik == null) {
            return { val : false, msg : "Alamat Pemilik masih kosong, harap lengkapi data input !" };
        } else if (kd_pos_pemilik == "" || kd_pos_pemilik == null) {
            return { val : false, msg : "Kode Pos Pemilik masih kosong, harap lengkapi data input !" };
        } else if (latitude == "" || latitude == null) {
            return { val : false, msg : "Latitude masih kosong, harap lengkapi data input !" };
        } else if (longitude == "" || longitude == null) {
            return { val : false, msg : "Longitude masih kosong, harap lengkapi data input !" };
        } else if (nm_pic_kacab == "" || nm_pic_kacab == null) {
            return { val : false, msg : "Nama PIC Kantor Cabang masih kosong, harap lengkapi data input !" };
        } else if (handphone_pic_kacab == "" || handphone_pic_kacab == null) {
            return { val : false, msg : "No Handphone PIC cabang masih kosong, harap lengkapi data input !" };
        }
        return { val : true, msg : "Valid"} 

    }
</script>   