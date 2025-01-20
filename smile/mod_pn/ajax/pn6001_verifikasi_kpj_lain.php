<?php

include_once '../../includes/fungsi_newrpt.php';
$php_file_name='pn6001_faskes';

$p_task = $_REQUEST["task"];
$p_no_identitas = $_REQUEST["kdf"];
$p_no_identitas = $ls_no_identitas == "" ? $p_no_identitas : $ls_no_identitas;
$mid = $_REQUEST["mid"];
$st_submit_kartu = !isset($_GET['st_submit_kartu']) ? $_POST['st_submit_kartu'] : $_GET['st_submit_kartu'];


if(isset($_GET['kd_agenda'])){
    $kd_agenda = $_GET['kd_agenda'];
        
        $sql = "
                SELECT AA.nama_lengkap,
                    AA.nomor_identitas,
                    AA.nama_ibu_kandung,
                    AA.tempat_lahir,
                    AA.tgl_lahir,
                    AA.jenis_kelamin,
                    AA.status_kawin,
                    AA.alamat,
                    AA.kode_pos,
                    BB.nama_kelurahan,
                    BB.nama_kecamatan,
                    BB.nama_kabupaten,
                    BB.nama_propinsi,
                    AA.no_hp,
                    AA.email,
                    AA.status_submit_tindak_lanjut,
                    AA.status_batal,
                    AA.status_approval
                FROM PN.PN_AGENDA_VERIFIKASI_JHT AA, KN.KN_PENDUDUK BB
                WHERE AA.NOMOR_IDENTITAS = BB.NIK(+) AND AA.kode_agenda = '$kd_agenda'";
    
        $DB->parse($sql);
        $DB->execute();
        $row = $DB->nextrow();
        $ls_nama_tk                     = $row["NAMA_LENGKAP"];
        $p_no_identitas                 = $row["NOMOR_IDENTITAS"];
        $ls_nama_ibu_kandung            = $row["NAMA_IBU_KANDUNG"] ;       
        $ls_tempat_lahir                = $row["TEMPAT_LAHIR"];
        $ls_tgl_lahir                   = $row["TGL_LAHIR"];
        $ls_jenis_kelamin               = $row["JENIS_KELAMIN"];
        $ls_status_kawin                = $row["STATUS_KAWIN"];
        $ls_alamat                      = $row["ALAMAT"];
        $ls_nama_kelurahan              = $row["NAMA_KELURAHAN"];
        $ls_nama_kecamatan              = $row["NAMA_KECAMATAN"];
        $ls_nama_kabupaten              = $row["NAMA_KABUPATEN"];
        $ls_nama_propinsi               = $row["NAMA_PROPINSI"];
        $ls_kode_pos                    = $row["KODE_POS"];      
        $ls_handphone                   = $row["NO_HP"];
        $ls_email                       = $row["EMAIL"]; 
        $ls_status_submit_tindak_lanjut = $row["STATUS_SUBMIT_TINDAK_LANJUT"]; 
        $ls_status_batal                = $row["STATUS_BATAL"]; 
        $ls_status_approval             = $row["STATUS_APPROVAL"]; 


        $sql_daftar_kartu = "SELECT aa.kode_tk,
                                    (SELECT b.kpj
                                    FROM kn.kn_tk b
                                    WHERE b.kode_tk = aa.kode_tk AND ROWNUM = 1)
                                    kpj,
                                    (SELECT b.npp
                                    FROM kn.kn_perusahaan b
                                    WHERE b.kode_perusahaan = aa.kode_perusahaan AND ROWNUM = 1)
                                    npp,
                                    aa.nama_perusahaan,
                                    (SELECT b.aktif_tk
                                    FROM kn.vw_kn_tk b
                                    WHERE     b.kode_tk = aa.kode_tk
                                            AND b.kode_kepesertaan = aa.kode_kepesertaan
                                            AND b.kode_perusahaan = aa.kode_perusahaan
                                            AND b.no_mutasi = aa.no_mutasi)
                                    aktif_tk,
                                    (SELECT b.kode_na
                                    FROM kn.vw_kn_tk b
                                    WHERE     b.kode_tk = aa.kode_tk
                                            AND b.kode_kepesertaan = aa.kode_kepesertaan
                                            AND b.kode_perusahaan = aa.kode_perusahaan
                                            AND b.no_mutasi = aa.no_mutasi)
                                    kode_na,
                                    TO_CHAR (aa.tgl_aktif, 'DD-MM-RRRR') tgl_aktif,
                                    TO_CHAR (aa.tgl_na, 'DD-MM-RRRR') tgl_expired,
                                    aa.nom_saldo_jht jml_saldo
                            FROM PN.PN_AGENDA_VERIFIKASI_JHT_KEPS aa
                            WHERE aa.kode_agenda = '$kd_agenda'
                                AND NOT EXISTS
                                                (SELECT NULL
                                                    FROM PN.PN_AGENDA_VERIFIKASI_JHT Y,
                                                        PN.PN_AGENDA_VERIFIKASI_JHT_TUKEP Z
                                                    WHERE     Y.KODE_AGENDA = Z.KODE_AGENDA
                                                        AND Z.KODE_TK = aa.KODE_TK
                                                        AND Y.STATUS_APPROVAL = 'Y'
                                                        AND Y.STATUS_BATAL = 'T')    
                            ";
        
        $total_rows_daftar_kartu = f_count_rows($DB,$sql_daftar_kartu);

        $sql_daftar_kartu_tdk_proses = "SELECT aa.kode_tk,
                                            (SELECT b.kpj
                                            FROM kn.kn_tk b
                                            WHERE b.kode_tk = aa.kode_tk AND ROWNUM = 1)
                                            kpj,
                                            (SELECT b.npp
                                            FROM kn.kn_perusahaan b
                                            WHERE b.kode_perusahaan = aa.kode_perusahaan AND ROWNUM = 1)
                                            npp,
                                            aa.nama_perusahaan,
                                            (SELECT b.aktif_tk
                                            FROM kn.vw_kn_tk b
                                            WHERE     b.kode_tk = aa.kode_tk
                                                    AND b.kode_kepesertaan = aa.kode_kepesertaan
                                                    AND b.kode_perusahaan = aa.kode_perusahaan
                                                    AND b.no_mutasi = aa.no_mutasi)
                                            aktif_tk,
                                            (SELECT b.kode_na
                                            FROM kn.vw_kn_tk b
                                            WHERE     b.kode_tk = aa.kode_tk
                                                    AND b.kode_kepesertaan = aa.kode_kepesertaan
                                                    AND b.kode_perusahaan = aa.kode_perusahaan
                                                    AND b.no_mutasi = aa.no_mutasi)
                                            kode_na,
                                            TO_CHAR (aa.tgl_aktif, 'DD-MM-RRRR') tgl_aktif,
                                            TO_CHAR (aa.tgl_na, 'DD-MM-RRRR') tgl_expired,
                                            aa.nom_saldo_jht jml_saldo
                                    FROM PN.PN_AGENDA_VERIFIKASI_JHT_TUKEP aa
                                    WHERE aa.kode_agenda = '$kd_agenda' ORDER BY tgl_rekam desc";
        

        $total_rows = f_count_rows($DB,$sql_daftar_kartu_tdk_proses);

        $sql_dokumen = "SELECT kode_tk,
                                kpj,
                                CASE
                                WHEN kode_jenis_agenda_detil = 'PP0301'
                                THEN
                                    'Surat Pernyataan Peserta terverifikasi memliki kpj yang lain namun persyaratan dokumen belum lengkap'
                                WHEN kode_jenis_agenda_detil = 'PP0302'
                                THEN
                                    'Surat Pernyataan Peserta terverifikasi tidak memliki kpj yang lain'
                                END
                                agenda_detil,
                                path_url,
                                flag_mandatory,
                                tgl_upload,
                                petugas_rekam
                        FROM (SELECT a.kode_tk,
                                        (SELECT b.kpj
                                        FROM kn.vw_kn_tk b
                                        WHERE b.kode_tk = a.kode_tk AND ROWNUM = 1)
                                        kpj,
                                        (SELECT b.kode_jenis_agenda_detil
                                        FROM PN.PN_AGENDA_VERIFIKASI_JHT b
                                        WHERE b.kode_agenda = a.kode_agenda)
                                        kode_jenis_agenda_detil,
                                        a.path_url,
                                        a.flag_mandatory,
                                        a.tgl_upload,
                                        a.petugas_rekam
                                FROM PN.PN_AGENDA_VERIFIKASI_JHT_TUDOK a
                                WHERE a.kode_agenda = '$kd_agenda')";

        $total_rows_dok = f_count_rows($DB,$sql_dokumen);
        

    
} else {
    if ($p_no_identitas != ""){     
        $sql = "
                SELECT AA.nama_tk,
                    AA.nama_ibu_kandung,
                    AA.tempat_lahir,
                    AA.tgl_lahir,
                    AA.jenis_kelamin,
                    AA.status_kawin,
                    AA.golongan_darah,
                    AA.alamat,
                    AA.kode_pos,
                    BB.nama_kelurahan,
                    BB.nama_kecamatan,
                    BB.nama_kabupaten,
                    BB.nama_propinsi,
                    AA.handphone,
                    AA.email
                FROM sijstk.vw_kn_tk AA, KN.KN_PENDUDUK BB
                WHERE AA.NOMOR_IDENTITAS = BB.NIK(+)
                    AND AA.kode_paket IN ('L', 'N')
                    AND AA.flag_klaim_jht = 'T'
                    AND AA.nomor_identitas = '$p_no_identitas'
                    AND ROWNUM = 1";
    
        $DB->parse($sql);
        $DB->execute();
        $row = $DB->nextrow();
        $ls_nama_tk             = $row["NAMA_TK"];
        $ls_nama_ibu_kandung    = $row["NAMA_IBU_KANDUNG"] ;       
        $ls_tempat_lahir        = $row["TEMPAT_LAHIR"];
        $ls_tgl_lahir           = $row["TGL_LAHIR"];
        $ls_jenis_kelamin       = $row["JENIS_KELAMIN"];
        $ls_status_kawin        = $row["STATUS_KAWIN"];
        $ls_golongan_darah      = $row["GOLONGAN_DARAH"];
        $ls_alamat              = $row["ALAMAT"];
        $ls_nama_kelurahan      = $row["NAMA_KELURAHAN"];
        $ls_nama_kecamatan      = $row["NAMA_KECAMATAN"];
        $ls_nama_kabupaten      = $row["NAMA_KABUPATEN"];
        $ls_nama_propinsi       = $row["NAMA_PROPINSI"];
        $ls_kode_pos            = $row["KODE_POS"];      
        $ls_handphone           = $row["HANDPHONE"];
        $ls_email               = $row["EMAIL"]; 

        $sql_daftar_kartu = "SELECT *
                                FROM (SELECT aa.kode_tk,
                                            aa.kpj,
                                            aa.kode_perusahaan,
                                            aa.npp,
                                            aa.nama_perusahaan,
                                            aa.aktif_tk,
                                            aa.kode_na,
                                            to_char(aa.tgl_aktif,'DD-MM-RRRR') tgl_aktif,
                                            to_char(aa.tgl_expired,'DD-MM-RRRR') tgl_expired,
                                            (SELECT SUM (bb.nilai_transaksi)
                                                FROM kn.kn_tk_saldo bb
                                            WHERE     bb.kode_tk = aa.kode_tk
                                                    AND bb.kode_perusahaan = aa.kode_perusahaan
                                                    AND tahun =
                                                            (SELECT MAX (x.tahun)
                                                            FROM kn.kn_tk_saldo x
                                                            WHERE     x.kode_tk = bb.kode_tk
                                                                    AND x.kode_perusahaan =
                                                                        bb.kode_perusahaan))
                                                jml_saldo
                                        FROM kn.vw_kn_tk aa
                                    WHERE     aa.kode_paket IN ('L', 'N')
                                            AND aa.flag_klaim_jht = 'T'
                                            AND aa.kode_segmen = 'PU'
                                            AND NVL(aa.kode_na,'XXX') <> 'AG'
                                            AND aa.nomor_identitas = '$p_no_identitas'
                                            AND aa.no_mutasi =
                                                    (SELECT MAX (b.no_mutasi)
                                                    FROM kn.vw_kn_tk b
                                                    WHERE     b.kode_tk = aa.kode_tk
                                                            AND b.kpj = aa.kpj
                                                            AND b.kode_perusahaan = aa.kode_perusahaan)
                                            AND NOT EXISTS
                                                (SELECT NULL
                                                    FROM PN.PN_AGENDA_VERIFIKASI_JHT Y,
                                                        PN.PN_AGENDA_VERIFIKASI_JHT_TUKEP Z
                                                    WHERE     Y.KODE_AGENDA = Z.KODE_AGENDA
                                                        AND Z.KODE_TK = aa.KODE_TK
                                                        AND Y.STATUS_APPROVAL = 'Y'
                                                        AND Y.STATUS_BATAL = 'T')                
                                            )
                            WHERE jml_saldo > 0";

    }
}  

