<!-- FIELDS VIEW -->
<!-- ########### -->

<!-- BULAN MANFAAT JKP -->
<?php
	if($field == "tb_bulan_manfaat") { 
		$ls_bulan_manfaat = $_POST["tb_bulan_manfaat"];
?>
<div class="form-row_kiri">	
	  <label>Bulan Manfaat *</label>
	<select size="1" id="tb_bulan_manfaat" name="tb_bulan_manfaat" class="select_format" style="width:320px;">			
				<option selected value="">Semua</option>			
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>				
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>					
	</select>						
</div>			
<div class="clear"></div>
<?php } ?>

<!-- STATUS PANGEJUAN JKP -->
<?php
	if($field == "tb_status_pengajuan") { 
		$ls_status_pengajuan = $_POST["tb_status_pengajuan"];
?>
<div class="form-row_kiri">	
	  <label>Status Pengajuan *</label>
	<select size="1" id="tb_status_pengajuan" name="tb_status_pengajuan" class="select_format" style="width:320px;">			
				<option selected value="">Semua</option>			
				<option value="Terkirim">Terkirim</option>
				<option value="Diverifikasi">Diverifikasi</option>
				<option value="Ditetapkan">Ditetapkan</option>	
				<option value="Disetujui">Disetujui</option>	
				<option value="Dibayarkan">Dibayarkan</option>
				<option value="Ditolak">Ditolak</option>				
	</select>						
</div>			
<div class="clear"></div>
<?php } ?>


<!-- KODE SEGMEN + SEMUA-->
<?php
	if($field == "tb_kode_segmen_all") { 
		$ls_kode_segmen = $_POST["tb_kode_segmen_all"];
?>
<div class="form-row_kiri">	
	  <label>Segmentasi *</label>
	<select size="1" id="tb_kode_segmen_all" name="tb_kode_segmen_all" class="select_format" style="width:320px;">
	<option value="">SEMUA</option>
	  <? 
		$sql = "select kode_segmen,nama_segmen from sijstk.kn_kode_segmen order by no_urut";
		$DB->parse($sql);
		$DB->execute();
		while($row = $DB->nextrow())
		{
			if ($row["KODE_SEGMEN"] == $ls_kode_segmen) {
				echo "<option selected value=\"".$row["KODE_SEGMEN"]."\">".$row["KODE_SEGMEN"]." - ".$row["NAMA_SEGMEN"]."</option>";
			} else {
				echo "<option value=\"".$row["KODE_SEGMEN"]."\">".$row["KODE_SEGMEN"]." - ".$row["NAMA_SEGMEN"]."</option>";
			}
		}
	?>
	</select>						
</div>			
<div class="clear"></div>
<?php } ?>


<!-- ALASAN SELESAI KLAIM -->
<?php
	if($field == "alasan_selesai") { 
		$ls_alasan_selesai = $_POST["alasan_selesai"];
?>
<div class="form-row_kiri">	
	  <label>Alasan Selesai Klaim *</label>
	<select size="1" id="alasan_selesai" name="alasan_selesai" class="select_format" style="width:320px;">			
				<option selected value="">SEMUA</option>			
				<option value="MENINGGAL">MENINGGAL</option>
				<option value="KEPESERTAAN AKTIF">KEPESERTAAN AKTIF</option>
				<option value="TELAH DIBAYARKAN MANFAAT BULAN KE-6">TELAH DIBAYARKAN MANFAAT BULAN KE-6</option>
	</select>						
</div>			
<div class="clear"></div>
<?php } ?>


<!-- PERIODE BLTH-->
<?php 
	if($field == "periode_blth") { 
		$ld_periode_blth = $_POST["periode_blth"];
		if (!isset($ld_periode_blth)) {
			$sql = "select replace(substr(to_char(sysdate,'dd/mm/yyyy'),4,7),'/','-')  tgl from dual";
			$DB->parse($sql);
			$DB->execute();
			$row = $DB->nextrow();
			$ld_periode_blth= $row["TGL"];
		} 
?>
<div class="form-row_kiri">	
	  <label>BLTH Laporan*</label>
	<input style="width: 80px; background-color:#CCCCCC;" readonly="readonly" type="text" name="periode_blth" id="periode_blth" size="12" onblur="f_ajax_set_blth();"  value="<?=$ld_periode_blth;?>"> 
	<input id="btn_periode" type="image" align="top" onclick="return showCalendar('periode_blth', 'dd-mm-yyyy');" src="../../images/calendar.gif" />										
</div>			
<div class="clear"></div>
<?php } ?> 


