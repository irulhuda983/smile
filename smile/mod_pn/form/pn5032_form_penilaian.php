<?php
if($task=="View" || $task=="Edit")
{       
    $sql = "SELECT to_char(TGL_PENILAIAN_AWAL,'DD/MM/YYYY') TGL_PENILAIAN_AWAL,
    NAMA_PENILAI_AWAL,
    JABATAN_PENILAI_AWAL,
    TEKANAN_DARAH_AWAL,
    TINGGI_BADAN_AWAL,
    BERAT_BADAN_AWAL,
    RIWAYAT_MEDIS_AWAL,
    REF_TK_AWAL,
    REF_PERUSAHAAN_AWAL,
    KETERANGAN_AWAL,
    DESKRIPSI_TUGAS_AWAL,
    PROSES_KERJA_AWAL,
    KODE_BEBAN_KERJA_AWAL,
    KODE_PAK_AWAL
FROM PN_RTW_PENILAIAN_INFO_AWAL 
where kode_Penilaian='NIL{$ls_kode_rtw}01'";
    $DB->parse($sql); //echo $sql;
    $DB->execute();
    $kode_kantor="";
    if($row = $DB->nextrow())
    {
        $ls_a_tgl_penilaian		        = $row["TGL_PENILAIAN_AWAL"];
        $ls_a_nama_penilai			    = $row["NAMA_PENILAI_AWAL"];
        $ls_a_jbt_penilai		            = $row["JABATAN_PENILAI_AWAL"];
        $ls_a_tek_darah	                = $row["TEKANAN_DARAH_AWAL"];
        $ls_a_tg_badan		            = $row["TINGGI_BADAN_AWAL"];
        $ls_a_brt_badan		            = $row["BERAT_BADAN_AWAL"];
        $ls_a_rwy_medis		            = $row["RIWAYAT_MEDIS_AWAL"];
        $ls_a_ref_tk  		            = $row["REF_TK_AWAL"];
        $ls_a_ref_prs 		            = $row["REF_PERUSAHAAN_AWAL"];
        $ls_a_keterangan		            = $row["KETERANGAN_AWAL"];
        $ls_a_job_desc		            = $row["DESKRIPSI_TUGAS_AWAL"];
        $ls_a_bbn_kerja		            = $row["KODE_BEBAN_KERJA_AWAL"];
        $ls_a_proc_kerja		            = $row["PROSES_KERJA_AWAL"];
        $ls_a_pak		                    = $row["KODE_PAK_AWAL"];
    }
    $sql = "SELECT to_char(TGL_PENILAIAN_LINGKUNGAN,'DD/MM/YYYY') TGL_PENILAIAN_LINGKUNGAN,
       NAMA_PENILAI_LINGKUNGAN,
       JABATAN_PENILAI_LINGKUNGAN,
       TEKANAN_DARAH_LINGKUNGAN,
       TINGGI_BADAN_LINGKUNGAN,
       BERAT_BADAN_LINGKUNGAN,
       RIWAYAT_MEDIS_LINGKUNGAN,
       EVALUASI_FISIK,
       EVALUASI_MENTAL,
       EVALUASI_KAPASITAS,
       KETERANGAN_ANALISA,
       KETERANGAN_REKOMENDASI,
       KETERANGAN_INTERVENSI
  FROM PN_RTW_PENILAIAN_INFO_LINK
where kode_Penilaian='NIL{$ls_kode_rtw}02'";
    $DB->parse($sql); //echo $sql;
    $DB->execute();
    $kode_kantor="";
    if($row = $DB->nextrow())
    {
        $ls_l_tgl_penilaian              = $row["TGL_PENILAIAN_LINGKUNGAN"];
        $ls_l_nama_penilai             = $row["NAMA_PENILAI_LINGKUNGAN"];
        $ls_l_jbt_penilai                  = $row["JABATAN_PENILAI_LINGKUNGAN"];
        $ls_l_tek_darah                   = $row["TEKANAN_DARAH_LINGKUNGAN"];
        $ls_l_tg_badan                  = $row["TINGGI_BADAN_LINGKUNGAN"];
        $ls_l_brt_badan                  = $row["BERAT_BADAN_LINGKUNGAN"];
        $ls_l_rwy_medis                  = $row["RIWAYAT_MEDIS_LINGKUNGAN"];
        $ls_l_eval_fisik                    = $row["EVALUASI_MENTAL"];
        $ls_l_eval_mental                   = $row["EVALUASI_MENTAL"];
        $ls_l_eval_kapasitas                 = $row["EVALUASI_KAPASITAS"];
        $ls_l_analisa                  = $row["KETERANGAN_ANALISA"];
        $ls_l_rekomendasi                  = $row["KETERANGAN_REKOMENDASI"];
        $ls_l_intervensi                 = $row["KETERANGAN_INTERVENSI"];
    }
    $sql = "SELECT to_char(TGL_PENEMPATAN,'DD/MM/YYYY') TGL_PENEMPATAN,
       NAMA_PENILAI_PENEMPATAN,
       JABATAN_PENILAI_PENEMPATAN,
       DESKRIPSI_TUGAS_PENEMPATAN,
       PROSES_KERJA_PENEMPATAN,
       KODE_BEBAN_KERJA_PENEMPATAN,
       KODE_PAK_PENEMPATAN,
       KETERANGAN_HASIL_ANALISA,
       PERSENTASE_CACAT,
       KESIMPULAN,
       REF_PERUSAHAAN_PENEMPATAN,
       KETERANGAN_PENEMPATAN,
       BIAYA_ALAT
  FROM PN_RTW_PENILAIAN_INFO_KERJA
where kode_Penilaian='NIL{$ls_kode_rtw}03'";
 $DB->parse($sql); //echo $sql;
 $DB->execute();
 $kode_kantor="";
 if($row = $DB->nextrow())
 {
    $ls_k_tgl_penilaian              = $row["TGL_PENEMPATAN"];
    $ls_k_nama_penilai             = $row["NAMA_PENILAI_PENEMPATAN"];
    $ls_k_jbt_penilai                  = $row["JABATAN_PENILAI_PENEMPATAN"];
    $ls_k_ref_prs                   = $row["REF_PERUSAHAAN_PENEMPATAN"];
    $ls_k_job_desc                  = $row["DESKRIPSI_TUGAS_PENEMPATAN"];
    $ls_k_beban_kerja                 = $row["KODE_BEBAN_KERJA_PENEMPATAN"];
    $ls_k_proc_kerja                  = $row["PROSES_KERJA_PENEMPATAN"];
    $ls_k_pak                   = $row["KODE_PAK_PENEMPATAN"];
    $ls_k_analisa                   = $row["KETERANGAN_HASIL_ANALISA"];
    $ls_k_prosen                 = $row["PERSENTASE_CACAT"];
    $ls_k_kesimpulan                  = $row["KESIMPULAN"];
    $ls_k_keterangan                 = $row["KETERANGAN_PENEMPATAN"];
    $ls_k_biaya                = $row["BIAYA_ALAT"];
 }
}
?>
<script type="text/javascript">
var trpenilaian_id=1;
function show_penilaian(p_id)
{
    $("#boxpenilaian"+p_id).attr("checked","checked");
    $("#trpenilaian"+trpenilaian_id).attr('bgcolor','#FFFFFF');
    $("#trpenilaian"+p_id).attr('bgcolor','#C0C0FF');
    $("#divpenilaian"+trpenilaian_id).hide();
    $("#divpenilaian"+p_id).fadeIn();
    trpenilaian_id=p_id;
}
</script>
    <div style="padding:5px;background:#FFFFC0;margin-bottom:5px;">
        Pilih template penilaian di bawah ini untuk melakukan entry data penilaian
    </div>
    <table  id="listdata" cellspacing="0" border="0" bordercolor="#C0C0C0" background-color= "#ffffff" width="100%">
        <thead>
        <tr>
            <th colspan="7"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
        </tr>
        <tr bgcolor="#F0F0F0">
            <th class="align-left">&nbsp;</th>
            <th class="align-left">Template Penilaian</th>                        
            <th class="align-left">Deskripsi</th>
            <th class="align-left">Skor Penilaian</th>
            <th class="align-left">Prosentase</th> 
            <th class="align-left">Nilai Maksimal</th>
            <th class="align-left">&nbsp;</th>
        </tr>
        <tr>
            <th colspan="8"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        </tr>
        </thead>
        <tbody id="listdata_iks">
        <tr bgcolor="#C0C0FF" class="gw_tr" onclick="show_penilaian('1');" id="trpenilaian1">
            <td><input type="radio" id="boxpenilaian1" name="boxpenilaian" value="1" checked /></td>
            <td>Penilaian Awal</td>
            <td>Penilaian Awal</td>
            <td><span id="pen_skor_a"></span></td>
            <td><span id="pen_prosen_a"></span></td>
            <td><span id="pen_maks_a"></span></td>
            <td><div style="width:90px"><a href="javascript:NewWindow('pn5032_form_penilaian_awal.php?dataid=<?=$ls_kode_rtw;?>&task=Assessment&parenttask=<?php echo $global_readonly!=''?'View':$task;?>&tmp=RTWTEMP01','_per_id',900,500,1);"><img src="../../images/edit.gif" />Asessment</a></div></td>
        </tr>
        <tr bgcolor="#ffffff" class="gw_tr" onclick="show_penilaian('2');" id="trpenilaian2">
            <td><input type="radio" id="boxpenilaian2" name="boxpenilaian" value="1"  /></td>
            <td>Penilaian Linkungan Kerja</td>
            <td>Penilaian Lingkungan Kerja</td>
            <td><span id="pen_skor_b"></span></td>
            <td><span id="pen_prosen_b"></span></td>
            <td><span id="pen_maks_b"></span></td>
            <td><div style="width:90px"><a href="javascript:NewWindow('pn5032_form_penilaian_awal.php?dataid=<?=$ls_kode_rtw;?>&task=Assessment&parenttask=<?php echo $global_readonly!=''?'View':$task;?>&tmp=RTWTEMP02','_per_id',900,500,1);"><img src="../../images/edit.gif" />Asessment</a></div></td>
        </tr>
        <tr bgcolor="#ffffff" class="gw_tr" onclick="show_penilaian('3');" id="trpenilaian3">
            <td><input type="radio" id="boxpenilaian3" name="boxpenilaian" value="1"  /></td>
            <td>Penempatan Kerja</td>
            <td>Deskripsi Penempatan Kerja</td>
            <td><span id="pen_skor_c"></span></td>
            <td><span id="pen_prosen_c"></span></td>
            <td><span id="pen_maks_c"></span></td>
            <td><div style="width:90px"><a href="javascript:NewWindow('pn5032_form_penilaian_awal.php?dataid=<?php echo $global_readonly!=''?'View':$task;?>&task=Assessment&parenttask=<?=$task;?>&tmp=RTWTEMP03','_per_id',900,500,1);"><img src="../../images/edit.gif" />Asessment</a></div></td>
        </tr>
        <tr>
            <th colspan="8"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        </tr>
        </tbody>
    </table>
    <br />
    <div id="divpenilaian1">
        <form id="form_pen_1">
            <input type="hidden" name="pen_no" value="1" />
            <input type="hidden" id="formregact" name="formregact" value="<?=$task;?>" />
            <input type="hidden" id="kode_rtw" name="kode_rtw" value="<?=$ls_kode_rtw;?>"/>
                <fieldset>
                <legend>Informasi Penilaian Awal</legend>
                <table border='0' width="100%">
                    <tr>
                        <td width="50%" valign="top">
                            <div class="f_1" ><label for="pen_a_tgl_penilaian">Tgl. Penilaian :</label></div>
                            <div class="f_2">
                                <input type="text" id="pen_a_tgl_penilaian" name="pen_a_tgl_penilaian" style="width:100px;background-color:#ffffff;" value="<?=$ls_a_tgl_penilaian;?>" readonly/><input type="image" align="top" onclick="return showCalendar('pen_a_tgl_penilaian', 'dd-mm-y');" src="../../images/calendar.gif"/>
                            </div>
                            <div class="f_1" ><label for="pen_a_nama_penilai">Nama Penilai :</label></div>
                            <div class="f_2"><input type="text" id="pen_a_nama_penilai" name="pen_a_nama_penilai" style="width:200px;background-color:#ffffff;" value="<?=$ls_a_nama_penilai?>" <?=$global_readonly;?>/></div>
                            <div class="f_1" ><label for="pen_a_jbt_penilai">Jabatan Penilai :</label></div>
                            <div class="f_2"><input type="text" id="pen_a_jbt_penilai" name="pen_a_jbt_penilai" style="width:200px;background-color:#ffffff;" value="<?=$ls_a_jbt_penilai?>"  <?=$global_readonly;?>/></div>
                            <div class="f_1" ><label for="pen_a_tek_darah">Tekanan Darah :</label></div>
                            <div class="f_2"><input type="text" id="pen_a_tek_darah" name="pen_a_tek_darah" style="width:80px;background-color:#ffffff;text-align:right;" value="<?=$ls_a_tek_darah?>"   <?=$global_readonly;?>/></div>
                            <div class="f_1" ><label for="pen_a_tg_bdn">Tinggi Badan :</label></div>
                            <div class="f_2"><input type="text" id="pen_a_tg_bdn" name="pen_a_tg_bdn" style="width:80px;background-color:#ffffff;text-align:right;"  value="<?=$ls_a_tg_badan?>"  <?=$global_readonly;?>/></div>
                            <div class="f_1" ><label for="pen_a_brt_bdn">Berat Badan :</label></div>
                            <div class="f_2"><input type="text" id="pen_a_brt_bdn" name="pen_a_brt_bdn" style="width:80px;background-color:#ffffff;text-align:right;" value="<?=$ls_a_brt_badan?>"   <?=$global_readonly;?>/></div>
                        </td>
                        <td valign="top"><div class="f_1" >
                            <label for="pen_a_rwyt_medis">Riwayat Medis :</label></div>
                            <div class="f_2"><textarea id="pen_a_rwyt_medis" name="pen_a_rwyt_medis" maxlength="300" style="width:275px;background-color:#ffffff;" rows="2" <?=$global_readonly;?>><?=$ls_a_rwy_medis;?></textarea></div>
                            <div class="f_1" ><label for="pen_a_ref_tk">Referensi untuk TK :</label></div>
                            <div class="f_2"><input type="text" id="pen_a_ref_tk" name="pen_a_ref_tk" style="width:200px;background-color:#ffffff;" value="<?=$ls_a_ref_tk?>"   <?=$global_readonly;?>/></div>
                            <div class="f_1" ><label for="pen_a_ref_prs">Referensi untuk Perusahaan :</label></div>
                            <div class="f_2"><input type="text" id="pen_a_ref_prs" name="pen_a_ref_prs" style="width:200px;background-color:#ffffff;"  value="<?=$ls_a_ref_prs?>"  <?=$global_readonly;?>/></div>
                            <div class="f_1" ><label for="pen_a_ket">Keterangan :</label></div>
                            <div class="f_2"><textarea id="pen_a_ket" name="pen_a_ket" maxlength="300" style="width:275px;background-color:#ffffff;" rows="2" <?=$global_readonly;?>><?=$ls_a_keterangan;?></textarea></div>
                        </td>
                    </tr>
                </table>
                <br />
                <b>Job Desc Sebelum Terjadi Kecelakaan</b>
                <hr />
                <table border='0' width="100%">
                    <tr>
                        <td width="50%" valign="top">
                            <div class="f_1" ><label for="pen_a_job_desc">Desc tugas Sehari2 :</label></div>
                            <div class="f_2"><textarea id="pen_a_job_desc" name="pen_a_job_desc" maxlength="300" style="width:275px;background-color:#ffffff;" rows="2" <?=$global_readonly;?>><?=$ls_a_job_desc;?></textarea></div>
                            <div class="f_1" ><label for="pen_a_beban_kerja">Beban kerja :</label></div>
                            <div class="f_2"><select id="pen_a_beban_kerja" name="pen_a_beban_kerja" <?=$global_readonly;?>></select></div>
                        </td>
                        <td valign="top">
                            <div class="f_1" ><label for="pen_a_proc_kerja">Tugas &amp; Proses kerja :</label></div>
                            <div class="f_2"><textarea id="pen_a_proc_kerja" name="pen_a_proc_kerja" maxlength="300" style="width:275px;background-color:#ffffff;" rows="2" <?=$global_readonly;?>><?=$ls_a_proc_kerja;?></textarea></div>
                            <div class="f_1" ><label for="pen_a_pak">Lingkungan Kerja (PAK) :</label></div>
                            <div class="f_2"><select id="pen_a_pak" name="pen_a_pak" <?=$global_readonly;?>></select></div>
                        </td>
                    </tr>
                </table>
                </fieldset>    
            </fieldset>
        </form>
    </div>
    <div id="divpenilaian2" style="display:none">
        <form id="form_pen_2">
        <input type="hidden" name="pen_no" value="2" />
            <input type="hidden" id="formregact" name="formregact" value="<?=$task;?>" />
            <input type="hidden" id="kode_rtw" name="kode_rtw" value="<?=$ls_kode_rtw;?>"/>
            <fieldset>
            <legend>Penilaian Lingkungan kerja</legend>
                <fieldset>
                <legend>Informasi Penilaian Lingkungan Kerja</legend>
                <table border='0' width="100%">
                    <tr>
                        <td width="50%" valign="top">
                            <div class="f_1" ><label for="pen_lk_tgl_penilaian">Tgl. Penilaian :</label></div>
                            <div class="f_2">
                            <input type="text" id="pen_lk_tgl_penilaian" name="pen_lk_tgl_penilaian" style="width:100px;background-color:#ffffff;" value="<?=$ls_l_tgl_penilaian;?>" readonly/><input type="image" align="top" onclick="return showCalendar('pen_lk_tgl_penilaian', 'dd-mm-y');" src="../../images/calendar.gif"/>
                            </div>
                            <div class="f_1" ><label for="pen_lk_nama_penilai">Nama Penilai :</label></div>
                            <div class="f_2"><input type="text" id="pen_lk_nama_penilai" name="pen_lk_nama_penilai" style="width:200px;background-color:#ffffff;" value="<?=$ls_l_nama_penilai;?>"  <?=$global_readonly;?>/></div>
                            <div class="f_1" ><label for="pen_lk_jbt_penilai">Jabatan Penilai :</label></div>
                            <div class="f_2"><input type="text" id="pen_lk_jbt_penilai" name="pen_lk_jbt_penilai" style="width:200px;background-color:#ffffff;" value="<?=$ls_l_jbt_penilai;?>"  <?=$global_readonly;?>/></div>
                            <div class="f_1" ><label for="pen_lk_tek_darah">Tekanan Darah :</label></div>
                            <div class="f_2"><input type="text" id="pen_lk_tek_darah" name="pen_lk_tek_darah" style="width:80px;background-color:#ffffff;text-align:right;" value="<?=$ls_l_tek_darah;?>"  <?=$global_readonly;?>/></div>
                            <div class="f_1" ><label for="pen_lk_tg_bdn">Tinggi Badan :</label></div>
                            <div class="f_2"><input type="text" id="pen_lk_tg_bdn" name="pen_lk_tg_bdn" style="width:80px;background-color:#ffffff;text-align:right;"  value="<?=$ls_l_tg_badan;?>"  <?=$global_readonly;?>/></div>
                            <div class="f_1" ><label for="pen_lk_brt_bdn">Berat Badan :</label></div>
                            <div class="f_2"><input type="text" id="pen_lk_brt_bdn" name="pen_lk_brt_bdn" style="width:80px;background-color:#ffffff;text-align:right;"  value="<?=$ls_l_brt_badan;?>"  <?=$global_readonly;?>/></div>
                        </td>
                        <td valign="top">
                            <div class="f_1" ><label for="pen_lk_rwyt_medis">Riwayat Medis Lingk :</label></div>
                            <div class="f_2"><textarea id="pen_lk_rwyt_medis" name="pen_lk_rwyt_medis" maxlength="300" style="width:275px;background-color:#ffffff;" rows="2" <?=$global_readonly;?>><?=$ls_l_rwy_medis;?></textarea></div>
                        </td>
                    </tr>
                </table>
                <br />
                <b>Evaluasi Kapasitas Fungsi Berdasarkan Keterngan Dokter Rehab Medis/ Terapis yang Merawat</b>
                <hr />
                <table border='0' width="100%">
                    <tr>
                        <td width="50%" valign="top">
                            <div class="f_1" ><label for="pen_lk_eval_fisik">Kemampuan Fisik Pekerja :</label></div>
                            <div class="f_2"><input type="text" id="pen_lk_eval_fisik" name="pen_lk_eval_fisik" style="width:200px;background-color:#ffffff;"  value="<?=$ls_l_eval_fisik;?>"  <?=$global_readonly;?>/></div>
                            <div class="f_1" ><label for="pen_lk_eval_mental">Fungsi Mental Pekerja :</label></div>
                            <div class="f_2"><input type="text" id="pen_lk_eval_mental" name="pen_lk_eval_mental" style="width:200px;background-color:#ffffff;"  value="<?=$ls_l_eval_mental;?>" <?=$global_readonly;?>/></div>
                        </td>
                        <td valign="top">
                            <div class="f_1" ><label for="pen_lk_eval_kapasitas">Kapasitas Pekerja :</label></div>
                            <div class="f_2"><input type="text" id="pen_lk_eval_kapasitas" name="pen_lk_eval_kapasitas" style="width:200px;background-color:#ffffff;"  value="<?=$ls_l_eval_kapasitas;?>"  <?=$global_readonly;?>/></div>
                        </td>
                    </tr>
                </table><br />
                <b>Analisa Terhadap kemampuan Bekerja Karyawan</b>
                <hr />
                <table border='0' width="100%">
                    <tr>
                        <td width="50%" valign="top">
                            <div class="f_1" ><label for="pen_lk_ket_analisa">Keterangan Analisa :</label></div>
                            <div class="f_2"><textarea id="pen_lk_ket_analisa" name="pen_lk_ket_analisa" maxlength="300" style="width:275px;background-color:#ffffff;" rows="2" <?=$global_readonly;?>><?=$ls_l_analisa;?></textarea></div>
                            <div class="f_1" ><label for="pen_lk_ket_rekomendasi">Rekomendasi :</label></div>
                            <div class="f_2"><textarea id="pen_lk_ket_rekomendasi" name="pen_lk_ket_rekomendasi" maxlength="300" style="width:275px;background-color:#ffffff;" rows="2" <?=$global_readonly;?>><?=$ls_l_rekomendasi;?></textarea></div>
                        </td>
                        <td valign="top">
                            <div class="f_1" ><label for="pen_lk_ket_intervensi">Intervensi Yang Diakukan :</label></div>
                            <div class="f_2"><textarea id="pen_lk_ket_intervensi" name="pen_lk_ket_intervensi" maxlength="300" style="width:275px;background-color:#ffffff;" rows="2" <?=$global_readonly;?>><?=$ls_l_intervensi;?></textarea></div>
                        </td>
                    </tr>
                </table>
                </fieldset>
            </fieldset>
        </form>
    </div>
    <div id="divpenilaian3" style="display:none">
    <form id="form_pen_3">
        <input type="hidden" name="pen_no" value="3" />
        <input type="hidden" id="formregact" name="formregact" value="<?=$task;?>" />
        <input type="hidden" id="kode_rtw" name="kode_rtw" value="<?=$ls_kode_rtw;?>"/>
        <fieldset>
        <legend>Informasi Penempatan Kerja</legend>
        <table border='0' width="100%">
            <tr>
                <td width="50%" valign="top">
                    <div class="f_1" ><label for="pen_pk_tgl_penilaian">Tgl. Penilaian :</label></div>
                    <div class="f_2">
                    <input type="text" id="pen_pk_tgl_penilaian" name="pen_pk_tgl_penilaian" style="width:100px;background-color:#ffffff;" value="<?=$ls_k_tgl_penilaian;?>" readonly/><input type="image" align="top" onclick="return showCalendar('pen_pk_tgl_penilaian', 'dd-mm-y');" src="../../images/calendar.gif"/>
                    </div>
                    <div class="f_1" ><label for="pen_pk_nama_penilai">Nama Penilai :</label></div>
                    <div class="f_2"><input type="text" id="pen_pk_nama_penilai" name="pen_pk_nama_penilai" style="width:200px;background-color:#ffffff;" value="<?=$ls_k_nama_penilai;?>" <?=$global_readonly;?>/></div>
                </td>
                <td valign="top">
                    <div class="f_1" ><label for="pen_pk_jbt_penilai">Jabatan Penilai :</label></div>
                    <div class="f_2"><input type="text" id="pen_pk_jbt_penilai" name="pen_pk_jbt_penilai" style="width:200px;background-color:#ffffff;"  value="<?=$ls_k_jbt_penilai;?>"  <?=$global_readonly;?>/></div>                    
                    <div class="f_1" ><label for="pen_pk_ref_prs">Ref Perusahaan Penempatan :</label></div>
                    <div class="f_2"><input type="text" id="pen_pk_ref_prs" name="pen_pk_ref_prs" style="width:200px;background-color:#ffffff;"  value="<?=$ls_k_ref_prs;?>" <?=$global_readonly;?>/></div>                    
                </td>
            </tr>
        </table>
        <br />
        <b>Deskripsi Pekerjaan</b>
        <hr />
        <table border='0' width="100%">
            <tr>
                <td width="50%" valign="top">
                    <div class="f_1" ><label for="pen_pk_job_desc">Desc tugas Sehari2 :</label></div>
                    <div class="f_2"><textarea id="pen_pk_job_desc" name="pen_pk_job_desc" maxlength="300" style="width:275px;background-color:#ffffff;" rows="2" <?=$global_readonly;?>><?=$ls_k_job_desc;?></textarea></div>
                    <div class="f_1" ><label for="pen_pk_beban_kerja">Beban kerja :</label></div>
                    <div class="f_2"><select id="pen_pk_beban_kerja" name="pen_pk_beban_kerja" <?=$global_readonly;?>></select></div>
                </td>
                <td valign="top">
                    <div class="f_1" ><label for="pen_pk_proc_kerja">Tugas &amp; Proses kerja :</label></div>
                    <div class="f_2"><textarea id="pen_pk_proc_kerja" name="pen_pk_proc_kerja" maxlength="300" style="width:275px;background-color:#ffffff;" rows="2" <?=$global_readonly;?>><?=$ls_k_proc_kerja;?></textarea></div>
                    <div class="f_1" ><label for="pen_pk_pak">Lingkungan Kerja (PAK) :</label></div>
                    <div class="f_2"><select id="pen_pk_pak" name="pen_pk_pak" <?=$global_readonly;?>></select></div>
                </td>
            </tr>
        </table>
        <br />
        <b>Hasil Analisa Penempatan Kerja</b>
        <hr />
        <table border='0' width="100%">
            <tr>
                <td width="50%" valign="top">
                    <div class="f_1" ><label for="pen_pk_analisa">Hasil Analisa :</label></div>
                    <div class="f_2"><textarea id="pen_pk_analisa" name="pen_pk_analisa" maxlength="300" style="width:275px;background-color:#ffffff;" rows="2" <?=$global_readonly;?>><?=$ls_k_analisa;?></textarea></div>
                    <div class="f_1" ><label for="pen_pk_prosen_cacat">Prosentase Cacat % :</label></div>
                    <div class="f_2"><input type="text" id="pen_pk_prosen_cacat" name="pen_pk_prosen_cacat" style="width:80px;background-color:#ffffff;text-align:right;"  value="<?=$ls_k_prosen;?>"  <?=$global_readonly;?>/></div>
                    <div class="f_1" ><label for="pen_pk_kesimpulan">Kesimpulan :</label></div>
                    <div class="f_2"><textarea id="pen_pk_kesimpulan" name="pen_pk_kesimpulan" maxlength="300" style="width:275px;background-color:#ffffff;" rows="2" <?=$global_readonly;?>><?=$ls_k_kesimpulan;?></textarea></div>
                </td>
                <td valign="top">
                    <div class="f_1" ><label for="pen_pk_keterangan">Keterangan :</label></div>
                    <div class="f_2"><textarea id="pen_pk_keterangan" name="pen_pk_keterangan" maxlength="300" style="width:275px;background-color:#ffffff;" rows="2" <?=$global_readonly;?>><?=$ls_k_keterangan;?></textarea></div>
                    <div class="f_1" ><label for="pen_pk_biaya_alat">Biaya Alat Kesehatan :</label></div>
                    <div class="f_2"><input type="text" id="pen_pk_biaya_alat" name="pen_pk_biaya_alat" style="width:80px;background-color:#ffffff;text-align:right;" value="<?=$ls_k_biaya;?>"   <?=$global_readonly;?>/></div>
                </td>
            </tr>
        </table>
        </fieldset>    
    </fieldset>
    </form>
    </div>

