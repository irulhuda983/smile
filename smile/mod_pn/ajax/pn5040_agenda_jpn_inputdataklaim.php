<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Input Agenda Klaim JP
Hist: - 20/07/2017 : Pembuatan Form (Tim SIJSTK)			 						 
-----------------------------------------------------------------------------*/

//ambil case dan kondisi tk ----------------------------------------------------
$ls_jpn_tk_kode_jenis_kasus = $ls_kode_jenis_kasus;
$ld_jpn_tk_tgl_jenis_kasus	= $ld_tgl_kejadian;
$ld_jpn_tk_tgl_mulai_pensiun = $ld_tgl_mulai_pensiun;

//ambil kondisi terakhir tk --------------------------------------------------------
$sql = "select nama_lengkap, kode_kondisi_terakhir, to_char(tgl_kondisi_terakhir,'dd/mm/yyyy') tgl_kondisi_terakhir ". 
		 	 "from sijstk.pn_klaim_penerima_berkala ". 
			 "where kode_klaim = :p_kode_klaim ". 
			 "and kode_penerima_berkala = 'TK' ". 
			 "and rownum = 1";
$proc = $DB->parse($sql);
oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
// 20-03-2024 penyesuaian bind variable
$DB->execute();
$row = $DB->nextrow();
$ls_jpn_tk_kode_kondisi_terakhir 	= $row["KODE_KONDISI_TERAKHIR"];	
$ld_jpn_tk_tgl_kondisi_terakhir 	= $row["TGL_KONDISI_TERAKHIR"];

//ambil nama suamis/istri ------------------------------------------------------
$sql = "select nama_lengkap, kode_kondisi_terakhir, (select nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir where kode_kondisi_terakhir = a.kode_kondisi_terakhir) nama_kondisi_terakhir, ".
		 	 "			 to_char(tgl_kondisi_terakhir,'dd/mm/yyyy') tgl_kondisi_terakhir ".
		 	 "from sijstk.pn_klaim_penerima_berkala a ".
			 "where kode_klaim = :p_kode_klaim ". 
			 "and kode_penerima_berkala = 'JD' ". 
			 "and rownum = 1";
$proc = $DB->parse($sql);
oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
// 20-03-2024 penyesuaian bind variable
$DB->execute();
$row = $DB->nextrow();
$ls_jpn_pasangan_nama = $row["NAMA_LENGKAP"];
$ls_jpn_pasangan_kode_kondisi_terakhir 	= $row["KODE_KONDISI_TERAKHIR"];	
$ls_jpn_pasangan_nama_kondisi_terakhir	= $row["NAMA_KONDISI_TERAKHIR"];
$ld_jpn_pasangan_tgl_kondisi_terakhir		= $row["TGL_KONDISI_TERAKHIR"];

//ambil nama anak pertama ------------------------------------------------------
$sql = "select nama_lengkap, kode_kondisi_terakhir, (select nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir where kode_kondisi_terakhir = a.kode_kondisi_terakhir) nama_kondisi_terakhir, ".
		 	 "			 to_char(tgl_kondisi_terakhir,'dd/mm/yyyy') tgl_kondisi_terakhir ". 
		 	 "from sijstk.pn_klaim_penerima_berkala a ". 
		 	 "where kode_klaim = :p_kode_klaim ". 
			 "and kode_penerima_berkala = 'A1' ". 
			 "and rownum = 1";
$proc = $DB->parse($sql);
oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
// 20-03-2024 penyesuaian bind variable
$DB->execute();
$row = $DB->nextrow();
$ls_jpn_anak1_nama = $row["NAMA_LENGKAP"];
$ls_jpn_anak1_kode_kondisi_terakhir 	= $row["KODE_KONDISI_TERAKHIR"];	
$ls_jpn_anak1_nama_kondisi_terakhir		= $row["NAMA_KONDISI_TERAKHIR"];
$ld_jpn_anak1_tgl_kondisi_terakhir		= $row["TGL_KONDISI_TERAKHIR"];
				
//ambil nama anak kedua --------------------------------------------------------
$sql = "select nama_lengkap, kode_kondisi_terakhir, (select nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir where kode_kondisi_terakhir = a.kode_kondisi_terakhir) nama_kondisi_terakhir, ".
		 	 "			 to_char(tgl_kondisi_terakhir,'dd/mm/yyyy') tgl_kondisi_terakhir ". 
		 	 "from sijstk.pn_klaim_penerima_berkala a ". 
			 "where kode_klaim = :p_kode_klaim ". 
			 "and kode_penerima_berkala = 'A2' ". 
			 "and rownum = 1";
$proc = $DB->parse($sql);
oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
// 20-03-2024 penyesuaian bind variable
$DB->execute();
$row = $DB->nextrow();
$ls_jpn_anak2_nama = $row["NAMA_LENGKAP"];
$ls_jpn_anak2_kode_kondisi_terakhir 	= $row["KODE_KONDISI_TERAKHIR"];
$ls_jpn_anak2_nama_kondisi_terakhir		= $row["NAMA_KONDISI_TERAKHIR"];	
$ld_jpn_anak2_tgl_kondisi_terakhir		= $row["TGL_KONDISI_TERAKHIR"];

//ambil nama orang tua --------------------------------------------------------
$sql = "select nama_lengkap, kode_kondisi_terakhir, (select nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir where kode_kondisi_terakhir = a.kode_kondisi_terakhir) nama_kondisi_terakhir, ".
		 	 "			 to_char(tgl_kondisi_terakhir,'dd/mm/yyyy') tgl_kondisi_terakhir ". 
		 	 "from sijstk.pn_klaim_penerima_berkala a ". 
			 "where kode_klaim = :p_kode_klaim ". 
			 "and kode_penerima_berkala = 'OT' ". 
			 "and rownum = 1";
$proc = $DB->parse($sql);
oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
// 20-03-2024 penyesuaian bind variable
$DB->execute();
$row = $DB->nextrow();
$ls_jpn_ortu_nama = $row["NAMA_LENGKAP"];
$ls_jpn_ortu_kode_kondisi_terakhir 	= $row["KODE_KONDISI_TERAKHIR"];
$ls_jpn_ortu_nama_kondisi_terakhir	= $row["NAMA_KONDISI_TERAKHIR"];	
$ld_jpn_ortu_tgl_kondisi_terakhir		= $row["TGL_KONDISI_TERAKHIR"];


