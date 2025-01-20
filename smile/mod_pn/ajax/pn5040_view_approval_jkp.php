<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Input Agenda Klaim JKP
Hist: - 28/10/2021	 						 
-----------------------------------------------------------------------------*/
$sql = "select
            kode_manfaat, no_urut, to_char(a.tgl_saldo_awaltahun,'dd/mm/yyyy') tgl_saldo_awaltahun,
            a.nom_saldo_awaltahun,
            to_char(a.tgl_pengembangan_estimasi,'dd/mm/yyyy') tgl_pengembangan_estimasi,
            a.nom_pengembangan_estimasi, a.nom_manfaat_sudahdiambil, a.pengambilan_ke
        from sijstk.pn_klaim_manfaat_detil a
        where kode_klaim = :p_kode_klaim
        and kode_manfaat = :p_kode_manfaat
        and no_urut = :p_no_urut ";
$proc = $DB->parse($sql);
$pram_bv = [':p_kode_klaim' => $ls_kode_klaim,':p_kode_manfaat' => '46',':p_no_urut' => 1];
foreach ($param_bv as $key => $value) {
  oci_bind_by_name($proc, $key, $param_bv[$key]);
}
$DB->execute();
$row = $DB->nextrow();
$ls_jkpinput_kode_manfaat 	 				 		= $row["KODE_MANFAAT"];
$ls_jkpinput_no_urut 	 				 					= $row["NO_URUT"];
$ld_jhtinput_tgl_saldo_awaltahun 				= $row["TGL_SALDO_AWALTAHUN"];
$ln_jhtinput_nom_saldo_awaltahun 	 			= $row["NOM_SALDO_AWALTAHUN"];
$ld_jhtinput_tgl_pengembangan_estimasi 	= $row["TGL_PENGEMBANGAN_ESTIMASI"];
$ln_jhtinput_nom_pengembangan_estimasi 	= $row["NOM_PENGEMBANGAN_ESTIMASI"];
$ln_jhtinput_nom_manfaat_sudahdiambil 	= $row["NOM_MANFAAT_SUDAHDIAMBIL"];	
$ln_jhtinput_pengambilan_ke 						= $row["PENGAMBILAN_KE"];	 	 

$sql = "select 
            a.kode_klaim, a.kode_tipe_penerima, a.kode_hubungan, a.ket_hubungan_lainnya, a.no_urut_keluarga, 
            a.nomor_identitas, a.nama_pemohon, a.tempat_lahir, to_char(tgl_lahir,'dd/mm/yyyy') tgl_lahir, a.jenis_kelamin,a.golongan_darah, 
            a.alamat, a.rt, a.rw, 
            a.kode_kelurahan, (select nama_kelurahan from sijstk.ms_kelurahan where kode_kelurahan = a.kode_kelurahan and kode_kecamatan=a.kode_kecamatan and kode_pos =a.kode_pos) nama_kelurahan,
            a.kode_kecamatan, (select nama_kecamatan from sijstk.ms_kecamatan where kode_kecamatan = a.kode_kecamatan) nama_kecamatan,
            a.kode_kabupaten, (select nama_kabupaten from sijstk.ms_kabupaten where kode_kabupaten = a.kode_kabupaten) nama_kabupaten,
            a.kode_pos, a.telepon_area, a.telepon, 
            a.telepon_ext, a.handphone, a.email, a.npwp
        from sijstk.pn_klaim_penerima_manfaat a
        where kode_klaim = :p_kode_klaim
        and exists
        (
            select null from sijstk.pn_klaim_manfaat_detil
            where kode_klaim = a.kode_klaim
            and kode_tipe_penerima = a.kode_tipe_penerima
            and kode_manfaat = :p_jkpinput_kode_manfaat    
        )
        and rownum = 1";
$param_bv = [':p_kode_klaim' => $ls_kode_klaim, ':p_jkpinput_kode_manfaat' => $ls_jkpinput_kode_manfaat];
$proc = $DB->parse($sql);
foreach ($param_bv as $key => $value) {
  oci_bind_by_name($proc, $key, $param_bv[$key]);
}
$DB->execute();
$row = $DB->nextrow();
$ls_jkpinput_kode_tipe_penerima 	= $row["KODE_TIPE_PENERIMA"];
$ls_jkpinput_kode_hubungan 				= $row["KODE_HUBUNGAN"];
$ls_jkpinput_ket_hubungan_lainnya	= $row["KET_HUBUNGAN_LAINNYA"];
$ls_jkpinput_no_urut_keluarga 		= $row["NO_URUT_KELUARGA"];
$ls_jkpinput_nomor_identitas 			= $row["NOMOR_IDENTITAS"];
$ls_jkpinput_nama_pemohon 				= $row["NAMA_PEMOHON"];
$ls_jkpinput_tempat_lahir 				= $row["TEMPAT_LAHIR"];
$ld_jhtinput_tgl_lahir 						= $row["TGL_LAHIR"];
$ls_jkpinput_jenis_kelamin 				= $row["JENIS_KELAMIN"];
$ls_jkpinput_golongan_darah				= $row["GOLONGAN_DARAH"];
$ls_jkpinput_alamat 							= $row["ALAMAT"];
$ls_jkpinput_rt 									= $row["RT"];
$ls_jkpinput_rw 									= $row["RW"];
$ls_jkpinput_kode_kelurahan 			= $row["KODE_KELURAHAN"];
$ls_jkpinput_nama_kelurahan 			= $row["NAMA_KELURAHAN"];
$ls_jkpinput_kode_kecamatan 			= $row["KODE_KECAMATAN"];
$ls_jkpinput_nama_kecamatan 			= $row["NAMA_KECAMATAN"];
$ls_jkpinput_kode_kabupaten 			= $row["KODE_KABUPATEN"];
$ls_jkpinput_nama_kabupaten 			= $row["NAMA_KABUPATEN"];
$ls_jkpinput_kode_pos 						= $row["KODE_POS"];
$ls_jkpinput_telepon_area 				= $row["TELEPON_AREA"];
$ls_jkpinput_telepon 							= $row["TELEPON"];
$ls_jkpinput_telepon_ext 					= $row["TELEPON_EXT"];
$ls_jkpinput_handphone 						= $row["HANDPHONE"];
$ls_jkpinput_email 								= $row["EMAIL"];
$ls_jkpinput_npwp 								= $row["NPWP"];

