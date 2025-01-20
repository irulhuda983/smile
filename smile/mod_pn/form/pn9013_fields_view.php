<!-- FIELDS VIEW -->
<!-- ########### -->

<!-- USIA AWAL-->
<?php
	if($field == "usia1") {
		$ls_usia1 = $_POST["usia1"];
?>
<div class="form-row_kiri">
	<label>Usia Awal</label>
	<input type="number" id="usia1" name="usia1" value="<?=$ls_usia1;?>" size="10" style="width: 80px;" >
</div>
<div class="clear"></div>
<?php } ?>


<!-- USIA AKHIR-->
<?php
	if($field == "usia2") {
		$ls_usia2 = $_POST["usia2"];
?>
<div class="form-row_kiri">
	<label>Usia Akhir</label>
	<input type="number" id="usia2" name="usia2" value="<?=$ls_usia2;?>" size="10" style="width: 80px;" >
</div>
<div class="clear"></div>
<?php } ?>

<!-- NPP KODE KANTOR -->
<?php
    if($field == "npp_kantor") {
        $ls_npp_kantor = $_POST["npp_kantor"];
        $ls_nama_perusahaan_kantor = $_POST["nama_perusahaan_kantor"];
?>
<div class="form-row_kiri">
    <label>NPP *</label>
    <input type="text" id="npp_kantor" name="npp_kantor" value="<?=$ls_npp_kantor;?>" size="10" style="width: 80px;" readonly>
    <input type="text" name="nama_perusahaan_kantor" id="nama_perusahaan_kantor" size="30" style="width: 320px;" value="<?=$ls_nama_perusahaan_kantor;?>" readonly>
    <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_npp_kantor.php?p=kn9001.php&a=adminForm&b=npp_kantor&c=nama_perusahaan_kantor&d='+document.getElementById('kode_kantor').value,'',800,500,1)">
    <img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php } ?>

<!-- FIELD KODE BATCH -->
<?php
if($field == "kode_batch") {
$ld_sorting = $_POST["kode_batch"];
?>
<div class="form-row_kiri">
<label>Batch Penyaluran*</label>
<select size="1" id="kode_batch" name="kode_batch"
class="select_format" style="width:320px;">
<?
echo "<option selected value=''>--SEMUA BATCH--</option>";
echo "<option value='1'>BATCH 1";
echo "<option value='2'>BATCH 2";
echo "<option value='3'>BATCH 3";
echo "<option value='4'>BATCH 4";
echo "<option value='5'>BATCH 5";
echo "<option value='6'>BATCH 6";
echo "<option value='61'>BATCH 6 Susulan";
echo "<option value='7'>BATCH 7";
echo "<option value='71'>BATCH 7 Susulan";
echo "<option value='8'>BATCH 8";
?>
</select>
</div>
<div class="clear"></div>
<?php } ?>


<!-- NIK BPU  -->
<?php
	if($field == "nik_bpu") {
?>
<div class="form-row_kiri">
	<label>NIK *</label>
	<input type="text" id="nik_bpu" name="nik_bpu"  value="<?=$nik_bpu;?>" size="100" style="width: 200px;">
	</div>
<div class="clear"></div>
<?php } ?>

<!-- KODE SEGMEN PU & BPU ONLY-->
<?php
	if($field == "kode_segmen") {
		$ls_kode_segmen = $_POST["tb_kode_segmen_pu_bpu"];
?>
<div class="form-row_kiri">
	  <label>Segmentasi *</label>
	  <select size="1" id="kode_segmen" name="kode_segmen" class="select_format" style="width:320px;">
	  <option value="PU">PU - PENERIMA UPAH</option><option value="BPU">BPU - BUKAN PENERIMA UPAH</option><option value="TKI">TKI - PEKERJA MIGRAN INDONESIA</option>	</select>
</div>
<div class="clear"></div>
<?php } ?>