//cek fitur alert JP
$sql = "SELECT KODE_KLAIM, KODE_TK FROM PN.PN_KLAIM
		WHERE KODE_KLAIM = :p_kode_klaim";
$proc = $DB->parse($sql);
oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
// 20-03-2024 penyesuaian bind variable
$DB->execute();
$row = $DB->nextrow();
$p_kode_klaim 	= $row["KODE_KLAIM"];
$p_kode_tk  	= $row["KODE_TK"];

$qry = "BEGIN PN.X_ALERT_JP(:p_kode_klaim,:p_kode_tk,:p_alert); END;";											 	
$proc = $DB->parse($qry);				
oci_bind_by_name($proc, ":p_kode_klaim", $p_kode_klaim,1000);
oci_bind_by_name($proc, ":p_kode_tk", $p_kode_tk,1000);
oci_bind_by_name($proc, ":p_alert", $p_alert,1000);
$DB->execute();				
$ls_mess = $p_alert;

// ambil tgl_grace_pensiun
$sql = "select 
case  
  when to_char(TGL_NA,'yyyymm') >= to_char(add_months(TGL_MULAI_PENSIUN,48),'yyyymm') 
    then to_char(add_months(TGL_MULAI_PENSIUN,48),'DD/MM/YYYY')
  else
    to_char(TGL_NA,'DD/MM/YYYY')
end TGL_GRACE_PENSIUN
from PN.PN_KLAIM 
where KODE_KLAIM = :p_kode_klaim
and STATUS_BATAL = 'T'";
$proc = $DB->parse($sql);
oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
// 20-03-2024 penyesuaian bind variable
$DB->execute();
$row = $DB->nextrow();
$ls_tgl_grace_pensiun = $row["TGL_GRACE_PENSIUN"];



// cek iuran jp apakah ada lebih dari BLTH TGL_GRACE_PENSIUN
$sql = "select count(*) jml_bulan_iur_jp_lebih
from PN.PN_KLAIM_TKUPAH 
where KODE_KLAIM = :p_kode_klaim
and BLTH > trunc(to_date(:p_tgl_grace_pensiun,'DD/MM/YYYY'),'MM')";
$proc = $DB->parse($sql);
oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
oci_bind_by_name($proc, ':p_tgl_grace_pensiun', $ls_tgl_grace_pensiun, 50);
// 20-03-2024 penyesuaian bind variable
$DB->execute();
$row = $DB->nextrow();
$ls_jml_bulan_iur_jp_lebih = $row["JML_BULAN_IUR_JP_LEBIH"];






?>

<script language="javascript">
  function fl_js_jpn_val_input() 
  {     			
    //jika case tk meninggal maka kondisi akhir tk (peristiwa B) jg meninggal --
	var v_jpn_tk_kode_jenis_kasus = window.document.getElementById('jpn_tk_kode_jenis_kasus').value;
    var v_jpn_tk_tgl_jenis_kasus  = window.document.getElementById('jpn_tk_tgl_jenis_kasus').value;
	

	// Mendapatkan tanggal, bulan, dan tahun
	var tanggal = new Date();
	var dd = String(tanggal.getDate()).padStart(2, '0');
	var mm = String(tanggal.getMonth() + 1).padStart(2, '0'); // bulan dimulai dari 0, sehingga perlu ditambahkan 1.
	var yyyy = tanggal.getFullYear();

	var today = yyyy + mm + dd;

	var cek_tgl_jenis_kasus = v_jpn_tk_tgl_jenis_kasus.split('/');
	var cek_tgl_jenis_kasus0 = cek_tgl_jenis_kasus[0]; 
	var cek_tgl_jenis_kasus1 = cek_tgl_jenis_kasus[1]; 
	var cek_tgl_jenis_kasus2 = cek_tgl_jenis_kasus[2]; 
	cek_tgl_jenis_kasus = cek_tgl_jenis_kasus2 + cek_tgl_jenis_kasus1 + cek_tgl_jenis_kasus0;
	
	if (cek_tgl_jenis_kasus > today){
		alert('Tanggal kejadian tidak boleh lebih besar dari hari ini');
		window.document.getElementById('jpn_tk_tgl_jenis_kasus').value = '';
		return;
	}
    
    if (v_jpn_tk_kode_jenis_kasus == "KS13")
    { 
     	window.document.getElementById('jpn_tk_kode_kondisi_terakhir').value = "KA11"; //meninggal
			window.document.getElementById('jpn_tk_tgl_kondisi_terakhir').value = v_jpn_tk_tgl_jenis_kasus;
    }		
  }
										
</script>		
		
