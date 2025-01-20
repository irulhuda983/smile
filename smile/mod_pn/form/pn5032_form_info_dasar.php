<?php 
/*****LOAD DATA INFORMASI DASAR*************************************/
if($task=="View" || $task=="Edit")
{       
    $sql = "select A.KODE_RTW_KLAIM,A.KODE_KLAIM, A.NAMA_TK,A.NOMOR_IDENTITAS,B.NAMA_PERUSAHAAN,
    B.NPP,C.NAMA_JENIS_KASUS,D.NAMA_LOKASI_KECELAKAAN,F.NAMA_KANTOR,
    H.TELEPON_AREA_KANTOR,H.TELEPON_KANTOR,H.TELEPON_EXT_KANTOR,H.HANDPHONE,
    H.ALAMAT_DOMISILI,TGL_KEJADIAN,TGL_LAPOR1,TGL_LAPOR2,KODE_PPK,
     (select keterangan from sijstk.ms_lookup where tipe='KLMJAMKERJ' and kode=KODE_JAM_KECELAKAAN) JAM_KECELAKAAN
from
(
SELECT KODE_RTW_KLAIM,A.KODE_KLAIM, NAMA_TK,NOMOR_IDENTITAS,B.KODE_JAM_KECELAKAAN, A.KODE_KANTOR_RTW,KODE_PPK,
   STATUS_RTW_KLAIM, A.KETERANGAN,to_char(TGL_SELESAI,'DD/MM/YYYY') TGL_SELESAI,to_char(A.TGL_REKAM,'DD/MM/YYYY') TGL_REKAM  ,
   to_char(TGL_KLAIM,'DD/MM/YYYY') TGL_KLAIM, to_char(TGL_LAPOR,'DD/MM/YYYY') TGL_LAPOR,to_char(TGL_KEJADIAN,'DD/MM/YYYY') TGL_KEJADIAN,KODE_PERUSAHAAN,
   KODE_JENIS_KASUS,KODE_LOKASI_KECELAKAAN,B.KODE_TK,to_char(TGL_SUBMIT_AGENDA,'DD/MM/YYYY') TGL_LAPOR1
   ,to_char(TGL_SUBMIT_AGENDA2,'DD/MM/YYYY') TGL_LAPOR2
FROM sijstk.PN_KLAIM B INNER JOIN sijstk.PN_RTW_KLAIM A ON (B.KODE_KLAIM = A.KODE_KLAIM)
WHERE A.KODE_RTW_KLAIM='{$ls_kode_rtw}'
) A left outer join sijstk.KN_PERUSAHAAN B on A.KODE_PERUSAHAAN=B.KODE_PERUSAHAAN
left outer join sijstk.PN_KODE_JENIS_KASUS C on A.KODE_JENIS_KASUS=C.KODE_JENIS_KASUS
left outer join sijstk.PN_KODE_LOKASI_KECELAKAAN D on A.KODE_LOKASI_KECELAKAAN=D.KODE_LOKASI_KECELAKAAN
left outer join sijstk.MS_KANTOR F on A.KODE_KANTOR_RTW=F.KODE_KANTOR
left outer join sijstk.kn_tk h on a.KODE_TK=H.KODE_TK
";
$DB->parse($sql); //echo $sql;
$DB->execute();
$kode_kantor="";
if($row = $DB->nextrow())
{
    $ls_id_kode_klaim  = $row['KODE_KLAIM'];
    $ls_id_nm_tk       = $row['NAMA_TK'];
    $ls_id_nik         = $row['NOMOR_IDENTITAS'];
    $ls_id_jabatan     = '';
    $ls_id_nm_prs      = $row['NAMA_PERUSAHAAN'];
    $ls_id_telpon_are  = $row['TELEPON_AREA_KANTOR'];
    $ls_id_telpon      = $row['TELEPON_KANTOR'];
    $ls_id_ext         = $row['TELEPON_EXT_KANTOR'];
    $ls_id_hp          = $row['HANDPHONE'];
    $ls_id_alamat      = $row['ALAMAT_DOMISILI'];
    $ls_id_jenis_kasus = $row['NAMA_JENIS_KASUS'];
    
    $ls_id_jenis_kasus = $row['NAMA_JENIS_KASUS'];
    $ls_id_jam_kecelakaan = $row['JAM_KECELAKAAN'];
    $ls_id_tgl_kecelakaan =$row['TGL_KEJADIAN'];
    $ls_id_tgl_lapor1  = $row['TGL_LAPOR1'];
    $ls_id_tgl_lapor2  = $row['TGL_LAPOR2'];
    $ls_id_lokasi_kecelakaan = $row['NAMA_LOKASI_KECELAKAAN'];
    $ls_kode_ppk = $row['KODE_PPK'];
}
    //The unfiltered SELECT
    $sql = "select A.NAMA_FASKES,A.NAMA_PIC,A.HANDPHONE_PIC,A.TELEPON_AREA_PEMILIK,A.TELEPON_PEMILIK,A.TELEPON_EXT_PEMILIK,
    b.NAMA_JENIS,c.NAMA_JENIS_DETIL,A.KODE_JENIS,A.KODE_JENIS_DETIL,A.KODE_FASKES,
    TELEPON_AREA_PIC,TELEPON_PIC,TELEPON_EXT_PIC
    from sijstk.tc_faskes A left outer join sijstk.tc_kode_jenis b on a.kode_jenis=b.kode_jenis
    left outer join sijstk.tc_kode_jenis_detil c on a.kode_jenis_detil=c.kode_jenis_detil
    where KODE_FASKES='{$ls_kode_ppk}'
    order by A.NAMA_FASKES"; 
    $DB->parse($sql);
    $DB->execute();
    $kode_kantor="";
    if($row = $DB->nextrow())
    {
    $ls_nama_faskes=$row['NAMA_FASKES'];
    $ls_nama_pic=$row['NAMA_PIC'];
    $ls_telepon_area_pic=$row['TELEPON_AREA_PIC'];
    $ls_telepon_pic=$row['TELEPON_PIC'];
    $ls_telepon_ext_pic=$row['TELEPON_EXT_PIC'];
    $ls_jenis=$row['NAMA_JENIS'];
    $ls_jenis_detil=$row['NAMA_JENIS_DETIL'];
    $ls_hp_pic=$row['HANDPHONE_PIC'];
    }

    $sql = "SELECT * FROM PN_RTW_INFODASAR
    where KODE_RTW_KLAIM='{$ls_kode_rtw}'"; 
    $DB->parse($sql);//echo $sql;
    $DB->execute();
    $kode_kantor="";
    if($row = $DB->nextrow())
    {
        $ls_jabatan_tk      = $row['JABATAN_TK'];
        $ls_kode_upah       = $row['KODE_UPAH_TK'];
        $ls_kode_pendidikan = $row['KODE_PENDIDIKAN_TK'];
        $ls_riwayat_sakit   = $row['RIWAYAT_PENYAKIT'];
        $ls_prognosis       = $row['PROGNOSIS'];
        $ls_yg_terkena      = $row['BAGIAN_TUBUH'];
        $ls_kode_kondisi    = $row['KODE_KONDISI'];
        $ls_kode_tindakan_medis =$row['KODE_TINDAKAN_MEDIS'];
        $ls_kode_rehabilitasi   =$row['KODE_REHABILITASI'];
        $ls_kode_duk_kel    =$row['KODE_DUKUNGAN_KEL'];
        $ls_keterangan      =$row['KETERANGAN'];
        $ls_job_desc        =$row['JOB_DESC'];
        $ls_tugas           =$row['TUGAS'];
        $ls_kode_beban      =$row['KODE_BEBAN'];
        $ls_kode_pak        =$row['KODE_PAK'];
    }
}  //   echo $sql;
/*****end LOAD DATA*********************************/ 
?>
    <form name="form_id" id="form_id" role="form" method="post">
    <input type="hidden" id="formregact" name="formregact" value="<?=$task;?>" />
    <input type="hidden" id="kode_rtw" name="kode_rtw" value="<?=$ls_kode_rtw;?>"/>
    <fieldset><legend><b>Informasi Peserta Klaim</b></legend>
    <table border='0' width="100%">
        <tr>
            <td width="50%" valign="top">
                <div class="f_1" ><label for="nama_tk">Nama Tenaga Kerja :</label></div>
                <div class="f_2"><input type="text" id="nama_tk" name="nama_tk" value="<?=$ls_id_nm_tk;?>" style="width:200px;background-color:#e9e9e9;" /></div>
                <div class="f_1" ><label for="nik">No Identitas :</label></div>
                <div class="f_2"><input type="text" id="nik" name="nik" maxlength="100" value="<?=$ls_id_nik;?>" style="width:140px;background-color:#e9e9e9;" /></div>
                <div class="f_1" ><label for="nm_prshn">Nama Perusahaan :</label></div>
                <div class="f_2"><input type="text" id="nm_prshn" name="nm_prshn" value="<?=$ls_id_nm_prs;?>" style="width:200px;background-color:#e9e9e9;" /></div>
                <div class="f_1" ><label for="hp">Handphone :</label></div>
                <div class="f_2"><input type="text" id="hp" name="hp" value="<?=$ls_id_hp;?>" style="width:140px;background-color:#e9e9e9;" /></div>
            </td>
            <td valign="top">
                <div class="f_1" ><label for="telp_area">Telepon :</label></div>
                <div class="f_2"><input type="text" id="telp_area" name="telp_area" style="width:30px;background-color:#e9e9e9;" value="<?=$ls_id_telpon_are;?>" /><input type="text" id="telp" name="telp" maxlength="100" style="width:130px;background-color:#e9e9e9;" value="<?=$ls_id_telpon;?>" /></div>
                <div class="f_1" ><label for="telp_ext">Ext :</label></div>
                <div class="f_2"><input type="text" id="telp_ext" name="telp_ext" maxlength="100" style="width:30px;background-color:#e9e9e9;" value="<?=$ls_id_ext;?>" /></div>
                <div class="f_1" ><label for="alamat">Alamat :</label></div>
                <div class="f_2"><textarea id="alamat" name="alamat" maxlength="300" style="width:275px;background-color:#e9e9e9;" rows="2" tabindex="4"><?=$ls_id_alamat;?></textarea></div>
            </td>
        </tr>
    </table>
    </fieldset>
    <fieldset><legend><b>Informasi Kecelakaan Pada Laporan</b></legend>
        <table border='0' width="100%">
            <tr>
                <td width="50%" valign="top">
                    <div class="f_1" ><label for="kasus">Kasus :</label></div>
                    <div class="f_2"><input type="text" id="kasus" name="kasus" value="<?=$ls_id_jenis_kasus;?>" style="width:240px;background-color:#e9e9e9;" /></div>
                    <div class="f_1" ><label for="tgl_kece">Tgl. Kecelakaan :</label></div>
                    <div class="f_2"><input type="text" id="tgl_kece" name="tgl_kece" value="<?=$ls_id_tgl_kecelakaan;?>" style="width:140px;background-color:#e9e9e9;" /></div>
                    <div class="f_1" ><label for="tgl_lapor1">Tgl Lapor Thp1 :</label></div>
                    <div class="f_2"><input type="text" id="tgl_lapor1" name="tgl_lapor1"  value="<?=$ls_id_tgl_lapor1;?>" style="width:140px;background-color:#e9e9e9;" /></div>
                </td>
                <td width="50%" valign="top">
                    <div class="f_1" ><label for="lokasi">Lokasi Kecelakaan :</label></div>
                    <div class="f_2"><input type="text" id="lokasi" name="lokasi"  value="<?=$ls_id_lokasi_kecelakaan;?>" style="width:240px;background-color:#e9e9e9;" /></div>
                    <div class="f_1" ><label for="jam">Jam Kecelakaan :</label></div>
                    <div class="f_2"><input type="text" id="jam" name="jam"  value="<?=$ls_id_jam_kecelakaan;?>" style="width:140px;background-color:#e9e9e9;" /></div>
                    <div class="f_1" ><label for="tgl_lapor2">Tgl Lapor Thp2 :</label></div>
                    <div class="f_2"><input type="text" id="tgl_lapor2" name="tgl_lapor2" value="<?=$ls_id_tgl_lapor2;?>" style="width:140px;background-color:#e9e9e9;" /></div>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset><legend><b>Kontak Faskes</b></legend>
        <table border='0' width="100%">
            <tr>
                <td width="50%" valign="top">
                    <input type="hidden" id="faskes_kode" name="faskes_kode" value="<?=$ls_kode_ppk;?>" />
                    <div class="f_1" ><label for="faskes_nama">Nama Faskes :</label></div>
                    <div class="f_2"><input type="text" id="faskes_nama" name="faskes_nama" maxlength="100" value="<?=$ls_nama_faskes;?>" style="width:240px;background-color:#e9e9e9;" readonly /></div>
                    <div class="f_1" ><label for="faskes_pic">Nama PIC Faskes :</label></div>
                    <div class="f_2"><input type="text" id="faskes_pic" name="faskes_pic" maxlength="100"  value="<?=$ls_nama_pic;?>" style="width:240px;background-color:#e9e9e9;" readonly /></div>
                    <div class="f_1" ><label for="nama_jenis">Jenis Faskes/BLK* :</label></div>
                    <div class="f_2"><input type="text" id="nama_jenis" name="nama_jenis"  style="width:240px;background-color:#e9e9e9;" value="<?=$ls_jenis;?>" readonly ></div>
                    <div class="f_1"><label for="nama_jenis_detil">Sub Jenis Faskes/BLK* :</label></div>
                    <div class="f_2"><input type="text" id="nama_jenis_detil" name="nama_jenis_detil" style="width:240px;background-color:#e9e9e9;" value="<?=$ls_jenis_detil;?>" readonly /></div>                    
                </td>
                <td width="50%" valign="top">
                    <div class="f_1" ><label for="faskestelp_area">Telepon :</label></div>
                    <div class="f_2"><input type="text" id="faskestelp_area" name="faskestelp_area" maxlength="100" style="width:30px;background-color:#e9e9e9;" value="<?=$ls_telepon_area_pic;?>" /><input type="text" id="faskestelp" name="faskestelp" maxlength="100" style="width:130px;background-color:#e9e9e9;" value="<?=$ls_telepon_pic;?>" /></div>
                    <div class="f_1" ><label for="faskestelp_ext">Ext :</label></div>
                    <div class="f_2"><input type="text" id="faskestelp_ext" name="faskestelp_ext" maxlength="100" style="width:30px;background-color:#e9e9e9;" value="<?=$ls_telepon_ext_pic;?>"/></div>
                    <div class="f_1" ><label for="handphone">handphone :</label></div>
                    <div class="f_2"><input type="text" id="handphone" name="handphone" maxlength="100" value="<?=$ls_hp_pic;?>" style="width:140px;background-color:#e9e9e9;" /></div>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset><legend><b>Informasi Medis</b></legend>
        <table border='0' width="100%">
            <tr>
                <td width="50%" valign="top">
                    <div class="f_1" ><label for="riwayat_pkt">Riwayat Penyakit :</label></div>
                    <div class="f_2"><input type="text" id="riwayat_pkt" name="riwayat_pkt" maxlength="100" value="<?=$ls_riwayat_sakit;?>" style="width:240px;background-color:#ffffff;" <?=$global_readonly;?> /></div>
                    <div class="f_1" ><label for="prognosis">Prognosis :</label></div>
                    <div class="f_2"><input type="text" id="prognosis" name="prognosis" maxlength="100" value="<?=$ls_prognosis;?>" style="width:240px;background-color:#ffffff;" <?=$global_readonly;?>/></div>
                    <div class="f_1" ><label for="ygterkena">Bag Tbh yg Terkena :</label></div>
                    <div class="f_2"><input type="text" id="ygterkena" name="ygterkena" maxlength="100" value="<?=$ls_yg_terkena;?>" style="width:240px;background-color:#ffffff;" <?=$global_readonly;?>/></div>                    
                </td>
                <td width="50%" valign="top">
                    <div class="f_1" ><label for="kondisitk">Kondisi Peserta :</label></div>
                    <div class="f_2"><select id="kondisitk" name="kondisitk" tabindex="15" style="background-color:#ffffff;" <?=$global_readonly;?>></select></div>
                    <div class="f_1"><label for="tdkmedis">Tindakan Medis :</label></div>
                    <div class="f_2"><select id="tdkmedis" name="tdkmedis" <?=$global_readonly;?>>   </select></div>
                    <div class="f_1"><label for="rehab">Rehabilitasi :</label></div>
                    <div class="f_2"><select id="rehab" name="rehab" <?=$global_readonly;?>></select></div>
                    <div class="f_1"><label for="dukkeluarga">Dukungan Keluarga:</label></div>
                    <div class="f_2"><select id="dukkeluarga" name="dukkeluarga" <?=$global_readonly;?>></select></div>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset><legend><b>Job Desc Sebelum Terjadi Kecelakaan</b></legend>
        <table border='0' width="100%">
            <tr>
                <td width="50%" valign="top">
                    <div class="f_1" ><label for="jobdesc">Desc Tugas Sehari2 :</label></div>
                    <div class="f_2"><textarea id="jobdesc" name="jobdesc" maxlength="300" style="width:275px;background-color:#ffffff;" rows="2" tabindex="4" <?=$global_readonly;?>><?=$ls_job_desc;?></textarea></div>
                    <div class="f_1" ><label for="bebankerja">Beban Kerja :</label></div>
                    <div class="f_2"><select id="bebankerja" name="bebankerja" tabindex="15" style="background-color:#ffffff;" <?=$global_readonly;?>></select></div>
                </td>
                <td width="50%" valign="top">
                    <div class="f_1" ><label for="tugas">Tugas &amp; Proses Kerja  :</label></div>
                    <div class="f_2"><textarea id="tugas" name="tugas" maxlength="300" style="width:275px;background-color:#ffffff;" rows="2" tabindex="4" <?=$global_readonly;?>><?=$ls_tugas;?></textarea></div>
                    <div class="f_1" ><label for="linkkerja">Linkungan Kerja(PAK) :</label></div>
                    <div class="f_2"><select id="linkkerja" name="linkkerja" tabindex="15" style="background-color:#ffffff;" <?=$global_readonly;?>></select></div>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset><legend><b>Data Tenaga Kerja</b></legend>
        <table border='0' width="100%">
            <tr>
                <td width="50%" valign="top">
                <div class="f_1" ><label for="jbt">Jabatan :</label></div>
                    <div class="f_2"><input type="text" id="jbt" name="jbt" maxlength="50" style="width:275px;;background-color:#ffffff;" value="<?=$ls_jabatan_tk;?>" <?=$global_readonly;?>/></div>
                    <div class="f_1"><label for="upah">Upah :</label></div>
                    <div class="f_2"><select id="upah" name="upah" <?=$global_readonly;?>></select></div>
                </td>
                <td width="50%" valign="top">
                    <div class="f_1"><label for="pddk">Pendidikan :</label></div>
                    <div class="f_2"><select id="pddk" name="pddk" <?=$global_readonly;?>></select></div>
                </td>
            </tr>
        </table> 
    </fieldset>
    <br />
    <b>Diagnosa</b>
    <hr />
    <table  id="" cellspacing="0" border="0" bordercolor="#C0C0C0" background-color= "#ffffff" width="100%">
        <tr>
            <th colspan="6"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
        </tr>
        <thead>
          <tr bgcolor="#e0e0e0">
            <?php if(!$ro_data){?>
            <td></td>
            <?php } ?>
            <th class="align-left">Nama Tenaga Medis</th>
            <th class="align-left">Group ICD</th>
            <th class="align-left">Diagnosa</th>   
            <th class="align-left">Diagnosa Detil</th>   
            <th class="align-left">Keterangan</th>
          </tr>
          <tr>
            <th colspan="6"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        </tr>
        </thead>
        <tbody id="listdata_id_diagnosa">
        </tbody>
        <tr>
            <th colspan="6"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        </tr>
      </table> <br /> 
      <?php if( (!$ro_data && $jml_data['infodasar']>0) && $global_readonly=="" ) {?>
      <div style="border-bottom: 1px double #C0C0FF;width:100px;float:right;" align="right">
        <a href="javascript:NewWindow('pn5032_form_info_dasar_diagnosa.php?dataid=<?=$ls_kode_rtw;?>&task=NewInfo&parenttask=<?=$task;?>','_per_id',800,300,1);" ><img src="../../images/plus.png" alt="" />Tambah</a>
      </div>
      <br />
      <?php } ?>
    </form>