<!-- SKALA USAHA -->
<?php
	if($field == "skala_usaha") { 
		$ls_skala_usaha = $_POST["skala_usaha"];
?>
<div class="form-row_kiri">	
	<label>Skala Usaha *</label>
	<select size="1" id="skala_usaha" name="skala_usaha" class="select_format" style="width:320px;">	
	<option value="-1">SEMUA</option>			
	<? 
		$sql = "select KETERANGAN from KN.KN_KODE_SKALA_USAHA A  where status_nonaktif = 'T' order by kode_skala_usaha asc";
		$DB->parse($sql);
		$DB->execute();
		while($row = $DB->nextrow())
		{
			if ($row["KETERANGAN"] == $ls_status_pengajuan) {
				echo "<option selected value=\"".$row["KETERANGAN"]."\">".$row["KETERANGAN"]."</option>";
			} else {
				echo "<option value=\"".$row["KETERANGAN"]."\">".$row["KETERANGAN"]."</option>";
			}
		}
	?>
	</select>					
</div>			
<div class="clear"></div>
<?php } ?>


<!-- KODE ILO -->
<?php
	if($field == "kode_ilo") {
		$ls_kode_ilo = $_POST["KODE_ILO"]; 
		$ls_nama_ilo = $_POST["NAMA_ILO"];  
?>
<div class="form-row_kiri">
	<label>Kode ILO / Bidang Usaha</label>
	<input type="text" id="kode_ilo" name="kode_ilo" readonly="readonly" class="disabled" value="<?=$ls_kode_ilo;?>" size="10" style="width: 80px; background-color:#CCCCCC;">
	<input type="text" name="nama_ilo" id="nama_ilo" readonly="readonly" class="disabled" size="30" style="width: 320px; background-color:#CCCCCC;" value="<?=$ls_nama_ilo;?>">
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kode_ilo.php?p=kn9002.php&a=adminForm&b=kode_ilo&c=nama_ilo&d=kode_usaha_utama&e=tarif_ilo','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php } ?> 

<!-- ALASAN PHK (JKP) -->

<?php
	if($field == "alasan_phk") { 
		//$ls_alasan_phk = $_POST["alasan_phk"];
		
?> 
<div class="form-row_kiri">	
	<label>Alasan PHK *</label>
	<input type="text" id="alasan_phk" name="alasan_phk" readonly="readonly" class="disabled" value="<?=$ls_alasan_phk;?>" size="10" style="width: 80px; background-color:#CCCCCC;">
</div>			
<div class="clear"></div>
<div>
	<label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	<textarea name="alasan_nama_phk" id="alasan_nama_phk" size="30" rows="3" cols="84" value="<?=$ls_alasan_nama_phk;?>" style="background-color:#CCCCCC;" readonly> </textarea> 
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn9012_lov_alasan_phk.php?p=kn900121.php&a=adminForm&b=alasan_phk&c=alasan_nama_phk','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>	
</div>			
<div class="clear"></div>
<?php } ?>


<!-- HUBUNGAN KERJA -->
<?php
	if($field == "hubungan_kerja") { 
		$ls_hubungan_kerja = $_POST["hubungan_kerja"];
?>
<div class="form-row_kiri">	
	  <label>Hubungan Kerja *</label>
	<select size="1" id="hubungan_kerja" name="hubungan_kerja" class="select_format" style="width:320px;">			
				<option selected value="-1">SEMUA</option>			
				<option value="PKWT">PKWT</option>
				<option value="PKWTT">PKWTT</option>
	</select>						
</div>			
<div class="clear"></div>
<?php } ?>






<!-- JENIS PELATIHAN (JKP) -->
<?php
	if($field == "jenis_pelatihan") { 
		$ls_jenis_pelatihan = $_POST["jenis_pelatihan"];
?>
<div class="form-row_kiri">	
	  <label>Jenis Pelatihan *</label>
	<select size="1" id="jenis_pelatihan" name="jenis_pelatihan" class="select_format" style="width:320px;">			
				<option selected value="-1">SEMUA</option>			
				<option value="1">LURING</option>
				<option value="2">DARING</option>
				<option value="3">CAMPURAN (LURING & DARING)</option>
	</select>						
</div>			
<div class="clear"></div>
<?php } ?>