if (isset($_POST['cetakDok'])) {
    $kd_agenda = $_GET['kd_agenda'];
	$ls_user_param .= " P_KODE_AGENDA='$kd_agenda'"; 
	$ls_user_param .= ""; 		
    if($_GET['kd_perihal_detil'] == 'PP0301'){
        $ls_nama_rpt   .= "PNR900201.rdf";
    } else {
        $ls_nama_rpt   .= "PNR900202.rdf";
    }					  	 
	
	$tipe     = "PDF";
	$ls_modul = "pn";
	exec_rpt_enc_new(1,$ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);
}
    
          

?>

<script type="text/javascript">	
  let _asyncAwaitFn = [];
  let _asyncPreloadRunning = [];
  let _asyncPreload;
  
  window.asyncPreload = function(idloader, state) {
    idloader = idloader || 'data';
    if (state) {
      _asyncPreloadRunning[idloader] = state;
    } else {
      delete _asyncPreloadRunning[idloader];
    }
  }

  window.asyncPreloadStart = function() {
    _asyncPreload= setInterval(function () {
			try {
				if (_asyncPreloadRunning) {
					if (Object.keys(_asyncPreloadRunning).length > 0) {
						preload(true);
					} else {
						let fn = _asyncAwaitFn.shift();
						if (fn && typeof fn === 'function') {
							fn();
						} else {
							preload(false);
						}
					}
				}
			} catch(e) {
				window.asyncPreloadEnd();
			}
    }, 100)
  }

  window.asyncPreloadEnd = function () {
    preload(false);
    clearInterval(_asyncPreload);
  }

	window.asyncAwaitFn = function (fn) {
		_asyncAwaitFn.push(fn);
	}

  window.asyncPreloadStart();