<!-- KODE SEGMEN PU & BPU ONLY-->
<?php
	if($field == "tb_kode_segmen_pu_bpu") {
		$ls_kode_segmen = $_POST["tb_kode_segmen_pu_bpu"];
?>
<div class="form-row_kiri">
	  <label>Segmentasi *</label>
	<select size="1" id="tb_kode_segmen_pu_bpu" name="tb_kode_segmen_pu_bpu" class="select_format" style="width:320px;">
	  <?
		$sql = "select kode_segmen,nama_segmen from sijstk.kn_kode_segmen where kode_segmen in ('BPU','PU')";
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
<!-- KODE BANK SPO -->
<?php
	if($field == "kode_bank_rekening") {
		$ls_kode_bank_rekening = $_POST["kode_bank_rekening"];
		$ls_nama_bank_rekening = $_POST["nama_bank_rekening"];
?>
<div class="form-row_kiri">
	<label>Bank</label>
	<input type="text" id="kode_bank_rekening" name="kode_bank_rekening" readonly="readonly" class="disabled" value="<?=$ls_kode_bank_rekening;?>" size="10" style="width: 80px;">
	<input type="text" name="nama_bank_rekening" id="nama_bank_rekening" readonly="readonly" class="disabled" size="30" style="width: 320px;" value="<?=$ls_nama_bank_rekening;?>">
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kode_bank_rekening.php?p=kn900121.php&a=adminForm&b=kode_bank_rekening&c=nama_bank_rekening','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php } ?>




<!-- kode persen perubahan nama -->
<?php
	if($field == "kode_persen_nama") {
		$ld_sorting = $_POST["kode_persen_nama"];
?>
<div class="form-row_kiri">
	<label>Persentase Perubahan Nama*</label>
	<select size="1" id="kode_persen_nama" name="kode_persen_nama" class="select_format" style="width:320px;">
		<?
			echo "<option selected value='1'>--SEMUA STATUS--</option>";
			echo "<option value='A'><30%</option>";
			echo "<option value='B'>30% - 50%</option>";
			echo "<option value='C'>50% - 70%</option>";
			echo "<option value='D'>> 70%</option>";
		?>
	</select>
</div>
<div class="clear"></div>
<?php } ?>


<!-- valid_format_rekening -->
<?php
	if($field == "valid_format_rekening") {
		$ld_sorting = $_POST["valid_format_rekening"];
?>
<div class="form-row_kiri">
	<label>Valid Format*</label>
	<select size="1" id="valid_format_rekening" name="valid_format_rekening" class="select_format" style="width:320px;">
		<?
			echo "<option selected value='0'>--SEMUA STATUS--</option>";
			echo "<option value='Y'>Valid</option>";
			echo "<option value='T'>Tidak Valid</option>";
		?>
	</select>
</div>
<div class="clear"></div>
<?php } ?>

<!-- valid_bank_rekening -->
<?php
	if($field == "valid_bank_rekening") {
		$ld_sorting = $_POST["valid_bank_rekening"];
?>
<div class="form-row_kiri">
	<label>Valid Bank*</label>
	<select size="1" id="valid_bank_rekening" name="valid_bank_rekening" class="select_format" style="width:320px;">
		<?
			echo "<option selected value='0'>--SEMUA STATUS--</option>";
			echo "<option value='Y'>Valid</option>";
			echo "<option value='X'>Tidak Valid</option>";
			echo "<option value='T'>Belum Proses</option>";
		?>
	</select>
</div>
<div class="clear"></div>
<?php } ?>

<!-- RELAKSASI STATUS_TK -->
<?php
	if($field == "status_tk") {
		$ld_sorting = $_POST["status_tk"];
?>
<div class="form-row_kiri">
	<label>Status TK*</label>
	<select size="1" id="status_tk" name="status_tk" class="select_format" style="width:320px;">
		<?
			echo "<option selected value='0'>--SEMUA STATUS--</option>";
			echo "<option value='1'>Aktif</option>";
			echo "<option value='2'>Non Aktif</option>";
		?>
	</select>
</div>
<div class="clear"></div>
<?php } ?>

<!-- SKALA USAHA PK/BU -->
<?php
	if($field == "skala_usaha") {
		$ld_sorting = $_POST["skala_usaha"];
?>
<div class="form-row_kiri">
	<label>Skala Usaha*</label>
	<select size="1" id="skala_usaha" name="skala_usaha" class="select_format" style="width:320px;">
		<?
			echo "<option selected value='0'>--SEMUA SKALA USAHA--</option>";
			echo "<option value='1'>BESAR</option>";
			echo "<option value='2'>MENENGAH</option>";
			echo "<option value='3'>KECIL</option>";
			echo "<option value='4'>MIKRO</option>";
		?>
	</select>
</div>
<div class="clear"></div>
<?php } ?>

<!-- RELAKSASI STATUS_RLX_JP -->
<?php
	if($field == "status_rlx_jp") {
		$ld_sorting = $_POST["status_rlx_jp"];
?>
<div class="form-row_kiri">
	<label>Status Proses*</label>
	<select size="1" id="status_rlx_jp" name="status_rlx_jp" class="select_format" style="width:320px;">
		<?
			echo "<option selected value='0'>--SEMUA STATUS--</option>";
			echo "<option value='DIAJUKAN'>DIAJUKAN</option>";
			echo "<option value='DIVERIFIKASI'>DIVERIFIKASI</option>";
			echo "<option value='DISETUJUI'>DISETUJUI</option>";
			echo "<option value='DITOLAK'>DITOLAK</option>";
		?>
	</select>
</div>
<div class="clear"></div>
<?php } ?>

<!-- RELAKSASI LAMA_PROSES_RLX_JP -->
<?php
	if($field == "lama_proses_rlx_jp") {
		$ld_sorting = $_POST["lama_proses_rlx_jp"];
?>
<div class="form-row_kiri">
	<label>Lama Proses (Diajukan & Diverifikasi)*</label>
	<select size="1" id="lama_proses_rlx_jp" name="lama_proses_rlx_jp" class="select_format" style="width:320px;">
		<?
			echo "<option selected value='0'>--KESELURUHAN LAMA PROSES--</option>";
			echo "<option value='1'>>= 1 HARI (PK/BU Mikro & Kecil)</option>";
			echo "<option value='3'>>= 3 HARI (PK/BU Menengah & Besar)</option>";
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
		$sql = "select kode_segmen,nama_segmen from sijstk.kn_kode_segmen";
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
<!-- PEMBINA PPTKIS -->
<?php
	if($field == "pembina_pptkis") {
		$ls_kd_pembina_pptkis = $_POST["pembina_pptkis"];
		$ls_nm_pembina_pptkis = $_POST["nama_pembina_pptkis"];
		$ls_kd_kantor_pembina_pptkis = $_POST["kode_kantor_pembina_pptkis"];
		if ($_SESSION['regrole']==22||$_SESSION['regrole']==20||$_SESSION['regrole']==18||$_SESSION['regrole']==15||$_SESSION['regrole']==10||$_SESSION['regrole']==9){
?>
<div class="form-row_kiri">
	<label>Pembina *</label>
	<input type="text" id="pembina_pptkis" name="pembina_pptkis" value="<?=$ls_kd_pembina_pptkis;?>" size="10" style="width: 80px;" readonly required>
	<input type="text" name="nama_pembina_pptkis" id="nama_pembina_pptkis" size="30" style="width: 320px;" value="<?=$ls_nm_pembina_pptkis;?>" readonly required>
	<input type="text" name="kode_kantor_pembina_pptkis" id="kode_kantor_pembina_pptkis" size="30" style="width: 30px;" value="<?=$ls_kd_kantor_pembina_pptkis;?>" readonly>
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kode_pembina_pptkis.php?p=kn900121.php&a=adminForm&b=pembina_pptkis&c=nama_pembina_pptkis&d=kode_kantor_pembina_pptkis','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php
	}else{
		$kode_user = $_SESSION['USER'];
		$sql = "select kode_user,nama_user from sijstk.sc_user where kode_user = '$kode_user'";
		$DB->parse($sql);
		$DB->execute();
		$row = $DB->nextrow();
		$ls_kode_user = $row["KODE_USER"];
		$ls_nama_user = $row["NAMA_USER"];
?>
<div class="form-row_kiri">
	<label>Pembina *</label>
	<input type="text" id="pembina_pptkis" name="pembina_pptkis" value="<?=$ls_kode_user;?>" size="10" style="width: 80px;" readonly>
	<input type="text" name="nama_pembina_pptkis" id="nama_pembina_pptkis" size="30" style="width: 320px;" value="<?=$ls_nama_user;?>" readonly>
	<input type="text" name="kode_kantor_pembina" id="kode_kantor_pembina" size="30" style="width: 30px;" value="<?=$_SESSION['kdkantorrole'];?>" readonly>
</div>
<div class="clear"></div>
<?php }
}
 ?>


<!-- PEMBINA ALL SR -->
<?php
	if($field == "pembina_all") {
		$ls_kd_pembina_all = $_POST["pembina_all"];
		$ls_nm_pembina_all = $_POST["pembina_all"];
		$ls_kd_kantor_pembina_all = $_POST["kode_kantor_pembina_all"];
		if ($_SESSION['regrole']==22||$_SESSION['regrole']==20||$_SESSION['regrole']==18||$_SESSION['regrole']==15||$_SESSION['regrole']==10||$_SESSION['regrole']==9||$_SESSION['regrole']==1){
?>
<div class="form-row_kiri">
	<label>Pembina *</label>
	<input type="text" id="pembina_all" name="pembina_all" value="<?=$ls_kd_pembina_all;?>" size="10" style="width: 80px;" readonly required>
	<input type="text" name="nama_pembina_all" id="nama_pembina_all" size="30" style="width: 320px;" value="<?=$ls_nm_pembina_all;?>" readonly required>
	<input type="text" name="kode_kantor_pembina_all" id="kode_kantor_pembina_all" size="30" style="width: 30px;" value="<?=$ls_kd_kantor_pembina_all;?>" readonly>
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_sr/ajax/sr_lov_kode_pembina_report.php?p=kn900121.php&a=adminForm&b=pembina_all&c=nama_pembina_all&d=kode_kantor_pembina_all','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php
	}else{
		$kode_user = $_SESSION['USER'];
		$sql = "select kode_user,nama_user from sijstk.sc_user where kode_user = '$kode_user'";
		$DB->parse($sql);
		$DB->execute();
		$row = $DB->nextrow();
		$ls_kode_user = $row["KODE_USER"];
		$ls_nama_user = $row["NAMA_USER"];
?>
<div class="form-row_kiri">
	<label>Pembina *</label>
	<input type="text" id="pembina_all" name="pembina_all" value="<?=$ls_kode_user;?>" size="10" style="width: 80px;" readonly>
	<input type="text" name="nama_pembina_all" id="nama_pembina_all" size="30" style="width: 320px;" value="<?=$ls_nama_user;?>" readonly>
	<input type="text" name="kode_kantor_pembina" id="kode_kantor_pembina" size="30" style="width: 30px;" value="<?=$_SESSION['kdkantorrole'];?>" readonly>
</div>
<div class="clear"></div>
<?php }
}
 ?>


<!-- Kelompok Segmentasi Kepesertaan Badan Hukum -->
<?php
	if($field == "kel_badan_hukum") {
		$ld_sorting = $_POST["kel_badan_hukum"];
?>
<div class="form-row_kiri">
	<label>Kelompok Segmentasi *</label>
	<select size="1" id="kel_badan_hukum" name="kel_badan_hukum" class="select_format" style="width:320px;">
		<?
			echo "<option selected value='Kel-1'>Kel-1 Badan Usaha Millik Negara/Daerah (BUMN/D)</option>";
			echo "<option value='Kel-2'>Kel-2 Perseroan Terbatas (PT)</option>";
			echo "<option value='Kel-3'>Kel-3 Persekutuan Komanditer (CV)</option>";
			echo "<option value='Kel-4'>Kel-4 Firma</option>";
			echo "<option value='Kel-5'>Kel-5 Koperasi</option>";
			echo "<option value='Kel-6'>Kel-6 Yayasan</option>";
			echo "<option value='Kel-7'>Kel-7 Instansi/Lembaga</option>";
			if(($_SESSION['regrole']!=7) && ($_SESSION['regrole']!=8)){
				echo "<option value='Kel-8'>Kel-8 Tenaga Kerja Indonesia</option>";
			}
			echo "<option value='Kel-9'>Kel-9 Pemberi Kerja Lainnya</option>";
		?>
	</select>
</div>
<div class="clear"></div>
<?php } ?>
<!-- KODE PROYEK -->
<?php
	if($field == "kode_proyek") {
?>
<div class="form-row_kiri">
	<label>Proyek *</label>
	<input type="text" id="kode_proyek" name="kode_proyek" readonly="readonly" value="<?=$ls_kode_proyek;?>" size="10" style="width: 80px; background-color:#CCCCCC;">
	<input type="text" name="nama_proyek" id="nama_proyek" readonly="readonly" size="30" style="width: 320px; background-color:#CCCCCC;" value="<?=$ls_nama_proyek;?>">
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_proyek_piutang.php?p=kn9023.php&a=adminForm&b=kode_proyek&c=nama_proyek','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle">
    </a>
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

<!-- KODE SEGMEN BPU ONLY -->
<?php
	if($field == "tb_kode_segmen_bpu") {
		$ls_kode_segmen = $_POST["tb_kode_segmen_bpu"];
?>
<div class="form-row_kiri">
	  <label>Segmentasi *</label>
	<select size="1" id="tb_kode_segmen_bpu" name="tb_kode_segmen_bpu" class="select_format" style="width:320px;">
	  <?
		$sql = "select kode_segmen, nama_segmen from sijstk.kn_kode_segmen where kode_segmen = 'BPU'";
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

<!-- KODE KANTOR KANWIL -->
<?php
	if($field == "provinsi") {
?>
<div class="form-row_kiri">
	<label>Propinsi</label>
	<input type="text" id="kode_provinsi" name="kode_provinsi" readonly="readonly" class="disabled" value="<?=$ls_provinsi;?>" size="10" style="width: 80px;">
	<input type="text" name="provinsi" id="provinsi" readonly="readonly" class="disabled" size="30" style="width: 320px;" value="<?=$ls_nm_provinsi;?>">
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_provinsi.php?p=kn900121.php&a=adminForm&b=kode_provinsi&c=provinsi','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php } ?>

<!-- KODE PROVINSI FM-->
<?php
	if($field == "kode_provinsi") {
?>
<div class="form-row_kiri">
	<label>Propinsi</label>
	<input type="text" id="kode_provinsi" name="kode_provinsi" readonly="readonly" class="disabled" value="<?=$ls_provinsi;?>" size="10" style="width: 80px;">
	<input type="text" name="provinsi" id="provinsi" readonly="readonly" class="disabled" size="30" style="width: 320px;" value="<?=$ls_nm_provinsi;?>">
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_provinsi.php?p=kn900121.php&a=adminForm&b=kode_provinsi&c=provinsi','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php } ?>

<!-- KODE KABUPATEN FM-->
<?php
	if($field == "kode_kabupaten") {
?>
<div class="form-row_kiri">
	<label>Kabupaten</label>
	<input type="text" id="kode_kabupaten" name="kode_kabupaten" readonly="readonly" class="disabled" value="<?=$ls_kabupaten;?>" size="10" style="width: 80px;">
	<input type="text" name="kabupaten" id="kabupaten" readonly="readonly" class="disabled" size="30" style="width: 320px;" value="<?=$ls_kabupaten;?>">
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kabupaten.php?p=kn900121.php&a=adminForm&b=kode_kabupaten&c=kabupaten','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php } ?>

<!-- KODE BANK SPO -->
<?php
	if($field == "kode_bank") {
?>
<div class="form-row_kiri">
	<label>Bank</label>
	<input type="text" id="kode_bank" name="kode_bank" readonly="readonly" class="disabled" value="<?=$ls_kode_bank;?>" size="10" style="width: 80px;">
	<input type="text" name="nama_bank" id="nama_bank" readonly="readonly" class="disabled" size="30" style="width: 320px;" value="<?=$ls_nama_bank;?>">
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kode_bank.php?p=kn900121.php&a=adminForm&b=kode_bank&c=nama_bank','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php } ?>

<!-- KODE KANTOR KANWIL -->
<?php
	if($field == "kd_kanwil") {
?>
<div class="form-row_kiri">
	<label>Kantor *</label>
	<input type="text" id="kd_kanwil" name="kd_kanwil" value="<?=$ls_kd_kanwil;?>" size="10" style="width: 80px;">
	<input type="text" name="nm_kanwil" id="nm_kanwil" size="30" style="width: 320px;" value="<?=$ls_nm_kanwil;?>">
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kantor_report.php?p=kn900121.php&a=adminForm&b=kd_kanwil&c=nm_kanwil','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php } ?>

<!-- KODE KANTOR KANWIL -->
<?php
	if($field == "kd_kanwil_report") {
?>
<div class="form-row_kiri">
	<label>Kantor *</label>
	<input type="text" id="kd_kanwil_report" name="kd_kanwil_report" value="<?=$ls_kd_kanwil_report;?>" size="10" style="width: 80px;">
	<input type="text" name="nm_kanwil_report" id="nm_kanwil_report" size="30" style="width: 320px;" value="<?=$ls_nm_kanwil_report;?>">
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kantor_kanwil_report2.php?p=kn900121.php&a=adminForm&b=kd_kanwil_report&c=nm_kanwil_report','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php } ?>

<!-- KODE KANTOR -->
<?php
	if($field == "kode_kantor") {
		$ls_kode_kantor = $_POST["kode_kantor"];
		$ls_kode_kantor = $ls_kode_kantor == null ? $ses_kode_kantor : $ls_kode_kantor;
		if (isset($ls_kode_kantor)) {
			$ls_kode_kantor = $row["KODE_KANTOR"];
			$ls_nama_kantor = $row["NAMA_KANTOR"];
		}
?>
<div class="form-row_kiri">
	<label>Kode Kantor *</label>
	<select size="1" id="kode_kantor" name="kode_kantor" class="select_format" style="width:320px;">
	  <?
	  	if($ses_kode_kantor != ''):
			$sql = "SELECT * FROM SC.MS_KANTOR WHERE KODE_TIPE NOT IN ('2')	START WITH KODE_KANTOR = '".$ses_kode_kantor."'	CONNECT BY PRIOR KODE_KANTOR = KODE_KANTOR_INDUK";
		else:
			$sql = "SELECT KODE_KANTOR, NAMA_KANTOR FROM MS.MS_KANTOR ORDER BY KODE_KANTOR ASC";
		endif;
		$DB->parse($sql);
		$DB->execute();
		while($row = $DB->nextrow())
		{
			if ($row["KODE_KANTOR"] == $ls_kode_kantor) {
				echo "<option selected value=\"".$row["KODE_KANTOR"]."\">".$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"]."</option>";
			} else {
				echo "<option value=\"".$row["KODE_KANTOR"]."\">".$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"]."</option>";
			}
		}
	?>
	</select>
</div>
<div class="clear"></div>
<?php } ?>

<!-- PROVINSI NON ASN -->
<?php
	if($field == "kode_provinsi_nonasn") {
			$ls_kode_kantor = $_POST["kode_kantor"];
			$ls_kode_kantor = $ls_kode_kantor == null ? $ses_kode_kantor : $ls_kode_kantor;

		if ($_SESSION['regrole']==22||$_SESSION['regrole']==20||$_SESSION['regrole']==18||$_SESSION['regrole']==15||$_SESSION['regrole']==10||$_SESSION['regrole']==9||$_SESSION['regrole']==3020
				||$_SESSION['regrole']==29||$_SESSION['regrole']==79||$_SESSION['regrole']==90||$_SESSION['regrole']==213||$_SESSION['regrole']==214||$_SESSION['regrole']==215||$_SESSION['regrole']==1){
?>
		<div class="form-row_kiri">
			<label>Propinsi *</label>
			<input type="hidden" id="kode_kantor" name="kode_kantor" readonly="readonly" value="<?=$ls_kode_kantor;?>" size="10" style="width: 80px; background-color:#CCCCCC;">
			<input type="text" id="kode_provinsi_nonasn" name="kode_provinsi_nonasn" readonly="readonly" value="<?=$ls_kode_provinsi_nonasni;?>" size="10" style="width: 80px; background-color:#CCCCCC;">
			<input type="text" name="nama_provinsi" id="nama_provinsi" readonly="readonly" size="30" style="width: 320px; background-color:#CCCCCC;" value="<?=$ls_nama_kantor;?>">
			<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_provinsi_nonasn.php?p=kn9001.php&a=adminForm&b=kode_provinsi_nonasn&c=nama_provinsi','',800,500,1)">
			<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle">
		    </a>
		</div>
		<div class="clear"></div>

<?php
}else {
			$ls_kode_kantor = $_POST["kode_kantor"];
			$ls_kode_kantor = $ls_kode_kantor == null ? $ses_kode_kantor : $ls_kode_kantor;

			$sql = "SELECT KODE_PROPINSI, NAMA_PROPINSI FROM TABLE(KN.F_GET_PROPINSI('$ls_kode_kantor'))";
			$DB->parse($sql);
			$DB->execute();
			$row = $DB->nextrow();
			$ls_kode_provinsi_nonasn = $row["KODE_PROPINSI"];
			$ls_nama_provinsi = $row["NAMA_PROPINSI"];

?>
<div class="form-row_kiri">
	<label>Propinsi *</label>
	<input type="hidden" id="kode_kantor" name="kode_kantor" readonly="readonly" value="<?=$ls_kode_kantor;?>" size="10" style="width: 80px; background-color:#CCCCCC;">
	<input type="text" id="kode_provinsi_nonasn" name="kode_provinsi_nonasn" readonly="readonly" value="<?=$ls_kode_provinsi_nonasn;?>" size="10" style="width: 80px; background-color:#CCCCCC;">
	<input type="text" name="nama_provinsi" id="nama_provinsi" readonly="readonly" size="30" style="width: 320px; background-color:#CCCCCC;" value="<?=$ls_nama_provinsi;?>">
	<!-- <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_provinsi_nonasn.php?p=kn9002.php&a=adminForm&b=kode_provinsi&c=nama_provinsi','',800,500,1)"> -->
	<!-- <img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"> -->
    </a>
</div>
<div class="clear"></div>

<?php }
 } ?>


<!-- KABUPATEN NON ASN -->
<?php
	if($field == "kode_kabupaten_nonasn") {
?>
<div class="form-row_kiri">
	<label>Kabupaten *</label>
	<!-- <input type="hidden" id="kode_kantor" name="kode_kantor" readonly="readonly" value="<?=$ls_kode_kantor;?>" size="10" style="width: 80px; background-color:#CCCCCC;"> -->
	<input type="text" id="kode_kabupaten_nonasn" name="kode_kabupaten_nonasn" readonly="readonly" value="<?=$ls_kode_kabupaten_nonasn;?>" size="10" style="width: 80px; background-color:#CCCCCC;">
	<input type="text" name="nama_kabupaten_nonasn" id="nama_kabupaten_nonasn" readonly="readonly" size="30" style="width: 320px; background-color:#CCCCCC;" value="<?=$ls_nama_kabupaten_nonasn;?>">
	<a href="#" onclick="openKabupaten();">
	<!-- <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kabupaten_nonasn.php?p=kn9002.php&a=adminForm&b=kode_kabupaten&c=nama_kabupaten&d=31','',800,500,1)"> -->
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle">
    </a>
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

<!-- KODE KANTOR DEFAULT-->
<?php
	if($field == "kode_kantor_report") {
		$ls_kode_kantor = $_POST["kode_kantor_report"];
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
<div style="display: none" class="form-row_kiri">
	<label>Kantor *</label>
	<input type="hidden" id="kode_kantor_report" name="kode_kantor_report" readonly="readonly" value="<?=$ls_kode_kantor;?>" size="10" style="width: 80px; background-color:#CCCCCC;">
	<input type="hidden" name="nama_kantor_report" id="nama_kantor_report" readonly="readonly" size="30" style="width: 320px; background-color:#CCCCCC;" value="<?=$ls_nama_kantor;?>">
</div>
<div class="clear"></div>
<?php } ?>

<!-- NPP PEMBINA -->
<?php
	if($field == "npp_pembina") {
		$ls_npp_pembina = $_POST["npp_pembina"];
		$ls_nama_perusahaan = $_POST["nama_perusahaan"];
?>
<div class="form-row_kiri">
	<label>NPP *</label>
	<input type="text" id="npp_pembina" name="npp_pembina" value="<?=$ls_npp_pembina;?>" size="10" style="width: 80px;" readonly>
	<input type="text" name="nama_perusahaan" id="nama_perusahaan" size="30" style="width: 320px;" value="<?=$ls_nama_perusahaan;?>" readonly>
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_npp_aktif_pembina.php?p=kn900121.php&a=adminForm&b=npp_pembina&c=nama_perusahaan','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php } ?>

<!-- NPP PERUSAHAAN PU & BPU-->
<?php
	if($field == "npp_pu_bpu") {
		$ls_npp = $_POST["npp_pu_bpu"];
		$ls_nama_perusahaan = $_POST["nama_perusahaan"];
?>
<div class="form-row_kiri">
	<label>NPP/NPW </label>
	<input type="text" id="npp_pu_bpu" name="npp_pu_bpu" value="<?=$ls_npp;?>" size="10" style="width: 80px;" readonly>
	<input type="text" name="nama_perusahaan" id="nama_perusahaan" size="30" style="width: 320px;" value="<?=$ls_nama_perusahaan;?>" readonly>
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_npp_aktif_nonaktif_pu_bpu.php?p=kn900121.php&a=adminForm&b=npp_pu_bpu&c=nama_perusahaan','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
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
	<input type="text" id="npp" name="npp" value="<?=$ls_npp;?>" size="10" style="width: 80px;" readonly>
	<input type="text" name="nama_perusahaan" id="nama_perusahaan" size="30" style="width: 320px;" value="<?=$ls_nama_perusahaan;?>" readonly>
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_npp_aktif_nonaktif.php?p=kn900121.php&a=adminForm&b=npp&c=nama_perusahaan','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php } ?>

<!-- NPP ALL SR -->
<?php
	if($field == "kode_perusahaan_all") {
		$ls_npp_all = $_POST["npp_all"];
		$ls_kode_perusahaan_all = $_POST["kode_perusahaan_all"];
		$ls_nama_perusahaan_all = $_POST["nama_perusahaan_all"];
?>
<div class="form-row_kiri">
	<label>NPP-PK/BU*</label>
	<input type="text" id="npp_all" name="npp_all" value="<?=$ls_npp_all;?>" size="20" style="width: 80px;" readonly>
	<input type="hidden" id="kode_perusahaan_all" name="kode_perusahaan_all" value="<?=$ls_kode_perusahaan_all;?>" size="20" style="width: 160px;" readonly>
	<input type="text" name="nama_perusahaan_all" id="nama_perusahaan_all" size="30" style="width: 320px;" value="<?=$ls_nama_perusahaan_all;?>" readonly>
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_sr/ajax/sr_lov_npp_report.php?p=kn900121.php&a=adminForm&b=kode_perusahaan_all&c=nama_perusahaan_all&d=npp_all','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php } ?>

<!-- KODE DIVISI PERUSAHAAN -->
<?php
	if($field == "npp_div") {
		$ls_npp = $_POST["npp_div"];
?>
<div class="form-row_kiri">
	<label>NPP *</label>
	<input type="text" id="npp_div" name="npp_div" value="<?=$ls_npp;?>" size="10" style="width: 80px;" readonly>
</div>
<div class="clear"></div>
<?php } ?>
<?php
	if($field == "kode_divisi") {
		$ls_divisi 		= $_POST["kode_divisi"];
		$ls_nama_divisi = $_POST["nama_divisi"];
?>
<div class="form-row_kiri">
	<label>Divisi *</label>
	<input type="text" id="kode_divisi" name="kode_divisi" value="<?=$ls_divisi;?>" size="10" style="width: 80px;" >
	<input type="text" name="nama_divisi" id="nama_divisi" size="30" style="width: 320px;" value="<?=$ls_nama_divisi;?>">
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kode_divisi.php?p=kn900121.php&a=adminForm&b=kode_divisi&c=nama_divisi&d=npp_div','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php } ?>

<!-- MENAMPILKAN DIVISI DARI NPP AKTIF DAN NON AKTIF -->
<?php
	if($field == "kode_divisi_aktif_nonaktif") {
		$ls_divisi 		= $_POST["kode_divisi_aktif_nonaktif"];
		$ls_nama_divisi = $_POST["nama_divisi"];
?>
<div class="form-row_kiri">
	<label>Divisi *</label>
	<input type="text" id="kode_divisi_aktif_nonaktif" name="kode_divisi_aktif_nonaktif" value="<?=$ls_divisi;?>" size="10" style="width: 80px;" >
	<input type="text" name="nama_divisi" id="nama_divisi" size="30" style="width: 320px;" value="<?=$ls_nama_divisi;?>">
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kode_divisi_aktif_nonaktif.php?p=kn900121.php&a=adminForm&b=kode_divisi_aktif_nonaktif&c=nama_divisi&d=npp_div','',800,500,1)">
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
	<label>No Peserta</label>
	<input type="text" name="tb_kpj" id="tb_kpj" value="<?=$ls_kpj?>" style="width: 120px;" onblur="f_ajax_val_kpj();">
	<a href="#" onclick="f_ajax_val_exists_kpj()">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
	<span id="span_kpj_error" style="color: red; display: none;"></span>
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

<!-- KPJ TENAGA KERJA BPU-->
<?php
	if($field == "tb_kpj_bpu") {
		$ls_kpj = $_POST["tb_kpj_bpu"];
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
	<label>No Peserta</label>
	<input type="text" name="tb_kpj_bpu" id="tb_kpj_bpu" value="<?=$ls_kpj?>" style="width: 120px;" onblur="f_ajax_val_kpj_bpu();">
	<a href="#" onclick="f_ajax_val_exists_kpj_bpu()">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
	<span id="span_kpj_error" style="color: red; display: none;"></span>
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


<!-- PEMBINA BISA DIISI KOSONG-->
<?php
	if($field == "pembina_tdk_mandatory") {
		$ls_kd_pembina = $_POST["pembina_tdk_mandatory"];
		$ls_nm_pembina = $_POST["nama_pembina"];
		$ls_kd_kantor_pembina = $_POST["kode_kantor_pembina"];
		if ($_SESSION['regrole']==24||$_SESSION['regrole']==22||$_SESSION['regrole']==20||$_SESSION['regrole']==18||$_SESSION['regrole']==15||$_SESSION['regrole']==10||$_SESSION['regrole']==9||$_SESSION['regrole']==3020
				||$_SESSION['regrole']==29||$_SESSION['regrole']==79||$_SESSION['regrole']==90||$_SESSION['regrole']==213||$_SESSION['regrole']==214||$_SESSION['regrole']==215){
?>
<div class="form-row_kiri">
	<label>Pembina </label>
	<input type="text" id="pembina_tdk_mandatory" name="pembina_tdk_mandatory" value="<?=$ls_kd_pembina;?>" size="10" style="width: 80px;" readonly required>
	<input type="text" name="nama_pembina" id="nama_pembina" size="30" style="width: 320px;" value="<?=$ls_nm_pembina;?>" readonly required>
	<input type="text" name="kode_kantor_pembina" id="kode_kantor_pembina" size="30" style="width: 30px;" value="<?=$ls_kd_kantor_pembina;?>" readonly>
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kode_pembina.php?p=kn900121.php&a=adminForm&b=pembina_tdk_mandatory&c=nama_pembina&d=kode_kantor_pembina','',800,500,1)">
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
	<label>Pembina </label>
	<input type="text" id="pembina_tdk_mandatory" name="pembina_tdk_mandatory" value="<?=$ls_kode_user;?>" size="10" style="width: 80px;" readonly>
	<input type="text" name="nama_pembina" id="nama_pembina" size="30" style="width: 320px;" value="<?=$ls_nama_user;?>" readonly>
	<input type="text" name="kode_kantor_pembina" id="kode_kantor_pembina" size="30" style="width: 30px;" value="<?=$_SESSION['kdkantorrole'];?>" readonly>
</div>
<div class="clear"></div>
<?php }
}
 ?>

<!-- PEMBINA -->
<?php
	if($field == "pembina") {
		$ls_kd_pembina = $_POST["pembina"];
		$ls_nm_pembina = $_POST["nama_pembina"];
		$ls_kd_kantor_pembina = $_POST["kode_kantor_pembina"];
		if ($_SESSION['regrole']==22||$_SESSION['regrole']==20||$_SESSION['regrole']==18||$_SESSION['regrole']==15||$_SESSION['regrole']==10||$_SESSION['regrole']==9||$_SESSION['regrole']==3020
				||$_SESSION['regrole']==29||$_SESSION['regrole']==79||$_SESSION['regrole']==90||$_SESSION['regrole']==213||$_SESSION['regrole']==214||$_SESSION['regrole']==215){
?>
<div class="form-row_kiri">
	<label>Pembina *</label>
	<input type="text" id="pembina" name="pembina" value="<?=$ls_kd_pembina;?>" size="10" style="width: 80px;" readonly required>
	<input type="text" name="nama_pembina" id="nama_pembina" size="30" style="width: 320px;" value="<?=$ls_nm_pembina;?>" readonly required>
	<input type="text" name="kode_kantor_pembina" id="kode_kantor_pembina" size="30" style="width: 30px;" value="<?=$ls_kd_kantor_pembina;?>" readonly>
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kode_pembina.php?p=kn900121.php&a=adminForm&b=pembina&c=nama_pembina&d=kode_kantor_pembina','',800,500,1)">
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
	<input type="text" id="pembina" name="pembina" value="<?=$ls_kode_user;?>" size="10" style="width: 80px;" readonly>
	<input type="text" name="nama_pembina" id="nama_pembina" size="30" style="width: 320px;" value="<?=$ls_nama_user;?>" readonly>
	<input type="text" name="kode_kantor_pembina" id="kode_kantor_pembina" size="30" style="width: 30px;" value="<?=$_SESSION['kdkantorrole'];?>" readonly>
</div>
<div class="clear"></div>
<?php }
}
 ?>

 <!-- KODE KANTOR KANWIL JNR3004-->
 <?php
	 if($field == "kd_kanwil_report_jnr3004") {
		$ls_kd_kanwil_report = $_POST["kd_kanwil_report"];
		$ls_nm_kanwil_report = $_POST["nm_kanwil_report"];

		$ls_kd_kanwil_report = $ls_kd_kanwil_report == null ? $ses_kode_kantor : $ls_kd_kanwil_report;
		 if ($ls_kd_kanwil_report == '12A') {
			$ls_kd_kanwil_report = 0;
		 }
		 if (isset($ls_kd_kanwil_report)) {
			 $sql = "SELECT KODE_KANTOR, NAMA_KANTOR FROM MS.MS_KANTOR WHERE KODE_KANTOR = '$ls_kd_kanwil_report'";
			 $DB->parse($sql);
			 $DB->execute();
			 $row = $DB->nextrow();
			 $ls_kd_kanwil_report = $row["KODE_KANTOR"];
			 $ls_nm_kanwil_report = $row["NAMA_KANTOR"];
		 }
		 if ($_SESSION['regrole']==215 || $_SESSION['regrole']==214 || $_SESSION['regrole']==213){
 ?>
 <div class="form-row_kiri">
	 <label>Kantor *</label>
	 <input type="text" id="kd_kanwil_report" name="kd_kanwil_report" value="<?=$ls_kd_kanwil_report;?>" size="10" style="width: 80px;">
	 <input type="text" name="nm_kanwil_report" id="nm_kanwil_report" size="30" style="width: 320px;" value="<?=$ls_nm_kanwil_report;?>">
	 <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kantor_kanwil_report2_jnr3004.php?p=kn900121.php&a=adminForm&b=kd_kanwil_report&c=nm_kanwil_report','',800,500,1)">
	 <img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
 </div>
 <div class="clear"></div>
 <?php
	 }else{
 ?>

<div style="display: none" class="form-row_kiri">
	 <label>Kantor *</label>
	 <input type="text" id="kd_kanwil_report" name="kd_kanwil_report" value="<?=$ls_kd_kanwil_report;?>" size="10" style="width: 80px;">
	 <input type="text" name="nm_kanwil_report" id="nm_kanwil_report" size="30" style="width: 320px;" value="<?=$ls_nm_kanwil_report;?>">
	 <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kantor_kanwil_report2_jnr3004.php?p=kn900121.php&a=adminForm&b=kd_kanwil_report&c=nm_kanwil_report','',800,500,1)">
	 <img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
 </div>
 <div class="clear"></div>
 <?php }
 }?>

<!-- NOMOR PROYEK -->
<?php
	if($field == "no_proyek") {
		$ls_nomor_proyek = $_POST["no_proyek"];
		$ls_nama_proyek = $_POST["nama_proyek"];
?>
<div class="form-row_kiri">
	<label>Proyek *</label>
	<input type="text" id="no_proyek" name="no_proyek" readonly="readonly" value="<?=$ls_nomor_proyek;?>" size="15" style="width: 90px; background-color:#CCCCCC;">
	<input type="text" name="nama_proyek" id="nama_proyek" readonly="readonly" size="30" style="width: 320px; background-color:#CCCCCC;" value="<?=$ls_nama_proyek;?>">
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_proyek_aktif_jnr3003.php?p=kn9022.php&a=adminForm&b=no_proyek&c=nama_proyek','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle">
    </a>
</div>
<div class="clear"></div>
<?php } ?>

<!-- KODE KANTOR JNR3002-->
<?php
	if($field == "kode_kantor_jnr") {
		$ls_kode_kantor = $_POST["kode_kantor"];
		$ls_kode_kantor = $ls_kode_kantor == null ? $ses_kode_kantor : $ls_kode_kantor;
		if ((int)$ls_kode_kantor > 0 && (int)$ls_kode_kantor < 10) {
			$ls_kode_kantor = '90'.$ls_kode_kantor;
		} if ((int)$ls_kode_kantor == 10 || (int)$ls_kode_kantor == 11) {
		   $ls_kode_kantor = '9'.$ls_kode_kantor;
		} else if ($ls_kode_kantor == '12A') {
		   $ls_kode_kantor = 0;
		}
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
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kantor_report.php?p=kn9002.php&a=adminForm&b=kode_kantor&c=nama_kantor','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle">
    </a>
</div>
<div class="clear"></div>
<?php } ?>

 <!-- KODE KANTOR REPORT JNR3001-->
 <?php
	 if($field == "kode_kantor_report_jnr3001") {
		 $ls_kode_kantor = $_POST["kode_kantor_report"];
		 $ls_kode_kantor = $ls_kode_kantor == null ? $ses_kode_kantor : $ls_kode_kantor;
		 if ((int)$ls_kode_kantor > 0 && (int)$ls_kode_kantor < 10) {
			 $ls_kode_kantor = '90'.$ls_kode_kantor;
		 } if ((int)$ls_kode_kantor == 10 || (int)$ls_kode_kantor == 11) {
			$ls_kode_kantor = '9'.$ls_kode_kantor;
		 } else if ($ls_kode_kantor == '12A') {
			$ls_kode_kantor = 0;
		 }
		 if (isset($ls_kode_kantor)) {
			 $sql = "SELECT KODE_KANTOR, NAMA_KANTOR FROM MS.MS_KANTOR WHERE KODE_KANTOR = '$ls_kode_kantor'";
			 $DB->parse($sql);
			 $DB->execute();
			 $row = $DB->nextrow();
			 $ls_kode_kantor = $row["KODE_KANTOR"];
			 $ls_nama_kantor = $row["NAMA_KANTOR"];
		 }
		 // 22/18 KANWIL : KABAG PEM / PENATA KEPS WIL
		 // 213/215/214	KAPU : ASDEP KEPS / PENATA MADYA ( PMKEPS ) / PENATA MADYA (PUKEPS)
		 if ($_SESSION['regrole']==215 || $_SESSION['regrole']==214 || $_SESSION['regrole']==213){
 ?>
 <div class="form-row_kiri">
	 <label>Kantor *</label>
	 <input onblur="loadPembina(this.value)" type="text" id="kode_kantor_report" name="kode_kantor_report" readonly="readonly" value="<?=$ls_kode_kantor;?>" size="10" style="width: 80px; background-color:#CCCCCC;">
	 <input type="text" name="nama_kantor_report" id="nama_kantor_report" readonly="readonly" size="30" style="width: 320px; background-color:#CCCCCC;" value="<?=$ls_nama_kantor;?>">
	 <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kantor_report.php?p=kn9022.php&a=adminForm&b=kode_kantor_report&c=nama_kantor_report','',800,500,1)">
	 <img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle">
	 </a>
 </div>
 <div class="clear"></div>
 <?php
		 } else if ($_SESSION['regrole']==22 || $_SESSION['regrole']==18){
 ?>
 <div class="form-row_kiri">
	 <label>Kantor *</label>
	 <input onblur="loadPembina(this.value)" type="text" id="kode_kantor_report" name="kode_kantor_report" readonly="readonly" value="<?=$ls_kode_kantor;?>" size="10" style="width: 80px; background-color:#CCCCCC;">
	 <input type="text" name="nama_kantor_report" id="nama_kantor_report" readonly="readonly" size="30" style="width: 320px; background-color:#CCCCCC;" value="<?=$ls_nama_kantor;?>">
	 <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kantor_report.php?p=kn9022.php&a=adminForm&b=kode_kantor_report&c=nama_kantor_report','',800,500,1)">
	 <img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle">
	 </a>
 </div>
 <div class="clear"></div>
 <?php
	 }else{
 ?>

 <div style="display: none" class="form-row_kiri">
	 <label>Kantor *</label>
	 <input id="kode_kantor_report" name="kode_kantor_report" readonly="readonly" value="<?=$ls_kode_kantor;?>" size="10" style="width: 80px; background-color:#CCCCCC;">
	 <input name="nama_kantor_report" id="nama_kantor_report" readonly="readonly" size="30" style="width: 320px; background-color:#CCCCCC;" value="<?=$ls_nama_kantor;?>">
 </div>
 <div class="clear"></div>
 <?php }
 }
 ?>

<!-- PEMBINA JNR3001 -->
<?php
	if($field == "pembina_jnr3001") {
		$ls_kd_pembina = $_POST["pembina"];
		$ls_nm_pembina = $_POST["nama_pembina"];
		$ls_kd_kantor_pembina = $_POST["kode_kantor_pembina"];
		$ls_kode_kantor = $ls_kode_kantor == null ? $ses_kode_kantor : $ls_kode_kantor;
		// 9/10/15 PAP/KABID/KAKACAB
		// 22/18 KANWIL : KABAG PEM / PENATA KEPS WIL
		// 213/215/214	KAPU : ASDEP KEPS / PENATA MADYA ( PMKEPS ) / PENATA MADYA (PUKEPS)
		if ($_SESSION['regrole']==9 || $_SESSION['regrole']==10 || $_SESSION['regrole']==15 ||
		$_SESSION['regrole']==22 || $_SESSION['regrole']==18 ||
		$_SESSION['regrole']==215 || $_SESSION['regrole']==214 || $_SESSION['regrole']==213){
?>
<div class="form-row_kiri">
	<label>Pembina *</label>
	<select size="1" id="pembina" name="pembina" class="select_format select2" style="width:320px;">
		<option value="ALL" selected>ALL - PILIH SEMUA</option>
		<?
			$sql = "SELECT   DISTINCT(C.KODE_USER), C.NAMA_USER, C.KODE_KANTOR, (select nama_kantor from sijstk.ms_kantor where kode_kantor = c.kode_kantor) nama_kantor
            FROM     SIJSTK.SC_FUNGSI A, SIJSTK.SC_USER_FUNGSI B, SIJSTK.SC_USER C
            WHERE
            (A.KODE_FUNGSI = '7' OR A.KODE_FUNGSI = '8' OR A.KODE_FUNGSI = '9')
            AND      NVL (A.AKTIF, 'T') = 'Y'
            AND      NVL (C.AKTIF, 'T') = 'Y'
            AND      A.KODE_FUNGSI = B.KODE_FUNGSI
            AND      C.KODE_USER = B.KODE_USER
            AND      B.KODE_KANTOR = C.KODE_KANTOR
            AND      B.KODE_KANTOR in (
                select kode_kantor
            from   (select     a.kode_kantor, a.nama_kantor, a.kode_tipe,
                               (select nama_tipe
                                from   sijstk.ms_kantor_tipe
                                where  kode_tipe = a.kode_tipe) nama_tipe,
                               case
                                  when a.kode_tipe in ('4', '5')
                                     then (select a.kode_kantor_induk
                                           from   sijstk.ms_kantor
                                           where  kode_kantor =
                                                     a.kode_kantor_induk)
                                  else null
                               end kode_kacab_induk,
                               case
                                  when a.kode_tipe in ('4', '5')
                                     then (select nama_kantor
                                           from   sijstk.ms_kantor
                                           where  kode_kantor =
                                                     a.kode_kantor_induk)
                                  else null
                               end nama_kacab_induk,
                               case
                                  when a.kode_tipe in ('4', '5')
                                     then (select kode_kantor
                                           from   sijstk.ms_kantor
                                           where  kode_kantor in (
                                                     select kode_kantor_induk
                                                     from   sijstk.ms_kantor
                                                     where  kode_kantor =
                                                                       a.kode_kantor_induk))
                                  when a.kode_tipe = '3'
                                     then a.kode_kantor_induk
                                  else null
                               end kode_group_kanwil,
                               case
                                  when a.kode_tipe in ('4', '5')
                                     then (select nama_kantor
                                           from   sijstk.ms_kantor
                                           where  kode_kantor in (
                                                     select kode_kantor_induk
                                                     from   sijstk.ms_kantor
                                                     where  kode_kantor =
                                                                       a.kode_kantor_induk))
                                  when a.kode_tipe = '3'
                                     then (select nama_kantor
                                           from   sijstk.ms_kantor
                                           where  kode_kantor = a.kode_kantor_induk)
                                  else null
                               end nama_group_kanwil,
                               a.kode_kabupaten, b.nama_kabupaten, a.kode_kota,
                               c.nama_kota
                    from       sijstk.ms_kantor a, sijstk.ms_kabupaten b,
                               sijstk.ms_kota c
                    where      a.kode_kabupaten = b.kode_kabupaten(+)
                    and        a.kode_kota = c.kode_kota(+)
                    and        a.aktif = 'Y'
                    and        kode_tipe <> '1'
                    start with a.kode_kantor = '".$ls_kode_kantor."'
                    connect by prior a.kode_kantor = a.kode_kantor_induk) tt
            )";
			$DB->parse($sql);
			$DB->execute();
			while($row = $DB->nextrow())
			{	if (!(($ls_kode_kantor > 900 && $ls_kode_kantor < 910) || $ls_kode_kantor == 0 || $ls_kode_kantor == '12A' )) {
					if ($ls_kd_pembina == $row["KODE_USER"]) {
						echo "<option selected value=\"".$row["KODE_USER"]."\">".$row["KODE_USER"]." - ".$row["NAMA_USER"]."</option>";
					} else {
						echo "<option value=\"".$row["KODE_USER"]."\">".$row["KODE_USER"]." - ".$row["NAMA_USER"]."</option>";
					}
				}
			}
		?>
	</select>
</div>
<div class="clear"></div>
<?php
	}else{
		$kode_user = $_SESSION['USER'];
		$sql = "select kode_user,nama_user from sijstk.sc_user where kode_user = '$kode_user' ";
		$DB->parse($sql);
		$DB->execute();
		$row = $DB->nextrow();
		$ls_kode_user = $row["KODE_USER"];
		$ls_nama_user = $row["NAMA_USER"];
?>
<div style="display: none" class="form-row_kiri">
	<label>Pembina *</label>
	<input type="text" id="pembina" name="pembina" value="<?=$ls_kode_user;?>" size="10" style="width: 80px; background-color:#CCCCCC;" readonly="readonly">
	<input type="text" name="nama_pembina" id="nama_pembina" size="30" style="width: 320px; background-color:#CCCCCC;" value="<?=$ls_nama_user;?>" readonly="readonly">
	<input type="text" name="kode_kantor_pembina" id="kode_kantor_pembina" size="30" style="width: 30px; background-color:#CCCCCC;" value="<?=$_SESSION['kdkantorrole'];?>" readonly="readonly">
</div>
<div class="clear"></div>
<?php }
}
 ?>

<!-- CHECKBOX KONSOLIDASI -->
<?php
	if($field == "konsolidasi" && ($_SESSION['regrole']==22||$_SESSION['regrole']==20||$_SESSION['regrole']==18||$_SESSION['regrole']==15)) {
		$cb_konsol = $_POST['konsolidasi'];
		if (!isset($cb_konsol)) {
			$cb_konsol = 'Y';
		}
?>
<div class="form-row_kiri">
<label>Konsolidasi *</label>
	<input type="checkbox" name="konsolidasi" id="konsolidasi" value="<?=$cb_konsol;?>">
</div>
<div class="clear"></div>
<?php } ?>

<!-- CHECKBOX TK AKTIF / NON AKTIF -->
<?php
	if($field == "aktif_nonaktif") {
		$ls_cb_aktif 	= $_POST["cb_aktif"];
		$ls_cb_nonaktif = $_POST["cb_nonaktif"];
?>
<div class="form-row_kiri">
	<label>TK Aktif</label>
	<input type="checkbox" name="cb_aktif" id="cb_aktif" value="A" <?php if(isset($_POST['cb_aktif'])){echo 'checked';}?>>
</div>
<div class="clear"></div>
<div class="form-row_kiri">
	<label>TK Non Aktif</label>
	<input type="checkbox" name="cb_nonaktif" id="cb_nonaktif" value="T" <?php if(isset($_POST['cb_nonaktif'])){echo 'checked';}?>>
</div>
<div class="clear"></div>
<?php } ?>

<!-- TAHUN -->
<?php
	if($field == "tahun") {
		$tahun = $_POST["tahun"];
		if (!isset($tahun)) {
			$sql = "select to_char(add_months(sysdate,-12),'yyyy') tahun from dual";
			$DB->parse($sql);
			$DB->execute();
			$row = $DB->nextrow();
			$tahun= $row["TAHUN"];
		}
?>
<div class="form-row_kiri">
<label>Tahun*</label>
	<input type="text" name="tahun" id="input-tahun" size="10" value="<?=$tahun?>" tabindex="1">
</div>
<div class="clear"></div>
<?php } ?>

<!-- TAHUN -->
<?php
	if($field == "tb_tahun") {
		$ls_tahun = $_POST["tb_tahun"];
		if (!isset($ls_tahun)) {
			$sql = "select to_char(add_months(sysdate,-12),'yyyy') tahun from dual";
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

<!-- TAHUN1 -->
<?php
	if($field == "tb_tahun1") {
		$ls_tahun = $_POST["tb_tahun1"];
		if (!isset($ls_tahun)) {
			$sql = "select to_char(sysdate,'yyyy') tahun from dual";
			$DB->parse($sql);
			$DB->execute();
			$row = $DB->nextrow();
			$ls_tahun= $row["TAHUN"];
		}
?>
<div class="form-row_kiri">
<label>Tahun Awal *</label>
	<input type="text" name="tb_tahun1" id="tb_tahun1" size="10" value="<?=$ls_tahun?>" tabindex="1">
</div>
<div class="clear"></div>
<?php } ?>

<!-- TAHUN2 -->
<?php
	if($field == "tb_tahun2") {
		$ls_tahun = $_POST["tb_tahun2"];
		if (!isset($ls_tahun)) {
			$sql = "select to_char(sysdate,'yyyy') tahun from dual";
			$DB->parse($sql);
			$DB->execute();
			$row = $DB->nextrow();
			$ls_tahun= $row["TAHUN"];
		}
?>
<div class="form-row_kiri">
<label>Tahun Akhir *</label>
	<input type="text" name="tb_tahun2" id="tb_tahun2" size="10" value="<?=$ls_tahun?>" tabindex="1">
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

<!-- NPW DIVISI WADAH -->
<?php
	if($field == "npw_div") {
		$ls_npw = $_POST["npw_div"];
		$ls_nama_wadah = $_POST["nama_wadah"];
		$ls_kode_divisi = $_POST["kode_divisi"];
		$ls_nama_divisi = $_POST["nama_divisi"];
?>
<div class="form-row_kiri">
	<label>NPW *</label>
	<input type="text" id="npw_div" name="npw_div" value="<?=$ls_npw;?>" size="10" style="width: 80px;" readonly>
	<input type="text" name="nama_wadah" id="nama_wadah" size="30" style="width: 320px;" value="<?=$ls_nama_wadah;?>" readonly>
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kode_npw.php?p=kn900121.php&a=adminForm&b=npw_div&c=nama_wadah&d=kode_divisi&e=nama_divisi','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<div class="form-row_kiri">
	<label>Divisi *</label>
	<input type="text" id="kode_divisi" name="kode_divisi" value="<?=$ls_kode_divisi;?>" size="10" style="width: 80px"  readonly>
	<input type="text" name="nama_divisi" id="nama_divisi" size="30" style="width: 320px;" value="<?=$ls_nama_divisi;?>" readonly>
</div>
<div class="clear"></div>
<?php } ?>

<!-- JENIS KEPESERTAAN WADAH -->
<?php
	if($field == "jenis_keps") {
		$ls_jenis_keps = $_POST["jenis_keps"];
		$ls_nama_jenis_keps = $_POST["nama_jenis_keps"];
?>
<div class="form-row_kiri">
	<label>Jenis Kepesertaan *</label>
	<input type="text" id="jenis_keps" name="jenis_keps" value="<?=$ls_jenis_keps;?>" size="10" style="width: 80px;" readonly>
	<input type="text" name="nama_jenis_keps" id="nama_jenis_keps" size="30" style="width: 320px;" value="<?=$ls_nama_jenis_keps;?>" readonly>
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/kb5001_lov_jenispeserta_wadah.php?p=kn900121.php&a=adminForm&b=jenis_keps&c=nama_jenis_keps&d=BPU','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php } ?>

<!-- NPW_LOV PAIR DENGAN DIV_LOV-->
<?php
	if($field == "npw_lov") {
		$ls_npw = $_POST["npw_lov"];
		$ls_nama_wadah = $_POST["nama_wadah"];
?>
<div class="form-row_kiri">
	<label>NPW *</label>
	<input type="text" id="npw_lov" name="npw_lov" value="<?=$ls_npw;?>" size="10" style="width: 80px;" readonly>
	<input type="text" name="nama_wadah" id="nama_wadah" size="30" style="width: 320px;" value="<?=$ls_nama_wadah;?>" readonly>
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kode_npw.php?p=kn900121.php&a=adminForm&b=npw_lov&c=nama_wadah&d=div_lov&e=nama_divisi','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php } ?>

<!-- NPW_LOV PAIR DENGAN DIV_LOV-->
<?php
	if($field == "div_lov") {
		$ls_kode_divisi = $_POST["div_lov"];
		$ls_nama_divisi = $_POST["nama_divisi"];
?>
<div class="form-row_kiri">
	<label>Divisi *</label>
	<input type="text" id="div_lov" name="div_lov" value="<?=$ls_kode_divisi;?>" size="10" style="width: 80px"  readonly>
	<input type="text" name="nama_divisi" id="nama_divisi" size="30" style="width: 320px;" value="<?=$ls_nama_divisi;?>" readonly>
</div>
<div class="clear"></div>
<?php } ?>

<!-- NPW TAHUN1 -->
<?php
	if($field == "tahun1") {
		$ls_tahun1 = $_POST["tahun1"];
?>
<div class="form-row_kiri">
	<label>Tahun Awal</label>
	<input type="number" id="tahun1" name="tahun1" value="<?=$ls_tahun1;?>" size="10" style="width: 80px;" >
</div>
<div class="clear"></div>
<?php } ?>

<!-- NPW TAHUN2 -->
<?php
	if($field == "tahun2") {
		$ls_tahun2 = $_POST["tahun2"];
?>
<div class="form-row_kiri">
	<label>Tahun Akhir</label>
	<input type="number" id="tahun2" name="tahun2" value="<?=$ls_tahun2;?>" size="10" style="width: 80px;" >
</div>
<div class="clear"></div>
<?php } ?>

<!-- NPW TAHUN1 -->
<?php
	if($field == "tahun3") {
		$ls_tahun3 = $_POST["tahun3"];
?>
<div class="form-row_kiri">
	<label>Tahun</label>
	<input type="number" id="tahun3" name="tahun3" value="<?=$ls_tahun3;?>" size="10" style="width: 80px;" >
</div>
<div class="clear"></div>
<?php } ?>

<!-- NPW_LOV PEKERJA RENTAN -->
<?php
	if($field == "npw_lov_rentan") {
		$ls_npw = $_POST["npw_lov_rentan"];
		$ls_nama_wadah = $_POST["nama_wadah"];
?>
<div class="form-row_kiri">
	<label>NPW *</label>
	<input type="text" id="npw_lov_rentan" name="npw_lov_rentan" value="<?=$ls_npw;?>" size="10" style="width: 80px;" readonly>
	<input type="text" name="nama_wadah" id="nama_wadah" size="30" style="width: 320px;" value="<?=$ls_nama_wadah;?>" readonly>
	<a href="#" onclick="openNppNpw();">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php } ?>

<!-- PEMBINA PEKERJA RENTAN -->
<?php
	if($field == "pembina_rentan") {
		$ls_kd_pembina = $_POST["pembina_rentan"];
		$ls_nm_pembina = $_POST["nama_pembina"];
		$ls_kd_kantor_pembina = $_POST["kode_kantor_pembina"];
		if ($_SESSION['regrole']==22||$_SESSION['regrole']==20||$_SESSION['regrole']==18||$_SESSION['regrole']==15||$_SESSION['regrole']==10||$_SESSION['regrole']==9||$_SESSION['regrole']==3020
				||$_SESSION['regrole']==29||$_SESSION['regrole']==79||$_SESSION['regrole']==90||$_SESSION['regrole']==213||$_SESSION['regrole']==214||$_SESSION['regrole']==215){
?>
<div class="form-row_kiri">
	<label>Pembina *</label>
	<input type="text" id="pembina_rentan" name="pembina_rentan" value="<?=$ls_kd_pembina;?>" size="10" style="width: 80px;" readonly required>
	<input type="text" name="nama_pembina" id="nama_pembina" size="30" style="width: 320px;" value="<?=$ls_nm_pembina;?>" readonly required>
	<input type="text" name="kode_kantor_pembina" id="kode_kantor_pembina" size="30" style="width: 30px;" value="<?=$ls_kd_kantor_pembina;?>" readonly>
	<a href="#" onclick="openPembina()">
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
	<input type="text" id="pembina_rentan" name="pembina_rentan" value="<?=$ls_kode_user;?>" size="10" style="width: 80px;" readonly>
	<input type="text" name="nama_pembina" id="nama_pembina" size="30" style="width: 320px;" value="<?=$ls_nama_user;?>" readonly>
	<input type="text" name="kode_kantor_pembina" id="kode_kantor_pembina" size="30" style="width: 30px;" value="<?=$_SESSION['kdkantorrole'];?>" readonly>
</div>
<div class="clear"></div>
<?php }
}
 ?>

 <!-- KODE KANTOR WILAYAH RENTAN-->
 <?php
	 if($field == "kode_kantor_wilayah_rentan") {
		 $ls_kode_kantor = $_POST["kode_kantor"];
		 $ls_kode_kantor_wilayah = $_POST["kode_kantor_wilayah_rentan"];
		 $ls_kode_kantor = $ls_kode_kantor == null ? $ses_kode_kantor : $ls_kode_kantor;
		 if (isset($ls_kode_kantor)) {
			 $sql = "SELECT KODE_KANTOR, KODE_KANTOR_INDUK, NAMA_KANTOR FROM MS.MS_KANTOR WHERE KODE_KANTOR = '$ls_kode_kantor'";
			 $DB->parse($sql);
			 $DB->execute();
			 $row = $DB->nextrow();
			 $ls_kode_kantor = $row["KODE_KANTOR_INDUK"] == "P" ? "" : $row["KODE_KANTOR"];
			 $ls_kode_kantor_wilayah = $row["KODE_KANTOR"];
			 $ls_nama_kantor = $row["KODE_KANTOR_INDUK"] == "P" ? "" : $row["NAMA_KANTOR"];

			$sql2 = "SELECT KODE_KANTOR_INDUK FROM MS.MS_KANTOR WHERE KODE_KANTOR = '$ses_kode_kantor'";
			$DB->parse($sql2);
			$DB->execute();
			$row2 = $DB->nextrow();
			$ls_kode_kantor_induk = $row2["KODE_KANTOR_INDUK"];

 ?>
 <div class="form-row_kiri">
	 <label>Kantor *</label>
	 <input type="text" id="kode_kantor" name="kode_kantor" readonly="readonly" value="<?=$ls_kode_kantor;?>" size="10" style="width: 80px; background-color:#CCCCCC;">
	 <input type="hidden" id="kode_kantor_wilayah_rentan" name="kode_kantor_wilayah_rentan" readonly="readonly" value="<?=$ls_kode_kantor_wilayah;?>" size="10" style="width: 80px; background-color:#CCCCCC;">
	 <input type="text" name="nama_kantor_wilayah" id="nama_kantor_wilayah" readonly="readonly" size="30" style="width: 320px; background-color:#CCCCCC;" value="<?=$ls_nama_kantor;?>">
	 <?php if ($ls_kode_kantor_induk == "P") { ?>
	 <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kantor_kanwil_rentan.php?p=kn9019.php&a=adminForm&b=kode_kantor_wilayah_rentan&c=nama_kantor_wilayah&d=kode_kantor','',800,500,1)">
	 <?php } else { ?>
	 <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kantor_kanwil_rentan.php?p=kn9019.php&a=adminForm&b=kode_kantor_wilayah_rentan&c=nama_kantor_wilayah&d=kode_kantor','',800,500,1)">
	 <?php } ?>
	 <img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle">
	 </a>
 </div>
 <div class="clear"></div>
 <?php
		 }
	 } ?>

<!-- KODE KANTOR RENTAN -->
<?php
	if($field == "kode_kantor_rentan") {
		$ls_kode_kantor = $_POST["kode_kantor_rentan"];
		$ls_kode_kantor = $ls_kode_kantor == null ? $ses_kode_kantor : $ls_kode_kantor;
		if (isset($ls_kode_kantor)) {
			$sql = "SELECT KODE_TIPE, KODE_KANTOR, NAMA_KANTOR FROM MS.MS_KANTOR WHERE KODE_KANTOR = '$ls_kode_kantor'";
			$DB->parse($sql);
			$DB->execute();
			$row = $DB->nextrow();
			$ls_kode_kantor = $row["KODE_KANTOR"];
			$ls_nama_kantor = $row["NAMA_KANTOR"];

			$sql2 = "SELECT KODE_TIPE, KODE_KANTOR, NAMA_KANTOR FROM MS.MS_KANTOR WHERE KODE_KANTOR = '$ses_kode_kantor'";
			$DB->parse($sql2);
			$DB->execute();
			$row2 = $DB->nextrow();
			$ls_kode_tipe = $row2["KODE_TIPE"];
		}
?>
<?php if ($ls_kode_tipe > 2 && ($_SESSION['regrole'] == "8" || $_SESSION['regrole'] == "7")) {?>
<div style="display: none" class="form-row_kiri">
<?php } else { ?>
<div class="form-row_kiri">
<?php } ?>
	<label>Kantor *</label>
	<input type="text" id="kode_kantor_rentan" name="kode_kantor_rentan" readonly="readonly" value="<?=$ls_kode_kantor;?>" size="10" style="width: 80px; background-color:#CCCCCC;">
	<input type="text" name="nama_kantor" id="nama_kantor" readonly="readonly" size="30" style="width: 320px; background-color:#CCCCCC;" value="<?=$ls_nama_kantor;?>">
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kantor_report_all.php?p=kn9002.php&a=adminForm&b=kode_kantor_rentan&c=nama_kantor','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle">
    </a>
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


<!-- NPP1 -->
<?php
	if($field == "npp1") {
		$ls_npp1 = $_POST["npp1"];
?>
<div class="form-row_kiri">
	<label>NPP *</label>
	<input type="text" id="npp1" name="npp1" value="<?=$ls_npp1;?>" size="10" style="width: 80px;" >
</div>
<div class="clear"></div>
<?php } ?>


<!-- Parameter report status hubungan kerja tk aktif / PKWT -->

<?php
	if($field == "flag_pkwt") {
		$ls_flag_pkwt = $_POST["flag_pkwt"];
?>
<div class="form-row_kiri">
	<label>Status PKWT *</label>
	<select size="1" id="flag_pkwt" name="flag_pkwt" class="select_format" style="width:220px;">
		<?
			echo "<option selected value='ALL'>-- Pilih Status PKWT --</option>";
			echo "<option value='ALL'>ALL</option>";
			echo "<option value='Y'>Ya</option>";
			echo "<option value='T'>Tidak</option>";
			echo "<option value='BU'>Belum Update</option>";
		?>
	</select>
</div>
<div class="clear"></div>
<?php } ?>

<!-- Parameter report KUR -->

<?php
	if($field == "bank_penyalur") {
		$ls_bank_penyalur = $_POST["bank_penyalur"];
?>
<div class="form-row_kiri">
	<label>Bank Penyalur *</label>


  <select size="1" id="bank_penyalur" name="bank_penyalur" class="select_format" style="width:220px;">
		<?
			$sql = "select kode_bank, nama_bank from mis.MIS_BANK_KUR
              UNION
              SELECT NULL KODE_BANK, 'ALL' NAMA_BANK FROM DUAL";
			$DB->parse($sql);
			$DB->execute();
			while($row = $DB->nextrow())
			{
				if ($row["kode_bank"] == $ls_bank_penyalur) {
					echo "<option selected value=\"".$row["KODE_BANK"]."\">".$row["NAMA_BANK"]."</option>";
				} else {
					echo "<option value=\"".$row["KODE_BANK"]."\">".$row["NAMA_BANK"]."</option>";
				}
			}
		?>
	</select>

</div>
<div class="clear"></div>
<?php } ?>

<!-- Parameter report mutasi data kepesertaan segmen -->

<?php
	if($field == "reportmutasi") {
		$ls_reportmutasi = $_POST["reportmutasi"];
?>
<div class="form-row_kiri">
	<label>Segmen *</label>
	<select size="1" id="reportmutasi" name="reportmutasi" class="select_format" style="width:320px;">
		<?
			echo "<option selected value='0'>--Pilih Segmen--</option>";
			echo "<option value='1'>Penerima Upah</option>";
			echo "<option value='2'>Bukan Penerima Upah</option>";
			echo "<option value='3'>Jasa Konstruksi</option>";
		?>
	</select>
</div>
<div class="clear"></div>
<?php } ?>


<!-- Parameter report mutasi data kepesertaan program -->

<?php
	if($field == "reportmutasi2") {
		$ls_reportmutasi2 = $_POST["reportmutasi2"];
?>
<div class="form-row_kiri">
	<label id="reportmutasi2label">Program *</label>
	<select size="1" id="reportmutasi2" name="reportmutasi2" class="select_format" style="width:320px;">
		<?
			echo "<option selected value='-1'>--Pilih Program--</option>";
			echo "<option value='0'>Seluruh Program</option>";
			echo "<option id='jht' value='1'>JHT</option>";
			echo "<option id='jkk' value='2'>JKK</option>";
			echo "<option id='jkm' value='3'>JKM</option>";
			echo "<option id='jpn' value='4'>JPN</option>";
		?>
	</select>
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

<!-- ROLE -->
<?php
	if($field == "role") {
		$ld_kode_fungsi = $_POST['role'];
		if (!isset($ld_kode_fungsi)) {
			$ld_kode_fungsi = $_SESSION['regrole'];
			$sql = "select nama_fungsi from sijstk.sc_fungsi where kode_fungsi = '$ld_kode_fungsi'";
			$DB->parse($sql);
			$DB->execute();
			$row = $DB->nextrow();
			$ld_nama_fungsi = $row["NAMA_FUNGSI"];
		}else{
			$sql = "select nama_fungsi from sijstk.sc_fungsi where kode_fungsi = '$ld_kode_fungsi'";
			$DB->parse($sql);
			$DB->execute();
			$row = $DB->nextrow();
			$ld_nama_fungsi = $row["NAMA_FUNGSI"];
		}
?>
<div class="form-row_kiri">
	  <label>Role *</label>
	<input type="hidden" name="role" id="role" size="12" value="<?=$ld_kode_fungsi;?>" required>
	<input type="text" id="nama_fungsi" name="nama_fungsi" value="<?=$ld_nama_fungsi;?>" style="width: 220px;" readonly>
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

<!-- RSJHT -->
<?php
	if($field == "sorting_rsjht") {
		$ld_sorting = $_POST["sorting_rsjht"];
?>
<div class="form-row_kiri">
	<label>Sorting *</label>
	<select size="1" id="sorting_rsjht" name="sorting_rsjht" class="select_format" style="width:320px;">
		<?
			echo "<option selected value='NAMA'>Nama</option>";
			echo "<option selected value='NOPEG'>Nomor Pegawai</option>";
			echo "<option selected value='NOREF'>Nomor Referensi</option>";
		?>
	</select>
</div>
<div class="clear"></div>
<?php } ?>

<!-- Upah Minimum -->
<?php
	if($field == "upah_minimum") {
		$ld_sorting = $_POST["upah_minimum"];
?>
<div class="form-row_kiri">
	<label>Upah Minimum *</label>
	<!-- <input type="text" id="upah_minimum" name="upah_minimum" readonly="readonly" value="1.704.608" size="10" style="width: 80px; background-color:#CCCCCC;"> -->
	<input type="text" id="upah_minimum" name="upah_minimum" value="1.704.608" size="10" style="width: 80px;">
</div>
<div class="clear"></div>
<?php } ?>

<!-- STATUS TK RSJHT -->
<?php
	if($field == "status_aktif") {
		$ld_sorting = $_POST["status_aktif"];
?>
<div class="form-row_kiri">
	<label>Status TK *</label>
	<select size="1" id="status_aktif" name="status_aktif" class="select_format" style="width:320px;">
		<?
			echo "<option selected value=''>All</option>";
			echo "<option selected value='A'>Peserta</option>";
			echo "<option selected value='NA'>Non Aktif</option>";
		?>
	</select>
</div>
<div class="clear"></div>
<?php } ?>

<!-- TAHUN RSJHT -->
<?php
	if($field == "tahun_rsjht") {
		$ld_sorting = $_POST["tahun_rsjht"];
?>
<div class="form-row_kiri">
	<label>Tahun *</label>
	<select size="1" id="tahun_rsjht" name="tahun_rsjht" class="select_format" style="width:320px;">
		<?
			for($i=3; $i>=1; $i--){
				$sql_tahun = "select to_char(sysdate,'rrrr')-$i as tahun from dual";
				$DB->parse($sql_tahun);
				$DB->execute();
				$data_tahun  = $DB->nextrow();
				$tahun_rsjht = $data_tahun['TAHUN'];
				echo "<option selected value='$tahun_rsjht'>$tahun_rsjht</option>";
			}
		?>
	</select>
</div>
<div class="clear"></div>
<?php } ?>


<!-- IS LABEL RSJHT -->
<?php
	if($field == "is_label") {
		$is_label = $_POST['is_label'];
		if (!isset($is_label)) {
			$is_label = 'NO_LABEL';
		}
?>
<div class="form-row_kiri">
<label>Tanpa Label</label>
	<input type="checkbox" name="is_label" id="is_label" value="<?=$is_label;?>">
</div>
<div class="clear"></div>
<?php } ?>


<!-- CLEAR CACHE  RSJHT -->
<?php
	if($field == "clear_cache") {
		$is_clear = $_POST['clear_cache'];
		if (!isset($is_clear)) {
			$is_clear = 'clearCachePDF';
		}
?>
<div class="form-row_kiri">
<label>Clear Cache</label>
	<input type="checkbox" name="clear_cache" id="clear_cache" value="<?=$is_clear;?>">
</div>
<div class="clear"></div>
<?php } ?>


<!-- JENIS PEKERJAAN -->
<?php
	if($field == "jenis_pekerjaan") {
		$ls_pekerjaan = $_POST["jenis_pekerjaan"];
?>
<div class="form-row_kiri">
	<label>Jenis Pekerjaan *</label>
	<select size="1" id="jenis_pekerjaan" name="jenis_pekerjaan" class="select_format" style="width:320px;">
		<option value="ALL">ALL</option>
		<?
			$sql = "select kode_pekerjaan, nama_pekerjaan from sijstk.kn_kode_pekerjaan where status_nonaktif = 'T'";
			$DB->parse($sql);
			$DB->execute();
			while($row = $DB->nextrow())
			{
				if ($row["kode_pekerjaan"] == $ls_pekerjaan) {
					echo "<option selected value=\"".$row["KODE_PEKERJAAN"]."\">".$row["NAMA_PEKERJAAN"]."</option>";
				} else {
					echo "<option value=\"".$row["KODE_PEKERJAAN"]."\">".$row["NAMA_PEKERJAAN"]."</option>";
				}
			}
		?>
	</select>
</div>
<div class="clear"></div>
<?php } ?>


<!-- PERIODE EXSUM -->
<?php
	if($field == "periode_exsum") {
		$ls_periode_piutang = $_POST["periode_exsum"];
?>
<div class="form-row_kiri">
	<label>Periode *</label>
	<select size="1" id="periode_bln_exsum" name="periode_bln_exsum" class="select_format" style="width:86px;">
		<?php
			$sql = "select to_char(to_date(sysdate,'dd/mm/rrrr'),'mm') bulan from dual";
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
	<select size="1" id="periode_bln_exsum" name="periode_thn_exsum" class="select_format" style="width:50px;">
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
<!-- 	<select size="1" id="periode_bln_exsum" name="periode_bln_exsum" class="select_format" style="width:100px;">
		<?
			// $sql = "  SELECT TO_CHAR (blth_proses, 'mm/rrrr') blth_proses_disp, blth_proses
			// 			    FROM (SELECT DISTINCT
			// 			                 (TO_DATE ( (TO_CHAR (BLTH_PROSES, 'dd/mm/rrrr')),
			// 			                           'dd/mm/rrrr'))
			// 			                    AS blth_proses
			// 			            FROM MIS.MIS_RKP_PERUSAHAAN
			// 			           WHERE     blth_proses >
			// 			                        ADD_MONTHS (TO_DATE (SYSDATE, 'dd/mm/rrrr'), -36))
			// 			ORDER BY BLTH_PROSES DESC ";
			// $DB->parse($sql);
			// $DB->execute();
			// while($row = $DB->nextrow())
			// {
			// 	if ($row["BLTH_PROSES"] == $ls_periode_piutang) {
			// 		echo "<option selected value=\"".$row["BLTH_PROSES"]."\">".$row["BLTH_PROSES_DISP"]."</option>";
			// 	} else {
			// 		echo "<option value=\"".$row["BLTH_PROSES"]."\">".$row["BLTH_PROSES_DISP"]."</option>";
			// 	}
			// }
		?>
	</select> -->
</div>
<div class="clear"></div>
<?php } ?>


<!-- PERIODE PIUTANG -->
<?php
	if($field == "periode_piutang") {
		$ls_periode_piutang = $_POST["periode_piutang"];
?>
<div class="form-row_kiri">
	<label>Periode *</label>
	<select size="1" id="periode_piutang" name="periode_piutang" class="select_format" style="width:100px;">
		<?
			$sql = "  SELECT TO_CHAR (blth_proses, 'mm/rrrr') blth_proses_disp, blth_proses
						    FROM (SELECT DISTINCT
						                 (TO_DATE ( (TO_CHAR (BLTH_PROSES, 'dd/mm/rrrr')),
						                           'dd/mm/rrrr'))
						                    AS blth_proses
						            FROM SIJSTK.KN_PIUTANG_IURAN
						           WHERE     KODE_KANTOR = 'J0P'
						                 AND blth_proses >
						                        ADD_MONTHS (TO_DATE (SYSDATE, 'dd/mm/rrrr'), -36))
						ORDER BY BLTH_PROSES DESC ";
			$DB->parse($sql);
			$DB->execute();
			while($row = $DB->nextrow())
			{
				if ($row["BLTH_PROSES"] == $ls_periode_piutang) {
					echo "<option selected value=\"".$row["BLTH_PROSES"]."\">".$row["BLTH_PROSES_DISP"]."</option>";
				} else {
					echo "<option value=\"".$row["BLTH_PROSES"]."\">".$row["BLTH_PROSES_DISP"]."</option>";
				}
			}
		?>
	</select>
</div>
<div class="clear"></div>
<?php } ?>

<!-- PROGRAM OJK -->

<?php
	if($field == "program_ojk") {
?>
<div class="form-row_kiri">
        <label>Program</label>
		<select size="1" id="program_ojk" name="program_ojk" class="select_format" tabindex="3" >
			<option value="">-- Pilih Program --</option>
			<?
				$sql = "SELECT  REPLACE(PRG,'XALL','ALL') PRG, PAKET
						FROM    (
								SELECT  DISTINCT REPLACE(PAKET,'XXX','XALL') PRG, PAKET
								FROM    RPEXT.KN_OJK_TK_REKAP
								ORDER   BY PRG
								)";

						$DB->parse($sql);
						$DB->execute();
						while($row = $DB->nextrow())
						{
							if ($row["PAKET"]==$ls_jenis_program) {
								$pilih = "selected=selected";
							} else {
								$pilih = "";
							}
						    echo "<option value='". $row["PAKET"] . "' " . $pilih . ">" . $row["PRG"] . "</option>";
						}
			?>
		</select>
	</div>
	<div class="clear"></div>
<?php } ?>


<!-- SEGMEN OJK -->
<?php
	if($field == "segmen_ojk") {
?>
	<div class="form-row_kiri">
  		<label>Segmen</label>
  				<select size="1" id="segmen_ojk" name="segmen_ojk" class="select_format" tabindex="3" value=<?php $ld_segmen ?>>
                        <option value="">-- Pilih Laporan --</option>
						<option value="ALL" <?php if($ld_segmen=="ALL") echo 'selected="selected"'; ?> >Semua</option>
						<option value="1.2" <?php if($ld_segmen=="1.2") echo 'selected="selected"'; ?> >1.2</option>
						<option value="2.1" <?php if($ld_segmen=="2.1") echo 'selected="selected"'; ?> >2.1</option>
						<option value="3" <?php if($ld_segmen=="3") echo 'selected="selected"'; ?> >3</option>
				</select>
	</div>
	<div class="clear"></div>
<?php } ?>


<!-- TIPE KANTOR OJK -->
<?php
	if($field == "tipe_kantor_ojk") {
?>
	<div class="form-row_kiri">
  		<label>Kantor</label>
  				<select size="1" id="tipe_kantor_ojk" name="tipe_kantor_ojk" class="select_format" tabindex="4" value=<?php $ld_kantor ?>>
                        <option value="">-- Pilih Jenis Kantor --</option>
						<option value="CAB" <?php if($ld_kantor=="CAB") echo 'selected="selected"'; ?> >Cabang</option>
						<option value="WIL" <?php if($ld_kantor=="WIL") echo 'selected="selected"'; ?> >Wilayah</option>
				</select>
	</div>
	<div class="clear"></div>
<?php } ?>

<!-- PERIODE OJK-->
<?php
	if($field == "periode_ojk") {
?>
<div class="form-row_kiri">
	  <label>Periode *</label>
	<select size="1" id="periode_bln_ojk" name="periode_bln_ojk" class="select_format" style="width:86px;">
		<?php
			$sql = "select to_char(to_date(sysdate,'dd/mm/rrrr'),'mm') bulan from dual";
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
	<select size="1" id="periode_thn_ojk" name="periode_thn_ojk" class="select_format" style="width:50px;">
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




<!-- Parameter report OJK paket -->

<?php
	if($field == "paketojk") {
		$ls_paketojk = $_POST["paketojk"];
?>
<div class="form-row_kiri">
	<label id="reportmutasi2label">Program *</label>
	<select size="1" id="reportmutasi2" name="paketojk" class="select_format" style="width:320px;">
		<?
			echo "<option selected value=''>--Pilih Program--</option>";
			echo "<option id='XXX' value='XXX'>Seluruh Program</option>";
			echo "<option id='JP' value='JP'>JP</option>";
			echo "<option id='JHT' value='JHT'>JHT</option>";
			echo "<option id='JKK' value='JKK'>JKK</option>";
			echo "<option id='JKM' value='JKM'>JKM</option>";
		?>
	</select>
</div>
<div class="clear"></div>
<?php } ?>


<!-- PERIODE1 OJK-->
<?php
	if($field == "periode1_ojk") {
		$ld_periode1 = $_POST["periode1_ojk"];
		$ld_periode2 = $_POST["periode2_ojk"];
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
	<input type="text" name="periode1_ojk" id="periode1_ojk" size="12" onblur="convert_date(periode1_ojk);"  value="<?=$ld_periode1;?>">
	<input id="btn_periode1" type="image" align="top" onclick="return showCalendar('periode1_ojk', 'dd-mm-yyyy');" src="../../images/calendar.gif" />
	s/d
	<input type="text" name="periode2_ojk" id="periode2_ojk" size="12" onblur="convert_date(periode2_ojk);"  value="<?=$ld_periode2;?>">
	<input id="btn_periode2" type="image" align="top" onclick="return showCalendar('periode2_ojk', 'dd-mm-yyyy');" src="../../images/calendar.gif" />
</div>

<div class="clear"></div>

<?php } ?>

<!-- LOV KANAL PEMBAYARAN (LAP-PMI) -->
<?php
	if($field == "kanal_pmi") {
		$ld_kanal_kode = $_POST["kanal_pmi"];
?>
<div class="form-row_kiri">
	<label>Kanal</label>
	<select size="1" id="kanal_pmi" name="kanal_pmi" class="select_format" style="width:100px;">
		<option selected value="">ALL</option>
		<?
			$sql = "SELECT CH_ID,
							CASE WHEN CH_ID IN ('001','002','003') THEN (SELECT BB.CH_ID||'-'||NAMA_BANK FROM SPO.SPO_BANK WHERE KODE_BANK = BB.KODE_BANK) ELSE CH_ID END AS NAMA_BANK
							FROM SPO.CH_KODE_BANK@TO_NSP BB";
			$DB->parse($sql);
			$DB->execute();
			while($row = $DB->nextrow())
			{
				if ($row["KODE_BANK"] == $ld_kanal_kode) {
					echo "<option value=\"".$row["CH_ID"]."\">".$row["NAMA_BANK"]."</option>";
				} else {
					echo "<option value=\"".$row["CH_ID"]."\">".$row["NAMA_BANK"]."</option>";
				}
			}
		?>
	</select>
</div>

<div class="clear"></div>

<?php } ?>


<!-- LOV KANAL daftar BPU RIFF -->
<?php
	if($field == "kanal_daftar_bpu") {
		$kanal_daftar_bpu = $_POST["kanal_daftar_bpu"];
?>
<div class="form-row_kiri">
	<label>Kanal Daftar</label>
	<select size="1" id="kanal_daftar_bpu" name="kanal_daftar_bpu" class="select_format" style="width:100px;">
		<option selected value="">ALL</option>
		<?
			$sql = "SELECT KANAL_DAFTAR FROM KN.REF_CHANNEL_ID
              GROUP BY KANAL_DAFTAR
              ORDER BY KANAL_DAFTAR ASC";
			$DB->parse($sql);
			$DB->execute();
			while($row = $DB->nextrow())
			{
					echo "<option value=\"".$row["KANAL_DAFTAR"]."\">".$row["KANAL_DAFTAR"]."</option>";
			}
		?>
	</select>
</div>
<div class="clear"></div>

<?php } ?>

<!-- LOV KANAL bayar BPU RIFF -->
<?php
	if($field == "kanal_bayar_bpu") {
		$kanal_bayar_bpu = $_POST["kanal_bayar_bpu"];
?>
<div class="form-row_kiri">
	<label>Kanal Bayar</label>
	<select size="1" id="kanal_bayar_bpu" name="kanal_bayar_bpu" class="select_format" style="width:100px;">
		<option selected value="">ALL</option>
		<?
			$sql = "SELECT KANAL_BAYAR FROM KN.REF_CHANNEL_ID_BAYAR
              GROUP BY KANAL_BAYAR
              ORDER BY KANAL_BAYAR ASC";
			$DB->parse($sql);
			$DB->execute();
			while($row = $DB->nextrow())
			{
					echo "<option value=\"".$row["KANAL_BAYAR"]."\">".$row["KANAL_BAYAR"]."</option>";
			}
		?>
	</select>
</div>
<div class="clear"></div>

<?php } ?>

<!-- KODE JENIS PESERTA riff-->

<?php
	if($field == "kode_jenis_peserta") {
		$kode_jenis_peserta= $_POST["kode_jenis_peserta"];
?>
<div class="form-row_kiri">
	<label>Jenis Peserta</label>
	<select size="1" id="kode_jenis_peserta" name="kode_jenis_peserta" class="select_format" style="width:150px;">
		<option selected value="">Pilih Jenis Peserta</option>
		<option value="SG">Pemerintah</option>
		<option value="DS">Desa</option>
		<option value="SC">Korporasi</option>
	</select>
</div>

<div class="clear"></div>

<?php } ?>




<!-- PERIODE untuk Laporan Kepesertaan PMI -->
<?php
	if($field == "periode_pmi_aktif") {
		$ld_periode1 = $_POST["periode_pmi_aktif"];
		if (!isset($ld_periode1)) {
			$sql = "select '01/' || to_char(sysdate,'mm/yyyy') tgl1 from dual";
			$DB->parse($sql);
			$DB->execute();
			$row = $DB->nextrow();
			$ld_periode1 = $row["TGL1"];
		}
?>
<div class="form-row_kiri">
	<label>Periode *</label>
		s/d
	<input type="text" name="periode_pmi_aktif" id="periode_pmi_aktif" size="12" onblur="convert_date(periode_pmi_aktif);"  value="<?=$ld_periode1;?>">
	<input id="btn_periode1" type="image" align="top" onclick="return showCalendar('periode_pmi_aktif', 'dd-mm-yyyy');" src="../../images/calendar.gif" />
</div>
<div class="clear"></div>
<?php } ?>


<!-- LOV KANAL PEMBAYARAN (LAP-PMI) -->
<?php
	if($field == "jenis_pmi") {
		$jenis_pmi= $_POST["jenis_pmi"];
?>
<div class="form-row_kiri">
	<label>Jenis Tagihan</label>
	<select size="1" id="jenis_pmi" name="jenis_pmi" class="select_format" style="width:100px;">
		<option selected value="">ALL</option>
		<option value="CTKI">CPMI</option>
		<option value="TKI">PMI</option>
		<option value="TKIE">Perpanjangan</option>
	</select>
</div>

<div class="clear"></div>

<?php } ?>

<!-- Jenis detil laporan Segmentasi-->

<?php
	if($field == "field_1") {
		$field_1= $_POST["field_1"];
?>
<div class="form-row_kiri">
        <label>Filter 1</label>
		<select size="1" id="field_1" name="field_1" class="select_format" tabindex="3" >
			<option selected value="zero">-- Pilih Segmentasi --</option>
			<?
				$sql = "SELECT KODE_JENIS_SEGMENTASI, NAMA_JENIS_SEGMENTASI, NAMA_KOLOM FROM SR.SR_KODE_SEGMENTASI WHERE AKTIF='Y'
						AND NAMA_KOLOM IS NOT NULL
						UNION ALL
						SELECT 'SG009' KODE_JENIS_SEGMENTASI, 'Komunitas' NAMA_JENIS_SEGMENTASI, 'SEGMENTASI_KOMUNITAS' NAMA_KOLOM FROM DUAL
						UNION ALL
						SELECT 'SG010' KODE_JENIS_SEGMENTASI, 'Serikat Pekerja' NAMA_JENIS_SEGMENTASI, 'SEGMENTASI_SERIKATPEKERJA' NAMA_KOLOM FROM DUAL
						UNION ALL
						SELECT 'SG900' KODE_JENIS_SEGMENTASI, 'Identifikasi PK/BU' NAMA_JENIS_SEGMENTASI, 'UNIDENTIFIED' NAMA_KOLOM FROM DUAL";

						$DB->parse($sql);
						$DB->execute();
						while($row = $DB->nextrow())
						{

						    echo "<option id='". $row["NAMA_KOLOM"] . "'  value='". $row["NAMA_KOLOM"] . "' " . $pilih . ">" . $row["NAMA_JENIS_SEGMENTASI"] . "</option>";
						}
			?>
		</select>
	</div>
	<div class="clear"></div>
<?php } ?>

<!-- Jenis isi detil laporan Segmentasi-->

<?php
	if($field == "isi_field_1") {
		$isi_field_1= $_POST["isi_field_1"];
?>
<div class="form-row_kiri">
        <label>Kategori 1</label>
		<select size="1" id="isi_field_1" name="isi_field_1" class="select_format" tabindex="3" >
			<option selected value="zero">-- Pilih Kategori --</option>
			<?
				$sql = "SELECT KODE_JENIS_SEGMENTASI_DETIL, NAMA_JENIS_SEGMENTASI_DETIL FROM SR.SR_KODE_SEGMENTASI_DETIL WHERE AKTIF='Y'
						UNION ALL
						SELECT 'Y' KODE_JENIS_SEGMENTASI_DETIL, 'Y' NAMA_JENIS_SEGMENTASI_DETIL FROM DUAL
						UNION ALL
						SELECT 'T' KODE_JENIS_SEGMENTASI_DETIL, 'T' NAMA_JENIS_SEGMENTASI_DETIL FROM DUAL";

						$DB->parse($sql);
						$DB->execute();
						while($row = $DB->nextrow())
						{

						    echo "<option id='". $row["KODE_JENIS_SEGMENTASI_DETIL"] . "' value='". $row["KODE_JENIS_SEGMENTASI_DETIL"] . "' " . $pilih . ">" . $row["NAMA_JENIS_SEGMENTASI_DETIL"] . "</option>";
						}
			?>
		</select>
	</div>
	<div class="clear"></div>
<?php } ?>


<!-- Jenis detil laporan Segmentasi 2-->

<?php
	if($field == "field_2") {
		$field_2= $_POST["field_2"];
?>
<div class="form-row_kiri">
        <label>Filter 2</label>
		<select size="1" id="field_2" name="field_2" class="select_format" tabindex="3" >
			<option selected value="zero">-- Pilih Segmentasi --</option>
			<?
				$sql = "SELECT KODE_JENIS_SEGMENTASI, NAMA_JENIS_SEGMENTASI, NAMA_KOLOM FROM SR.SR_KODE_SEGMENTASI WHERE AKTIF='Y'
						AND NAMA_KOLOM IS NOT NULL
						UNION ALL
						SELECT 'SG009' KODE_JENIS_SEGMENTASI, 'Komunitas' NAMA_JENIS_SEGMENTASI, 'SEGMENTASI_KOMUNITAS' NAMA_KOLOM FROM DUAL
						UNION ALL
						SELECT 'SG010' KODE_JENIS_SEGMENTASI, 'Serikat Pekerja' NAMA_JENIS_SEGMENTASI, 'SEGMENTASI_SERIKATPEKERJA' NAMA_KOLOM FROM DUAL
						UNION ALL
						SELECT 'SG900' KODE_JENIS_SEGMENTASI, 'Identifikasi PK/BU' NAMA_JENIS_SEGMENTASI, 'UNIDENTIFIED' NAMA_KOLOM FROM DUAL";

						$DB->parse($sql);
						$DB->execute();
						while($row = $DB->nextrow())
						{

						    echo "<option id='". $row["NAMA_KOLOM"] . "'  value='". $row["NAMA_KOLOM"] . "' " . $pilih . ">" . $row["NAMA_JENIS_SEGMENTASI"] . "</option>";
						}
			?>
		</select>
	</div>
	<div class="clear"></div>
<?php } ?>

<!-- Jenis isi detil laporan Segmentasi 2-->

<?php
	if($field == "isi_field_2") {
		$isi_field_2= $_POST["isi_field_2"];
?>
<div class="form-row_kiri">
        <label>Kategori 2</label>
		<select size="1" id="isi_field_2" name="isi_field_2" class="select_format" tabindex="3" >
			<option selected value="zero">-- Pilih Kategori --</option>
			<?
				$sql = "SELECT KODE_JENIS_SEGMENTASI_DETIL, NAMA_JENIS_SEGMENTASI_DETIL FROM SR.SR_KODE_SEGMENTASI_DETIL WHERE AKTIF='Y'
						UNION ALL
						SELECT 'Y' KODE_JENIS_SEGMENTASI_DETIL, 'Y' NAMA_JENIS_SEGMENTASI_DETIL FROM DUAL
						UNION ALL
						SELECT 'T' KODE_JENIS_SEGMENTASI_DETIL, 'T' NAMA_JENIS_SEGMENTASI_DETIL FROM DUAL";

						$DB->parse($sql);
						$DB->execute();
						while($row = $DB->nextrow())
						{

						    echo "<option id='X". $row["KODE_JENIS_SEGMENTASI_DETIL"] . "' value='". $row["KODE_JENIS_SEGMENTASI_DETIL"] . "' " . $pilih . ">" . $row["NAMA_JENIS_SEGMENTASI_DETIL"] . "</option>";
						}
			?>
		</select>
	</div>
	<div class="clear"></div>
<?php } ?>

<!-- Lov Tema Rakor Komunikasi-->

<?php
	if($field == "tema_rakor") {
		$tema_rakor= $_POST["tema_rakor"];
?>
<div class="form-row_kiri">
        <label>Tema</label>
		<select size="1" id="tema_rakor" name="tema_rakor" class="select_format" tabindex="3" >
			<option selected value="zero">-- Pilih Tema --</option>
			<?
				$sql = "SELECT KODE_TEMA_RAKOR, NAMA_TEMA_RAKOR FROM SR.SR_KODE_TEMA_RAKOR";

						$DB->parse($sql);
						$DB->execute();
						while($row = $DB->nextrow())
						{

						    echo "<option id='". $row["KODE_TEMA_RAKOR"] . "'  value='". $row["KODE_TEMA_RAKOR"] . "' " . $pilih . ">" . $row["NAMA_TEMA_RAKOR"] . "</option>";
						}
			?>
		</select>
	</div>
	<div class="clear"></div>
<?php } ?>

<!-- Lov Tema Direct Komunikasi-->

<?php
	if($field == "tema_komunikasi") {
		$tema_rakor= $_POST["tema_komunikasi"];
?>
<div class="form-row_kiri">
        <label>Tema</label>
		<select size="1" id="tema_komunikasi" name="tema_komunikasi" class="select_format" tabindex="3" >
			<option selected value="zero">-- Pilih Tema --</option>
			<?
				$sql = "SELECT KODE_TEMA_KOMUNIKASI, NAMA_TEMA_KOMUNIKASI FROM SR.SR_KODE_KOMUNIKASI_TEMA";

						$DB->parse($sql);
						$DB->execute();
						while($row = $DB->nextrow())
						{

						    echo "<option id='". $row["KODE_TEMA_KOMUNIKASI"] . "'  value='". $row["KODE_TEMA_KOMUNIKASI"] . "' " . $pilih . ">" . $row["NAMA_TEMA_KOMUNIKASI"] . "</option>";
						}
			?>
		</select>
	</div>
	<div class="clear"></div>
<?php } ?>

<!-- Lov Sasaran Kegiatan Gathering-->

<?php
	if($field == "sasaran_kegiatan_gathering") {
		$sasaran_kegiatan_gathering= $_POST["sasaran_kegiatan_gathering"];
?>
<div class="form-row_kiri">
        <label>Sasaran Kegiatan</label>
		<select size="1" id="sasaran_kegiatan_gathering" name="sasaran_kegiatan_gathering" class="select_format" tabindex="3" >
			<option selected value="zero">-- Pilih Sasaran Kegiatan --</option>
			<?
				$sql = "SELECT KODE_SASARAN_KEGIATAN, NAMA_SASARAN_KEGIATAN FROM SR.SR_KODE_SASARAN_KEGIATAN";

						$DB->parse($sql);
						$DB->execute();
						while($row = $DB->nextrow())
						{

						    echo "<option id='". $row["KODE_SASARAN_KEGIATAN"] . "'  value='". $row["KODE_SASARAN_KEGIATAN"] . "' " . $pilih . ">" . $row["NAMA_SASARAN_KEGIATAN"] . "</option>";
						}
			?>
		</select>
	</div>
	<div class="clear"></div>
<?php } ?>

<!-- Report PKBU KNR3325P, KNR3325C, KNR3325W-->

<?php
	if($field == "kepemilikan_badan_hukum") {
		$kepemilikan_badan_hukum= $_POST["kepemilikan_badan_hukum"];
?>
<div class="form-row_kiri">
        <label>Status</label>
		<select size="1" id="kepemilikan_badan_hukum" name="kepemilikan_badan_hukum" class="select_format" tabindex="3" >
			<option selected value="zero">-- Pilih Status --</option>
			<option value="kepemilikan">Kepemilikan</option>
			<option value="badan hukum">Badan Hukum</option>
		</select>
	</div>
	<div class="clear"></div>
<?php } ?>

<!-- Report PENGEMBALIAN IURAN JAKON-->

<?php
	if($field == "flag_relaksasi_jakon") {
		$flag_relaksasi_jakon= $_POST["flag_relaksasi_jakon"];
?>
<div class="form-row_kiri">
        <label>Relaksasi</label>
		<select size="1" id="flag_relaksasi_jakon" name="flag_relaksasi_jakon" class="select_format" tabindex="3" >
			<option selected value="All">-- Pilih Status --</option>
			<option value="Y">Ya</option>
			<option value="T">Tidak</option>
		</select>
	</div>
	<div class="clear"></div>
<?php } ?>


<!-- RANGE REPORT NIK INVALID CABANG -->
<?php
	if($field == "range_nik_invalid") {
		$ls_kode_kantor = $_POST["kode_kantor"];
		$ls_kode_kantor = $ls_kode_kantor == null ? $ses_kode_kantor : $ls_kode_kantor;

		$sql = "SELECT COUNT(1) JUMLAH_NIK_INVALID
					FROM MDT.DAMON_PEMADANAN@TO_MDT  ---@TO_MDT
				--FROM MDT.DAMON_PEMADANAN@TO_OLAP_RO  ---@TO_OLAP_RO
				WHERE     TRUNC (BLTH_PROSES, 'MM') =
							TRUNC (TO_DATE ('2021/02/01', 'YYYY/MM/DD'), 'MM')
						--TRUNC(SYSDATE, 'MM')
						AND KODE_KANTOR = '$ls_kode_kantor'";

		$DB->parse($sql);
		$DB->execute();
		$row = $DB->nextrow();
		$jumlah_data = $row['JUMLAH_NIK_INVALID'];
		//$jumlah_data = 8350000;

		$pembagi=500000;
		$sisaBagi=$jumlah_data%$pembagi;
		$hasilBagi=($jumlah_data-$sisaBagi)/$pembagi;

?>
	<div class="form-row_kiri">
		<label>Jumlah Data </label>
		<input type="text" name="jumlah_data" id="jumlah_data" value="<?=ExtendedFunction::number_format_null($jumlah_data,0,"",".");?>" style="width:140px;" readonly>
	</div>
	<div class="clear"></div>

	<div class="form-row_kiri">
		<label>Range Data *</label>
		<select size="1" id="range" name="range" class="select_format" style="width:150px;">
			<?php
			for($i=0;$i<=$hasilBagi;$i++){
				$d = $i * 500000;
				$e = $d + 500000;
				$dd = ExtendedFunction::number_format_null($d,0,"",".");
				$ee = ExtendedFunction::number_format_null($e,0,"",".");
				echo "<option value='$d'>$dd - $ee</option>";
			}
			?>
		</select>
	</div>
	<div class="clear"></div>
<?php } ?>

<!-- Nama Bank  -->
<?php
	if($field == "nama_bank") {
?>
<div class="form-row_kiri">
	<label>Nama Bank *</label>
	<input type="text" id="nama_bank" name="nama_bank"  value="<?=$nama_bank;?>" size="100" style="width: 200px;">
	</div>
<div class="clear"></div>
<?php } ?>

<!-- NO Rekening  -->
<?php
	if($field == "nomor_rekening") {
?>
<div class="form-row_kiri">
	<label>No Rekening *</label>
	<input type="text" id="nomor_rekening" name="nomor_rekening"  value="<?=$no_rekening;?>" size="100" style="width: 200px;">
	</div>
<div class="clear"></div>
<?php } ?>

<!-- NAMA TTD  -->
<?php
	if($field == "nama_ttd") {
?>
<div class="form-row_kiri">
	<label>Nama *</label>
	<input type="text" id="nama_ttd" name="nama_ttd"  value="<?=$nama_ttd;?>" size="100" style="width: 200px;">
	</div>
<div class="clear"></div>
<?php } ?>

<!-- JABATAN TTD  -->
<?php
	if($field == "jabatan_ttd") {
?>
<div class="form-row_kiri">
	<label>Jabatan *</label>
	<input type="text" id="jabatan_ttd" name="jabatan_ttd"  value="<?=$jabatan_ttd;?>" size="100" style="width: 200px;">
	</div>
<div class="clear"></div>
<?php } ?>

<!-- TGL PROSES -->
<?php
	if($field == "tanggal_proses") {
		$ld_periode = $_POST["tanggal_proses"];
		if (!isset($ld_periode)) {
			$sql = "select to_char(sysdate,'dd/mm/yyyy') tgl from dual";
			$DB->parse($sql);
			$DB->execute();
			$row = $DB->nextrow();
			$ld_periode= $row["TGL"];
		}
?>
<div class="form-row_kiri">
	  <label>Tanggal Proses *</label>
	<input type="text" name="tanggal_proses" id="tanggal_proses" size="12" onblur="convert_date(tanggal_proses);"  value="<?=$ld_periode;?>">
	<input id="btn_periode" type="image" align="top" onclick="return showCalendar('tanggal_proses', 'dd-mm-yyyy');" src="../../images/calendar.gif" />
</div>
<div class="clear"></div>
<?php } ?>

<!-- Status Eligible JKP -->
<?php
	if($field == "status_eligible_jkp") {
?>
<div class="form-row_kiri">
	  <label>Status Eligible *</label>
	  <select size="1" id="status_eligible_jkp" name="status_eligible_jkp" tabindex="2" >
			<option selected value="">-- All --</option>
			<option value="Y">Eligible</option>
			<option value="T">Tidak Eligible</option>
		</select>
	</div>
<div class="clear"></div>
<?php } ?>

<!--Status  Verivali -->
<?php
	if($field == "status_pilih_verivali") {
		$ld_sorting = $_POST["status_pilih_verivali"];
?>
<div class="form-row_kiri">
        <label>Status Verivali</label>
		<select size="1" id="status_pilih_verivali" name="status_pilih_verivali" class="select_format" style="width:60px;" >
			<option selected value="">All</option>
			<option value="1">Ya</option>
			<option value="0">Tidak</option>
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
	<label>Kode ILO</label>
	<input type="text" id="kode_ilo" name="kode_ilo" readonly="readonly" class="disabled" value="<?=$ls_kode_ilo;?>" size="10" style="width: 80px; background-color:#CCCCCC;">
	<input type="text" name="nama_ilo" id="nama_ilo" readonly="readonly" class="disabled" size="30" style="width: 320px; background-color:#CCCCCC;" value="<?=$ls_nama_ilo;?>">
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kode_ilo.php?p=kn9002.php&a=adminForm&b=kode_ilo&c=nama_ilo&d=kode_usaha_utama&e=tarif_ilo','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php } ?>


<!-- PERIODE-Onchange -->
<?php
	if($field == "periode1_onchange") {
		$ld_periode1_onchange = $_POST["periode1_onchange"];
		$ld_periode2_onchange = $_POST["periode2_onchange"];
		if (!isset($ld_periode1) && !isset($ld_periode2)) {
			$sql = "select '01/' || to_char(sysdate,'mm/yyyy') tgl1, to_char(sysdate,'dd/mm/yyyy') tgl2 from dual";
			$DB->parse($sql);
			$DB->execute();
			$row = $DB->nextrow();
			$ld_periode1_onchange = $row["TGL1"];
			$ld_periode2_onchange = $row["TGL2"];
		}
?>
<div class="form-row_kiri">
	<label>Periode*</label>
	<input type="text" name="periode1_onchange" id="periode1_onchange" size="12" onblur="changePeriode();"  value="<?=$ld_periode1_onchange;?>">
	<input id="btn_periode1_onchange" type="image" align="top" onclick="return showCalendar('periode1_onchange', 'dd-mm-yyyy');" src="../../images/calendar.gif" />
	s/d
	<input type="text" name="periode2_onchange" id="periode2_onchange" size="12" onblur="changePeriode();"  value="<?=$ld_periode2_onchange;?>">
	<input id="btn_periode2_onchange" type="image" align="top" onclick="return showCalendar('periode2_onchange', 'dd-mm-yyyy');" src="../../images/calendar.gif" />
</div>
<div class="clear"></div>
<?php } ?>

<!-- ID_DOK -->
<?php
	if($field == "id_dokxx") {
		$ls_id_dok = $_POST["id_dok"];
?>
<div class="form-row_kiri">
	<label>Kode Tagihan *</label>
	<input type="text" id="id_dok" name="id_dok" value="<?=$ls_id_dok;?>" size="10" style="width: 120px;">
</div>
<div class="clear"></div>
<?php } ?>

<!-- Lov ID_DOK-->

<?php
	if($field == "id_dok") {
		$id_dok= $_POST["id_dok"];
?>
<div class="form-row_kiri">
        <label>ID Dokumen Induk *</label>
		<select size="1" id="list_data" name="id_dok" class="select_format" tabindex="3" >
			<div id="list_data" name="list_data"><option selected value="zero">-- Pilih ID Dokumen Induk --</option></div>

		</select>
	</div>
	<div class="clear"></div>
<?php } ?>


<!-- DONATUR -->
<?php
	if($field == "donatur") {
		$ls_kd_pembina = $_POST["donatur"];
		$ls_nm_pembina = $_POST["nama_pembina"];
		$ls_kd_kantor_pembina = $_POST["kode_kantor_pembina"];
?>
<div class="form-row_kiri">
	<label>Donatur *</label>
	<input type="text" id="donatur" name="donatur" value="<?=$ls_kd_pembina;?>" size="10" style="width: 80px;" readonly required>
	<input type="text" name="nama_pembina" id="nama_pembina" size="30" style="width: 320px;" value="<?=$ls_nm_pembina;?>" readonly required>
	<input type="text" name="kode_kantor_pembina" id="kode_kantor_pembina" size="30" style="width: 30px;" value="<?=$ls_kd_kantor_pembina;?>" readonly>
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kode_donatur.php?p=kn900121.php&a=adminForm&b=donatur&c=nama_pembina&d=kode_kantor_pembina','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php }?>


<!-- KODE KEPESERTAAN PERUSAHAAN -->
<?php
	if($field == "kode_kepesertaan") {
		$ls_kode_kepesertaan = $_POST["kode_kepesertaan"];
		$ls_npp = $_POST["npp"];
		$ls_nama_perusahaan = $_POST["nama_perusahaan"];
?>
<div class="form-row_kiri">
	<label>NPP *</label>
	<input type="text" id="npp" name="npp" value="<?=$ls_npp;?>" size="10" style="width: 80px;" readonly>
	<input type="text" name="nama_perusahaan" id="nama_perusahaan" size="30" style="width: 320px;" value="<?=$ls_nama_perusahaan;?>" readonly>
	<input type="hidden" id="kode_kepesertaan" name="kode_kepesertaan" value="<?=$ls_kode_kepesertaan;?>" size="10" style="width: 80px;" readonly>
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kepesertaan_aktif_nonaktif.php?p=kn900121.php&a=adminForm&b=npp&c=nama_perusahaan&d=kode_kepesertaan','',800,500,1)">
	<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
</div>
<div class="clear"></div>
<?php } ?>

<!-- JENIS PIUTANG - KNR1009 -->
<?php
        if($field == "jenis_piutang") {
                $ls_jenis_piutang = $_POST["jenis_piutang"];
?>
<div class="form-row_kiri">
        <label>Jenis Piutang *</label>
        <select size="1" id="jenis_piutang" name="jenis_piutang" class="select_format" style="width:320px;">
                <?
                        echo "<option selected value='ALL'>--PILIH SEMUA--</option>";
                        echo "<option value='A'>LANCAR</option>";
                        echo "<option value='B'>KURANG LANCAR</option>";
                        echo "<option value='C'>DIRAGUKAN</option>";
                        echo "<option value='D'>MACET</option>";
                        echo "<option value='E'>REMINDER</option>";
                ?>
        </select>
</div>
<div class="clear"></div>
<?php } ?>
<!-- KUNJUNGAN PIUTANG - KNR1009-->
<?php
        if($field == "kunjungan_piutang") {
                $ls_jenis_piutang = $_POST["kunjungan_piutang"];
?>
<div class="form-row_kiri">
        <label>Kunjungan *</label>
        <select size="1" id="kunjungan_piutang" name="kunjungan_piutang" class="select_format" style="width:320px;">
                <?
                        echo "<option selected value='ALL'>--PILIH SEMUA--</option>";
                        echo "<option value='1'>SUDAH KUNJUNGAN</option>";
                        echo "<option value='0'>BELUM KUNJUNGAN</option>";
                ?>
        </select>
</div>
<div class="clear"></div>
<?php } ?>
<!-- STATUS SUBMIT DOKUMEN - KNR1009 -->
<?php
        if($field == "status_dokumen") {
                $ls_status_dokumen = $_POST["status_dokumen"];
?>
<div class="form-row_kiri">
        <label>Status Submit *</label>
        <select size="1" id="status_dokumen" name="status_dokumen" class="select_format" style="width:320px;">
                <?
                        echo "<option selected value='ALL'>--PILIH SEMUA--</option>";
                        echo "<option value='DRAFT'>DRAFT</option>";
                        echo "<option value='SELESAI'>SELESAI</option>";
                ?>
        </select>
</div>
<div class="clear"></div>
<?php } ?>
<!-- STATUS SUBMIT DOKUMEN - KNR1009 -->
<?php
        if($field == "status_verifikasi") {
                $ls_status_verif = $_POST["status_verifikasi"];
?>
<div class="form-row_kiri">
        <label>Status Verifikasi *</label>
        <select size="1" id="status_verifikasi" name="status_verifikasi" class="select_format" style="width:320px;">
                <?
                        echo "<option selected value='ALL'>--PILIH SEMUA--</option>";
                        echo "<option value='SELESAI'>SELESAI</option>";
                        echo "<option value='DALAM PROSES'>DALAM PROSES</option>";
                        echo "<option value='TOLAK'>TOLAK</option>";
                ?>
        </select>
</div>
<div class="clear"></div>
<?php } ?>

<!-- PEMBINA - KNR1009 -->
<?php
	if($field == "pembina_knr1009") {
		$ls_kd_pembina = $_POST["pembina_knr1009"];
		$ls_nm_pembina = $_POST["nama_pembina"];
		$ls_kd_kantor_pembina = $_POST["kode_kantor_pembina"];
		if ($_SESSION['regrole']==22||$_SESSION['regrole']==20||$_SESSION['regrole']==18||$_SESSION['regrole']==15||$_SESSION['regrole']==10||$_SESSION['regrole']==9||$_SESSION['regrole']==3020
				||$_SESSION['regrole']==29||$_SESSION['regrole']==79||$_SESSION['regrole']==90||$_SESSION['regrole']==213||$_SESSION['regrole']==214||$_SESSION['regrole']==215||$_SESSION['regrole']==283){
?>
<div class="form-row_kiri">
	<label>Pembina *</label>
	<input type="text" id="pembina_knr1009" name="pembina_knr1009" value="<?=$ls_kd_pembina;?>" size="10" style="width: 80px;" readonly required>
	<input type="text" name="nama_pembina" id="nama_pembina" size="30" style="width: 320px;" value="<?=$ls_nm_pembina;?>" readonly required>
	<input type="text" name="kode_kantor_pembina" id="kode_kantor_pembina" size="30" style="width: 30px;" value="<?=$ls_kd_kantor_pembina;?>" readonly>
	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/lov_kode_pembina_invpiut.php?p=kn9008.php&a=adminForm&b=pembina_knr1009&c=nama_pembina&d=kode_kantor_pembina','',800,500,1)">
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
	<input type="text" id="pembina" name="pembina" value="<?=$ls_kode_user;?>" size="10" style="width: 80px;" readonly>
	<input type="text" name="nama_pembina" id="nama_pembina" size="30" style="width: 320px;" value="<?=$ls_nama_user;?>" readonly>
	<input type="text" name="kode_kantor_pembina" id="kode_kantor_pembina" size="30" style="width: 30px;" value="<?=$_SESSION['kdkantorrole'];?>" readonly>
</div>
<div class="clear"></div>
<?php }
}
 ?>


<!-- ############### -->
<!-- END FIELDS VIEW -->

<script type="text/javascript" src="../../javascript/validator.js"></script>
<script type="text/javascript" src="../../javascript/ajax.js"></script>

<script type="text/javascript">
    var validator = new formValidator();
    var ajax = new sack();

	function f_ajax_val_kpj() {
		var c_kpj = window.document.getElementById('tb_kpj').value;

  	 	if (c_kpj != "") {
			ajax.requestFile = '../ajax/kn9001_fields_validasi.php?getClientId=f_ajax_val_kpj&c_kpj='+c_kpj;
        	ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found
        	ajax.runAJAX();	// Execute AJAX function
  		}
	}

	function f_ajax_val_exists_kpj() {
		NewWindow('../ajax/kn9001_lov_kpj.php?p=kn900132.php&a=adminForm&b=&c=tb_npp_kpj&d=tb_nama_perusahaan_kpj&e=tb_tgl_lahir_kpj&f=tb_tgl_na_kpj&g=tb_kode_perusahaan_kpj&h=tb_kpj&j=tb_kode_tk_kpj&k=tb_nik_kpj&l=tb_nama_tk_kpj','',800,500,1);
	}

	function f_ajax_val_kpj_bpu() {
		var c_kpj = window.document.getElementById('tb_kpj_bpu').value;

  	 	if (c_kpj != "") {
			ajax.requestFile = '../ajax/kn9001_fields_validasi.php?getClientId=f_ajax_val_kpj_bpu&c_kpj='+c_kpj;
        	ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found
        	ajax.runAJAX();	// Execute AJAX function
  		}
	}

	function f_ajax_val_exists_kpj_bpu() {
		NewWindow('../ajax/kn9001_lov_kpj_bpu.php?p=kn900132.php&a=adminForm&b=&c=tb_npp_kpj&d=tb_nama_perusahaan_kpj&e=tb_tgl_lahir_kpj&f=tb_tgl_na_kpj&g=tb_kode_perusahaan_kpj&h=tb_kpj_bpu&j=tb_kode_tk_kpj&k=tb_nik_kpj&l=tb_nama_tk_kpj','',800,500,1);
	}

	function showClientData() {
		var formObj = document.formreg;
      	eval(ajax.response);
  	}

	function changePeriode(){
		// Selected value
		var value1 = $('#periode1_onchange').val();
		var value2 = $('#periode2_onchange').val();
		// var xnxx = "20/06/2022";

		// alert(`${value1} - ${value2}`);
		$.ajax({
				type: 'POST',
				url: "../../mod_kn/ajax/periode-onchange.php",
				data: {value1:value1,value2:value2},
				success: function(result){
					//do something here with return value like alert
					var jdata = JSON.parse(result);

					if (jdata.ret == 1){
						var html_data = "";
						for(var i = 0; i < jdata.data.length; i++){
							html_data += jdata.data[i];

						}

						if (html_data == "") {
							html_data += '<option selected value="zero">-- Pilih ID 2Dokumen Induk --</option>';
						}
						//console.log(html_data);
						$("#list_data").html(html_data);


					}
				}
			})
	}
</script>