<!-- STATUS PELATIHAN (JKP) -->
<?php
	if($field == "status_pelatihan") { 
		$ls_status_pelatihan = $_POST["status_pelatihan"];
?>
<div class="form-row_kiri">	
	  <label>Status Pelatihan *</label>
	<select size="1" id="status_pelatihan" name="status_pelatihan" class="select_format" style="width:320px;">			
				<option selected value="-1">SEMUA</option>			
				<option value="Y">LULUS</option>
				<option value="T">TIDAK LULUS</option>
	</select>						
</div>			
<div class="clear"></div>
<?php } ?>

<!-- STATUS KLAIM (JKP) -->
<?php
	if($field == "status_klaim_jkp") { 
		$ls_status_klaim_jkp = $_POST["status_klaim_jkp"];
?>
<div class="form-row_kiri">	 
	  <label>Status Klaim *</label>
	<select size="1" id="status_klaim_jkp" name="status_klaim_jkp" class="select_format" style="width:320px;">			
				<option selected value="-1">SEMUA</option>			
				<option value="SELESAI">SELESAI</option>
				<option value="PROSES">PROSES</option> 
	</select>						
</div>			
<div class="clear"></div>
<?php } ?>


<!-- STATUS PENGAJUAN (ASIK) -->
<?php
	if($field == "status_pengajuan") { 
		$ls_status_pengajuan = $_POST["status_pengajuan"];
?>
<div class="form-row_kiri">	
	  <label>Status Pengajuan *</label>
	<select size="1" id="status_pengajuan" name="status_pengajuan" class="select_format" style="width:320px;">	
	<option value="">All</option>			
	<? 
		$sql = "select KETERANGAN from BPJSTKU.ASIK_MS_LOOKUP@to_ec c  where tipe = 'KODE_STATUS_PENGAJUAN_KLAIM'  and kode not in ('KLA2','KLA4')  order by KETERANGAN";
		$DB->parse($sql);
		$DB->execute();
		while($row = $DB->nextrow())
		{
			if ($row["KETERANGAN"] == $ls_status_pengajuan) {
				echo "<option selected value=\"".$row["KETERANGAN"]."\">".$row["KETERANGAN"]."</option>";
			} else {
				echo "<option value=\"".$row["KETERANGAN"]."\">".$row["KETERANGAN"]."</option>";
			}
		}
	?>
	</select>					
</div>			
<div class="clear"></div>
<?php } ?>





<!-- STATUS KLAIM ALL-->
<?php
	if($field == "tb_status_klaim_all") { 
		$ls_status_klaim = $_POST["tb_status_klaim_all"];
?>
<div class="form-row_kiri">	
	  <label>Status Klaim *</label>
	<select size="1" id="tb_status_klaim_all" name="tb_status_klaim_all" class="select_format" style="width:320px;">			
				<option value="">Semua</option>	
				<option selected value="AGENDA">Agenda</option>	
				<option value="AGENDA_TAHAP_I">Agenda Tahap 1</option>
				<option value="PENGAJUAN_TAHAP_I">Pengajuan Tahap 1</option>
				<option value="AGENDA_TAHAP_II">Agenda Tahap 2</option>				
				<option value="PENETAPAN">Penetapan</option>
				<option value="PERSETUJUAN">Persetujuan</option>
				<option value="DISETUJUI">Disetujui</option>		
				<option value="SELESAI">Selesai</option>
				<option value="BATAL">Batal</option>	
	</select>						
</div>			
<div class="clear"></div>
<?php } ?>


<!-- STATUS KLAIM -->
<?php
	if($field == "tb_status_klaim") { 
		$ls_status_klaim = $_POST["tb_status_klaim"];
?>
<div class="form-row_kiri">	
	  <label>Status Klaim *</label>
	<select size="1" id="tb_status_klaim" name="tb_status_klaim" class="select_format" style="width:320px;">			
				<option selected value="1">Semua</option>			
				<option value="AGENDA_TAHAP_I">Agenda Tahap 1</option>
				<option value="PENGAJUAN_TAHAP_I">Pengajuan Tahap 1</option>
				<option value="AGENDA_TAHAP_II">Agenda Tahap 2</option>				
				<option value="PENETAPAN">Penetapan</option>
				<option value="SELESAI">Selesai</option>
				<option value="BATAL">Batal</option>	
	</select>						
</div>			
<div class="clear"></div>
<?php } ?>