$sql_phkjkp = "SELECT A.KODE_KLAIM,
                      A.STATUS_PKWT,
                      TO_CHAR(A.TGL_MULAI_PERJANJIAN_KERJA,'DD-MM-YYYY') TGL_MULAI_PERJANJIAN_KERJA,
                      TO_CHAR(A.TGL_AKHIR_PERJANJIAN_KERJA,'DD-MM-YYYY') TGL_AKHIR_PERJANJIAN_KERJA, 
                      TO_CHAR(A.BLTH_AWAL_KEPESERTAAN_JKP,'MM-YYYY') BLTH_AWAL_KEPESERTAAN_JKP, 
                      TO_CHAR(A.BLTH_NA_JKP,'MM-YYYY') BLTH_NA_JKP,   
                      TO_CHAR(A.TGL_PHK,'DD-MM-YYYY') TGL_PHK, 
                      A.KODE_TIPE_KASUS_PHK,
                      (SELECT B.KETERANGAN
                        FROM SIAPKERJA.SK_MS_LOOKUP@TO_EC B
                        WHERE B.KODE = A.KODE_TIPE_KASUS_PHK)
                        KASUS_PHK,
                      A.KODE_SEBAB_PHK,
                      (SELECT B.NAMA_SEBAB_PHK
                        FROM SIAPKERJA.SK_KODE_SEBAB_PHK@TO_EC B
                        WHERE B.KODE_SEBAB_PHK = A.KODE_SEBAB_PHK)
                        SEBAB_PHK,
                      A.TAHAP_JKP,
                      A.BULAN_MANFAAT_KE,
                      A.FLAG_ENAM_BULAN_BERTURUT,
                      A.KODE_PROFILE_TK_JKP
                FROM PN.PN_KLAIM_MANFAAT_DETIL_PHKJKP A
              WHERE A.KODE_KLAIM = :p_kode_klaim";
$proc = $DB->parse($sql_phkjkp);
oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim);
$DB->execute();
$row_jkp = $DB->nextrow();

$ls_jkpkode_klaim                 = $row_jkp['KODE_KLAIM'];
$ls_jkpstatus_pkwt                = $row_jkp['STATUS_PKWT'];
$ls_jkptgl_mulai_perjanjian_kerja = $row_jkp['TGL_MULAI_PERJANJIAN_KERJA'];
$ls_jkptgl_akhir_perjanjian_kerja = $row_jkp['TGL_AKHIR_PERJANJIAN_KERJA'];
$ls_jkpblth_awal_keps_jkp         = $row_jkp['BLTH_AWAL_KEPESERTAAN_JKP'];
$ls_jkpblth_na_jkp                = $row_jkp['BLTH_NA_JKP'];
$ls_jkptgl_phk                    = $row_jkp['TGL_PHK'];
$ls_jkpkode_tipe_kasus_phk        = $row_jkp['KODE_TIPE_KASUS_PHK'];
$ls_jkpkasus_phk                  = $row_jkp['KASUS_PHK'];
$ls_jkpkode_sebab_phk             = $row_jkp['KODE_SEBAB_PHK'];
$ls_jkpsebab_phk                  = $row_jkp['SEBAB_PHK'];
$ls_jkptahap_jkp                  = $row_jkp['TAHAP_JKP'];
$ls_jkpbulan_manfaat_ke           = $row_jkp['BULAN_MANFAAT_KE'];
$ls_jkpflag_enam_bulan_berturut   = $row_jkp['FLAG_ENAM_BULAN_BERTURUT'];
$ls_jkpkode_profile_tk_jkp        = $row_jkp['KODE_PROFILE_TK_JKP'];

?>
<script language="JavaScript">    
  $(document).ready(function(){

		setTimeout(function(){ filter(); }, 1000);
						
	});

  function fl_js_val_numeric_jht(v_field_id)
  {
    var c_val = window.document.getElementById(v_field_id).value;
    var number=/^[0-9]+$/;
    
    if ((c_val!='') && (!c_val.match(number)))
    {
      document.getElementById(v_field_id).value = '';				 
      window.document.getElementById(v_field_id).focus();
      alert("Harus berisikan angka, tidak boleh alphabet atau karakter lainnya...! ");         
      return false; 				 
    }		
  }
	
  function fl_js_jhtinput_kode_tipe_penerima() 
  { 
		var	v_kode_tipe_penerima = window.document.getElementById('jhtinput_kode_tipe_penerima').value;
							
  	if (v_kode_tipe_penerima =="AW") //ahli waris ----------------------------
    {
      window.document.getElementById("span_jhtinput_kode_hubungan").style.display = 'block';	
    }else
    {
      window.document.getElementById("span_jhtinput_kode_hubungan").style.display = 'none';
      window.document.getElementById("jhtinput_kode_hubungan").value = "T";	
			window.document.getElementById("jhtinput_ket_hubungan_lainnya").value = "";
			window.document.getElementById("jhtinput_no_urut_keluarga").value = "";	
    }
  }
	
  function fl_js_jhtinput_ahliwaris_lain() 
  { 
		var	v_kode_tipe_penerima = window.document.getElementById('jhtinput_kode_tipe_penerima').value;
		var	v_kode_hubungan = window.document.getElementById('jhtinput_kode_hubungan').value;
    
    if (v_kode_tipe_penerima =="AW" && v_kode_hubungan=="0") //Ahli Waris Lainnya ---------
    {
		 	window.document.getElementById("span_jhtinput_ket_hubungan_lainnya").style.display = 'block'; 
    }else
    {
      window.document.getElementById("span_jhtinput_ket_hubungan_lainnya").style.display = 'none'; 	
    } 	
  }						

  function getValue(val){
		return val == null ? '' : val;
	}

  function filter(val = 0){
		var pages 				    = new Number($("#pages").val());
		var page 				      = new Number($("#page").val());
		var page_item 			  = $("#page_item").val();
    var kode_klaim        = '<?=$ls_kode_klaim?>';
	 

		if (val == 1) {
			page = (page + 1) > pages ? pages : (page + 1);
		} else if (val == 2) {
			page = pages;
		} else if (val == -1) {
			page = (page - 1) <= 0 ? 1 : (page - 1);
		} else if (val == -2){
			page = 1;
		}else if(val == 0){
			page=1;
		}

		$("#page").val(page); 
		
		preload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5040_action_view_jkp.php?"+Math.random(),
			data: {
				types			    : 'query',
				page			    : page,
				page_item		  : page_item,
        kode_klaim    : kode_klaim
			},
			success: function(data){
				var jdata = JSON.parse(data);	
				if (jdata.ret == 1){
					var html_data = "";
					for(var i = 0; i < jdata.data.length; i++){
						html_data += "<tr bgcolor=#"+(i%2 ? 'f3f3f3' : 'ffffff')+">";						
						html_data += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].KPJ) + '</td>';
						html_data += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].NPP) + '</td>';
						html_data += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].KODE_DIVISI) + '</td>';
						html_data += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].BLTH_IURAN) + '</td>';							
						html_data += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].TGL_BAYAR) + '</td>';							
						html_data += '<td style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;"> Rp  ' + getValue(jdata.data[i].NOM_IUR_REKOMPOSISI_JKK) + '</td>';						
						html_data += '<td style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;"> Rp  ' + getValue(jdata.data[i].NOM_IUR_REKOMPOSISI_JKM) + '</td>';						
						html_data += '<td style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;"> Rp  ' + getValue(jdata.data[i].NOM_SUBSIDI_PEMERINTAH) + '</td>';						
						html_data += '<td style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;"> Rp  ' + getValue(jdata.data[i].NOM_IUR_TOTAL) + '</td>';				
						html_data += '</tr>';
					}

          if (html_data == "") {
						html_data += '<tr class="nohover-color">';
						html_data += '<td colspan="9" style="text-align: center;">-- Data tidak ditemukan --</td>';
						html_data += '</tr>';
					}

					$("#data_list").html(html_data);
					
					// load info halaman
					$("#pages").val(jdata.pages);
					$("#span_info_halaman").html('dari ' + jdata.pages + ' halaman');

					// load info item
					$("#span_info_item").html('Menampilkan item ke ' + jdata.start + ' sampai dengan ' + jdata.end + ' dari ' + jdata.recordsTotal + ' items');
					$("#hdn_total_records").val(jdata.recordsTotal);

          $("#span_info_jml_iur").html(''+jdata.recordsTotal+''); 

				  preload(false);
				} else {
					alert(jdata.msg);
				}
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

    function persen(val){
      var a = +val;
      return a*100+'%';
    }


	}