</script>


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
<fieldset><legend><strong> Pilih Tenaga Kerja</strong> </legend>
<table aria-describedby="nik_" border='0' width="100%">
    <tr><th></th></tr>
    <tr>
     <td width="50%" valign="top">
    <div class="l_frm" ><label for="no_identitas">No Identitas<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
    <div class="r_frm">
        <input type="text" id="no_identitas" name="no_identitas" maxlength="100"  value="<?=$p_no_identitas;?>" style="width:150px;background-color:#ffff99; text-align: ;"readonly />
        <a href="#" onclick="fl_js_get_lov_by('NO_IDENTITAS')" tabindex="8" <?PHP if($_REQUEST["task"]!="New"){echo "style='pointer-events:none'";}?>/>                            
        <img src="../../images/help.png" alt="Cari TK" border="0" align="absmiddle"></a>
    </div>
    </td>
    <td width="50%" valign="top">
    </td>
   </tr>
</table>
</fieldset>
<br>
<fieldset><legend><strong> Identitas Tenaga Kerja</strong> </legend>
    <div class="form-row_kiri">
        <label for="nama_lengkap">Nama Lengkap</label>
        : <input type="text" id="nama_lengkap" name="nama_lengkap"  value="<?=$ls_nama_tk;?>" style="width:260px;background-color:#e9e9e9" readonly/>
    </div>
    <div class="clear"></div>

    <div class="form-row_kiri">
        <label for="ibu_kandung">Nama Ibu Kandung</label>
        : <input type="text" id="ibu_kandung" name="ibu_kandung" value="<?=$ls_nama_ibu_kandung;?>" style="width:260px;background-color:#e9e9e9" readonly />
    </div>
    <div class="clear"></div>

    <div class="form-row_kiri">
        <label for="tempat_lahir">Tempat/Tanggal Lahir</label>
        : <input type="text" id="tempat_lahir" name="tempat_lahir"  value="<?=$ls_tempat_lahir;?>" style="width:139px;background-color:#e9e9e9" readonly /> 
        / <input type="text" id="tgl_lahir" name="tgl_lahir"  value="<?=$ls_tgl_lahir;?>" style="width:105px;background-color:#e9e9e9" readonly /> 
    </div>
    <div class="clear"></div>

    <div class="form-row_kiri">
        <label for="jenis_kelamin">Jenis Kelamin</label>
        : <input type="text" id="jenis_kelamin" name="jenis_kelamin" value="<?=$ls_jenis_kelamin;?>" style="width:260px;background-color:#e9e9e9" readonly />
    </div>
    <div class="clear"></div>

    <div class="form-row_kiri">
        <label for="status_pernikahan">Status Pernikahan</label>
        : <input type="text" id="status_pernikahan" name="status_pernikahan" value="<?=$ls_status_kawin;?>" style="width:260px;background-color:#e9e9e9" readonly />
    </div>
    <div class="clear"></div>

    <div class="form-row_kiri">
        <label for="kode_kantor">Golongan Darah</label>
        : <input type="text" id="gol_darah" name="gol_darah" value="<?=$ls_golongan_darah;?>" style="width:260px;background-color:#e9e9e9" readonly />
    </div>
    <div class="clear"></div>

    <div class="form-row_kiri">
        <label for="alamat">Alamat</label>
        : <input type="text" id="alamat" name="alamat" value="<?=$ls_alamat;?>" style="width:260px;background-color:#e9e9e9" readonly />
    </div>
    <div class="clear"></div>

    <div class="form-row_kiri">
        <label for="kode_kelurahan">Nama Kelurahan</label>
        : <input type="text" id="nama_kelurahan" name="nama_kelurahan"  value="<?=$ls_nama_kelurahan;?>" style="width:260px;background-color:#e9e9e9" readonly />
    </div>
    <div class="clear"></div>

    <div class="form-row_kiri">
        <label for="kode_kabupaten">Nama Kecamatan</label>
        : <input type="text" id="nama_kecamatan" name="nama_kecamatan"  value="<?=$ls_nama_kecamatan;?>" style="width:260px;background-color:#e9e9e9" readonly />
    </div>
    <div class="clear"></div>

    <div class="form-row_kiri">
        <label for="kode_kabupaten">Nama Kota/Kabupaten</label>
        : <input type="text" id="nama_kabupaten" name="nama_kabupaten"  value="<?=$ls_nama_kabupaten;?>" style="width:260px;background-color:#e9e9e9" readonly />
    </div>
    <div class="clear"></div>

    <div class="form-row_kiri">
        <label for="kode_propinsi">Nama Propinsi</label>
        : <input type="text" id="nama_propinsi" name="nama_propinsi"  value="<?=$ls_nama_propinsi;?>" style="width:260px;background-color:#e9e9e9" readonly />
    </div>
    <div class="clear"></div>

    <div class="form-row_kiri">
        <label for="kode_pos">Kode Pos</label>
        : <input type="text" id="kode_pos" name="kode_pos" value="<?=$ls_kode_pos;?>" style="width:260px;background-color:#e9e9e9" readonly />
    </div>
    <div class="clear"></div>

    <div class="form-row_kiri">
        <label for="handphone">Handphone</label>
        : <input type="text" id="handphone" name="handphone" value="<?=$ls_handphone;?>" style="width:260px;background-color:#e9e9e9" readonly />
    </div>
    <div class="clear"></div>

    <div class="form-row_kiri">
        <label for="email">Email</label>
        : <input type="text" id="email" name="email" value="<?=$ls_email;?>" style="width:260px;background-color:#e9e9e9" readonly />
    </div>
    <div class="clear"></div>