<!-- KODE SEGMEN + SEMUA-->
<?php
	if($field == "tb_kode_segmen_all") { 
		$ls_kode_segmen = $_POST["tb_kode_segmen_all"];
?>
<div class="form-row_kiri">	
	  <label>Segmentasi *</label>
	<select size="1" id="tb_kode_segmen_all" name="tb_kode_segmen_all" class="select_format" style="width:320px;">
	<option value="">SEMUA</option>
	  <? 
		$sql = "select kode_segmen,nama_segmen from sijstk.kn_kode_segmen order by no_urut";
		$DB->parse($sql);
		$DB->execute();
		while($row = $DB->nextrow())
		{
			if ($row["KODE_SEGMEN"] == $ls_kode_segmen) {
				echo "<option selected value=\"".$row["KODE_SEGMEN"]."\">".$row["KODE_SEGMEN"]." - ".$row["NAMA_SEGMEN"]."</option>";
			} else {
				echo "<option value=\"".$row["KODE_SEGMEN"]."\">".$row["KODE_SEGMEN"]." - ".$row["NAMA_SEGMEN"]."</option>";
			}
		}
	?>
	</select>						
</div>			
<div class="clear"></div>
<?php } ?>

<!-- KODE SEGMEN -->
<?php
	if($field == "tb_kode_segmen") { 
		$ls_kode_segmen = $_POST["tb_kode_segmen"];
?>
<div class="form-row_kiri">	
	  <label>Segmentasi *</label>
	<select size="1" id="tb_kode_segmen" name="tb_kode_segmen" class="select_format" style="width:320px;">
	  <? 
		$sql = "select kode_segmen,nama_segmen from sijstk.kn_kode_segmen order by no_urut";
		$DB->parse($sql);
		$DB->execute();
		while($row = $DB->nextrow())
		{
			if ($row["KODE_SEGMEN"] == $ls_kode_segmen) {
				echo "<option selected value=\"".$row["KODE_SEGMEN"]."\">".$row["KODE_SEGMEN"]." - ".$row["NAMA_SEGMEN"]."</option>";
			} else {
				echo "<option value=\"".$row["KODE_SEGMEN"]."\">".$row["KODE_SEGMEN"]." - ".$row["NAMA_SEGMEN"]."</option>";
			}
		}
	?>
	</select>						
</div>			
<div class="clear"></div>
<?php } ?>

<!-- KODE SEGMEN PU ONLY -->
<?php
	if($field == "tb_kode_segmen_pu") { 
		$ls_kode_segmen = $_POST["tb_kode_segmen_pu"];
?>
<div class="form-row_kiri">	
	  <label>Segmentasi *</label>
	<select size="1" id="tb_kode_segmen_pu" name="tb_kode_segmen_pu" class="select_format" style="width:320px;">
	  <? 
		$sql = "select kode_segmen, nama_segmen from sijstk.kn_kode_segmen where kode_segmen = 'PU'";
		$DB->parse($sql);
		$DB->execute();
		while($row = $DB->nextrow())
		{
			if ($row["KODE_SEGMEN"] == $ls_kode_segmen) {
				echo "<option selected value=\"".$row["KODE_SEGMEN"]."\">".$row["KODE_SEGMEN"]." - ".$row["NAMA_SEGMEN"]."</option>";
			} else {
				echo "<option value=\"".$row["KODE_SEGMEN"]."\">".$row["KODE_SEGMEN"]." - ".$row["NAMA_SEGMEN"]."</option>";
			}
		}
	?>
	</select>						
</div>			
<div class="clear"></div>
<?php } ?>

<!-- KODE KANTOR WILAYAH-->
<?php 
	if($field == "kode_kantor_wilayah") { 
		$ls_kode_kantor = $_POST["kode_kantor"];
		$ls_kode_kantor_wilayah = $_POST["kode_kantor_wilayah"];
		$ls_kode_kantor = $ls_kode_kantor == null ? $ses_kode_kantor : $ls_kode_kantor;
		if (isset($ls_kode_kantor)) {
			$sql = "SELECT KODE_KANTOR, KODE_KANTOR_INDUK, NAMA_KANTOR FROM MS.MS_KANTOR WHERE KODE_KANTOR = '$ls_kode_kantor'";
			$DB->parse($sql);
			$DB->execute();
			$row = $DB->nextrow();
			$ls_kode_kantor = $row["KODE_KANTOR"];
			$ls_kode_kantor_wilayah = $row["KODE_KANTOR_INDUK"];
			$ls_nama_kantor = $row["NAMA_KANTOR"];
		}
?>
<div class="form-row_kiri">
	<label>Kantor *</label>
	<input type="text" id="kode_kantor" name="kode_kantor" readonly="readonly" value="<?=$ls_kode_kantor;?>" size="10" style="width: 80px; background-color:#CCCCCC;">
	<input type="hidden" id="kode_kantor_wilayah" name="kode_kantor_wilayah" readonly="readonly" value="<?=$ls_kode_kantor_wilayah;?>" size="10" style="width: 80px; background-color:#CCCCCC;">
	<input type="text" name="nama_kantor_wilayah" id="nama_kantor_wilayah" readonly="readonly" size="30" style="width: 320px; background-color:#CCCCCC;" value="<?=$ls_nama_kantor;?>">
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kantor_kanwil_report.php?p=kn9002.php&a=adminForm&b=kode_kantor_wilayah&c=nama_kantor_wilayah&d=kode_kantor','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle">
    </a>   															 								
</div>
<div class="clear"></div>
<?php } ?>