</script>	

<script type="text/javascript">
	var curr_jhtinput_kode_hubungan =<?php echo ($ls_jkpinput_kode_hubungan=='') ? 'false' : "'".$ls_jkpinput_kode_hubungan."'"; ?>;
</script>	
		
<div id="formKiri" style="width:962px;">
	<table width="962px;" border="0">
		<tr>
			<td width="50%" valign="top"">
				<fieldset style="height:395px;"><legend><b><i><font color="#009999">Manfaat JKP :</font></i></b></legend>
            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">Status PKWT &nbsp;</label>
                <input type="text" id="status_pkwt" name="status_pkwt" value="<?=$ls_jkpstatus_pkwt;?>" maxlength="20" readonly class="disabled" style="width:270px;">                					
            </div>																																																				
            <div class="clear"></div>

            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">Tanggal Mulai Perjanjian Kerja &nbsp;</label>
                <input type="text" id="tgl_mulai_perjanjian_kerja" name="tgl_mulai_perjanjian_kerja" value="<?=$ls_jkptgl_mulai_perjanjian_kerja;?>" maxlength="20" readonly class="disabled" style="width:270px;">                					
            </div>	
            <div class="clear"></div>

            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">Tanggal Berakhir Perjanjian Kerja &nbsp;</label>
                <input type="text" id="tgl_akhir_perjanjian_kerja" name="tgl_akhir_perjanjian_kerja" value="<?=$ls_jkptgl_akhir_perjanjian_kerja;?>" maxlength="20" readonly class="disabled" style="width:270px;">                					
            </div>	
            <div class="clear"></div>

            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">BLTH Awal Kepesertaan JKP &nbsp;</label>
                <input type="text" id="status_layak_klaim" name="blth_awal_keps_jkp" value="<?=$ls_jkpblth_awal_keps_jkp;?>" maxlength="20" readonly class="disabled" style="width:270px;">                					
            </div>	
            <div class="clear"></div>

            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">BLTH Non Aktif JKP &nbsp;</label>
                <input type="text" id="blth_na_jkp" name="blth_na_jkp" value="<?=$ls_jkpblth_na_jkp;?>" maxlength="20" readonly class="disabled" style="width:270px;">                					
            </div>	
            <div class="clear"></div> 
          
            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">Tanggal PHK &nbsp;</label>
                <input type="text" id="tgl_phk" name="tgl_phk" value="<?=$ls_jkptgl_phk;?>" maxlength="20" readonly class="disabled" style="width:270px;">                					
            </div>	
            <div class="clear"></div>

            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">Tipe Kasus PHK &nbsp;</label>
                <input type="text" id="tipe_kasus_phk" name="tipe_kasus_phk" value="<?=$ls_jkpkode_tipe_kasus_phk.' - '.$ls_jkpkasus_phk;?>" maxlength="20" readonly class="disabled" style="width:270px;">                					
            </div>	
            <div class="clear"></div>

            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">Sebab PHK &nbsp;</label>
                <input type="text" id="sebab_klaim" name="sebab_klaim" value="<?=$ls_jkpkode_sebab_phk.' - '.$ls_jkpsebab_phk;?>" maxlength="20" readonly class="disabled" style="width:270px;">                					
            </div>	
            <div class="clear"></div>

            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">Tahap JKP &nbsp;</label>
                <input type="text" id="tahap_jkp" name="tahap_jkp" value="<?=$ls_jkptahap_jkp;?>" maxlength="20" readonly class="disabled" style="width:270px;">                					
            </div>	
            <div class="clear"></div>

            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">Bulan Manfaat &nbsp;</label>
                <input type="text" id="bulan_manfaat_ke" name="bulan_manfaat_ke" value="<?=$ls_jkpbulan_manfaat_ke;?>" maxlength="20" readonly class="disabled" style="width:270px;">                					
            </div>	
            <div class="clear"></div>
																									 
				</fieldset>	
			</td>

			<td width="50%" valign="top">
				<fieldset style="height:395px;" ><legend><b><i><font color="#009999">Penerima Manfaat :</font></i></b></legend>
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Tipe Penerima &nbsp;*</label>
      			<select size="1" id="jhtinput_kode_tipe_penerima" name="jhtinput_kode_tipe_penerima" value="<?=$ls_jkpinput_kode_tipe_penerima;?>" tabindex="1" class="select_format" onChange="fl_js_jhtinput_kode_tipe_penerima();" <?=($ls_status_submit_agenda =="Y")? " style=\"width:275px;background-color:#F5F5F5\"" : " style=\"width:275px;background-color:#ffff99\"";?>>
            <option value="">- Tipe Penerima --</option>
            <?
            $param_bv = [];
      			if ($ls_status_submit_agenda=="T")
      			{
              $param_bv[':p_kode_manfaat'] = $ls_jkpinput_kode_manfaat;
              $param_bv[':p_status'] = 'T';
              $sql = "select a.kode_tipe_penerima, b.nama_tipe_penerima from sijstk.pn_kode_manfaat_eligibility a, sijstk.pn_kode_tipe_penerima b ".
                     "where a.kode_tipe_penerima = b.kode_tipe_penerima ".
                     "and a.kode_manfaat = :p_kode_manfaat ".
                     "and nvl(a.status_nonaktif,'T')= :p_status ".
                     "order by b.no_urut";			
      			}else
      			{
              $param_bv[':p_kode_manfaat'] = $ls_jkpinput_kode_manfaat;
              $param_bv[':p_kode_tipe_penerima'] = $ls_jkpinput_kode_tipe_penerima;
              $sql = "select a.kode_tipe_penerima, b.nama_tipe_penerima from sijstk.pn_kode_manfaat_eligibility a, sijstk.pn_kode_tipe_penerima b ".
                     "where a.kode_tipe_penerima = b.kode_tipe_penerima ".
                     "and a.kode_manfaat = :p_kode_manfaat ".
      							 "and a.kode_tipe_penerima = :p_kode_tipe_penerima";
      			}			 
            $proc = $DB->parse($sql);
            foreach ($param_bv as $key => $value) {
             oci_bind_by_name($proc, $key, $param_bv[$key]);
            }           
            $DB->execute();
            while($row = $DB->nextrow())
            {
            echo "<option ";
            if (($row["KODE_TIPE_PENERIMA"]==$ls_jkpinput_kode_tipe_penerima && strlen($row["KODE_TIPE_PENERIMA"])==strlen($ls_jkpinput_kode_tipe_penerima))) { echo " selected"; }
            echo " value=\"".$row["KODE_TIPE_PENERIMA"]."\">".$row["NAMA_TIPE_PENERIMA"]."</option>";
            }
            ?>
            </select>
      			<input type="hidden" id="jhtinput_kode_tipe_penerima_old" name="jhtinput_kode_tipe_penerima_old" value="<?=$ls_jkpinput_kode_tipe_penerima;?>">
      			<input type="hidden" id="jhtinput_kode_manfaat" name="jhtinput_kode_manfaat" value="<?=$ls_jkpinput_kode_manfaat;?>">
      			<input type="hidden" id="jhtinput_no_urut" name="jhtinput_no_urut" value="<?=$ls_jkpinput_no_urut;?>"> 
      		</div>		    																																				
        	<div class="clear"></div>
					
          <span id="span_jhtinput_kode_hubungan" style="display:none;">
            <div class="form-row_kiri">
            <label style = "text-align:right;">Ahli Waris *</label>		 	    				
        			<select size="1" id="jhtinput_kode_hubungan" name="jhtinput_kode_hubungan" value="<?=$ls_jkpinput_kode_hubungan;?>" tabindex="2" class="select_format" onChange="fl_js_jhtinput_ahliwaris_lain(); f_ajax_jhtinput_val_kode_hubungan();" <?=($ls_status_submit_agenda =="Y")? " style=\"width:275px;background-color:#F5F5F5\"" : " style=\"width:275px;background-color:#ffff99\"";?>>
              <option value="">-- Pilih --</option>
              <?
              $param_bv = [];
        			if ($ls_status_submit_agenda=="T")
        			{
                $param_bv[':p_status'] = 'Y';
                $param_bv[':p_kode_tk'] = $ls_kode_tk;
                $param_bv['p_1'] = 'L';
                $param_bv['p_2'] = 'S';
                $param_bv['p_3'] = 'P';
                $param_bv['p_4'] = 'I';
                $sql = "select kode_hubungan,nama_hubungan from sijstk.kn_kode_hubungan_tk where nvl(aktif,'T') = :p_status and kode_hubungan <> 'T' ".
      							 	 "and kode_hubungan <> decode((select jenis_kelamin from kn.vw_kn_tk where kode_tk=p_kode_tk and rownum=1),:p_1,:p_2,:p_3,:p_4,'') ".
      								 "order by urutan";			
        			}else
        			{
                $param_bv[':p_kode_hubungan'] = $ls_jkpinput_kode_hubungan;
                $sql = "select kode_hubungan,nama_hubungan from sijstk.kn_kode_hubungan_tk where kode_hubungan=:p_kode_hubungan";
        			}
              $proc = $DB->parse($sql);
              foreach ($param_bv as $key => $value) {
               oci_bind_by_name($proc, $key, $param_bv[$key]);
              }
              $DB->execute();
              while($row = $DB->nextrow())
              {
                echo "<option ";
                if ($row["KODE_HUBUNGAN"]==$ls_jkpinput_kode_hubungan && strlen($ls_jkpinput_kode_hubungan)==strlen($row["KODE_HUBUNGAN"])){ echo " selected"; }
                echo " value=\"".$row["KODE_HUBUNGAN"]."\">".$row["NAMA_HUBUNGAN"]."</option>";
              }
              ?>
              </select>
      				<input type="hidden" id="jhtinput_no_urut_keluarga" name="jhtinput_no_urut_keluarga" value="<?=$ls_jkpinput_no_urut_keluarga;?>" size="2" maxlength="3" readonly class="disabled">        				
            </div>																																									
            <div class="clear"></div>
          </span>
      
      		<span id="span_jhtinput_ket_hubungan_lainnya" style="display:none;">
            <div class="form-row_kiri">
            <label style = "text-align:right;">&nbsp; *</label>		
      				<input type="text" id="jhtinput_ket_hubungan_lainnya" name="jhtinput_ket_hubungan_lainnya" value="<?=$ls_jkpinput_ket_hubungan_lainnya;?>" tabindex="3" placeholder="-- isikan jenis kekerabatan lainya -- " maxlength="300" <?=($ls_status_submit_agenda =="Y")? " style=\"width:269px;background-color:#F5F5F5\"" : " style=\"width:269px;background-color:#ffff99\"";?>>
            </div>																																									
            <div class="clear"></div>			
      		</span>				
					
      		<div class="form-row_kiri">
          <label style = "text-align:right;">Nama &nbsp;&nbsp;*</label>
      			<input type="text" id="jhtinput_nama_pemohon" name="jhtinput_nama_pemohon" value="<?=$ls_jkpinput_nama_pemohon;?>" tabindex="4" maxlength="100" <?=($ls_status_submit_agenda =="Y")? " style=\"width:270px;\" readonly class=disabled" : " style=\"width:270px;background-color:#ffff99\"";?>>
          </div>	
					<div class="clear"></div>

          <div class="form-row_kiri">
          <label style = "text-align:right;">Tempat & Tgl Lahir &nbsp; *</label>	    				
            <input type="text" id="jhtinput_tempat_lahir" name="jhtinput_tempat_lahir" value="<?=$ls_jkpinput_tempat_lahir;?>" tabindex="5" maxlength="50" <?=($ls_status_submit_agenda =="Y")? " style=\"width:164px;\" readonly class=disabled" : " style=\"width:164px;background-color:#ffff99\"";?>>
            <input type="text" id="jhtinput_tgl_lahir" name="jhtinput_tgl_lahir" value="<?=$ld_jhtinput_tgl_lahir;?>" tabindex="6" maxlength="10" onblur="convert_date(jhtinput_tgl_lahir);" <?=($ls_status_submit_agenda =="Y")? " style=\"width:78px;\" readonly class=disabled" : " style=\"width:78px;background-color:#ffff99\"";?>>
            <?
            if ($ls_status_submit_agenda=="T")
            {
            ?>      								
            <input id="btn_jhtinput_tgl_lahir" type="image" align="top" onclick="return showCalendar('jhtinput_tgl_lahir', 'dd-mm-y');" src="../../images/calendar.gif" />							
            <?
            }
            ?> 			
          </div>																													
          <div class="clear"></div>

          <div class="form-row_kiri">
          <label style = "text-align:right;">Jenis Kelamin *</label>		 	    				
      			<select size="1" id="jhtinput_jenis_kelamin" name="jhtinput_jenis_kelamin" value="<?=$ls_jkpinput_jenis_kelamin;?>" tabindex="7" class="select_format" <?=($ls_status_submit_agenda =="Y")? " style=\"width:105px;background-color:#F5F5F5\"" : " style=\"width:105px;background-color:#ffff99\"";?>>
            <option value="">-- Pilih --</option>
            <?
            $param_bv = [];
      			if ($ls_status_submit_agenda=="T")
      			{
              $param_bv[':p_tipe'] = 'JNSKELAMIN';
              $param_bv[':p_status'] = 'Y';
             	$sql = "select kode, keterangan from sijstk.ms_lookup where tipe = :p_tipe and nvl(aktif,'T')=:p_status order by seq";			
      			}else
      			{
              $param_bv['p_tipe'] = 'JNSKELAMIN';
              $param_bv['p_kode'] = $ls_jkpinput_jenis_kelamin;
              $sql = "select kode, keterangan from sijstk.ms_lookup where tipe = :p_tipe and kode=:p_kode";
      			}
            $proc = $DB->parse($sql);
            foreach ($param_bv as $key => $value) {
             oci_bind_by_name($proc, $key, $param_bv[$key]);
            }           
            $DB->execute();
            while($row = $DB->nextrow())
            {
              echo "<option ";
              if ($row["KODE"]==$ls_jkpinput_jenis_kelamin && strlen($ls_jkpinput_jenis_kelamin)==strlen($row["KODE"])){ echo " selected"; }
              echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
            }
            ?>
            </select>
      			&nbsp;
          	Gol. Darah	 	    				
      			<select size="1" id="jhtinput_golongan_darah" name="jhtinput_golongan_darah" value="<?=$ls_jkpinput_golongan_darah;?>" tabindex="7" class="select_format" <?=($ls_status_submit_agenda =="Y")? " style=\"width:103px;background-color:#F5F5F5\"" : " style=\"width:103px;\"";?>>
            <option value="">-- Pilih --</option>
            <?
            $param_bv = [];
      			if ($ls_status_submit_agenda=="T")
      			{
              $param_bv[':p_tipe'] = 'GOLDARAH';
              $param_bv[':p_status'] = 'Y';
             	$sql = "select kode, keterangan from sijstk.ms_lookup where tipe = :p_tipe and nvl(aktif,'T')=:p_status order by seq";			
      			}else
      			{
              $param_bv[':p_tipe'] = 'GOLDARAH';
              $param_bv[':p_kode'] = $ls_jkpinput_jenis_kelamin;
              $sql = "select kode, keterangan from sijstk.ms_lookup where tipe = :p_tipe and kode=:p_kode";
      			}
            $proc = $DB->parse($sql);
            foreach ($param_bv as $key => $value) {
              oci_bind_by_name($proc, $key, $param_bv[$key]);
            }
            $DB->execute();
            while($row = $DB->nextrow())
            {
              echo "<option ";
              if ($row["KODE"]==$ls_jkpinput_golongan_darah && strlen($ls_jkpinput_golongan_darah)==strlen($row["KODE"])){ echo " selected"; }
              echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
            }
            ?>
            </select>			
      		</div>																																									
          <div class="clear"></div>

      		</br>
      												
          <div class="form-row_kiri">
          <label style = "text-align:right;">Alamat &nbsp;&nbsp;&nbsp;&nbsp;*</label>		 	    				
            <input type="text" id="jhtinput_alamat" name="jhtinput_alamat" value="<?=$ls_jkpinput_alamat;?>" tabindex="8" maxlength="300" <?=($ls_status_submit_agenda =="Y")? " style=\"width:269px;\" readonly class=disabled" : " style=\"width:269px;background-color:#ffff99\"";?>>
      		</div>																																										
        	<div class="clear"></div>
      
      
          <div class="form-row_kiri">
          <label style = "text-align:right;">RT/RW &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;</label>		 	    				
            <input type="text" id="jhtinput_rt" name="jhtinput_rt" value="<?=$ls_jkpinput_rt;?>" tabindex="9" maxlength="5" onblur="fl_js_val_numeric_jht('jhtinput_rt');" <?=($ls_status_submit_agenda =="Y")? " style=\"width:133px;\" readonly class=disabled" : " style=\"width:133px;\"";?>>
            /
            <input type="text" id="jhtinput_rw" name="jhtinput_rw" value="<?=$ls_jkpinput_rw;?>" tabindex="10" maxlength="5" onblur="fl_js_val_numeric_jht('jhtinput_rw');" <?=($ls_status_submit_agenda =="Y")? " style=\"width:80px;\" readonly class=disabled" : " style=\"width:80px;\"";?>>		
          </div>																																										
        	<div class="clear"></div>
      
      			
          <div class="form-row_kiri">
          <label style = "text-align:right;">Kode Pos &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label> 	    				
            <input type="text" id="jhtinput_kode_pos" name="jhtinput_kode_pos" value="<?=$ls_jkpinput_kode_pos;?>" tabindex="11" maxlength="10" readonly <?=($ls_status_submit_agenda =="Y")? " style=\"width:206px;\" readonly class=disabled" : " style=\"width:206px;background-color:#ffff99\"";?>>
      			<?
      			if ($ls_status_submit_agenda=="T")
      			{
      			?>      								
      				<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_lov_pos.php?p=pn5040_tabdataklaim_jht.php&a=formreg&b=jhtinput_kode_kelurahan&c=jhtinput_nama_kelurahan&d=jhtinput_kode_kecamatan&e=jhtinput_nama_kecamatan&f=jhtinput_kode_kabupaten&g=jhtinput_nama_kabupaten&h=jhtinput_kode_propinsi&j=jhtinput_nama_propinsi&k=jhtinput_kode_pos','',800,500,1)">							
            <?
      			}
      			?>
      			<img src="../../images/help.png" alt="Cari Kode Pos" border="0" align="absmiddle"></a>
      		</div>																																																	
          <div class="clear"></div>
      			
          <div class="form-row_kiri">
          <label style = "text-align:right;">Kelurahan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>		 	    				
            <input type="text" id="jhtinput_nama_kelurahan" name="jhtinput_nama_kelurahan" value="<?=$ls_jkpinput_nama_kelurahan;?>" style="width:239px;" readonly class="disabled">
      			<input type="hidden" id="jhtinput_kode_kelurahan" name="jhtinput_kode_kelurahan" value="<?=$ls_jkpinput_kode_kelurahan;?>" maxlength="20" readonly class="disabled">      
      		</div>																																																	
          <div class="clear"></div>
      				
          <div class="form-row_kiri">
          <label style = "text-align:right;">Kecamatan &nbsp;&nbsp;&nbsp;</label>		 	    				
            <input type="text" id="jhtinput_nama_kecamatan" name="jhtinput_nama_kecamatan" value="<?=$ls_jkpinput_nama_kecamatan;?>" style="width:239px;" readonly class="disabled">
            <input type="hidden" id="jhtinput_kode_kecamatan" name="jhtinput_kode_kecamatan" value="<?=$ls_jkpinput_kode_kecamatan;?>" maxlength="10">			
      		</div>
      		<div class="clear"></div>
      
          <div class="form-row_kiri">
          <label style = "text-align:right;">Kabupaten &nbsp;</label>		 	    				
            <input type="text" id="jhtinput_nama_kabupaten" name="jhtinput_nama_kabupaten" value="<?=$ls_jkpinput_nama_kabupaten;?>" style="width:239px;" readonly class="disabled">
      			<input type="hidden" id="jhtinput_kode_kabupaten" name="jhtinput_kode_kabupaten" value="<?=$ls_jkpinput_kode_kabupaten;?>" maxlength="20">      
            <input type="hidden" id="jhtinput_kode_propinsi" name="jhtinput_kode_propinsi" value="<?=$ls_jkpinput_kode_propinsi;?>" readonly class="disabled">
            <input type="hidden" id="jhtinput_nama_propinsi" name="jhtinput_nama_propinsi" value="<?=$ls_jkpinput_nama_propinsi;?>" readonly class="disabled">
      		</div>
          <div class="clear"></div>	

          <div class="form-row_kiri">
          <label style = "text-align:right;">Provinsi &nbsp;</label>		 	    				
            <input type="hidden" id="jhtinput_kode_propinsi" name="jhtinput_kode_propinsi" value="<?=$ls_jkpinput_kode_propinsi;?>" readonly class="disabled">
            <input type="text" id="jhtinput_nama_propinsi" name="jhtinput_nama_propinsi" value="<?=$ls_jkpinput_nama_propinsi;?>" style="width:239px;" readonly class="disabled">
      		</div>
          <div class="clear"></div>	
      																										
      		</br>
      		
          <div class="form-row_kiri">
          <label style = "text-align:right;">Email &nbsp;&nbsp;</label>		 	    				
      			<input type="text" id="jhtinput_email" name="jhtinput_email" value="<?=$ls_jkpinput_email;?>" tabindex="12" maxlength="200" <?=($ls_status_submit_agenda =="Y")? " style=\"width:270px;\" readonly class=disabled" : " style=\"width:270px;\"";?>>
      		</div>
      		<div class="clear"></div>
      
          <div class="form-row_kiri">
          <label style = "text-align:right;">No. Telp &nbsp;</label>	    				
            <input type="text" id="jhtinput_telepon_area" name="jhtinput_telepon_area" tabindex="13" value="<?=$ls_jkpinput_telepon_area;?>" maxlength="5" onblur="fl_js_val_numeric_jht('jhtinput_telepon_area');" <?=($ls_status_submit_agenda =="Y")? " style=\"width:40px;\" readonly class=disabled" : " style=\"width:40px;\"";?>>
            <input type="text" id="jhtinput_telepon" name="jhtinput_telepon" tabindex="14" value="<?=$ls_jkpinput_telepon;?>" maxlength="20" onblur="fl_js_val_numeric_jht('jhtinput_telepon');" <?=($ls_status_submit_agenda =="Y")? " style=\"width:148px;\" readonly class=disabled" : " style=\"width:148px;\"";?>>
            &nbsp;ext.
            <input type="text" id="jhtinput_telepon_ext" name="jhtinput_telepon_ext" tabindex="15" value="<?=$ls_jkpinput_telepon_ext;?>" maxlength="5" onblur="fl_js_val_numeric_jht('jhtinput_telepon_ext');" <?=($ls_status_submit_agenda =="Y")? " style=\"width:40px;\" readonly class=disabled" : " style=\"width:40px;\"";?>>
      		</div>
          <div class="clear"></div>
      
          <div class="form-row_kiri">
          <label style = "text-align:right;">Handphone &nbsp;&nbsp;</label>		 	    				
          	<input type="text" id="jhtinput_handphone" name="jhtinput_handphone" tabindex="16" value="<?=$ls_jkpinput_handphone;?>" maxlength="15" <?=($ls_status_submit_agenda =="Y")? " style=\"width:270px;\" readonly class=disabled" : " style=\"width:270px;\"";?>>
      		</div>																																																																																														
          <div class="clear"></div>
      
          <div class="form-row_kiri">
          <label style = "text-align:right;">No. Identitas &nbsp;</label>
            <input type="text" id="jhtinput_nomor_identitas" name="jhtinput_nomor_identitas" value="<?=$ls_jkpinput_nomor_identitas;?>" maxlength="30" tabindex="17" <?=($ls_status_submit_agenda =="Y")? " style=\"width:270px;\" readonly class=disabled" : " style=\"width:270px;\"";?>>
      		</div>																																									
          <div class="clear"></div>
      
          <div class="form-row_kiri">
          <label style = "text-align:right;">NPWP &nbsp; </label>
            <input type="text" id="jhtinput_npwp" name="jhtinput_npwp" value="<?=$ls_jkpinput_npwp;?>" size="41" maxlength="15" tabindex="18" onblur="fl_js_val_numeric_jht('jhtinput_npwp');" <?=($ls_status_submit_agenda =="Y")? " style=\"width:240px;\" readonly class=disabled" : " style=\"width:240px;\"";?>>
      		</div>																																									
          <div class="clear"></div>
      
          <?
            echo "<script type=\"text/javascript\">fl_js_jhtinput_kode_tipe_penerima();</script>";
            echo "<script type=\"text/javascript\">fl_js_jhtinput_ahliwaris_lain();</script>";
          ?>						
      		<br></br>																					
				</fieldset>			
			</td>			
		</tr>		 
	</table>	 	
  
  </br>
  
  <fieldset><legend><b><i><font color="#009999">Informasi Masa Iur dan Bulan Kepesertaan :</font></i></b></legend>
        <table width="98%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
          <thead id="tblmasaiurjkp">
            <tr>
                <th colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
            </tr>
            <tr>
                <th colspan="5"></th>	
                <th colspan="4" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Iuran</th>
            </tr>	
            <tr>
                <th colspan="5"></th>	
                <th colspan="4"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>
            </tr>	
            <tr>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>No. Referensi</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>NPP</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Div</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>BLTH</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Tanggal Bayar</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Rekomposisi JKK</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Rekomposisi JKM</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Subsidi Pemerintah</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Total</th>		
            </tr>
            <tr>
        	    <th colspan="9"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
            </tr>   
          </thead>  
          <tbody id="data_list"></tbody>
            <tr>
                <th colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
            </tr>																																	 							
        </table>

        <div id="div_page" style="padding-bottom: 26px; padding-left: 10px; padding-right: 10px;">
							<div style="padding-top: 8px;">
								<div style="float: left;">
									<span style="vertical-align: middle;">Halaman</span>
									<a href="javascript:void(0)" title="First Page" style="vertical-align: middle; padding-left: 2px; padding-right: 2px;" onclick="filter(-2)"><<</a>
									<a href="javascript:void(0)" title="Previous Page" style="vertical-align: middle; padding-left: 2px; padding-right: 2px;" onclick="filter(-1)">Prev</a>
									<input type="text" value="1" id="page" name="page" style="width: 50px;  vertical-align: middle; text-align: center;" onkeypress="return isNumber(event)" onblur="filter()"/>
									<a href="javascript:void(0)" title="Next Page" style="vertical-align: middle; padding-left: 2px; padding-right: 2px;" onclick="filter(1)">Next</a>
									<a href="javascript:void(0)" title="Last Page" style="vertical-align: middle; padding-left: 2px; padding-right: 2px;" onclick="filter(2)">>></a>
									<span style="vertical-align: middle;" id="span_info_halaman">dari 1 halaman</span>
									<input type="hidden" id="pages">
								</div>
								<div style="float: right">
									<input type="text" style="visibility:hidden; width: 2px;">
									<input type="hidden" id="hdn_total_records">
									<span style="vertical-align: middle;" id="span_info_item">Menampilkan item ke 0 sampai dengan 0 dari 0 items</span>
									<select name="page_item" id="page_item" style="width: 50px;" onchange="filter()">
										<option value="10">10</option>
										<option value="15">15</option>
										<option value="20">20</option>
										<option value="25">25</option>
										<option value="50">50</option>
									</select>								
							</div>
						</div>
						
        <br><br>
        <br><br>

        <div class="form-row_kiri">
          <label style="width:230px;"><b><i>Keterangan:</i></b></label>	    				
        </div>																																																	
        <div class="clear"></div>

        <div class="form-row_kiri">
          <label style="width:230px;">Jumlah Bulan Iur JKP</label>		 	    				
          <label>: 	<span style="vertical-align: middle;" id="span_info_jml_iur"></span> Bulan</label>		 	    				
        </div>																																																	
        <div class="clear"></div>

        <div class="form-row_kiri">
          <label style="width:230px;">Terdapat Enam Bulan Upah Berturut-turut</label>		 	    				
          <label>: <?=$ls_jkpflag_enam_bulan_berturut;?></label>
     		</div>																																																	
        <div class="clear"></div>

    </fieldset>

    </br>
    
    <fieldset><legend><b><i><font color="#009999">Informasi Manfaat :</font></i></b></legend>
        <table width="98%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
        <tbody id="tblinformasimanfaat">
            <tr>
                <th colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
            </tr>
            <tr>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Bulan ke-</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>BLTH Manfaat</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Upah Dilaporkan</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>BLTH Upah Dilaporkan</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Upah Terhitung</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>% Upah Terhitung</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Nilai Manfaat</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Status Manfaat</th>	
            </tr>
            <tr>
        	    <th colspan="9"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
            </tr>   									
            <?php
            $sql = "select BULAN_MANFAAT_KE, 
                          TO_CHAR(BLTH_MANFAAT,'MM-YYYY') BLTH_MANFAAT, 
                          NOM_UPAH_PELAPORAN, 
                          TO_CHAR(BLTH_UPAH_PELAPORAN,'MM-YYYY') BLTH_UPAH_PELAPORAN, 
                          NOM_UPAH_TERHITUNG, 
                          TARIF_UPAH_TERHITUNG,
                          NOM_MANFAAT, 
                          STATUS_PENGAJUAN
                    from table 
                    (
                      pn.p_pn_siapkerja2pn.f_get_informasi_manfaat_tk_jkp
                      (
                        :p_kode_profile,
                        :p_tahap_jkp
                      )
                    )";
            $param_bv = [':p_kode_profile' => $ls_jkpkode_profile_tk_jkp, ':p_tahap_jkp' => $ls_jkptahap_jkp];
            $proc = $DB->parse($sql);
            foreach ($param_bv as $key => $value) {
              oci_bind_by_name($proc, $key, $param_bv[$key]);
            }
            $DB->execute();
            $i=0;
            while($row = $DB->nextrow())
            {
              echo "
            <tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">
                <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['BULAN_MANFAAT_KE']."</td>
                <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['BLTH_MANFAAT']."</td>
                <td style='text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;'>Rp  ".number_format((float)$row['NOM_UPAH_PELAPORAN'],2,".",",")."</td>
                <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['BLTH_UPAH_PELAPORAN']."</td>
                <td style='text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;'>Rp  ".number_format((float)$row['NOM_UPAH_TERHITUNG'],2,".",",")."</td>
                <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".number_format(100*(float)$row['TARIF_UPAH_TERHITUNG'])." % </td>
                <td style='text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;'>Rp  ".number_format((float)$row['NOM_MANFAAT'],2,".",",")."</td>
                <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['STATUS_PENGAJUAN']."</td>
            </tr>";
            $i++;
            }  
            ?>
            <tr>
                <th colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
            </tr>
            <tr>

        </tbody>
         																																										 							
        </table>
        

    </fieldset>

    </br>

    <fieldset><legend><b><i><font color="#009999">Daftar Lamaran : </font></i></b></legend>
        <table width="98%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
        <tbody id="tbldaftarlamaran">
            <tr>
                <th colspan="5"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
            </tr>
            <tr>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Nama Perusahaan</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Tanggal Lamaran</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Posisi</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Status Lamaran</th> 
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Status Interview</th> 
            </tr>
            <tr>
        	    <th colspan="5"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
            </tr>    
            <?php
            $sql = "select NAMA_PERUSAHAAN_LAMARAN, 
                            TO_CHAR(TGL_LAMARAN,'DD-MM-YYYY') TGL_LAMARAN,
                            POSISI_LAMARAN, 
                            STATUS_LAMARAN_KERJA, 
                            STATUS_INTERVIEW
                      from PN.PN_KLAIM_JKP_LAMARAN_KERJA 
                      where kode_klaim = :p_kode_klaim";
                $proc = $DB->parse($sql);
                oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim);
                $DB->execute();
                $i=0;
                while($row = $DB->nextrow())
            {
              echo "
                <tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">
                    <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['NAMA_PERUSAHAAN_LAMARAN']."</td>
                    <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['TGL_LAMARAN']."</td>
                    <td style='text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['POSISI_LAMARAN']."</td>
                    <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['STATUS_LAMARAN_KERJA']."</td>
                    <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['STATUS_INTERVIEW']."</td>
                </tr>";
              $i++;
            }  
            ?>

            <tr>
                <th colspan="5"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
            </tr>
            <tr>

        </tbody>
         																																										 							
        </table> 
    </fieldset>

    <fieldset><legend><b><i><font color="#009999">Daftar Wawancara : </font></i></b></legend>
        <table width="98%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
        <tbody id="tbldaftarwawancara">
            <tr>
                <th colspan="4"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
            </tr>
            <tr>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Nama Perusahaan</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Tanggal Wawancara</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Posisi</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Hasil Wawancara</th> 
            </tr>
            <tr>
        	    <th colspan="4"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
            </tr>    
            <?php
            $sql = "select * from PN.PN_KLAIM_JKP_INTERVIEW where kode_klaim = :p_kode_klaim";
                $proc = $DB->parse($sql);
                oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim);
                $DB->execute();
                $i=0;
                while($row = $DB->nextrow())
            {
              echo "
            <tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">
                <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['NAMA_PERUSAHAAN']."</td>
                <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['TGL_INTERVIEW']."</td>
                <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['POSISI']."</td>
                <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['HASIL_WAWANCARA']."</td>
            </tr>";
            $i++;
            }  
            ?>

            <tr>
                <th colspan="4"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
            </tr>
            <tr>

        </tbody>
         																																										 							
        </table> 
    </fieldset>

    <fieldset><legend><b><i><font color="#009999">Daftar Pelatihan Kerja : </font></i></b></legend>
        <table width="98%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
        <tbody id="tbldaftarpelatihankerja">
            <tr>
                <th colspan="8"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
            </tr>
            <tr>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Nama BLK</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Tipe BLK</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Jenis Pelatihan</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Materi</th> 
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Tanggal Awal</th> 
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Tanggal Akhir</th> 
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Absensi</th> 
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Status</th> 
            </tr>
            <tr>
        	    <th colspan="8"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
            </tr>    
            <?php
            $sql = "select NAMA_BLK, 
                          JENIS_BLK, 
                          JENIS_PELATIHAN, 
                          NAMA_MATERI,
                          TO_CHAR(TGL_AWAL_PELATIHAN,'DD-MM-YYYY') TGL_AWAL_PELATIHAN, 
                          TO_CHAR(TGL_AKHIR_PELATIHAN,'DD-MM-YYYY') TGL_AKHIR_PELATIHAN, 
                          PERSENTASI_ABSENSI, STATUS_LULUS
                      from PN.PN_KLAIM_JKP_PELATIHAN_KERJA 
                      where kode_klaim = :p_kode_klaim";
                $proc = $DB->parse($sql);
                oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim);
                $DB->execute();
                $i=0;
                while($row = $DB->nextrow())
            {
              echo "
            <tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">
                <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['NAMA_BLK']."</td>
                <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['JENIS_BLK']."</td>
                <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['JENIS_PELATIHAN']."</td>
                <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['NAMA_MATERI']."</td>
                <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['TGL_AWAL_PELATIHAN']."</td>
                <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['TGL_AKHIR_PELATIHAN']."</td>
                <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['PERSENTASI_ABSENSI']." %</td>
                <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['STATUS_LULUS']."</td>
            </tr>";
            $i++;
            }  
            ?>

            <tr>
                <th colspan="8"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
            </tr>
            <tr>

        </tbody>
         																																										 							
        </table> 
    </fieldset>

    <fieldset><legend><b><i><font color="#009999">Daftar Counseling : </font></i></b></legend>
        <table width="98%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
        <tbody id="tbldaftarcounseling">
            <tr>
                <th colspan="3"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
            </tr>
            <tr>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Tanggal Counseling</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Rekomendasi Aktivitas</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Nama Counselor</th> 
            </tr>
            <tr>
        	    <th colspan="3"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
            </tr>    
            <?php
            $sql = "select TO_CHAR(TGL_KONSELING,'DD-MM-YYYY') TGL_KONSELING,
                          REKOMENDASI_AKTIVITAS,
                          NAMA_KONSELOR
                    from PN.PN_KLAIM_JKP_KONSELING 
                    where kode_klaim = :p_kode_klaim";
                $proc = $DB->parse($sql);
                oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim);
                $DB->execute();
                $i=0;
                while($row = $DB->nextrow())
            {
              echo "
            <tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">
                <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['TGL_KONSELING']."</td>
                <td style='text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['REKOMENDASI_AKTIVITAS']."</td>
                <td style='text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;'>".$row['NAMA_KONSELOR']."</td>
            </tr>";
            $i++;
            }  
            ?>

            <tr>
                <th colspan="3"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
            </tr>
            <tr>

        </tbody>
         																																										 							
        </table> 
    </fieldset>

</div>
