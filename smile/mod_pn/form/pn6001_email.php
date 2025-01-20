<?php
$php_file_name='pn6001_email';

$p_task = $_REQUEST["task"];
$p_kode_agenda = $_REQUEST["kd_agenda"];


if ($p_kode_agenda != "") {
        $sql = "
        SELECT  A.KODE_AGENDA,
                A.KODE_JENIS_AGENDA,
                A.KODE_JENIS_AGENDA_DETIL,
                B.KODE_FASKES
        FROM    PN.PN_AGENDA_KOREKSI A, TC.TC_REG_FASKES_HIST B
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

        $sql_agn = "SELECT MAX(KODE_AGENDA) AS KODE_AGENDA_LAST FROM TC.TC_REG_FASKES_HIST A
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

    if ($p_kode_faskes != ""){     
    $sql = "
          SELECT    A.KODE_FASKES,
                    A.KODE_TIPE,
                    A.KODE_KANTOR,
                    (SELECT NAMA_KANTOR FROM SIJSTK.MS_KANTOR
                        WHERE KODE_KANTOR = A.KODE_KANTOR
                    ) NAMA_KANTOR,
                    A.KODE_PEMBINA,
                    A.NAMA_FASKES,
                    A.ALAMAT,
                    A.RT,
                    A.RW,
                    A.KODE_KELURAHAN,
                    (SELECT NAMA_KELURAHAN FROM SIJSTK.MS_KELURAHAN
                        WHERE KODE_KELURAHAN = A.KODE_KELURAHAN
                    )NAMA_KELURAHAN,
                    A.KODE_KECAMATAN,
                    (SELECT NAMA_KECAMATAN FROM SIJSTK.MS_KECAMATAN
                        WHERE KODE_KECAMATAN = A.KODE_KECAMATAN
                    )NAMA_KECAMATAN,
                    A.KODE_KABUPATEN,
                    (SELECT NAMA_KABUPATEN FROM SIJSTK.MS_KABUPATEN
                        WHERE KODE_KABUPATEN = A.KODE_KABUPATEN
                    )NAMA_KABUPATEN,
                    A.KODE_POS,
                    A.NAMA_PIC,
                    A.HANDPHONE_PIC,
                    A.TGL_NONAKTIF,
                    A.TGL_AKTIF,
                    A.KODE_STATUS,
                    (SELECT NAMA_STATUS FROM TC.TC_KODE_STATUS
                        WHERE KODE_STATUS = A.KODE_STATUS
                    ) NAMA_STATUS,
                    A.KODE_JENIS,
                    A.KODE_JENIS_DETIL,
                    A.NO_IJIN_PRAKTEK,
                    A.NPWP,
                    A.MAX_TERTANGGUNG,
                    A.FLAG_UMUM,
                    A.FLAG_GIGI,
                    A.FLAG_SALIN,
                    A.FLAG_REG_WEBSITE,
                    A.KETERANGAN,
                    A.NAMA_PEMILIK,
                    A.ALAMAT_PEMILIK,
                    A.RT_PEMILIK,
                    A.RW_PEMILIK,
                    A.KODE_KELURAHAN_PEMILIK,
                    (SELECT NAMA_KELURAHAN FROM SIJSTK.MS_KELURAHAN
                        WHERE KODE_KELURAHAN = A.KODE_KELURAHAN_PEMILIK
                    )NAMA_KELURAHAN_PEMILIK,
                    A.KODE_KECAMATAN_PEMILIK,
                    (SELECT NAMA_KECAMATAN FROM SIJSTK.MS_KECAMATAN
                        WHERE KODE_KECAMATAN = A.KODE_KECAMATAN_PEMILIK
                    )NAMA_KECAMATAN_PEMILIK,
                    A.KODE_KABUPATEN_PEMILIK,
                    (SELECT NAMA_KABUPATEN FROM SIJSTK.MS_KABUPATEN
                        WHERE KODE_KABUPATEN = A.KODE_KABUPATEN_PEMILIK
                    )NAMA_KABUPATEN_PEMILIK,
                    A.KODE_POS_PEMILIK,
                    A.TELEPON_AREA_PEMILIK,
                    A.TELEPON_PEMILIK,
                    A.TELEPON_EXT_PEMILIK,
                    A.FAX_AREA_PEMILIK,
                    A.FAX_PEMILIK,
                    A.EMAIL_PEMILIK,
                    A.KODE_KEPEMILIKAN,
                    A.KODE_METODE_PEMBAYARAN,
                    A.KODE_BANK_PEMBAYARAN,
                    A.NAMA_REKENING_PEMBAYARAN,
                    A.NO_REKENING_PEMBAYARAN,
                    A.TELEPON_AREA_PIC,
                    A.TELEPON_PIC,
                    A.TELEPON_EXT_PIC,
                    A.NO_FASKES,
                    A.KELAS_PERAWATAN,
                    A.BAGIAN_PIC,
                    A.TGL_SUBMIT,
                    A.PETUGAS_SUBMIT,
                    A.TGL_REKAM,
                    A.PETUGAS_REKAM,
                    A.TGL_UBAH
            FROM TC.TC_FASKES A
            WHERE  A.KODE_FASKES = '$p_kode_faskes'";
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
            $ls_nama_status         = $row["NAMA_STATUS"];
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
           
        } 

    if (($p_kode_faskes != "" && $p_task == "New") ||($ls_kode_agenda_last == $p_kode_agenda && $p_kode_agenda != "")){     
            $sql = "
                  SELECT    EMAIL_FASKES,
                            HANDPHONE,
                            STATUS_AKTIF
                    FROM TC.TC_REG_FASKES 
                    WHERE  KODE_FASKES = '$p_kode_faskes'";


        } 
        else if ($p_task == "View" && $ls_kode_agenda_last != $p_kode_agenda) { 
            $sql_agn = "SELECT KODE_AGENDA FROM(
                            SELECT ROWNUM,A.* FROM TC.TC_REG_FASKES_HIST A
                            WHERE KODE_FASKES = '$ls_kode_faskes'
                            AND KODE_AGENDA > '$p_kode_agenda'
                            ORDER BY KODE_AGENDA ASC
                        )WHERE ROWNUM = 1 ";

            $DB->parse($sql_agn);
            $DB->execute();
            $row = $DB->nextrow();
            $ls_kode_agenda_vw         = $row["KODE_AGENDA"];


            $sql = "
              SELECT    EMAIL_FASKES,
                        HANDPHONE,
                        STATUS_AKTIF
                FROM TC.TC_REG_FASKES_HIST A
                WHERE KODE_AGENDA = '$ls_kode_agenda_vw'";
         }

            //echo($sql);
         $DB->parse($sql);
         $DB->execute();
         $row = $DB->nextrow();
         $ls_nama_email             = $row["EMAIL_FASKES"];
         $ls_no_hp                  = $row["HANDPHONE"];
         $ls_aktif                  = $row["STATUS_AKTIF"]; 
            
             
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
     <td width="55%" valign="top">
    <div class="l_frm" ><label for="kode_faskes">Kode Faskes/BLK<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
    <div class="r_frm">
        <input type="text" id="kode_faskes" name="kode_faskes" maxlength="100"  value="<?=$ls_kode_faskes;?>" style="width:70px;background-color:#ffff99; text-align:center;" readonly />
        <!-- <input type="text" id="no_faskes" name="no_faskes" maxlength="100"  value="<?=$ls_no_faskes;?>" style="width:100px;background-color:#e9e9e9; text-align:center;" readonly /> -->
        <input type="text" id="nama_faskes" name="nama_faskes" maxlength="100"  value="<?=$ls_nama_faskes;?>" style="width:200px;background-color:#e9e9e9" <?=$i_readonly;?> />
        <a href="#" onclick="fl_js_get_lov_by('KODE_FASKES')" tabindex="8" <?PHP if($_REQUEST["task"]!="New"){echo "style='pointer-events:none'";}?>/>                            
        <img src="../../images/help.png" alt="Cari Kabupaten" border="0" align="absmiddle"></a>
        <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="text" id="nama_faskes" name="nama_faskes" maxlength="100"  value="<?=$ls_nama_faskes;?>" style="width:200px;background-color:#e9e9e9" <?=$i_readonly;?> /> -->    
    </div>
    </td>
    <td width="50%" valign="top">
        <div class="l_frm" ><label for="status_faskes">Status Faskes</label>
        </div>
        <div class="r_frm">
            <input type="text" id="kode_status" name="kode_status" maxlength="100"  value="<?=$ls_kode_status;?>" style="width:30px;background-color:#e9e9e9; text-align: center;" readonly />
            <input type="text" id="nama_status" name="nama_status" maxlength="100"  value="<?=$ls_nama_status;?>" style="width:150px;background-color:#e9e9e9" readonly />
        </div>
    </td>
    <td width="20%" valign="top">
    <div class="l_frm" ><label for="histori_faskes" style="color:#009999;"></label></div>
     <div class="r_frm">
        <a href="#" onclick="open_faskes_hist()" tabindex="8" <?PHP if($_REQUEST["task"]!="View"){echo "hidden";}?>/>                            
            <!-- <img src="../../images/application_view_columns.png" alt="Lihat Histori" border="0" align="absmiddle"> -->
            <font  color="#009999"><i><b>Data Sebelumnya</i></font>
        </a>
        <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="text" id="nama_faskes" name="nama_faskes" maxlength="100"  value="<?=$ls_nama_faskes;?>" style="width:200px;background-color:#e9e9e9" <?=$i_readonly;?> /> -->    
    </div>
    </td>
   </tr>