<!-- KODE KANTOR -->
<?php 
	if($field == "kode_kantor") { 
		$ls_kode_kantor = $_POST["kode_kantor"];
		$ls_kode_kantor = $ls_kode_kantor == null ? $ses_kode_kantor : $ls_kode_kantor;
		if (isset($ls_kode_kantor)) {
			$sql = "SELECT KODE_KANTOR, NAMA_KANTOR FROM MS.MS_KANTOR WHERE KODE_KANTOR = '$ls_kode_kantor'";
			$DB->parse($sql);
			$DB->execute();
			$row = $DB->nextrow();
			$ls_kode_kantor = $row["KODE_KANTOR"];
			$ls_nama_kantor = $row["NAMA_KANTOR"];
		}
?>
<div class="form-row_kiri">
	<label>Kantor *</label>
	<input type="text" id="kode_kantor" name="kode_kantor" readonly="readonly" value="<?=$ls_kode_kantor;?>" size="10" style="width: 80px; background-color:#CCCCCC;">
	<input type="text" name="nama_kantor" id="nama_kantor" readonly="readonly" size="30" style="width: 320px; background-color:#CCCCCC;" value="<?=$ls_nama_kantor;?>">
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kantor_report_all.php?p=kn9002.php&a=adminForm&b=kode_kantor&c=nama_kantor','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle">
    </a>   															 								
</div>
<div class="clear"></div>
<?php } ?>

<!-- NPP PERUSAHAAN -->
<?php 
	if($field == "npp") { 
		$ls_npp = $_POST["npp"];
		$ls_nama_perusahaan = $_POST["nama_perusahaan"];
?>
<div class="form-row_kiri">
	<label>NPP *</label>
	<input type="text" id="npp" name="npp" value="<?=$ls_npp;?>" size="10" style="width: 80px;" >
	<input type="text" name="nama_perusahaan" id="nama_perusahaan" size="30" style="width: 320px;" value="<?=$ls_nama_perusahaan;?>">
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/kn9002_lov_perusahaan.php?p=kn900121.php&a=adminForm&b=npp&c=nama_perusahaan','',800,500,1)">   
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>   															 								
</div>
<div class="clear"></div>				
<?php } ?>