</fieldset>
<br>
<fieldset><legend>Daftar Kartu Kepesertaan</legend>
    <table aria-describedby="kartu_peserta" id="tblrincian1" width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
        <tbody>	
            <tr>
                <th id="top_line" colspan="11"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
            <tr>																				
            <tr>
                <th id="nomor" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No</th>
                <th id="kode_tk_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Kode TK</th>
                <th id="kpj_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">KPJ</th>
                <th id="npp_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">NPP</th>
                <th id="nama_prs" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama Perusahaan</th>
                <th id="status_aktif_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Status Aktif</th>
                <th id="kode_na_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Kode NA</th>
                <th id="tgl_aktif_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tanggal Aktif</th>
                <th id="tgl_na_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tanggal Non Aktif</th>
                <th id="saldo_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan='2'>Saldo</th>
            </tr>
            <tr>
                <th id="bottom_line" colspan="11"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
            </tr>	
            <?php
            // $kartu_tdk_diproses = array();
            $DB->parse($sql_daftar_kartu);
            $DB->execute();
            $i = 1;
            while ($row = $DB->nextrow()){			
                echo '<tr>
                    <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$i.'</td>
                    <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row["KODE_TK"].'</td>
                    <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row["KPJ"].'</td>
                    <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row["NPP"].'</td>
                    <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row["NAMA_PERUSAHAAN"].'</td>
                    <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row["AKTIF_TK"].'</td>
                    <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row["KODE_NA"].'</td>
                    <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row["TGL_AKTIF"].'</td>
                    <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row["TGL_EXPIRED"].'</td>
                    <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;">Rp </td>	
                    <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.number_format($row["JML_SALDO"], 2).'</td>	
                </tr>';
                $i++;    
            } 
            ?>        
                						             																
        </tbody>
		<tr>
            <td colspan="11"><hr style="border:0; border-top:3px double #8c8c8c;"/></td>
        </tr>
		<tr><td colspan="11"><hr/></td></tr>															
    </table>
		</br>
  </fieldset>
<br>

<fieldset id='tbl_kartu_tidak_diproses'><legend>Daftar Kartu Kepesertaan Tidak Diproses</legend>
    <table aria-describedby="tdk_diakui" id="tblrincian1" width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
        <thead>	
            <tr>
                <th id="top_line" colspan="12"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
            <tr>																				
            <tr>
                <th id="nomor_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No</th>
                <th id="kode_tk_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Kode TK</th>
                <th id="kpj_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">KPJ</th>
                <th id="npp_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">NPP</th>
                <th id="nama_prs_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama Perusahaan</th>
                <th id="status_aktif_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Status Aktif</th>
                <th id="kode_na_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Kode NA</th>
                <th id="tgl_aktif_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tanggal Aktif</th>
                <th id="tgl_na_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tanggal Non Aktif</th>
                <th id="saldo_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan='2'>Saldo</th>
                <th id="aksi_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Aksi</th>
            </tr>
            <tr>
                <th id="bottom_line" colspan="12"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
            </tr>	
        </thead>
        <?php
        if($total_rows < 1){
            echo '<tbody  id="kartu_tdk_diproses"><tr><td colspan="12" align="center" >-- Data tidak ditemukan --</td></tr></tbody>';
        } else {
            $DB->parse($sql_daftar_kartu_tdk_proses);
            $DB->execute();
            $i = 1;
            while ($row = $DB->nextrow()){			
                $ls_kode_tk             = $row["KODE_TK"];
                $ls_kode_kepesertaan    = $row['KODE_KEPESERTAAN'];
                $ls_kode_perusahaan     = $row['KODE_PERUSAHAAN'];
                echo '<tr>
                    <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$i.'</td>
                    <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$ls_kode_tk.'</td>
                    <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row["KPJ"].'</td>
                    <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row["NPP"].'</td>
                    <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row["NAMA_PERUSAHAAN"].'</td>
                    <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row["AKTIF_TK"].'</td>
                    <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row["KODE_NA"].'</td>
                    <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row["TGL_AKTIF"].'</td>
                    <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row["TGL_EXPIRED"].'</td>
                    <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;">Rp </td>	
                    <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.number_format($row["JML_SALDO"], 2).'</td>
                    <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><input type="button" id="btn_hapus_kartu'.$i.'" onclick="hapus_kartu('."'".$kd_agenda."',"."'".$ls_kode_tk."'".')" style="font-weight:bold; color:#0000CD;text-decoration: underline;" value="Hapus"/></td>	
                </tr>';
                $i++;  
            }
        }
        ?>
        <tr>
            <td colspan="12"><hr style="border:0; border-top:3px double #8c8c8c;"/></td>
        </tr>
		<tr><td colspan="12"><hr/></td></tr>															
    </table>
		</br>
        <input id= "btn_tambah_kartu" type="button" style="width: 100px;" class="btn green" value="Tambah" onclick="fl_js_get_lov_kartu('<?=$p_no_identitas;?>', '<?=$kd_agenda?>')"/>&emsp;
  </fieldset>
<br>