</table>
</fieldset>
<br>
<fieldset><legend><b>Informasi Account Email</b></legend>
<br>
<table border='0' width="100%">
    <tr>
        <td width="35%" valign="top">
            <div class="l_frm" ><label for="email_faskes"><b>Email :</b><span style="color:#ff0000;">&nbsp;*</span> :</label></div>
            <div class="r_frm">
                <input type="text" id="nama_email" maxlength="100" name="nama_email" style="width:200px;background-color:#ffff99" <?=$i_readonly;?> value="<?=$ls_nama_email;?>" onblur="fl_js_val_email('nama_email');" />
            </div>
        </td>
        <td width="35%" valign="top">
            <div class="l_frm" ><label for="handphone_faskes"><b>No. Handphone :</b></label></div>
            <div class="r_frm">
                <input type="text" id="no_hp" name="no_hp" style="width:150px;background-color:<?=$i_ocolor;?>" maxlength="13" <?=$i_readonly;?>  value="<?=$ls_no_hp;?>" onchange="fl_js_val_numeric('no_hp')" /> 
            
            </div>
        </td>
        <td width="35%" valign="top">
            <div class="l_frm" ><label for="aktif_faskes"><b>Aktif :</b></label></div>
            <div class="r_frm">
                <? $ls_aktif= isset($ls_aktif) ? $ls_aktif : "T"; ?>          
                  <input type="checkbox" id="cb_aktif" name="cb_aktif" class="cebox" <?=$ls_aktif=="Y" ||$ls_aktif=="ON" ||$ls_aktif=="on" ? "checked" : "";?>> 
            
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
            <div class="l_frm" ><label for="nama_faskes">Nama Faskes/BLK :</label></div>
            <div class="r_frm">
                <input type="text" id="nama_faskes" name="nama_faskes" maxlength="100"  value="<?=$ls_nama_faskes;?>" style="width:265px;background-color:#e9e9e9" readonly />
            </div>
            <div class="l_frm" ><label for="no_faskes">No. Faskes/BLK :</label></div>
            <!-- <div class="r_frm"><?=$ls_no_faskes;?></div> -->
            <div class="r_frm">
                <input type="text" id="no_faskes" name="no_faskes" maxlength="100"  value="<?=$ls_no_faskes;?>" style="width:200px;background-color:#e9e9e9" readonly />
            </div>
            <div class="l_frm" ><label for="kode_tipe">Tipe Faskes/BLK :</label></div>
            <div class="r_frm">
                <select size="1" id="kode_tipe" name="kode_tipe" value="<?=$ls_kode_tipe;?>" style="background-color:#e9e9e9" readonly>
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
            <div class="l_frm" ><label for="kode_kantor">Kantor :</label></div>
            <!-- <div class="r_frm"><?=$ls_kode_kantor;?></div> -->
            <div class="r_frm">
                <input type="text" id="kode_kantor" name="kode_kantor" maxlength="100"  value="<?=$ls_kd_kantor;?>" style="width:30px;background-color:#e9e9e9" readonly /> - 
                <input type="text" id="nama_kantor" name="nama_kantor" maxlength="100"  value="<?=$ls_nama_kantor;?>" style="width:170px;background-color:#e9e9e9" readonly />
            </div>
            <div class="l_frm" ><label for="alamat">Alamat Lengkap :</label></div>
            <div class="r_frm"><textarea id="alamat" name="alamat" maxlength="300" style="width:265px;background-color:#e9e9e9" rows="2" readonly><?=$ls_alamat;?></textarea></div>
            <div class="l_frm" ><label for="rt">RT/ RW :</label></div>
            <div class="r_frm">
                <input type="text" id="rt" name="rt" maxlength="5" style="width:30px;background-color:<?=$i_ocolor;?>" value="<?=$ls_rt;?>" readonly />/ 
                <input type="text" id="rw" name="rw" maxlength="5" style="width:30px;background-color:<?=$i_ocolor;?>"  value="<?=$ls_rw;?>"  readonly />
            </div>
            <div class="l_frm" ><label for="kode_pos">Kode Pos :</label></div>
            <div class="r_frm"><input type="text" id="kode_pos" name="kode_pos" style="width:90px;background-color: #e9e9e9" readonly   value="<?=$ls_kode_pos;?>"/>
                <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn2401_lov_pos.php?p=tc5001.php&a=formreg&b=kode_kelurahan&c=nama_kelurahan&d=kode_kecamatan&e=nama_kecamatan&f=kode_kabupaten&g=nama_kabupaten&h=kode_propinsi&j=nama_propinsi&k=kode_pos','_faskeslovprop',800,500,1)" tabindex="8" style="pointer-events:none"/>							
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
        </td>
        <td valign="top">
            <div class="l_frm" ><label for="telp_area_pic">No. Telepon :</label></div>
            <div class="r_frm">
                <input type="text" id="telp_area_pic" name="telp_area_pic" style="width:40px;background-color: <?=$i_ocolor;?>" maxlength="5" onchange="fl_js_val_numeric('telp_area_pic')"  value="<?=$ls_telepon_area_pic;?>"  readonly/> 
                <input type="text" id="telp_pic" name="telp_pic" style="width:170px;background-color: <?=$i_ocolor;?>" maxlength="20" onchange="fl_js_val_numeric('telp_pic')" value="<?=$ls_telepon_pic;?>"  readonly/>
            </div>
            <div class="l_frm" ><label for="telp_ext_pic">Telepon Ext. :</label></div>
            <div class="r_frm"><input type="text" id="telp_ext_pic" name="telp_ext_pic" style="width:100px;background-color: <?=$i_ocolor;?>" onchange="fl_js_val_numeric('telp_ext_pic')" value="<?=$ls_telepon_ext_pic;?>" maxlength="5"  readonly/> </div>
            <div class="l_frm" ><label for="nama_pic">Nama PIC :</label></div>
            <div class="r_frm"><input type="text" id="nama_pic" name="nama_pic" maxlength="100" style="width:265px;background-color:#e9e9e9" value="<?=$ls_nama_pic;?>" readonly/></div>
            
            <div class="l_frm" ><label for="bagian_pic">Bagian PIC :</label></div>
            <div class="r_frm"><input type="text" id="bagian_pic" name="bagian_pic" style="width:265px;background-color:#e9e9e9" maxlength="20" readonly value="<?=$ls_bagian_pic;?>" /></div>
            <div class="l_frm" ><label for="handphone_pic">Handphone PIC :</label></div>
            <div class="r_frm"><input type="text" id="handphone_pic" name="handphone_pic" maxlength="20" style="width:140px;background-color:#e9e9e9" onchange="fl_js_val_numeric('handphone_pic')" value="<?=$ls_handphone_pic;?>" readonly/></div>
            <div class="l_frm" ><label for="no_ijin_praktek">No. Ijin Praktek :</label></div>
            <div class="r_frm"><input type="text" id="no_ijin_praktek" name="no_ijin_praktek" maxlength="100" style="width:265px;background-color:#e9e9e9" value="<?=$ls_no_ijin_praktek;?>" readonly/></div>
            <div class="l_frm" ><label for="npwp">NPWP Faskes/BLK :</label></div>
            <div class="r_frm"><input type="text" id="npwp" name="npwp" maxlength="15" style="width:140px;background-color:#e9e9e9" readonly  value="<?=$ls_npwp;?>"/></div>
            <div class="l_frm" ><label for="kode_jenis">Jenis Faskes/BLK :</label></div>
            <div class="r_frm">
                <!-- <select id="kode_jenis" name="kode_jenis" tabindex="15" style="background-color:<?=$i_mcolor;?>" <?=$i_readonly;?> ><?=$kode_jenis;?>
                </select> -->
                <select size="1" id="kode_jenis" name="kode_jenis" value="<?=$ls_kode_jenis;?>" style="background-color:#e9e9e9" <?PHP if($p_task=="View"){echo "";}?> >
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
            <div class="l_frm"><label for="kode_jenis_detil">Sub Jenis Faskes/BLK :</label></div>
            <div class="r_frm">
                <!-- <select id="kode_jenis_detil" name="kode_jenis_detil" <?=$i_readonly;?> style="background-color:<?=$i_ocolor;?>">
                </select> -->
                <select size="1" id="kode_jenis_detil" name="kode_jenis_detil" value="<?=$ls_kode_jenis_detil;?>" <?PHP if($p_task=="View"){echo "";}?> >
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
            <div class="l_frm"><label for="kelas_perawatan">Kelas Perawatan :</label></div>
            <div class="r_frm">
            <select id="kelas_perawatan" name="kelas_perawatan" tabindex="15" style="background-color:<?=$i_ocolor;?>" <?=$i_readonly;?> >
                <option value="KELAS 1" <?php echo $ls_kelas_perawatan=='KELAS 1'?'selected':'';?>>KELAS 1</option>
                <option value="KELAS 2" <?php echo $ls_kelas_perawatan=='KELAS 2'?'selected':'';?>>KELAS 2</option>
                <option value="KELAS 3" <?php echo $ls_kelas_perawatan=='KELAS 3'?'selected':'';?>>KELAS 3</option>
            </select>
            </div>
            </div>
            <div class="l_frm" ><label for="keterangan">Keterangan :</label></div>
            <div class="r_frm"><textarea id="keterangan" name="keterangan" rows="2" maxlength="300" style="width:265px;background-color:<?=$i_ocolor;?>" readonly ><?=$ls_keterangan;?></textarea></div>
        </td>
    </tr>
</table>
</fieldset>
<br>
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
            var params = "p=pn6001_email.php&a=formreg";
            NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_email_lov_faskes.php?'+params,'',800,500,1);
        } 
    }

    function open_faskes_hist(lov) { 
        //if (lov == "kode_agenda") {
            var params = "kode_agenda=<?=$ls_kode_agenda?>";
            gw_newWindow('http://<?=$HTTP_HOST;?>/mod_pn/form/pn6001_email_hist.php?'+params,'',860,380,1);
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
        var nama_email     = $('#nama_email').val();

        if (kd_faskes== "" || kd_faskes== null)  {
            return { val : false, msg : "Kode Faskes masih kosong, harap lengkapi data input !" };
        } else if (nama_email == "" || nama_email == null) {
            return { val : false, msg : "Email Faskes masih kosong, harap lengkapi data input !" };
        } 
        return { val : true, msg : "Valid"} 

    }
</script>   