<!-- KPJ TENAGA KERJA -->
<?php 
	if($field == "tb_kpj") { 
		$ls_kpj = $_POST["tb_kpj"];
		$ls_kode_tk = $_POST["tb_kode_tk_kpj"];
		$ls_nik = $_POST["tb_nik_kpj"];
		$ls_nama_tk = $_POST["tb_nama_tk_kpj"];
		$ls_npp = $_POST["tb_npp_kpj"];
		$ls_kode_perusahaan = $_POST["tb_kode_perusahaan_kpj"];
		$ls_nama_perusahaan = $_POST["tb_nama_perusahaan_kpj"];
		$ls_tgl_lahir = $_POST["tb_tgl_lahir_kpj"];
		$ls_tgl_na = $_POST["tb_tgl_na_kpj"];
?>
<div class="form-row_kiri">
	<label>No KPJ</label>
	<input type="text" name="tb_kpj" id="tb_kpj" value="<?=$ls_kpj?>" readonly="readonly" style="width: 120px;" class="disabled">
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/kn9001_lov_kpj.php?p=kn900132.php&a=adminForm&b=&c=tb_npp_kpj&d=tb_nama_perusahaan_kpj&e=tb_tgl_lahir_kpj&f=tb_tgl_na_kpj&g=tb_kode_perusahaan_kpj&h=tb_kpj&j=tb_kode_tk_kpj&k=tb_nik_kpj&l=tb_nama_tk_kpj','',800,500,1);">   
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>   															 								
</div>
<div class="clear"></div>
<div class="form-row_kiri">
	<label>Nomor Identitas</label>
	<input type="hidden" name="tb_kode_tk_kpj" id="tb_kode_tk_kpj" value="<?=$ls_kode_tk?>">
	<input type="text" name="tb_nik_kpj" id="tb_nik_kpj" value="<?=$ls_nik?>" readonly="readonly" style="width: 220px;" class="disabled">
</div>
<div class="clear"></div>
<div class="form-row_kiri">
	<label>Nama Lengkap</label>
	<input type="text" name="tb_nama_tk_kpj" id="tb_nama_tk_kpj" value="<?=$ls_nama_tk?>" readonly="readonly" style="width: 220px;" class="disabled">
</div>
<div class="clear"></div>
<div class="form-row_kiri">
	<label>NPP</label>
	<input type="text" id="tb_npp_kpj" name="tb_npp_kpj" value="<?=$ls_npp?>" readonly="readonly" style="width: 120px;" class="disabled">	
	<input type="hidden" name="tb_kode_perusahaan_kpj" id="tb_kode_perusahaan_kpj" value="<?=$ls_kode_perusahaan?>">				
	<input type="text" name="tb_nama_perusahaan_kpj" id="tb_nama_perusahaan_kpj" value="<?=$ls_nama_perusahaan?>" readonly="readonly" style="width: 220px;" class="disabled">
</div>
<div class="clear"></div>
<div class="form-row_kiri">
	<label>Tanggal Lahir</label>
	<input type="text" name="tb_tgl_lahir_kpj" id="tb_tgl_lahir_kpj" value="<?=$ls_tgl_lahir?>" readonly="readonly" style="width: 120px;" class="disabled">				
</div>
<div class="clear"></div>
<div class="form-row_kiri">
	<label>Tanggal N/A</label>
	<input type="text" name="tb_tgl_na_kpj" id="tb_tgl_na_kpj" value="<?=$ls_tgl_na?>" readonly="readonly" style="width: 120px;" class="disabled">				
</div>
<div class="clear"></div>
<?php } ?>

<!-- TAHUN -->
<?php 
	if($field == "tb_tahun") { 
		$ls_tahun = $_POST["tb_tahun"];
		if (!isset($ls_tahun)) {
			$sql = "select to_char(sysdate,'yyyy') tahun from dual";
			$DB->parse($sql);
			$DB->execute();
			$row = $DB->nextrow();
			$ls_tahun= $row["TAHUN"];
		}
?>
<div class="form-row_kiri">
<label>Tahun*</label>
	<input type="text" name="tb_tahun" id="tb_tahun" size="10" value="<?=$ls_tahun?>" tabindex="1">
</div>
<div class="clear"></div>
<?php } ?>

<!-- NPW WADAH -->
<?php 
	if($field == "npw") { 
		$ls_npw = $_POST["npw"];
		$ls_nama_wadah = $_POST["nama_wadah"];
?>
<div class="form-row_kiri">
	<label>NPW *</label>
	<input type="text" id="npw" name="npw" value="<?=$ls_npw;?>" size="10" style="width: 80px;" >
	<input type="text" name="nama_wadah" id="nama_wadah" size="30" style="width: 320px;" value="<?=$ls_nama_wadah;?>">								 								
</div>
<div class="clear"></div>				
<?php } ?>

<!-- KODE DONATUR -->
<?php 
	if($field == "kode_donatur") { 
		$ls_kode_donatur = $_POST["kode_donatur"];
?>
<div class="form-row_kiri">
	<label>Donatur *</label>
	<input type="text" id="kode_donatur" name="kode_donatur" value="<?=$ls_kode_donatur;?>" size="10" style="width: 80px;" >
	<input type="text" name="nama_donatur" id="nama_donatur" size="30" style="width: 320px;" value="<?=$ls_nama_donatur;?>">		
</div>
<div class="clear"></div>				    
<?php } ?>

<!-- CHANNEL -->
<?php 
	if($field == "channel") { 
		$ls_channel = $_POST["channel"];
?>
<div class="form-row_kiri">
	<label>Channel *</label>
	<input type="text" id="channel" name="channel" value="<?=$ls_kode_donatur;?>" size="10" style="width: 80px;" >
</div>
<div class="clear"></div>				
<?php } ?>