<script type="text/javascript">
function get_Beban_Pen_Awal(par_data){$("#form_pen_1 select[name=pen_a_beban_kerja]").html(par_data);}
function get_PAK_Pen_Awal(par_data){$("#form_pen_1 select[name=pen_a_pak]").html(par_data);}
function get_Beban_Pen_Pk(par_data){$("#form_pen_3 select[name=pen_pk_beban_kerja]").html(par_data);}
function get_PAK_Pen_Pk(par_data){$("#form_pen_3 select[name=pen_pk_pak]").html(par_data);}
$(document).ready(function(){ 
    lov_subData('BebanPenAwal','getMSLookup','RTWBEBAN','<?=$ls_a_bbn_kerja;?>');
    lov_subData('PAKPenAwal','getMSLookup','RTWPAK','<?=$ls_a_pak;?>');
    lov_subData('BebanPenPk','getMSLookup','RTWBEBAN','<?=$ls_k_beban_kerja;?>');
    lov_subData('PAKPenPk','getMSLookup','RTWPAK','<?=$ls_k_pak;?>');
    LoadDataHasilPenilaian();
});
function savePenilaian()
{
    if(trpenilaian_id==1) {
        if(!fl_js_val_numeric('pen_a_tek_darah')||!fl_js_val_numeric('pen_a_tg_bdn')||!fl_js_val_numeric('pen_a_brt_bdn'))
        {
            alert('Tekanan Darah, Tinggi & Berat Badan haruslah format angka!');
            return 0;
        }else if(!confirm('Save Penilaian: Informasi Penilaian Awal?')) return 0;
    }
    else if(trpenilaian_id==2) {
        if(!fl_js_val_numeric('pen_lk_tek_darah')||!fl_js_val_numeric('pen_lk_tg_bdn')||!fl_js_val_numeric('pen_lk_brt_bdn'))
        {
            alert('Tekanan Darah, Tinggi & Berat Badan haruslah format angka!');
            return 0;
        }else if(!confirm('Save Penilaian: Informasi Penilaian Lingkungan Kerja?')) return 0;
    }
    else if(trpenilaian_id==3) {if(!confirm('Save Penilaian: Informasi Penilaian Penempatan Kerja?')) return 0;}
    else return 0;
    savePenilainInformasi();
}
function savePenilainInformasi()
{
    saveData('../ajax/pn5032_action_penilaian.php?',$("#form_pen_"+trpenilaian_id).serialize(),'pn5032.php?task=<?=$task;?>&dataid=<?=$ls_kode_rtw;?>&mid=<?=$mid;?>&noform=2')
}
window.LoadDataHasilPenilaian = function()
{ 
    lov_subData('PenNilA','getPenilaianNilai','<?php echo "NIL{$ls_kode_rtw}01";?>','RTWTEMP01');
    lov_subData('PenNilB','getPenilaianNilai','<?php echo "NIL{$ls_kode_rtw}02";?>','RTWTEMP02');
    lov_subData('PenNilC','getPenilaianNilai','<?php echo "NIL{$ls_kode_rtw}03";?>','RTWTEMP03');
}
function get_Pen_A(par_data)
{
    jdata = JSON.parse(par_data);
    $("#pen_skor_a").html(jdata.nilai);
    $("#pen_prosen_a").html(jdata.prosen);
    $("#pen_maks_a").html(jdata.maks);
}
function get_Pen_B(par_data)
{//confirm('l;ll;l'+par_data);
    jdata = JSON.parse(par_data);
    $("#pen_skor_b").html(jdata.nilai);
    $("#pen_prosen_b").html(jdata.prosen);
    $("#pen_maks_b").html(jdata.maks);
}
function get_Pen_C(par_data)
{
    jdata = JSON.parse(par_data);
    $("#pen_skor_c").html(jdata.nilai);
    $("#pen_prosen_c").html(jdata.prosen);
    $("#pen_maks_c").html(jdata.maks);
}
</script>