<?php /*****LOCAL JAVASCRIPT INFORMASI DASAR*************************************/?>
<script type="text/javascript">
function get_Kondisi_id(par_data){$("#form_id select[name=kondisitk]").html(par_data);}
function get_TindakanMedis_id(par_data){$("#form_id select[name=tdkmedis]").html(par_data);}
function get_Rehabilitasi_id(par_data){$("#form_id select[name=rehab]").html(par_data);}
function get_DukunganKeluarga_id(par_data){$("#form_id select[name=dukkeluarga]").html(par_data);}
function get_Upah_id(par_data){$("#form_id select[name=upah]").html(par_data);}
function get_Pendidikan_id(par_data){$("#form_id select[name=pddk]").html(par_data);}
function get_BebanKerja_id(par_data){$("#form_id select[name=bebankerja]").html(par_data);}
function get_PAK_id(par_data){$("#form_id select[name=linkkerja]").html(par_data);}
function get_InformasiDasarDiagnosa_id(par_data){$("#listdata_id_diagnosa").html(par_data);}
$(document).ready(function(){ 
    /*
    $("#form_id select[name=kode_jenis]").change(function(){ 
        lov_subData('subJenis','getSubJenis',$(this).val(),0);
    });*/
    lov_subData('KondisiTK','getMSLookup','KIMTK','<?=$ls_kode_kondisi;?>');
    lov_subData('TindakanMedisTK','getMSLookup','TM','<?=$ls_kode_tindakan_medis;?>');
    lov_subData('Rehabilitasi','getMSLookup','REHAB','<?=$ls_kode_rehabilitasi;?>');
    lov_subData('DukunganKeluarga','getMSLookup','DK','<?=$ls_kode_duk_kel;?>');
    lov_subData('Upah','getMSLookup','URTW','<?=$ls_kode_upah;?>');
    lov_subData('Pendidikan','getMSLookup','RTWPEND','<?=$ls_kode_pendidikan;?>');
    lov_subData('BebanKerja','getMSLookup','RTWBK','<?=$ls_kode_beban;?>');
    lov_subData('PAK','getMSLookup','RTWPAK','<?=$ls_kode_pak;?>');
    lov_subData('InformasiDasarDiagnosa','getInformasiDasarDiagnosa','<?=$ls_kode_rtw;?>','<?php echo ($ro_data?'ro':'');?>');  
});
window.get_InformasiDasarDiagnosa=function(p_kode_rtw)
{
    lov_subData('InformasiDasarDiagnosa','getInformasiDasarDiagnosa',p_kode_rtw,'');    
}
<?php if(!$ro_data){?>
function saveInformasiDasar()
{
    if(confirm("Save data Informasi Dasar?"))
    {
        saveData('../ajax/pn5032_action_informasi_dasar.php?',$("#form_id").serialize(),'pn5032.php?task=<?=$task;?>&dataid=<?=$ls_kode_rtw;?>&mid=<?=$mid;?>&noform=1')
    }
}
function deleteInfoDiagnosa(par_kode,par_no)
{
    if(confirm('Hapus data informasi diagnosa?'))
    {
        deleteData('../ajax/pn5032_action_informasi_dasar_diagnosa.php','delDiagnosa','delDiagnosa',par_kode,par_no);
    }
}
<?php }?>
</script> 
<?php /*****end LOCAL JAVASCRIPT LOCAL JAVASCRIPT INFORMASI DASAR*********************************/?>