<!-- KEPALA CABANG -->
<?php 
	if($field == "kakacab") { 
		$ls_kakacab = $_POST["kakacab"];
?>
<div class="form-row_kiri">
	<label>Kepala Cabang *</label>
	<input type="text" id="kakacab" name="kakacab" value="<?=$ls_kakacab;?>" style="width: 220px;">
</div>
<div class="clear"></div>
<?php } ?>

<!-- KEPALA PELAYANAN -->
<?php 
	if($field == "kepala_pelayanan") { 
		$ls_kepala_pelayanan = $_POST["kepala_pelayanan"];
?>
<div class="form-row_kiri">
	<label>Kepala Pelayanan *</label>
	<input type="text" id="kepala_pelayanan" name="kepala_pelayanan" value="<?=$ls_kepala_pelayanan;?>" style="width: 220px;" required>
</div>
<div class="clear"></div>
<?php } ?>

<!-- PERIODE -->
<?php 
	if($field == "periode") { 
		$ld_periode = $_POST["periode"];
		if (!isset($ld_periode)) {
			$sql = "select to_char(sysdate,'dd/mm/yyyy') tgl from dual";
			$DB->parse($sql);
			$DB->execute();
			$row = $DB->nextrow();
			$ld_periode= $row["TGL"];
		}
?>
<div class="form-row_kiri">	
	  <label>Periode *</label>
	<input type="text" name="periode" id="periode" size="12" onblur="convert_date(periode);"  value="<?=$ld_periode;?>"> 
	<input id="btn_periode" type="image" align="top" onclick="return showCalendar('periode', 'dd-mm-yyyy');" src="../../images/calendar.gif" />										
</div>			
<div class="clear"></div>
<?php } ?>

<!-- PERIODE1 -->
<?php 
	if($field == "periode1") { 
		$ld_periode1 = $_POST["periode1"];
		$ld_periode2 = $_POST["periode2"];
		if (!isset($ld_periode1) && !isset($ld_periode2)) {
			$sql = "select '01/' || to_char(sysdate,'mm/yyyy') tgl1, to_char(sysdate,'dd/mm/yyyy') tgl2 from dual";
			$DB->parse($sql);
			$DB->execute();
			$row = $DB->nextrow();
			$ld_periode1 = $row["TGL1"];
			$ld_periode2 = $row["TGL2"];
		}
?>
<div class="form-row_kiri">	
	<label>Periode *</label>
	<input type="text" name="periode1" id="periode1" size="12" onblur="convert_date(periode1);"  value="<?=$ld_periode1;?>"> 
	<input id="btn_periode1" type="image" align="top" onclick="return showCalendar('periode1', 'dd-mm-yyyy');" src="../../images/calendar.gif" />
	s/d
	<input type="text" name="periode2" id="periode2" size="12" onblur="convert_date(periode2);"  value="<?=$ld_periode2;?>"> 
	<input id="btn_periode2" type="image" align="top" onclick="return showCalendar('periode2', 'dd-mm-yyyy');" src="../../images/calendar.gif" />
</div>			
<div class="clear"></div>	
<?php } ?>

<!-- BLTH INPUT -->
<?php
	if($field == "blth_bulan") {
		$blth_bulan = $_POST["blth_bulan"];
		$blth_tahun = $_POST["blth_tahun"];
?>
<div class="form-row_kiri">
	<label >BLTH Laporan *</label>
	<select size="1" id="blth_bulan" name="blth_bulan" class="select_format" style="width:95px;">
		<?php
			$sql = "SELECT TO_CHAR(ADD_MONTHS(SYSDATE, -1), 'MM') AS bulan FROM DUAL";
			$DB->parse($sql);
			$DB->execute();
			$data = $DB->nextrow();
			$bulan = $data['BULAN']; 
		?>
		<option value="01" <?php if ($bulan == '01') { echo "selected"; }?> >Januari</option>
		<option value="02" <?php if ($bulan == '02') { echo "selected"; }?> >Februari</option>
		<option value="03" <?php if ($bulan == '03') { echo "selected"; }?> >Maret</option>
		<option value="04" <?php if ($bulan == '04') { echo "selected"; }?> >April</option>
		<option value="05" <?php if ($bulan == '05') { echo "selected"; }?> >Mei</option>
		<option value="06" <?php if ($bulan == '06') { echo "selected"; }?> >Juni</option>
		<option value="07" <?php if ($bulan == '07') { echo "selected"; }?> >Juli</option>
		<option value="08" <?php if ($bulan == '08') { echo "selected"; }?> >Agustus</option>
		<option value="09" <?php if ($bulan == '09') { echo "selected"; }?> >September</option>
		<option value="10" <?php if ($bulan == '10') { echo "selected"; }?> >Oktober</option>
		<option value="11" <?php if ($bulan == '11') { echo "selected"; }?> >November</option>
		<option value="12" <?php if ($bulan == '12') { echo "selected"; }?> >Desember</option>
	</select>
	<select size="1" id="blth_tahun" name="blth_tahun" class="select_format" style="width:60px;">
		<?php
			$sql = "select to_char(to_date(sysdate,'dd/mm/rrrr'),'rrrr') tahun from dual";
			$DB->parse($sql);
			$DB->execute();
			$data_tahun = $DB->nextrow();
			$tahun = intval($data_tahun['TAHUN']);
			$tahun_start = $tahun - 10;
			for($i=$tahun_start;$i<=$tahun;$i++){
				if($i == $tahun){
					echo "<option selected value='$tahun'>".$tahun."</option>";
				}else{
					echo "<option value='$i'>".$i."</option>";
				}
			}
		?>
	</select>
</div>
<div class="clear"></div>
<?php } ?>