<fieldset id='field_dokumen'><legend>Dokumen Pendukung</legend>
    <table aria-describedby="dokumen_pendukung" id="tblrincian1" width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
        <tbody>	
            <tr>
                <th id="top_line" colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
            <tr>																				
            <tr>
                <th id="nomor_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No</th>
                <th id="kode_tk_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Kode TK</th>
                <th id="kpj_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">KPJ</th>
                <th id="dokumen_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Dokumen</th>
                <th id="mandatory_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Mandatory</th>
                <th id="tmplt_surat_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Template Surat</th>
                <th id="upload_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Upload File</th>
                <th id="tgl_upload_" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tanggal Upload</th>
                <th id="ptgs_upload" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Petugas Upload</th>
            </tr>
            <tr>
                <th id="bottom_line" colspan="9"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
            </tr>				
            <?php 
            echo '<form name="formdownload" id="formdownload" method="post" action="'.$PHP_SELF.'">
            <input type="hidden" name="cetakDok" value="cetakDok">';
            
            $DB->parse($sql_dokumen);
            $DB->execute();
            $i = 1;
            
            while ($row = $DB->nextrow()){	
                $ls_checked     = '';
                $d_u_dokumen    = '';

                if($row['FLAG_MANDATORY']=='Y'){
                    $ls_checked = 'checked';
                }
                
                if($row['PATH_URL'] != ''){
                    $d_u_dokumen                = '<input type="button" onclick="downloadFileSmile('."'".$row['PATH_URL']."'".')" style="font-weight:bold; color:#0000CD;text-decoration: underline;" value="Download"/>';
                    
                    $btn_download_template      = '<input type="hidden" name="cetakDok" value="cetakDok">
                                                   <input type="submit" name="btnCetak" style="font-weight:bold; font-style: italic; color: grey;" value="Download" disabled/>';
                } else if($row['PATH_URL'] == '' && ($ls_status_submit_tindak_lanjut == 'Y' || $ls_status_batal == 'Y')){
                    $d_u_dokumen                = '<form name="formverif" id="formverif" role="form" method="post" enctype="multipart/form-data">
                                                    <input id="kode_tk_dok'.$i.'" name="kode_tk_dok'.$i.'" type="hidden" value="'.$row['KODE_TK'].'">
                                                    <span>-</span>
                                                   </form>';

                    $btn_download_template      = '<input type="submit" name="btnCetak" style="font-weight:bold; font-style: italic; color: grey;" value="Download" disabled/>';
                }else {
                    $d_u_dokumen                = '<form name="formverif" id="formverif" role="form" method="post" enctype="multipart/form-data">
                                                    <input id="kode_tk_dok'.$i.'" name="kode_tk_dok'.$i.'" type="hidden" value="'.$row['KODE_TK'].'">
                                                    <input id="dok_pernyataan'.$i.'" name="dok_pernyataan'.$i.'" type="file">
                                                   </form>';

                    $btn_download_template      = '<input type="submit" name="btnCetak" style="font-weight:bold; color:#0000CD;text-decoration: underline;" value="Download"/>';
                }

                echo $download_template_start.'<tr bgcolor="f3f3f3">
                        <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$i.'</td>
                        <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row['KODE_TK'].'</td>
                        <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row['KPJ'].'</td>
                        <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row['AGENDA_DETIL'].'</td>
                        <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><input type="checkbox" '.$ls_checked.' disabled></td>
                        <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$btn_download_template.'</td>
                        <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$d_u_dokumen .'</td>
                        <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row['TGL_UPLOAD'].'</td>
                        <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">'.$row['PETUGAS_REKAM'].'</td>
                    </tr>'.$download_template_end;
                $i++;
            } echo '</form>';   ?>
        </tbody>
		<tr>
            <td colspan="9"><hr style="border:0; border-top:3px double #8c8c8c;"/></td>
        </tr>
		<tr><td colspan="9"><hr/></td></tr>															
    </table>
	</br></br></br>
</fieldset>
<br>

<div class="form-row_kiri" width="100%">
    
    <?php if($ses_reg_role == '3' || $ses_reg_role == '4' || $ses_reg_role == '27'){?>
    <input id="check_pernyataan_submit" type="checkbox">
    <span id="kalimat_pernyataan_submit">Dengan mencentang kotak ini, saya telah memeriksa dan meneliti kebenaran serta keabsahan data yang diinput / upload</span>
    <br><br>

    <input type="button" id="btn_simpan" style="width: 100px;" class="btn green" value="Simpan" onclick="simpan()"/>
    <input type="button" id="btn_submit_koreksi" style="width: 100px;" class="btn green" value="Submit" onclick="submit_koreksi()"/>&emsp;
    <input type="button" id="btn_batal" style="width: 100px;" class="btn green" value="Batal" onclick="batal_koreksi()"/>&emsp;
    
    <?php 
    }
    if($ses_reg_role == '6' || $ses_reg_role == '25'){?>
    <input id="check_pernyataan_approval" type="checkbox">
    <span id="kalimat_pernyataan_approval">Dengan mencentang kotak ini, saya telah memeriksa dan meneliti kebenaran serta keabsahan data yang diinput / upload</span>
    <br><br>
    <input type="button" id="btn_setujuu" style="width: 100px;" class="btn green" value="Setuju" onclick="submit_setuju()"/>&emsp;
    <input type="button" id="btn_tolakk" style="width: 100px;" class="btn green" value="Tolak" onclick="batal_koreksi()"/>&emsp;
    <?php
    }
    if($_GET['task']=='Submit'){ ?>

        <a id="btn_close" href="http://<?=$HTTP_HOST;?>/mod_pn/form/pn6002.php?mid=<?=$mid;?>"><input type="button" style="width: 100px;" class="btn green" value="Tutup" /></a> 
    <?php
    } else {
    ?>
        <a id="btn_close" href="http://<?=$HTTP_HOST;?>/mod_pn/form/pn6001.php?mid=<?=$mid;?>"><input type="button" style="width: 100px;" class="btn green" value="Tutup" /></a> 
    <?php
    }
    ?>
</div>

<div class="clear"></div>
<br>
<br>
<br>

<fieldset style="background-color:#F5F5F5" ><legend>Keterangan</legend>
    <ul>
        <li>
            <span>*Kartu yang di proses merupakan kartu yang terverifikasi namun persyaratan dokumen belum lengkap</span><br>
                <strong> <em>atau</em></strong> <br>
            <span>*Kartu yang di proses merupakan kartu yang tidak terverifikasi oleh peserta</span>
        </li>
        <li>
            <span>
                Ukuran file dokumen pendukung yang dapat diupload adalah minimal 50kb dan maksimal 6mb
            </span>
        </li>
    </ul>
    </br>
</fieldset>
<br>