<div id="formKiri" style="width:955px;">
  <fieldset><legend>Input Data Klaim Manfaat Pensiun</legend>
		<table id="tbljpn" width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
			<tbody>
				<tr>
					<th colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
				</tr>
        <tr>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;" colspan="2">&nbsp;</th>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;" colspan="3">Peristiwa A</th>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;" colspan="3">Peristiwa B</th>			
        </tr>	
				<tr>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:120px;"">Hub. Keluarga</th>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:240px;">Nama</th>	
					<th colspan="8"><hr/></th>	
				</tr>							
        <tr>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">&nbsp;</th>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">&nbsp;</th>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:110px;">Jenis Peristiwa</th>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:110px;">Tgl Kejadian</th>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:110px;">Jenis Peristiwa</th>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:110px;">Tgl Kejadian</th>					
        </tr>
				<tr>
					<th colspan="10"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
				</tr>
				
				<!--  -------------------- TENAGA KERJA ------------------------------>
				<tr>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">Tenaga Kerja</td>
					<td><input type="text" id="jpn_tk_nama" name="jpn_tk_nama" value="<?=$ls_nama_tk;?>" style="width:217px;" maxlength="100" readonly class="disabled">
							<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_agenda_jpn_ahliwaristkentry.php?task=edit&kode_tk=<?=$ls_kode_tk;?>&kode_klaim=<?=$ls_kode_klaim;?>&kode_penerima_berkala=TK&root_sender=pn5040.php&sender=pn5040.php&sender_activetab=3&sender_mid=<?=$mid;?>&va=formreg&vb=jpn_tk_nama&vc=jpn_tk_kode_kondisi_terakhir&vd=jpn_tk_tgl_kondisi_terakhir','Ubah Data Keluarga',850,590,'no');"><img src="../../images/user_go.png" border="0" alt="Ubah Divisi" align="absmiddle" /></a>		 
					</td>
					<td></td>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
            <select id="jpn_tk_kode_jenis_kasus" name="jpn_tk_kode_jenis_kasus" value="<?=$ls_jpn_tk_kode_jenis_kasus;?>" tabindex="2" class="select_format" onchange="fl_js_jpn_val_input();" <?=($ls_status_kelayakan =="Y" || $ls_status_submit_agenda =="Y")? " style=\"text-align:center;width:130px;background-color:#F5F5F5\"" : " style=\"text-align:center;width:130px;background-color:#ffff99\"";?>>
              <?
			  $param_bv = [];
              if ($ls_status_kelayakan =="Y" || $ls_status_submit_agenda =="Y")
              {
               	$sql = "select kode_jenis_kasus, nama_jenis_kasus from sijstk.pn_kode_jenis_kasus where kode_jenis_kasus=:p_kode_jenis_kasus";
				$param_bv[':p_kode_jenis_kasus'] = $ls_jpn_tk_kode_jenis_kasus;
              }else
              {
              ?>
							<option value="">-- Pilih --</option>
							<?
							 	$sql = "select kode_jenis_kasus, nama_jenis_kasus from sijstk.pn_kode_jenis_kasus where kode_tipe_klaim = :p_kode_tipe_klaim and nvl(status_nonaktif,'T')='T' order by no_urut";
								 $param_bv[':p_kode_tipe_klaim'] = $ls_kode_tipe_klaim;
              } 			         
              $proc = $DB->parse($sql);
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
				// 20-03-2024 penyesuaian bind variable
              $DB->execute();
              while($row = $DB->nextrow())
              {
                echo "<option ";
                if ($row["KODE_JENIS_KASUS"]==$ls_jpn_tk_kode_jenis_kasus && strlen($ls_jpn_tk_kode_jenis_kasus)==strlen($row["KODE_JENIS_KASUS"])){ echo " selected"; }
                echo " value=\"".$row["KODE_JENIS_KASUS"]."\">".$row["NAMA_JENIS_KASUS"]."</option>";
              }
              ?>
            </select>
					</td>
					<td></td>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
            <input type="text" id="jpn_tk_tgl_jenis_kasus" name="jpn_tk_tgl_jenis_kasus" value="<?=$ld_jpn_tk_tgl_jenis_kasus;?>" tabindex="3" maxlength="10" onblur="convert_date(jpn_tk_tgl_jenis_kasus);fl_js_jpn_val_input();" <?=($ls_status_kelayakan =="Y" || $ls_status_submit_agenda =="Y")? " style=\"width:71px;background-color:#F5F5F5\"" : " style=\"width:71px;background-color:#ffff99\"";?>>
        		<?
      				if ($ls_status_kelayakan !="Y" || $ls_status_submit_agenda !="Y")  	
        			{
      				 	?> 
           			<input id="btn_jpn_tk_tgl_jenis_kasus" type="image" align="top" onclick="return showCalendar('jpn_tk_tgl_jenis_kasus', 'dd-mm-y');" src="../../images/calendar.gif" />									
      					<?  			
      				}
      			?>					
					</td>
					<td></td>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
            <select id="jpn_tk_kode_kondisi_terakhir" name="jpn_tk_kode_kondisi_terakhir" value="<?=$ls_jpn_tk_kode_kondisi_terakhir;?>" tabindex="4" class="select_format" onchange="fl_js_jpn_val_input();" <?=($ls_status_kelayakan =="Y" || $ls_status_submit_agenda =="Y")? " style=\"text-align:center;width:120px;background-color:#F5F5F5\"" : " style=\"text-align:center;width:120px;background-color:#ffffff\"";?>>
            <option value="">-- Pilih --</option>
              <?
			  $param_bv = [];
              if ($ls_status_kelayakan =="Y" || $ls_status_submit_agenda =="Y")
              {
               	$sql = "select kode_kondisi_terakhir, nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir where kode_kondisi_terakhir=:p_kode_kondisi_terakhir";
				$param_bv[':p_kode_kondisi_terakhir'] = $ls_jpn_tk_kode_kondisi_terakhir;
              }else
              {
               	$sql = "select kode_kondisi_terakhir, nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir ".
										 	 "where kode_tipe_klaim = :p_kode_tipe_klaim ".
											 //"and kode_kondisi_terakhir='$ls_jpn_tk_kode_kondisi_terakhir' ".
											 "and kode_kondisi_terakhir = 'KA11' ".
											 "and nvl(status_nonaktif,'T')='T' ".
											 "order by no_urut";
				$param_bv[':p_kode_tipe_klaim'] = $ls_kode_tipe_klaim;
              } 			         
              $proc = $DB->parse($sql);
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
				// 20-03-2024 penyesuaian bind variable
              $DB->execute();
              while($row = $DB->nextrow())
              {
                echo "<option ";
                if ($row["KODE_KONDISI_TERAKHIR"]==$ls_jpn_tk_kode_kondisi_terakhir && strlen($ls_jpn_tk_kode_kondisi_terakhir)==strlen($row["KODE_KONDISI_TERAKHIR"])){ echo " selected"; }
                echo " value=\"".$row["KODE_KONDISI_TERAKHIR"]."\">".$row["NAMA_KONDISI_TERAKHIR"]."</option>";
              }
              ?>
            </select>
					</td>
					<td></td>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
            <input type="text" id="jpn_tk_tgl_kondisi_terakhir" name="jpn_tk_tgl_kondisi_terakhir" value="<?=$ld_jpn_tk_tgl_kondisi_terakhir;?>" tabindex="5" maxlength="10" onblur="convert_date(jpn_tk_tgl_kondisi_terakhir);"  <?=($ls_status_kelayakan =="Y" || $ls_status_submit_agenda =="Y")? " style=\"width:71px;background-color:#F5F5F5\"" : " style=\"width:71px;background-color:#ffffff\"";?>>
        		<?
      				if ($ls_status_kelayakan !="Y" || $ls_status_submit_agenda !="Y")  	
        			{
      				 	?> 
           			<input id="btn_jpn_tk_tgl_kondisi_terakhir" type="image" align="top" onclick="return showCalendar('jpn_tk_tgl_kondisi_terakhir', 'dd-mm-y');" src="../../images/calendar.gif" />									
      					<?  			
      				}
      			?>					
					</td>	
				</tr>
				
				<!--  -------------------- ISTRI / SUAMI ------------------------------>
						
				<tr>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">Istri/Suami</td>
					<td><input type="text" id="jpn_pasangan_nama" name="jpn_pasangan_nama" value="<?=$ls_jpn_pasangan_nama;?>" style="width:240px;"  maxlength="100" tabindex="6" readonly class="disabled">
					</td>
					<td></td>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
						<input type="text" style="text-align:center;width:123px;" id="jpn_pasangan_nama_kondisi_terakhir" name="jpn_pasangan_nama_kondisi_terakhir" value="<?=$ls_jpn_pasangan_nama_kondisi_terakhir;?>" readonly class="disabled">
						<input type="hidden" id="jpn_pasangan_kode_kondisi_terakhir" name="jpn_pasangan_kode_kondisi_terakhir" value="<?=$ls_jpn_pasangan_kode_kondisi_terakhir;?>">	
					</td>
					<td></td>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
						<input type="text" id="jpn_pasangan_tgl_kondisi_terakhir" name="jpn_pasangan_tgl_kondisi_terakhir" value="<?=$ld_jpn_pasangan_tgl_kondisi_terakhir;?>" style="width:90px;" readonly class="disabled">				
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>						
				</tr>
				
				<!--  -------------------- ANAK I ------------------------------------->
						
				<tr>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">Anak I</td>
					<td><input type="text" id="jpn_anak1_nama" name="jpn_anak1_nama" value="<?=$ls_jpn_anak1_nama;?>" style="width:240px;" maxlength="100" tabindex="9" readonly class="disabled">
					</td>
					<td></td>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
						<input type="text" style="text-align:center;width:123px;" id="jpn_anak1_nama_kondisi_terakhir" name="jpn_anak1_nama_kondisi_terakhir" value="<?=$ls_jpn_anak1_nama_kondisi_terakhir;?>" readonly class="disabled">
						<input type="hidden" id="jpn_anak1_kode_kondisi_terakhir" name="jpn_anak1_kode_kondisi_terakhir" value="<?=$ls_jpn_anak1_kode_kondisi_terakhir;?>">	
					</td>
					<td></td>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
						<input type="text" id="jpn_anak1_tgl_kondisi_terakhir" name="jpn_anak1_tgl_kondisi_terakhir" value="<?=$ld_jpn_anak1_tgl_kondisi_terakhir;?>" style="width:90px;" readonly class="disabled">				
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>							
				</tr>
				
				<!--  -------------------- ANAK II ------------------------------------>
													
				<tr>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">Anak II</td>
					<td><input type="text" id="jpn_anak2_nama" name="jpn_anak2_nama" value="<?=$ls_jpn_anak2_nama;?>" style="width:240px;" maxlength="100" tabindex="12" readonly class="disabled">
					</td>
					<td></td>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
						<input type="text" style="text-align:center;width:123px;" id="jpn_anak2_nama_kondisi_terakhir" name="jpn_anak2_nama_kondisi_terakhir" value="<?=$ls_jpn_anak2_nama_kondisi_terakhir;?>" readonly class="disabled">
						<input type="hidden" id="jpn_anak2_kode_kondisi_terakhir" name="jpn_anak2_kode_kondisi_terakhir" value="<?=$ls_jpn_anak2_kode_kondisi_terakhir;?>">	
					</td>
					<td></td>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
						<input type="text" id="jpn_anak2_tgl_kondisi_terakhir" name="jpn_anak2_tgl_kondisi_terakhir" value="<?=$ld_jpn_anak2_tgl_kondisi_terakhir;?>" style="width:90px;" readonly class="disabled">				
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>							
				</tr>
				
				<!--  -------------------- ORANG TUA ---------------------------------->
																
				<tr>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">Orang Tua</td>
					<td><input type="text" id="jpn_ortu_nama" name="jpn_ortu_nama" value="<?=$ls_jpn_ortu_nama;?>" style="width:240px;" maxlength="100" tabindex="15" readonly class="disabled">
					</td>
					<td></td>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
						<input type="text" style="text-align:center;width:123px;" id="jpn_ortu_nama_kondisi_terakhir" name="jpn_ortu_nama_kondisi_terakhir" value="<?=$ls_jpn_ortu_nama_kondisi_terakhir;?>" readonly class="disabled">
						<input type="hidden" id="jpn_ortu_kode_kondisi_terakhir" name="jpn_ortu_kode_kondisi_terakhir" value="<?=$ls_jpn_ortu_kode_kondisi_terakhir;?>">	
					</td>
					<td></td>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
						<input type="text" id="jpn_ortu_tgl_kondisi_terakhir" name="jpn_ortu_tgl_kondisi_terakhir" value="<?=$ld_jpn_ortu_tgl_kondisi_terakhir;?>" style="width:90px;" readonly class="disabled">				
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>							
				</tr>
				<tr>
					<th colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
				</tr>
				<tr>
					<td colspan="1">
						<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_agenda_jpn_ahliwarisgantianak.php?task=edit&kode_tk=<?=$ls_kode_tk;?>&kode_klaim=<?=$ls_kode_klaim;?>&root_sender=pn5040.php&sender=pn5040.php&sender_activetab=3&sender_mid=<?=$mid;?>','Ubah Data Anak',650,400,'no');"><img src="../../images/application_form_edit.png" border="0" alt="Tambah" align="absmiddle" />&nbsp;Ubah Anak I/II</a>	
					</td>	
					<td colspan="4" style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;"><i>Tanggal saat mencapai pensiun:</i></td>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
            			<input type="text" id="jpn_tk_tgl_mulai_pensiun" name="jpn_tk_tgl_mulai_pensiun" value="<?=$ld_jpn_tk_tgl_mulai_pensiun;?>" style="width:90px;" maxlength="10" onblur="convert_date(jpn_tk_tgl_mulai_pensiun);"  readonly class="disabled">				
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>						
				</tr>	

				<?
      				if ($ls_mess !="-")  	
        			{
					?> 
           				<tr>
							<th colspan="10">
								<legend style="background: #ccffff; border: 1px solid #CCC; text-align:center;font: 11px Arial, Verdana, Helvetica, sans-serif; color:#ff0000">
									<i><?=$ls_mess;?></i>
								</legend>
							</th>	
						</tr>	
					<?  			
      				}
      			?>			
				
				<!--  -------------------- end ------------------------------------>
																																														 
			</tbody>	 
		</table>	
		</br>																		 	    																						  
  </fieldset>
		
	</br>
	
	<fieldset><legend>Amalgamasi JP</legend>
    <table id="tblrincianjp2" width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>			
        <tr>
        	<th colspan="7"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
        </tr>	
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No. Referensi</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">NIK</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama TK</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tempat Lahir</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tgl Lahir</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Kode TK</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Kode TK AG</th>                 		
        </tr>
        <tr>
        	<th colspan="7"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        </tr>
        <?
        if ($ln_dtl=="")
        {
         	 $ln_dtl = "0";
        }		
        if ($ls_kode_klaim!="")
        {									
          $sql = "select 
                      a.kode_klaim, a.kode_tk, a.kpj, a.nomor_identitas, a.nama_lengkap nama_tk, a.tempat_lahir, 
                      to_char(tgl_lahir,'dd/mm/yyyy') tgl_lahir, a.kode_tk_gabung,
					  (select count (1)
						from kn.kn_iuran_tk c
						where     c.kode_tk = a.kode_tk
							  and exists
									  (select null
										from kn.kn_iuran_tk_prg b
										where     b.kode_tk = c.kode_tk
											  and b.kode_iuran = c.kode_iuran
											  and b.kd_prg = '4')) cek_iur_jp 
                  from sijstk.pn_klaim_tkgabung a
                  where kode_klaim = :p_kode_klaim
                  order by a.kode_tk ";													
          $proc = $DB->parse($sql);
			oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
			// 20-03-2024 penyesuaian bind variable
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;
          while ($row = $DB->nextrow())
          {
            ?>									
            	<?php if($row['CEK_IUR_JP'] > 0){echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";} else {echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff")." style='position: absolute; top: 0; left: -4000px;'>";}?>					
              <td>								
                <input type="hidden" id="d_jp_kpj<?=$i;?>" name="d_jp_kpj<?=$i;?>" value="<?=$row['KPJ'];?>" maxlength="15">
								<input type="hidden" id="d_jp_nik<?=$i;?>" name="d_jp_nik<?=$i;?>" value="<?=$row['NOMOR_IDENTITAS'];?>">
								<input type="hidden" id="d_jp_nama_tk<?=$i;?>" name="d_jp_nama_tk<?=$i;?>" value="<?=$row['NAMA_TK'];?>">
								<input type="hidden" id="d_jp_tempat_lahir<?=$i;?>" name="d_jp_tempat_lahir<?=$i;?>" value="<?=$row['TEMPAT_LAHIR'];?>">
								<input type="hidden" id="d_jp_tgl_lahir<?=$i;?>" name="d_jp_tgl_lahir<?=$i;?>" value="<?=$row['TGL_LAHIR'];?>">
								<input type="hidden" id="d_jp_kode_tk<?=$i;?>" name="d_jp_kode_tk<?=$i;?>" value="<?=$row['KODE_TK'];?>">
								<?=$row['KPJ'];?>
							</td>
							<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NOMOR_IDENTITAS'];?></td>							
              <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_TK'];?></td>
							<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['TEMPAT_LAHIR'];?></td>
							<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['TGL_LAHIR'];?></td>
							<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['KODE_TK'];?></td>
							<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['KODE_TK_GABUNG'];?></td>																																								
            </tr>
            <?				    							
            $i++;//iterasi i				
          }	//end while
          $ln_dtl=$i;
        }
        ?>																																				 
      </tbody>
      <tr>
        <td colspan="7"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td>											
        </td>
  		</tr>			
      <tr>
				<td colspan="7" style="text-align:right;">
  				<input type="hidden" id="d_kounter_dtl_jp2" name="d_kounter_dtl_jp2" value="<?=$ln_dtl;?>">
          <input type="hidden" id="d_count_dtl_jp2" name="d_count_dtl_jp2" value="<?=$ln_countdtl;?>">
          <input type="hidden" name="d_realdtl_showmessage_jp2" style="border-width: 0;text-align:right">								
					<?
					if ($ls_status_klaim =="AGENDA")
          {
           	 ?> 
          	 <a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_agenda_jpn_amalgamasi.php?task=edit&kode_tk=<?=$ls_kode_tk;?>&kode_klaim=<?=$ls_kode_klaim;?>&root_sender=pn5040.php&sender=pn5040.php&sender_activetab=3&sender_mid=<?=$mid;?>','Amalgamasi JP',860,600,'no');"><img src="../../images/plus.png" border="0" alt="Tambah" align="absmiddle" />&nbsp;Entry Amalgamasi JP</a>
						 <?	               
          }
          ?>				
  			</td>									              
      </tr>
      <tr>
        <td colspan="7">&nbsp;</td>											
        </td>
  		</tr>				
      <tr>
      	<th colspan="7">
						<legend style="background: #ccffff; border: 1px solid #CCC; text-align:center;font: 11px Arial, Verdana, Helvetica, sans-serif; color:#ff0000"><i>Lakukan Amalgamasi JP jika ada lebih dari satu kartu peserta ...!</i></legend>
				</th>	
      </tr>																																												 							
    </table>
	</fieldset>

	</br>
		
	<fieldset><legend>Informasi Masa Iur, Bulan Kepesertaan dan Density Rate</legend>
    <table id="tblrincianjp3" width="98%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>
        <tr>
        	<th colspan="13"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
        </tr>	
        <tr>
			<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No. Referensi</th>
			<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Perusahaan</th>
			<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">BLTH</th>
			<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Flg Rlx</th>
			<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Lunas Rlx</th>
			<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tgl Bayar</th>
			<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Upah</th>
			<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Upah Terhitung</th>
			<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Iuran JP</th>
			<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Index Inflasi</th>
			<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Upah Tertimbang</th> 
			<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Iur htg Lumpsum</th>
			<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Masa Iur</th>		
        </tr>
        <tr>
        	<th colspan="13"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        </tr>
        <?
        if ($ln_dtl=="")
        {
         	 $ln_dtl = "0";
        }		
        if ($ls_kode_klaim!="")
        {	
		// modified 09092020
			/*
          $sql = "select a.kode_klaim, a.kode_tk, (select kpj from sijstk.pn_klaim_tkgabung where kode_klaim = a.kode_klaim and kode_tk = a.kode_tk) kpj,
                      a.kode_perusahaan, (select nama_perusahaan from sijstk.vw_pn_perusahaan where kode_perusahaan = a.kode_perusahaan and rownum = 1) nama_perusahaan,
                      (select npp from sijstk.vw_pn_perusahaan where kode_perusahaan = a.kode_perusahaan and rownum = 1) npp_perusahaan,
                      a.kode_divisi, a.kode_segmen, to_char(a.blth,'mm-yyyy') blth, to_char(a.tgl_bayar,'dd/mm/yyyy') tgl_bayar,
                      a.nom_iuran_jp_prs, a.nom_iuran_jp_tk, a.nom_iuran_jp,
                      a.nom_upah, a.akm_upah_blth, a.nom_batas_upah, a.nom_upah_terhitung, a.indeks_inflasi, a.nom_upah_tertimbang,
                      a.nom_upah_lumpsum, a.rate_iuran_jp, a.nom_iuran_jp_lumpsum, nvl(a.flag_rlx_jpn,'T') flag_rlx_jpn,
					  (
                        select iur.kode_iuran from kn.kn_iuran_perusahaan iur
                        where iur.kode_perusahaan = a.kode_perusahaan
                        and iur.kode_divisi = a.kode_divisi
                        and iur.kode_segmen = a.kode_segmen
                        and iur.blth = a.blth
                      ) kode_iuran,
					  nvl((
                        select nvl(iur.status_lunas,'T') from kn.kn_iuran_perusahaan_rlx iur
                        where iur.kode_perusahaan = a.kode_perusahaan
                        and iur.kode_divisi = a.kode_divisi
                        and iur.kode_segmen = a.kode_segmen
                        and iur.blth = a.blth
                      ),'-') flag_rlx_jpn_lunas,
					  'Y' masa_iur
                  from sijstk.pn_klaim_tkupah a
                  where kode_klaim = '$ls_kode_klaim'
                  order by a.blth, to_char(a.tgl_bayar,'yyyymmdd') ";
					*/
			$sql = "
				select 
				case 
				  when flag_rlx_jpn = 'Y' then
					case
					  when tgl_klaim > tgl_akhir_pelunasan and flag_rlx_jpn = 'Y' and flag_rlx_jpn_lunas = 'T' then
						'T'
					  else
						'Y'
					end
				  else
					'Y'
				end masa_iur,
				aa.*
				from
				(
				  select a.kode_klaim, a.kode_tk, (select kpj from sijstk.pn_klaim_tkgabung where kode_klaim = a.kode_klaim and kode_tk = a.kode_tk) kpj,
					  a.kode_perusahaan, (select nama_perusahaan from sijstk.vw_pn_perusahaan where kode_perusahaan = a.kode_perusahaan and rownum = 1) nama_perusahaan,
					  (select npp from sijstk.vw_pn_perusahaan where kode_perusahaan = a.kode_perusahaan and rownum = 1) npp_perusahaan,
					  a.kode_divisi, a.kode_segmen, a.blth blth_order, to_char(a.blth,'mm-yyyy') blth, to_char(a.tgl_bayar,'dd/mm/yyyy') tgl_bayar,
					  a.nom_iuran_jp_prs, a.nom_iuran_jp_tk, a.nom_iuran_jp,
					  a.nom_upah, a.akm_upah_blth, a.nom_batas_upah, a.nom_upah_terhitung, a.indeks_inflasi, a.nom_upah_tertimbang,
					  a.nom_upah_lumpsum, a.rate_iuran_jp, a.nom_iuran_jp_lumpsum, nvl(a.flag_rlx_jpn,'T') flag_rlx_jpn,
					  case when  nvl(a.flag_rlx_jpn,'T')='Y' then 
                      (
						select iur.kode_iuran from kn.kn_iuran_perusahaan iur
						where iur.kode_perusahaan = a.kode_perusahaan
						and iur.kode_divisi = a.kode_divisi
						and iur.kode_segmen = a.kode_segmen
						and iur.blth = a.blth and exists(select null from kn.kn_iuran_perusahaan_rlx rlx where rlx.kode_iuran=iur.kode_iuran)
					  ) else '' end as kode_iuran,
					 case when  nvl(a.flag_rlx_jpn,'T')='Y' then 
				  nvl((
						select max(nvl(iur.status_lunas,'T')) from kn.kn_iuran_perusahaan_rlx iur
						where iur.kode_perusahaan = a.kode_perusahaan
						and iur.kode_divisi = a.kode_divisi
						and iur.kode_segmen = a.kode_segmen
						and iur.blth = a.blth
					  ),'-') else 'T' end as flag_rlx_jpn_lunas,
					  (
						select tgl_akhir_pelunasan from kn.kn_tarif_relaksasi
						where kode_tarif = '3'
						and rownum = 1
					  ) tgl_akhir_pelunasan,
					  (
						select b.tgl_klaim from pn.pn_klaim b
						where b.kode_klaim = a.kode_klaim
					  ) tgl_klaim
				  from sijstk.pn_klaim_tkupah a
				  where kode_klaim = :p_kode_klaim
				  order by a.blth, a.tgl_bayar
				) aa --order by blth_order, blth, tgl_bayar
				";
          $proc = $DB->parse($sql);
			oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
			// 20-03-2024 penyesuaian bind variable
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;
          while ($row = $DB->nextrow())
          {
            ?>									
            	<?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>					
							<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['KPJ'];?></td>							
              <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_PERUSAHAAN'];?></td>
							<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
								<?php
									if($row['FLAG_RLX_JPN'] == "Y")
									{
								?>
									<a href="#" title="Relaksasi Iuran Jaminan Pensiun, klik untuk melihat detil pembayaran." onclick="NewWindow4('http://<?=$HTTP_HOST;?>/mod_kn/ajax/kn5004_iurantk_detil.php?kode_iuran=<?=$row["KODE_IURAN"];?>&nama_tk=<?=$ls_nama_tk;?>&kode_tk=<?=$row["KODE_TK"];?>&flag_rlx_jp=<?=$row["FLAG_RLX_JPN"];?>','',1200,700,1)"><font color="#009999"><b><?=$row['BLTH'];?></b></font> </a>
								<?php
									}
									else
									{
								?>
									<?=$row['BLTH'];?>
								<?php
									}
								?>
							</td>
							<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['FLAG_RLX_JPN'];?></td>
							<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['FLAG_RLX_JPN_LUNAS'];?></td>
							<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['TGL_BAYAR'];?></td>
							<td style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_UPAH'],2,".",",");?></td>																
							<td style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_UPAH_TERHITUNG'],2,".",",");?></td>
							<td style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_IURAN_JP'],2,".",",");?></td>
							<td style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['INDEKS_INFLASI'],2,".",",");?></td>
							<td style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_UPAH_TERTIMBANG'],2,".",",");?></td>
							<td style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_IURAN_JP_LUMPSUM'],2,".",",");?></td>	
							<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['MASA_IUR'];?></td>	
            </tr>
            <?				    							
            $i++;//iterasi i				
          }	//end while
          $ln_dtl=$i;
        }
        ?>																																				 
      </tbody>
      <tr>
        <td colspan="13"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td>											
        </td>
  		</tr>
      <tr>
        <td colspan="13" style="text-align:right;"></td>									              
      </tr>	
      <tr>
        <td colspan="13" style="text-align:right;"></td>									              
      </tr>
			<?
			//ambil data kepesertaan	 
    //   $sql2 = "select 
    //               tgl_awal_keps, tgl_akhir_keps, cnt_bln_iur, cnt_bln_keps,
    //               case when nvl(cnt_bln_keps,0)=0 then 0
    //               else
    //                   round(100*nvl(cnt_bln_iur,0)/nvl(cnt_bln_keps,0),2)
    //               end density_rate    
    //           from
    //           (			
  	// 						select
    //                 min(blth) tgl_awal_keps, 
    //                 trunc(min(tgl_kejadian),'mm') tgl_akhir_keps, 
    //                 count(distinct(blth)) cnt_bln_iur,
    //                 case when kode_jenis_kasus = 'KS11' then
    //                     ceil(months_between(trunc(min(nvl(tgl_na,tgl_kejadian)),'mm'),min(blth)))+1 
    //                 else
    //                     ceil(months_between(trunc(min(tgl_kejadian),'mm'),min(blth)))+1 
    //                 end cnt_bln_keps
    //             from
    //             (           
    //                 select 
    //                     a.blth, b.tgl_kejadian, b.kode_jenis_kasus, 
    //                     (
    //                         select tgl_na from sijstk.vw_kn_tk 
    //                         where kode_tk = b.kode_tk 
    //                         and kode_perusahaan = b.kode_perusahaan 
    //                         and kode_divisi = b.kode_divisi
    //                         and kode_segmen = b.kode_segmen
    //                         and aktif_tk = 'T'
    //                         and to_char(tgl_na,'yyyymmdd') <> '30001230'
    //                         and rownum = 1										
    //                     ) tgl_na
    //                 from sijstk.pn_klaim_tkupah a, sijstk.pn_klaim b
    //                 where a.kode_klaim = b.kode_klaim
    //                 and a.kode_klaim = '$ls_kode_klaim'  
    //             )
    //             group by kode_jenis_kasus
  	// 					)";
	
		// Bug Fix Permasalahan Berulang
			$sql2="select 
			tgl_awal_keps, tgl_akhir_keps, cnt_bln_iur, cnt_bln_keps,
			case when nvl(cnt_bln_keps,0)=0 then 
				0
			else
				round(100*nvl(cnt_bln_iur,0)/nvl(cnt_bln_keps,0),2)
			end density_rate
		from
		(			
			select
				min(blth) tgl_awal_keps, 
				case when kode_jenis_kasus = 'KS11' then
					add_months(trunc(min(nvl(tgl_na,tgl_kejadian)),'mm'),-1)
				else
					trunc(min(tgl_kejadian),'mm')
				end tgl_akhir_keps,
				count(distinct(blth)) cnt_bln_iur,
				case when kode_jenis_kasus = 'KS11' then
					ceil(months_between(trunc(min(nvl(tgl_na,tgl_kejadian)),'mm'),min(blth)))
				else
					ceil(months_between(trunc(min(tgl_kejadian),'mm'),min(blth)))+1 
				end cnt_bln_keps
			from
			(           
				select 
					a.blth, b.tgl_kejadian, b.kode_jenis_kasus, b.tgl_na
				from pn.pn_klaim_tkupah a, pn.pn_klaim b
				where a.kode_klaim = b.kode_klaim
				and a.kode_klaim = :p_kode_klaim  
			)
			group by kode_jenis_kasus
		)";

		$proc = $DB->parse($sql2);
			oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
			// 20-03-2024 penyesuaian bind variable
		$DB->execute();
		$row = $DB->nextrow();
			
			$ld_jp_tgl_awal_kepesertaan = $row["TGL_AWAL_KEPS"];
			$ld_jp_tgl_akhir_kepesertaan = $row["TGL_AKHIR_KEPS"];
			$ln_jp_jml_bln_masa_keps = $row["CNT_BLN_KEPS"];
			$ln_jp_jml_bln_masa_iur = $row["CNT_BLN_IUR"];
			$ln_jp_density_rate = $row["DENSITY_RATE"];
			
			// 09092020, get masa iur untuk kebutuhan relaksasi iuran JP
			$sql2 = "
				select 
				count(distinct(blth)) jml_masa_iur
				from
				(
				  select 
				  case 
					when flag_rlx_jpn = 'Y' then
					  case
						when tgl_klaim > tgl_akhir_pelunasan and flag_rlx_jpn = 'Y' and flag_rlx_jpn_lunas = 'T' then
						  'T'
						else
						  'Y'
					  end
					else
					  'Y'
				  end masa_iur,
				  aa.*
				  from
				  (
					select a.kode_klaim, a.kode_tk, (select kpj from sijstk.pn_klaim_tkgabung where kode_klaim = a.kode_klaim and kode_tk = a.kode_tk) kpj,
						a.kode_perusahaan, (select nama_perusahaan from sijstk.vw_pn_perusahaan where kode_perusahaan = a.kode_perusahaan and rownum = 1) nama_perusahaan,
						(select npp from sijstk.vw_pn_perusahaan where kode_perusahaan = a.kode_perusahaan and rownum = 1) npp_perusahaan,
						a.kode_divisi, a.kode_segmen, to_char(a.blth,'mm-yyyy') blth, to_char(a.tgl_bayar,'dd/mm/yyyy') tgl_bayar,
						a.nom_iuran_jp_prs, a.nom_iuran_jp_tk, a.nom_iuran_jp,
						a.nom_upah, a.akm_upah_blth, a.nom_batas_upah, a.nom_upah_terhitung, a.indeks_inflasi, a.nom_upah_tertimbang,
						a.nom_upah_lumpsum, a.rate_iuran_jp, a.nom_iuran_jp_lumpsum, nvl(a.flag_rlx_jpn,'T') flag_rlx_jpn,
					(
						  select iur.kode_iuran from kn.kn_iuran_perusahaan iur
						  where iur.kode_perusahaan = a.kode_perusahaan
						  and iur.kode_divisi = a.kode_divisi
						  and iur.kode_segmen = a.kode_segmen
						  and iur.blth = a.blth
						) kode_iuran,
					nvl((
						  select nvl(iur.status_lunas,'T') from kn.kn_iuran_perusahaan_rlx iur
						  where iur.kode_perusahaan = a.kode_perusahaan
						  and iur.kode_divisi = a.kode_divisi
						  and iur.kode_segmen = a.kode_segmen
						  and iur.blth = a.blth
						),'-') flag_rlx_jpn_lunas,
						(
						  select tgl_akhir_pelunasan from kn.kn_tarif_relaksasi
						  where kode_tarif = '3'
						  and rownum = 1
						) tgl_akhir_pelunasan,
						(
						  select b.tgl_klaim from pn.pn_klaim b
						  where b.kode_klaim = a.kode_klaim
						) tgl_klaim
					from sijstk.pn_klaim_tkupah a
					where kode_klaim = :p_kode_klaim
					order by a.blth, to_char(a.tgl_bayar,'yyyymmdd')
				  ) aa 
				) where masa_iur = 'Y'
				";
			$proc = $DB->parse($sql2);
			oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
			// 20-03-2024 penyesuaian bind variable
			$DB->execute();
			$row = $DB->nextrow();
			$ln_jp_jml_bln_masa_iur = $row["JML_MASA_IUR"];
			if ($ln_jp_jml_bln_masa_keps > 0)
			{				
				$ln_jp_density_rate = round(100*($ln_jp_jml_bln_masa_iur/$ln_jp_jml_bln_masa_keps),2);
			}
			else
			{
				$ln_jp_density_rate = 0;
			}

			$sql_density_rate = "
						BEGIN
							PN.P_PN_PN504001.X_AGENDA_INPUTJP_DENSITYRATE(
							:p_kode_klaim,
							:p_tgl_awal_keps,
							:p_tgl_akhir_keps,
							:p_cnt_bln_iur,
							:p_cnt_bln_keps,
							:p_density_rate,
							:p_sukses,
							:p_mess
							);
						END;";
						$proc_density_rate = $DB->parse($sql_density_rate);
						oci_bind_by_name($proc_density_rate, ":p_kode_klaim", $ls_kode_klaim, 100);
						oci_bind_by_name($proc_density_rate, ":p_tgl_awal_keps", $p_tgl_awal_keps, 50);
						oci_bind_by_name($proc_density_rate, ":p_tgl_akhir_keps", $p_tgl_akhir_keps, 50);
						oci_bind_by_name($proc_density_rate, ":p_cnt_bln_iur", $p_cnt_bln_iur, 50);
						oci_bind_by_name($proc_density_rate, ":p_cnt_bln_keps", $p_cnt_bln_keps, 50);
						oci_bind_by_name($proc_density_rate, ":p_density_rate", $p_density_rate, 50);
						oci_bind_by_name($proc_density_rate, ":p_sukses", $p_sukses, 10);
						oci_bind_by_name($proc_density_rate, ":p_mess", $p_mess, 4000);

						$DB->execute();
							
			
			?>
      <tr>
        <td style="text-align:center;" colspan="2"><i>Masa Kepesertaan :<i>
        </td>	  									
        <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;" colspan="8"><?=$p_tgl_awal_keps;?> s/d <?=$p_tgl_akhir_keps;?> (<?=$p_cnt_bln_keps;?> Bulan)</td>								        
      </tr>	
      <tr>
        <td style="text-align:center;" colspan="2"><i>Masa Iur :<i>
        </td>	  									
        <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;" colspan="8"><?=$ln_jp_jml_bln_masa_iur;?> Bulan</td>								        
      </tr>
      <tr>
        <td style="text-align:center;" colspan="2"><i>Density Rate :<i>
        </td>	  									
        <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;" colspan="8"><?=$p_density_rate;?> %</td>								        
      </tr>
	  <?php if($ls_jml_bulan_iur_jp_lebih > 0) { ?>
	  <tr>
        <td colspan="13">&nbsp;</td>											
        </td>
  		</tr>				
      <tr>
      	<th colspan="13">
						<legend style="background: #ccffff; border: 1px solid #CCC; text-align:center;font: 11px Arial, Verdana, Helvetica, sans-serif; color:#ff0000"><i>Peserta Telah Melewati Perpanjangan Kepesertaan JP Maksimal</i></legend>
				</th>	
      </tr>	
	  <?php } ?>																																																						 							
    </table>
	</fieldset>
	
	</br>	
	</br>								
</div>