<!-- PEMBINA -->
<?php
	if($field == "pembina") {
		$ls_kd_pembina = $_POST["pembina"];
		$ls_nm_pembina = $_POST["nama_pembina"];
		$ls_kd_kantor_pembina = $_POST["kode_kantor_pembina"];
		if ($_SESSION['regrole']==6||$_SESSION['regrole']==15||$_SESSION['regrole']==21||$_SESSION['regrole']==22||$_SESSION['regrole']==24||$_SESSION['regrole']==25||$_SESSION['regrole']==2002
		||$_SESSION['regrole']==10||$_SESSION['regrole']==79||$_SESSION['regrole']==1104){
?>
<div class="form-row_kiri">
	<label>Pembina *</label>
	<input type="text" id="pembina" name="pembina" value="<?=$ls_kd_pembina;?>" size="10" style="width: 80px;" readonly required>
	<input type="text" name="nama_pembina" id="nama_pembina" size="30" style="width: 320px;" value="<?=$ls_nm_pembina;?>" readonly required>
	<input type="text" name="kode_kantor_pembina" id="kode_kantor_pembina" size="30" style="width: 30px;" value="<?=$ls_kd_kantor_pembina;?>" readonly required>
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_ec/ajax/lov_kode_pembina_jmo.php?p=kn900121.php&a=adminForm&b=pembina&c=nama_pembina&d=kode_kantor_pembina','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php
	}else {
		$kode_user = $_SESSION['USER'];
		$sql = "select kode_user,nama_user from sijstk.sc_user where kode_user = '$kode_user' ";
		$DB->parse($sql);
		$DB->execute();
		$row = $DB->nextrow();
		$ls_kode_user = $row["KODE_USER"];
		$ls_nama_user = $row["NAMA_USER"];
?>
<div class="form-row_kiri">
	<label>Pembina *</label>
	<input type="text" id="pembina" name="pembina" value="<?=$ls_kode_user;?>" size="10" style="width: 80px;" readonly required>
	<input type="text" name="nama_pembina" id="nama_pembina" size="30" style="width: 320px;" value="<?=$ls_nama_user;?>" readonly required>
	<input type="text" name="kode_kantor_pembina" id="kode_kantor_pembina" size="30" style="width: 30px;" value="<?=$_SESSION['kdkantorrole'];?>" readonly required>
</div>
<div class="clear"></div>
<?php }
}
 ?>

<!-- NPP PERUSAHAAN -->
<?php 
	if($field == "npp_jmo") { 
		$ls_npp = $_POST["npp_jmo"];
		$ls_nama_perusahaan = $_POST["nama_perusahaan"];
?>
<div class="form-row_kiri">
	<label>NPP </label>
	<input type="text" id="npp_jmo" name="npp_jmo" value="<?=$ls_npp;?>" size="10" style="width: 80px;" >
	<input type="text" name="nama_perusahaan" id="nama_perusahaan" size="30" style="width: 320px;" value="<?=$ls_nama_perusahaan;?>">
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_ec/ajax/ec9002_lov_perusahaan_jmo.php?p=kn900121.php&a=adminForm&b=npp_jmo&c=nama_perusahaan','',800,500,1)">   
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>   															 								
</div>
<div class="clear"></div>				
<?php } ?>
<!-- ############### -->
<!-- END FIELDS VIEW -->