<table aria-describedby="mydesc" class="table responsive-table" id="mydata1" cellspacing="0" width="100%">
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
    let list_kode_tk = '';
    let path_url="";
    let mime_type="";

    $(document).ready(function(){
        $('#btn_submit').hide();
        $('#btn_tolak').hide();
        $('#btn_save').hide();
        $('#btn_close').hide();
        $('#tbl_kartu_tidak_diproses').hide();
        $('#btn_simpan').prop('disabled',true);
        $('#btn_submit_koreksi').prop('disabled',true);
        $('#btn_setujuu').prop('disabled',true);
        $('#btn_tolakk').prop('disabled',true);

        if('<?=$ls_status_submit_tindak_lanjut;?>' == 'T'){
            $('#tb_keterangan').prop('readonly',false);
        }

        if('<?=$ls_status_submit_tindak_lanjut;?>' == 'Y' || '<?=$ls_status_batal;?>' == 'Y'){
            $('#btn_simpan').hide();
            $('#btn_submit_koreksi').hide();
            $('#btn_tambah_kartu').hide();
            $('#btn_batal').hide();
            $('#check_pernyataan_submit').hide();
            $('#kalimat_pernyataan_submit').hide();

            var total_btn_hapus = '<?=$total_rows;?>';
            for(i=1; i<=total_btn_hapus; i++){
                $('#btn_hapus_kartu'+i).hide();
            }
        }

        if('<?=$ls_status_approval;?>' == 'Y' || '<?=$ls_status_batal;?>' == 'Y' || '<?=$ls_detil_status;?>' == 'AGENDA'){
            $('#btn_setujuu').hide();
            $('#btn_tolakk').hide();
            $('#check_pernyataan_approval').hide();
            $('#kalimat_pernyataan_approval').hide();
        }

        if('<?=$_GET['kd_agenda'];?>'){
            $('#tbl_kartu_tidak_diproses').show();
        }

        if('<?=$total_rows_daftar_kartu;?>' < 1){
            $('#btn_submit_koreksi').hide();
        } else {
            $('#btn_simpan').hide();
        }
  

        if('<?=$total_rows;?>' < 1 ){
            $('#field_dokumen').hide();
        }

        $('#check_pernyataan_submit').click(function() {
            if (!$(this).is(':checked')) {
                $('#btn_simpan').prop('disabled',true);
                $('#btn_submit_koreksi').prop('disabled',true);
            } else {
                $('#btn_simpan').prop('disabled',false);
                $('#btn_submit_koreksi').prop('disabled',false);
            }
        });

        $('#check_pernyataan_approval').click(function() {
            if (!$(this).is(':checked')) {
                $('#btn_setujuu').prop('disabled',true);
                $('#btn_tolakk').prop('disabled',true);
            } else {
                $('#btn_setujuu').prop('disabled',false);
                $('#btn_tolakk').prop('disabled',false);
            }
        }); 

    });
    

    function hapus_kartu(kode_agenda, kode_tk){
        var tanya = confirm("Apakah anda yakin akan menghapus data ini ?");
 
         if(tanya === true) {
            var kd_perihal_detil = '<?=$_GET['kd_perihal_detil'];?>';
            var path = "<?=$_GET['path'];?>";
            window.asyncPreload('filter', true); 
            $.ajax({
                type: 'POST',
                url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_verifikasi_kpj_lain_action.php?'+Math.random(),
                data: {
                    TYPE				    : 'HAPUS_KARTU',
                    kode_agenda             : kode_agenda,
                    kode_tk	                : kode_tk,			
                    SUBTYPE                 : kd_perihal_detil,
                    tipe_submit             : 'HAPUS_KARTU'
                },
                success: function(data){
                    var jdata = JSON.parse(data);	
                    if (jdata.ret == 0){
                        window.parent.Ext.notify.msg('Data berhasil dihapus dengan Kode TK '+jdata.dataid+', session dilanjutkan...', jdata.msg);
                        window.location='pn6001.php?task=Edit&path='+path+'&kd_agenda='+kode_agenda+'&kd_perihal_detil='+kd_perihal_detil;
                        
                    } else {
                        alert(jdata.msg);
                    }
                    window.asyncPreload('filter', false);
                },
                complete: function(){
                    window.asyncPreload('filter', false);
                },
                error: function(){
                    alert("Terjadi kesalahan, coba beberapa saat lagi!");
                    window.asyncPreload('filter', false);
                }
            });
         }
        
    }

    
    function tambah_kartu(data){
        var path = "<?=$_GET['path'];?>";
        var kd_perihal = "<?=$_GET['kd_perihal'];?>";
        var kd_perihal_detil = "<?=$_GET['kd_perihal_detil'];?>";
        var kd_tk = data;
        if(kd_tk != undefined){
            window.asyncPreload('filter', true);
            $.ajax({
                type: 'POST',
                url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_verifikasi_kpj_lain_action.php?'+Math.random(),
                data: {
                    TYPE				    : 'INSERT_KARTU',
                    kode_agenda             : '<?=$kd_agenda;?>',
                    kode_tk	                : kd_tk,			
                    SUBTYPE                 : "<?=$_GET['kd_perihal_detil'];?>",
                    tipe_submit             : 'TAMBAH_KARTU'
                },
                success: function(data){
                    
                    var jdata = JSON.parse(data);	
                    if (jdata.ret == 0){
                        window.parent.Ext.notify.msg('Penyimpanan data berhasil dengan kode agenda '+jdata.dataid+', session dilanjutkan...', jdata.msg);
                        window.location='pn6001.php?task=Edit&path='+path+'&kd_agenda='+jdata.dataid+'&kd_perihal_detil='+kd_perihal_detil+'&st_submit_kartu=done';
                        
                    } else {
                        alert(jdata.msg);
                    }
                    window.asyncPreload('filter', false);
                },
                complete: function(){
                    window.asyncPreload('filter', false);
                },
                error: function(){
                    alert("Terjadi kesalahan, coba beberapa saat lagi!");
                    window.asyncPreload('filter', false);
                }
            });
        }
		
  
	}

    function batal_koreksi(){
		var tanya = confirm("Apakah anda yakin akan membatalkan agenda ini ?");
        var tb_keterangan       = $('#tb_keterangan').val();
        var path                = "<?=$_GET['path'];?>";
        var kd_perihal          = "<?=$_GET['kd_perihal'];?>";
        var kd_perihal_detil    = "<?=$_GET['kd_perihal_detil'];?>";
 
        if(tanya === true) {
            window.asyncPreload('filter', true);
            $.ajax({
                type: 'POST',
                url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_verifikasi_kpj_lain_action.php?'+Math.random(),
                data: {
                    TYPE				    : 'BATAL',
                    kode_agenda 	        : '<?=$_GET['kd_agenda'];?>',	
                    keterangan              : tb_keterangan,	
                    tipe_submit             : 'BATAL_SUBMIT'
                },
                success: function(data){
                    
                    var jdata = JSON.parse(data);	
                    if (jdata.ret == 0){
                        window.parent.Ext.notify.msg('Penyimpanan data berhasil dengan kode agenda '+jdata.dataid+', session dilanjutkan...', jdata.msg);
                        if('<?=$ses_reg_role;?>' == '6' || '<?=$ses_reg_role;?>' == '25'){
                            window.location='pn6002.php?task=View';
                        } else {
                            window.location='pn6001.php?task=View&path='+path+'&kd_agenda='+jdata.dataid+'&kd_perihal_detil='+kd_perihal_detil;
                        }
                        
                        
                    } else {
                        alert(jdata.msg);
                    }
                    window.asyncPreload('filter', false);
                },
                complete: function(){
                    window.asyncPreload('filter', false);
                },
                error: function(){
                    alert("Terjadi kesalahan, coba beberapa saat lagi!");
                    window.asyncPreload('filter', false);
                }
            });
            window.asyncPreload('filter', false);
        }
  
    }
    

    function simpan(){

        $('#btn_simpan').prop('disabled',true);
        var path                = "<?=$_GET['path'];?>";
        var kd_perihal          = "<?=$_GET['kd_perihal'];?>";
        var kd_perihal_detil    = "<?=$_GET['kd_perihal_detil'];?>";
        var tb_keterangan       = $('#tb_keterangan').val();
        
        
		
		window.asyncPreload('filter', true);
		$.ajax({
			type: 'POST',
			url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_action.php?'+Math.random(),
			data: {
				TYPE				    : 'New',
				no_identitas	        : $('#no_identitas').val(),
                tb_kode_perihal         : "<?=$_GET['kd_perihal'];?>",			
                tb_kode_perihal_detil   : "<?=$_GET['kd_perihal_detil'];?>",
                keterangan              : tb_keterangan,
                tipe_submit             : 'SIMPAN_PROFIL'
			},
			success: function(data){
				
				var jdata = JSON.parse(data);	
				if (jdata.ret == 0){
                    window.parent.Ext.notify.msg('Penyimpanan data berhasil dengan kode agenda '+jdata.dataid+', session dilanjutkan...', jdata.msg);
                    window.location='pn6001.php?task=Edit&path='+path+'&kd_agenda='+jdata.dataid+'&kd_perihal_detil='+kd_perihal_detil;
					
				} else {
					alert(jdata.msg);
				}
				window.asyncPreload('filter', false);
			},
			complete: function(){
				window.asyncPreload('filter', false);
			},
			error: function(){
				alert("Terjadi kesalahan, coba beberapa saat lagi!");
				window.asyncPreload('filter', false);
			}
		});
	}

    function downloadFileSmile(path_url){

        let endPoint= "<?php echo $wsFileSmile ?>";
        preload(true);
        $.ajax({
            type: 'POST',
            url: "../ajax/pn6001_verifikasi_kpj_lain_action.php?"+Math.random(),
            data: {
                TYPE        : 'downloadFileSmile',
                path_url    : path_url
            },     
            success: function(data){     
                let jdata = JSON.parse(data);
                NewWindow(endPoint+"/krgBayar/downloadFileKB?data="+jdata.pathUrlEncrypt);
                preload(false);
            },
            complete: function(){
            preload(false);
            },
            error: function(){
            alert("Terjadi kesalahan, coba beberapa saat lagi!");
            preload(false);
            }
        });

    }

    function getBase64(file) {
        return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
    });
    }

    async function uploadFile(file){

        let image_file = await getBase64(file);
        
        let data = "<?php 

        function encrypt_decrypt2($action, $string)
        {
            /* =================================================
            * ENCRYPTION-DECRYPTION
            * =================================================
            * ENCRYPTION: encrypt_decrypt('encrypt', $string);
            * DECRYPTION: encrypt_decrypt('decrypt', $string) ;
            */
            $output = false;
            $encrypt_method = "AES-256-CBC";
            $secret_key = 'WS-SERVICE-KEY';
            $secret_iv = 'WS-SERVICE-VALUE';
            // hash
            $key = hash('sha256', $secret_key);
            // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
            $iv = substr(hash('sha256', $secret_iv), 0, 16);
            if ($action == 'encrypt') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
            } else {
            if ($action == 'decrypt') {
                $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
            }
            }
            return $output;
        }

        $json_spec = array(
            "kodeDokumen" => "",
            "bucketName" => "smile",
            "folderName" => "koreksiVerifKpjLain/".$gs_kantor_aktif.'/'.date("Ym"),
            "idDokumen" => ""
            );
        
        echo encrypt_decrypt2('encrypt',str_replace("\/", "/", json_encode($json_spec))) 		

        ?>";

    let spec = 
    {
        "data" : data,
        "fileBase64" : image_file	
    };


    return await doUploadDokumen(spec);

    }

    async function doUploadDokumen(spec){
        let end_point= "<?php echo $wsFileSmile ?>";
        const response = await fetch(end_point+"/krgBayar/uploadFileKB", {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json'
                                            },
                                            body: JSON.stringify({
                                                    data : spec
                                                }) 
                                        });
        const data = await response.json();
        return data;
    }

    function submit_koreksi(){

        if($('#tb_keterangan').val()==''){
            alert('Hasil Verifikasi tidak boleh kosong');
            return;
        }
        
        if('<?=$_GET['kd_agenda'];?>' && '<?=$total_rows;?>' < 1){
            alert('Lakukan klik tombol TAMBAH untuk penambahan kartu kepesertaan '+'<?=$ls_nama_jenis_agenda_detil;?>' + ' pada "Daftar Kartu Kepesertaan Tidak Diproses" atau klik tombol BATAL untuk membatalkan agenda koreksi');
            return; 
        }

        if('<?=$total_rows;?>' < 1){
            alert('"Daftar Kartu Kepesertaan Tidak Diproses" tidak boleh kosong.');
            return;
        }
        
        if('<?=$total_rows;?>' >= '<?=$total_rows_daftar_kartu;?>'){
            alert('"Daftar Kartu Kepesertaan Tidak Diproses" tidak boleh dipilih semua.');
            return;
        }


        if($('#dok_pernyataan').val() == ''){
            alert('Upload dokumen pendukung.')
        } else {            

            let konfirmasi="Apakah anda yakin akan menyimpan agenda ini?";

            confirmation("Konfirmasi", konfirmasi,
                async function () {
                    await prose_upload();
                    await proses_upload_db();
                    window.location='pn6001.php?task=View&path='+"<?=$_GET['path'];?>"+'&kd_agenda='+"<?=$_GET['kd_agenda'];?>"+'&kd_perihal_detil='+"<?=$_GET['kd_perihal_detil'];?>";
            }, 
            setTimeout(function(){}, 1000));



        } 
    }
    
    async function prose_upload(){
        return new Promise(async function (resolve, reject) {
            var total_rows_dok = '<?=$total_rows_dok;?>';
            for(i=1; i<=total_rows_dok; i++){
                
                let file_dokumen = $('#dok_pernyataan'+i).val();
                let file = document.getElementById("dok_pernyataan"+i).files[0];

                let upload="";
                    
                if(file){
                    let filesize = file.size;
                    let maxsize = 6097152;
                    if(filesize > maxsize){
                        return alert('Maximal file upload 6 MB!');
                    }else{
                        preload(true);
                        upload=await uploadFile(file);
                        preload(false);
                        if(upload.ret=="0"){
                            path_url +=upload.data.path+'###';
                            mime_type +=upload.data.mimeType+'###';
                                    
                        } 	
                    }  
                }

                if(parseInt(upload.ret) == -1 ){
                    return alert('Terjadi kesalahan, gagal mengupload file.');
                }      

            }
            resolve(true);
                    
        });
    }

    async function proses_upload_db(){
        return new Promise(async function (resolve, reject) {

            var total_rows_dok = '<?=$total_rows_dok;?>';
                    
            for(i=1; i<=total_rows_dok; i++){
                list_kode_tk += $('#kode_tk_dok'+i).val()+'###';
            } 

                var path = "<?=$_GET['path'];?>";
                var kd_perihal = "<?=$_GET['kd_perihal'];?>";
                var kd_perihal_detil = "<?=$_GET['kd_perihal_detil'];?>";
                    
                // window.asyncPreload('filter', true);
                preload(true);
                $.ajax({
                    type: 'POST',
                    url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_verifikasi_kpj_lain_action.php?'+Math.random(),
                    data: {
                        TYPE				    : 'SUBMIT',
                        no_identitas	        : $('#no_identitas').val(),
                        kode_agenda 	        : "<?=$_GET['kd_agenda'];?>",	
                        tb_kode_perihal         : "PP03",		
                        tb_kode_perihal_detil   : "<?=$_GET['kd_perihal_detil'];?>",
                        kode_tk_dok             : list_kode_tk,
                        i_mime_tipe             : mime_type,
                        i_path_url              : path_url,
                        tipe_submit             : 'SUBMIT_TINDAK_LANJUT'
                    },
                    success: function(data){
                            
                        var jdata = JSON.parse(data);	
                        if (jdata.ret == 0){
                            window.parent.Ext.notify.msg('Penyimpanan data berhasil dengan kode agenda '+jdata.dataid+', session dilanjutkan...', jdata.msg);                    
                        } else {
                            alert(jdata.msg);
                        }
                        preload(false);
                        // window.asyncPreload('filter', false);
                        resolve(true);
                    },
                    complete: function(){
                        preload(false);
                        // window.asyncPreload('filter', false);
                    },
                    error: function(){
                        alert("Terjadi kesalahan, coba beberapa saat lagi!");
                        // window.asyncPreload('filter', false);
                        preload(false);
                                
                        reject(false);
                    }
                });
        });
    }

    function submit_setuju(){
        var path                = "<?=$_GET['path'];?>";
        var kd_perihal          = "<?=$_GET['kd_perihal'];?>";
        var kd_perihal_detil    = "<?=$_GET['kd_perihal_detil'];?>";
        var tb_keterangan       = $('#tb_keterangan').val();
		
		window.asyncPreload('filter', true);
		$.ajax({
			type: 'POST',
			url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_verifikasi_kpj_lain_action.php?'+Math.random(),
			data: {
				TYPE				    : 'APPROVE',
                kode_agenda 	        : "<?=$_GET['kd_agenda'];?>",	
                tb_kode_perihal         : kd_perihal,			
                tb_kode_perihal_detil   : kd_perihal_detil,
                keterangan              : tb_keterangan,
                tipe_submit             : 'APPROVE_TINDAK_LANJUT'
			},
			success: function(data){
				
				var jdata = JSON.parse(data);	
				if (jdata.ret == 0){
                    window.parent.Ext.notify.msg('Penyimpanan data berhasil dengan kode agenda '+jdata.dataid+', session dilanjutkan...', jdata.msg);
                    window.location='pn6001.php?task=View&path='+path+'&kd_agenda='+jdata.dataid+'&kd_perihal_detil='+kd_perihal_detil;
					
				} else {
					alert(jdata.msg);
				}
				window.asyncPreload('filter', false);
			},
			complete: function(){
				window.asyncPreload('filter', false);
			},
			error: function(){
				alert("Terjadi kesalahan, coba beberapa saat lagi!");
				window.asyncPreload('filter', false);
			}
		});
    }

    function submit_tolak(){
		var tanya = confirm("Apakah anda yakin akan membatalkan agenda ini ?");
        var tb_keterangan       = $('#tb_keterangan').val();
        var path                = "<?=$_GET['path'];?>";
        var kd_perihal          = "<?=$_GET['kd_perihal'];?>";
        var kd_perihal_detil    = "<?=$_GET['kd_perihal_detil'];?>";
 
        if(tanya === true) {
            window.asyncPreload('filter', true);
            $.ajax({
                type: 'POST',
                url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_verifikasi_kpj_lain_action.php?'+Math.random(),
                data: {
                    TYPE				    : 'BATAL',
                    kode_agenda 	        : '<?=$_GET['kd_agenda'];?>',		
                    keterangan              : tb_keterangan,
                    tipe_submit             : 'TOLAK_TINDAK_LANJUT'
                },
                success: function(data){
                    
                    var jdata = JSON.parse(data);	
                    if (jdata.ret == 0){
                        window.parent.Ext.notify.msg('Penyimpanan data berhasil dengan kode agenda '+jdata.dataid+', session dilanjutkan...', jdata.msg);
                        window.location='pn6001.php?task=View&path='+path+'&kd_agenda='+jdata.dataid+'&kd_perihal_detil='+kd_perihal_detil;
                        
                        
                    } else {
                        alert(jdata.msg);
                    }
                    window.asyncPreload('filter', false);
                },
                complete: function(){
                    window.asyncPreload('filter', false);
                },
                error: function(){
                    alert("Terjadi kesalahan, coba beberapa saat lagi!");
                    window.asyncPreload('filter', false);
                }
            });
            window.asyncPreload('filter', false);
        }
  
    }

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


    function fl_js_get_lov_by(lov) { 
        if (lov == "NO_IDENTITAS") {
            NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/form/pn6001_verifikasi_kpj_lain_lov.php?','',800,500,1);
        } 
    }

    function fl_js_get_lov_kartu(lov,kode_agenda) { 
        if (lov != "") {
            var params = "nik="+lov+"&kode_agenda="+kode_agenda;
            NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/form/pn6001_verifikasi_kpj_lain_lov_tambah_kartu.php?'+params,'',800,500,1);
        } 
    }

    function fl_ls_get_lov_by_selected(lov, obj) {
        if (lov == "NOMOR_IDENTITAS") {
            if (obj.NOMOR_IDENTITAS != '') {
                var new_location = window.location.href.replace("#", "") + '&kdf=' + obj.NOMOR_IDENTITAS;
                window.location.replace(new_location);

            } else {
                alert("Data tidak ditemukan !");
            }
        } 

        if (lov == "TAMBAH_KARTU") {
            if (obj.KODE_TK != '') {
                tambah_kartu(obj.KODE_TK);

            } else {
                alert("Data tidak ditemukan !");
            }
        } 
    }

</script>   