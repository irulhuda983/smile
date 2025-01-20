<?php 
$leaf_name="pn6001_jht_terlambat_setupbunga";
$php_file_name="pn6001_jht_terlambat_setupbunga";

$ses_reg_role = $_SESSION['regrole'];
$ls_kode_agenda	 = !isset($_GET['kd_agenda']) ? $_POST['kd_agenda'] : $_GET['kd_agenda'];

if($_REQUEST['kd_agenda']!='')
{
    $streadonly=" readonly";
    require_once "../../includes/conf_global.php";
    require_once "../../includes/class_database.php";
  
  // Pengambilan JHT Sebelumnya
    $sql_sebelumnya ="
	  select 
	  KODE_AGENDA,
	  KODE_KLAIM,
	  KODE_MANFAAT,
	  NO_URUT,
	  KODE_MANFAAT_DETIL,
	  KATEGORI_MANFAAT,
	  KODE_TIPE_PENERIMA,
	  KD_PRG,
	  NOM_BIAYA_DIAJUKAN,
	  NOM_BIAYA_DISETUJUI,
	  PENGAMBILAN_KE,
	  NOM_MANFAAT_SUDAHDIAMBIL,
	  NOM_DIAMBIL_THNBERJALAN,
	  NOM_PENGEMBANGAN_ESTIMASI,
	  TGL_PENGEMBANGAN_ESTIMASI,
	  to_char(TGL_PENGEMBANGAN,'dd/mm/yyyy') TGL_PENGEMBANGAN,
	  to_char(TGL_SALDO_AWALTAHUN,'dd/mm/yyyy') TGL_SALDO_AWALTAHUN,
	  NOM_SALDO_AWALTAHUN,
	  NOM_SALDO_PENGEMBANGAN,
	  NOM_SALDO_TOTAL,
	  NOM_IURAN_THNBERJALAN,
	  NOM_IURAN_PENGEMBANGAN,
	  NOM_IURAN_TOTAL,
	  NOM_SALDO_IURAN_TOTAL,
	  PERSENTASE_PENGAMBILAN,
	  NOM_MANFAAT_MAXBISADIAMBIL,
	  NOM_MANFAAT_DIAMBIL,
	  NOM_MANFAAT_UTAMA,
	  NOM_MANFAAT_TAMBAHAN,
	  NOM_MANFAAT_GROSS,
	  NOM_PPN,
	  NOM_PPH,
	  NOM_PEMBULATAN,
	  NOM_MANFAAT_NETTO,
	  KODE_PAJAK_PPN,
	  KODE_PAJAK_PPH,
	  KODE_OBJEK_PPH,
	  KODE_KOMPONEN_PPH,
	  KODE_TARIF_PPH,
	  NOM_DPP_PPH,
	  TARIF_PPH,
	  FLAG_BUKTIPOTONG_PPH,
	  FLAG_PPH_PROGRESIF,
	  KETERANGAN,
	  NOM_TINGKAT_PENGEMBANGAN
	  from pn.pn_agenda_koreksi_klaim_mnfdtl where kode_agenda='{$_REQUEST['kd_agenda']}'  and status_seharusnya = 'T' "; 
    $DB->parse($sql_sebelumnya);
    if($DB->execute())
        if($row = $DB->nextrow())
        {
		  $ls_tp_penerima = $row['KODE_TIPE_PENERIMA'];
		  $ls_prosen_pengambilan = number_format($row['PERSENTASE_PENGAMBILAN'],2,'.',',');
		  $ls_tingkat_pengembangan = number_format($row['NOM_TINGKAT_PENGEMBANGAN'],2,'.',',');
		  $ls_saldo_awal1 = $row['TGL_SALDO_AWALTAHUN'];
		  $ls_saldo_awal2 = number_format($row['NOM_SALDO_AWALTAHUN'],2,'.',',');
		  $ls_pengambilan_berjalan = number_format($row['NOM_MANFAAT_GROSS_KOREKSI'],2,'.',',');
		  $ls_saldo_pengembangan1 = $row['TGL_PENGEMBANGAN'];
		  $ls_saldo_pengembangan2 = number_format($row['NOM_SALDO_PENGEMBANGAN'],2,'.',',');
		  $ls_max_pengambilan = number_format($row['NOM_MANFAAT_MAXBISADIAMBIL'],2,'.',',');
		  $ls_total_saldo = number_format($row['NOM_SALDO_TOTAL'],2,'.',',');
		  $ls_jmlh_diambil = number_format($row['NOM_MANFAAT_MAXBISADIAMBIL'],2,'.',',');
		  $ls_iuran_jht = number_format($row['NOM_IURAN_THNBERJALAN'],2,'.',',');
		  $ls_pph_21_1 = $row['KODE_PAJAK_PPH'];
		  $ls_pph_21_2 = number_format($row['NOM_PPH'],2,'.',',');
		  $ls_iuran_pengembangan1 = $row['TGL_PENGEMBANGAN'];
		  $ls_iuran_pengembangan2 = number_format($row['NOM_IURAN_PENGEMBANGAN'],2,'.',',');
		  $ls_pembulatan = number_format($row['NOM_PEMBULATAN'],2,'.',',');
		  $ls_total_iuran = number_format($row['NOM_IURAN_TOTAL'],2,'.',',');
		  $ls_jmlh_dibayar = number_format($row['NOM_MANFAAT_NETTO'],2,'.',',');
		  $ls_total_saldo_iuran = number_format($row['NOM_SALDO_IURAN_TOTAL'],2,'.',',');
        }
  
  // Pengambilan JHT Seharusnya
    $sql_seharusnya ="
	  select 
	  KODE_AGENDA,
	  KODE_KLAIM,
	  KODE_MANFAAT,
	  NO_URUT,
	  KODE_MANFAAT_DETIL,
	  KATEGORI_MANFAAT,
	  KODE_TIPE_PENERIMA,
	  KD_PRG,
	  NOM_BIAYA_DIAJUKAN,
	  NOM_BIAYA_DISETUJUI,
	  PENGAMBILAN_KE,
	  NOM_MANFAAT_SUDAHDIAMBIL,
	  NOM_DIAMBIL_THNBERJALAN,
	  NOM_PENGEMBANGAN_ESTIMASI,
	  TGL_PENGEMBANGAN_ESTIMASI,
	  to_char(TGL_PENGEMBANGAN,'dd/mm/yyyy') TGL_PENGEMBANGAN,
	  to_char(TGL_SALDO_AWALTAHUN,'dd/mm/yyyy') TGL_SALDO_AWALTAHUN,
	  NOM_SALDO_AWALTAHUN,
	  NOM_SALDO_PENGEMBANGAN,
	  NOM_SALDO_TOTAL,
	  NOM_IURAN_THNBERJALAN,
	  NOM_IURAN_PENGEMBANGAN,
	  NOM_IURAN_TOTAL,
	  NOM_SALDO_IURAN_TOTAL,
	  PERSENTASE_PENGAMBILAN,
	  NOM_MANFAAT_MAXBISADIAMBIL,
	  NOM_MANFAAT_DIAMBIL,
	  NOM_MANFAAT_UTAMA,
	  NOM_MANFAAT_TAMBAHAN,
	  NOM_MANFAAT_GROSS,
	  NOM_PPN,
	  NOM_PPH,
	  NOM_PEMBULATAN,
	  NOM_MANFAAT_NETTO,
	  KODE_PAJAK_PPN,
	  KODE_PAJAK_PPH,
	  KODE_OBJEK_PPH,
	  KODE_KOMPONEN_PPH,
	  KODE_TARIF_PPH,
	  NOM_DPP_PPH,
	  TARIF_PPH,
	  FLAG_BUKTIPOTONG_PPH,
	  FLAG_PPH_PROGRESIF,
	  KETERANGAN,
	  NOM_TINGKAT_PENGEMBANGAN
	  from pn.pn_agenda_koreksi_klaim_mnfdtl where kode_agenda='{$_REQUEST['kd_agenda']}'  and status_seharusnya = 'Y' "; 
    $DB->parse($sql_seharusnya);
    if($DB->execute())
        if($row = $DB->nextrow())
        {
			$ls_seharusnya_tp_penerima = $row['KODE_TIPE_PENERIMA'];
			$ls_seharusnya_prosen_pengambilan = number_format($row['PERSENTASE_PENGAMBILAN'],2,'.',',');
			$ls_seharusnya_tingkat_pengembangan = number_format($row['NOM_TINGKAT_PENGEMBANGAN'],2,'.',',');
			$ls_seharusnya_saldo_awal1 = $row['TGL_SALDO_AWALTAHUN'];
			$ls_seharusnya_saldo_awal2 = number_format($row['NOM_SALDO_AWALTAHUN'],2,'.',',');
			$ls_seharusnya_pengambilan_berjalan = number_format($row['NOM_MANFAAT_GROSS_KOREKSI'],2,'.',',');
			$ls_seharusnya_saldo_pengembangan1 = $row['TGL_PENGEMBANGAN'];
			$ls_seharusnya_saldo_pengembangan2 = number_format($row['NOM_SALDO_PENGEMBANGAN'],2,'.',',');
			$ls_seharusnya_max_pengambilan = number_format($row['NOM_MANFAAT_MAXBISADIAMBIL'],2,'.',',');
			$ls_seharusnya_total_saldo = number_format($row['NOM_SALDO_TOTAL'],2,'.',',');
			$ls_seharusnya_jmlh_diambil = number_format($row['NOM_MANFAAT_MAXBISADIAMBIL'],2,'.',',');
			$ls_seharusnya_iuran_jht = number_format($row['NOM_IURAN_THNBERJALAN'],2,'.',',');
			$ls_seharusnya_pph_21_1 = $row['KODE_PAJAK_PPH'];
			$ls_seharusnya_pph_21_2 = number_format($row['NOM_PPH'],2,'.',',');
			$ls_seharusnya_iuran_pengembangan1 = $row['TGL_PENGEMBANGAN'];
			$ls_seharusnya_iuran_pengembangan2 = number_format($row['NOM_IURAN_PENGEMBANGAN'],2,'.',',');
			$ls_seharusnya_pembulatan = number_format($row['NOM_PEMBULATAN'],2,'.',',');
			$ls_seharusnya_total_iuran = number_format($row['NOM_IURAN_TOTAL'],2,'.',',');
			$ls_seharusnya_jmlh_dibayar = number_format($row['NOM_MANFAAT_NETTO'],2,'.',',');
			$ls_seharusnya_total_saldo_iuran = number_format($row['NOM_SALDO_IURAN_TOTAL'],2,'.',',');
		} 
    
  // Koreksi Pembayaran JHT / Selisih Pembayaran JHT
    $sql_selisih ="select * from pn.pn_agenda_koreksi_klaim where kode_agenda='{$_REQUEST['kd_agenda']}'"; 
    $DB->parse($sql_selisih);
    if($DB->execute())
        if($row = $DB->nextrow())
        {
			$ls_selisih_diambil=number_format($row['NOM_MANFAAT_GROSS_KOREKSI'],2,'.',',');
			$ls_selisih_pph21=number_format($row['NOM_PPH_KOREKSI'],2,'.',',');
			$ls_selisih_pembulatan=number_format($row['NOM_PEMBULATAN_KOREKSI'],2,'.',',');
			$ls_selisih_jumlah_dibayar=number_format($row['NOM_MANFAAT_NETTO_KOREKSI'],2,'.',',');
			$ls_selisih_keterangan=number_format($row['KETERANGAN'],2,'.',',');
			$ls_klaim=$row['KODE_KLAIM'];
        } 
}
?>
<style>
.f_0{}
.f_0 form fieldset legend {
    font-size: 100%;
    font-weight: bold;
    color : #157fcc;
    font-family: verdana, arial, tahoma, sans-serif;         
  }
.f_0 input,textarea, select  {
          border: 1px solid #dddddd;
        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.056);
        box-shadow: inset 0 1px 1px rgba(0,0,0,0.056);
        padding:2px;
        font-size:10px;
        font-family: verdana, arial, tahoma, sans-serif;        
  }
  .f_0 input:disabled,textarea:disabled, select:disabled  {
          color: #C5C4C4;      
          border: 1px solid #dddddd;
        -webkit-box-shadow: inset 0 1px 1px rgba(255,255,255,0.556);
        box-shadow: inset 0 1px 1px rgba(255,255,255,0.556);
        padding:2px;
        font-size:10px;
        font-family: verdana, arial, tahoma, sans-serif;        
  }
  .f_0 input:readonly,textarea:readonly, select:readonly  {
          color: #3e3724;
        background: #F5F5F5;          
        -webkit-box-shadow: inset 0 1px 1px rgba(255,255,255,0.556);
        box-shadow: inset 0 1px 1px rgba(255,255,255,0.556);
        padding:2px;
        font-size:10px;
        font-family: verdana, arial, tahoma, sans-serif;        
  }
.f_1{width:150px;text-align:right;float:left;margin-bottom:2px;}
.f_2{width:340px;text-align:left;margin-left:2px;float:left;margin-bottom:2px;  }
.f_3 {min-width:460px;margin-bottom:2px;}
.f_4 {min-width:460px;text-align:left;margin-bottom:2px;}
.f_readonly{background-color:#e9e9e9;}
.f_mandatory{background-color:#ffff99;}
.f_5 {min-width:460px;text-align:left;margin-bottom:6px !Important;}
.f_3 label{
    display:block;
    float:left;
    width:160px;
    text-align:right !Important;
    margin:0px 5px 0px 0px;
   /* font-family: verdana, arial, tahoma, sans-serif;         */
}
.gw_tbl{border-collapse: collapse;}
.gw_tbl table, .gw_tbl th, .gw_tbl td {border: 1px solid #C0C0C0;}
.gw_tbl th{background-color: #F0F0F0; } 

.gw_tr{cursor:pointer}
.gw_tr:hover{background-color:#DDDDFF}
.f_4 label{
    display:block;
    float:left;
    width:130px !important;
    text-align:right !Important;
    margin:0px 5px 0px 0px;
   /* font-family: verdana, arial, tahoma, sans-serif;         */
}
.f_5 label{
    display:block;
    float:left;
    width:130px !important;
    text-align:right !Important;
    margin:0px 5px 0px 0px;
   /* font-family: verdana, arial, tahoma, sans-serif;         */
}

</style>
<style type="text/css">
input[type="checkbox"][readonly] {
  pointer-events: none;
}
</style>
<div class="f_0" style="width: 100%;clear:both;"> 
<fieldset ><legend>Tindaklanjut JHT Kurang Bayar Karena Keterlambatan Set Up Bunga</legend>
<input type="hidden" style="width: 290px;" value="" <?=$readonly;?> id="nom_tingkat_pengembangan" name="nom_tingkat_pengembangan" readonly />
<input type="hidden" style="width: 290px;" value="" <?=$readonly;?> id="nom_tingkat_pengembangan_kor" name="nom_tingkat_pengembangan_kor" readonly />
    <?php if($_REQUEST['task']!='View')  {?>
    <fieldset><legend>Pemilihan Klaim</legend>
        <div class="f_1">Kode Klaim &nbsp;:</div>
        <div class="f_2" style="width: 430px !Important">
            <input type="text" style="width: 290px;" value="" <?=$readonly;?> id="kd_klaim_pilihan" name="kd_klaim_pilihan" readonly /> 
            <a href="#" onclick="NewWindow('pn6001_jht_terlambat_setupbunga_lov.php','pn6001_jht_terlambat_setupbunga',1070,505,1)">
                <img src="../../images/help.png" alt="Cari Perihal Agenda" border="0" align="absmiddle">
            </a>
        </div>
    </fieldset>
    <?php }?>
    <input type="hidden" id="h_kode_agenda" value="" />
    <input type="hidden" id="h_kode_klaim" value="" />
    <fieldset><legend>Informasi Klaim</legend>
        <div class="f_1">Kantor &nbsp;:</div>
        <div class="f_2"><input type="text" style="background-color: #F5F5F5; width: 290px;" readonly value="" id="kd_kantor" /></div>
        <div class="f_1">Kode Klaim &nbsp;:</div>
        <div class="f_2""><input type="text" style="background-color: #F5F5F5; width: 290px;" readonly value="" id="kd_klaim" name="kd_klaim" /></div>
        <div class="f_1" style="clear: left">Segmentasi Keps &nbsp;:</div>
        <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 100px;" readonly value="" id="kd_segmen" /></div>
        <div class="f_1">Tgl Klaim &nbsp;:</div>
        <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 120px;" readonly value="" id="tgl_klaim" /></div>
        <div class="f_1" style="clear: left">No. Referensi &nbsp;:</div>
        <div class="f_2">
            <input type="text" style="background-color:  #F5F5F5; width: 100px;" readonly value="" id="no_ref" />
            <input type="text" style="background-color:  #F5F5F5; width: 180px;" readonly value="" id="nm_tk" />
        </div>
        <div class="f_1" style="clear: left">NIK &nbsp;:</div>
        <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 290px;" readonly value="" id="nik" /></div>
        <div class="f_1">Tgl Penetapan &nbsp;:</div>
        <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 120px;" readonly value="" id="tgl_penetapan" /></div>
        <div class="f_1" style="clear: left">NPP &nbsp;:</div>
        <div class="f_2">
            <input type="text" style="background-color:  #F5F5F5; width: 100px;" readonly value="" id="npp" />
            <input type="text" style="background-color:  #F5F5F5; width: 180px;" readonly value="" id="nm_prs" />
        </div>
        <div class="f_1">Petugas Penetapan &nbsp;:</div>
        <div class="f_2"><input type="text" style="background-color: #F5F5F5; width: 290px;" readonly value="" id="petugas_penetapan" /></div>
        <div class="f_1" style="clear: left">Unit &nbsp;:</div>
        <div class="f_2">
            <input type="text" style="background-color: #F5F5F5; width: 100px;" readonly value="" id="kd_unit" />
            <input type="text" style="background-color: #F5F5F5; width: 180px;" readonly value="" id="nm_unit" />
        </div>
        <div class="f_1">No. Penetapan &nbsp;:</div>
        <div class="f_2"><input type="text" style="background-color: #F5F5F5; width: 290px;" readonly value="" id="no_penetapan" /></div>
        <div class="f_1" style="clear: left">Tgl Lapor &nbsp;:</div>
        <div class="f_2"><input type="text" style="background-color: #F5F5F5; width: 100px;" readonly value="" id="tgl_lapor" /></div>
    </fieldset>
    <fieldset><legend>Pengambilan JHT Awal</legend>
        <div class="f_1">Tipe Penerima &nbsp;:</div>
        <div class="f_2"><input type="text" style="background-color: #F5F5F5; width: 100px;" readonly value="<?=$ls_tp_penerima;?>" id="tp_penerima" /></div>
        <div class="f_1">Prosentasi Pengambilan &nbsp;:</div>
        <div class="f_2""><input type="text" style="background-color: #F5F5F5; width: 100px;text-align: right;" readonly value="<?=$ls_prosen_pengambilan;?>" id="prosen_pengambilan" /></div>
        <div class="f_1" style="clear: left;color:#009999;">Saldo JHT &nbsp;:</div>
    <div class="f_1" style="clear: left">% Tingkat Pengembangan &nbsp;:</div>
    <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_tingkat_pengembangan;?>"  id="tingkat_pengembangan"/></div>
        <div class="f_1" style="clear: left">Saldo Awal Tahun &nbsp;:</div>
        <div class="f_2">
            <input type="text" style="background-color:  #F5F5F5; width: 100px;" readonly value="<?=$ls_saldo_awal1;?>" id="saldo_awal1" />
            <input type="text" style="background-color:  #F5F5F5; width: 180px;text-align: right;" readonly value="<?=$ls_saldo_awal2;?>" id="saldo_awal2" />
        </div>
        <div class="f_1">Pengambilan Thn Berjalan &nbsp;:</div>
        <div class="f_2""><input type="text" style="background-color: #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_pengambilan_berjalan;?>" id="pengambilan_berjalan" /></div>
        <div class="f_1" style="clear: left">Saldo Pengembangan &nbsp;:</div>
        <div class="f_2">
            <input type="text" style="background-color:  #F5F5F5; width: 100px;" readonly value="<?=$ls_saldo_pengembangan1;?>" id="saldo_pengembangan1" />
            <input type="text" style="background-color:  #F5F5F5; width: 180px;text-align: right;" readonly value="<?=$ls_saldo_pengembangan2;?>" id="saldo_pengembangan2"/>
        </div>
        <div class="f_1">Max Pengambilan &nbsp;:</div>
        <div class="f_2""><input type="text" style="background-color: #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_max_pengambilan;?>" id="max_pengambilan" /></div>
        <div class="f_1" style="clear: left"><i>Total Saldo &nbsp;:</i></div>
        <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_total_saldo;?>"  id="total_saldo"/></div>
        <div class="f_1">Jumlah Diambil &nbsp;:</div>
        <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_jmlh_diambil;?>" id="jmlh_diambil" /></div>
        <div class="f_1" style="clear: left;color:#009999;">Iuran JHT &nbsp;:</div>
        <div class="f_1" style="clear: left">Iuran Tambahan &nbsp;:</div>
        <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_iuran_jht;?>" id="iuran_jht" /></div>
        <div class="f_1">Pph Pasal 21 &nbsp;:</div>
        <div class="f_2"">
            <input type="text" style="background-color:  #F5F5F5; width: 100px;" readonly value="<?=$ls_pph_21_1;?>" id="pph_21_1" />
            <input type="text" style="background-color:  #F5F5F5; width: 180px;text-align: right;" readonly value="<?=$ls_pph_21_2;?>" id="pph_21_2" />
        </div>
        <div class="f_1" style="clear: left">Iuran Pengembangan &nbsp;:</div>
        <div class="f_2">
            <input type="text" style="background-color:  #F5F5F5; width: 100px;" readonly value="<?=$ls_iuran_pengembangan1;?>" id="iuran_pengembangan1" />
            <input type="text" style="background-color:  #F5F5F5; width: 180px;text-align: right;" readonly value="<?=$ls_iuran_pengembangan2;?>" id="iuran_pengembangan2" />
        </div>
        <div class="f_1">Pembulatan &nbsp;:</div>
        <div class="f_2""><input type="text" style="background-color: #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_pembulatan;?>" id="pembulatan" /></div>
        <div class="f_1" style="clear: left"><i>Total Iuran &nbsp;:</i></div>
        <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_total_iuran;?>" id="total_iuran" /></div>
        <div class="f_1">Jumlah Dibayar &nbsp;:</div>
        <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_jmlh_dibayar;?>" id="jmlh_dibayar" /></div>
    <div class="f_1" style="clear: left"><i>Total Saldo+Iuran  &nbsp;:</i></div>
        <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_total_saldo_iuran;?>" id="total_saldo_iuran" /></div>
    </fieldset>
  <fieldset><legend>Pengambilan JHT Seharusnya</legend>
        <div class="f_1">Tipe Penerima &nbsp;:</div>
        <div class="f_2"><input type="text" style="background-color: #F5F5F5; width: 100px;" readonly value="<?=$ls_seharusnya_tp_penerima;?>" id="seharusnya_tp_penerima" /></div>
        <div class="f_1">Prosentasi Pengambilan &nbsp;:</div>
        <div class="f_2""><input type="text" style="background-color: #F5F5F5; width: 100px;text-align: right;" readonly value="<?=$ls_seharusnya_prosen_pengambilan;?>" id="seharusnya_prosen_pengambilan" /></div>
        <div class="f_1" style="clear: left;color:#009999;">Saldo JHT &nbsp;:</div>
    <div class="f_1" style="clear: left">% Tingkat Pengembangan &nbsp;:</div>
    <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_seharusnya_tingkat_pengembangan;?>"  id="seharusnya_tingkat_pengembangan"/></div>
        <div class="f_1" style="clear: left">Saldo Awal Tahun &nbsp;:</div>
        <div class="f_2">
            <input type="text" style="background-color:  #F5F5F5; width: 100px;" readonly value="<?=$ls_seharusnya_saldo_awal1;?>" id="seharusnya_saldo_awal1" />
            <input type="text" style="background-color:  #F5F5F5; width: 180px;text-align: right;" readonly value="<?=$ls_seharusnya_saldo_awal2;?>" id="seharusnya_saldo_awal2" />
        </div>
        <div class="f_1">Pengambilan Thn Berjalan &nbsp;:</div>
        <div class="f_2""><input type="text" style="background-color: #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_seharusnya_pengambilan_berjalan;?>" id="seharusnya_pengambilan_berjalan" /></div>
        <div class="f_1" style="clear: left">Saldo Pengembangan &nbsp;:</div>
        <div class="f_2">
            <input type="text" style="background-color:  #F5F5F5; width: 100px;" readonly value="<?=$ls_seharusnya_saldo_pengembangan1;?>" id="seharusnya_saldo_pengembangan1" />
            <input type="text" style="background-color:  #F5F5F5; width: 180px;text-align: right;" readonly value="<?=$ls_seharusnya_saldo_pengembangan2;?>" id="seharusnya_saldo_pengembangan2"/>
        </div>
        <div class="f_1">Max Pengambilan &nbsp;:</div>
        <div class="f_2""><input type="text" style="background-color: #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_seharusnya_max_pengambilan;?>" id="seharusnya_max_pengambilan" /></div>
        <div class="f_1" style="clear: left"><i>Total Saldo &nbsp;:</i></div>
        <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_seharusnya_total_saldo;?>"  id="seharusnya_total_saldo"/></div>
        <div class="f_1">Jumlah Diambil &nbsp;:</div>
        <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_seharusnya_jmlh_diambil;?>" id="seharusnya_jmlh_diambil" /></div>
        <div class="f_1" style="clear: left;color:#009999;">Iuran JHT &nbsp;:</div>
        <div class="f_1" style="clear: left">Iuran Tambahan &nbsp;:</div>
        <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_seharusnya_iuran_jht;?>" id="seharusnya_iuran_jht" /></div>
        <div class="f_1">Pph Pasal 21 &nbsp;:</div>
        <div class="f_2"">
            <input type="text" style="background-color:  #F5F5F5; width: 100px;" readonly value="<?=$ls_seharusnya_pph_21_1;?>" id="seharusnya_pph_21_1" />
            <input type="text" style="background-color:  #F5F5F5; width: 180px;text-align: right;" readonly value="<?=$ls_seharusnya_pph_21_2;?>" id="seharusnya_pph_21_2" />
        </div>
        <div class="f_1" style="clear: left">Iuran Pengembangan &nbsp;:</div>
        <div class="f_2">
            <input type="text" style="background-color:  #F5F5F5; width: 100px;" readonly value="<?=$ls_seharusnya_iuran_pengembangan1;?>" id="seharusnya_iuran_pengembangan1" />
            <input type="text" style="background-color:  #F5F5F5; width: 180px;text-align: right;" readonly value="<?=$ls_seharusnya_iuran_pengembangan2;?>" id="seharusnya_iuran_pengembangan2" />
        </div>
        <div class="f_1">Pembulatan &nbsp;:</div>
        <div class="f_2""><input type="text" style="background-color: #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_seharusnya_pembulatan;?>" id="seharusnya_pembulatan" /></div>
        <div class="f_1" style="clear: left"><i>Total Iuran &nbsp;:</i></div>
        <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_seharusnya_total_iuran;?>" id="seharusnya_total_iuran" /></div>
        <div class="f_1">Jumlah Dibayar &nbsp;:</div>
        <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_seharusnya_jmlh_dibayar;?>" id="seharusnya_jmlh_dibayar" /></div>
    <div class="f_1" style="clear: left"><i>Total Saldo+Iuran  &nbsp;:</i></div>
        <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_seharusnya_total_saldo_iuran;?>" id="seharusnya_total_saldo_iuran" /></div>
    </fieldset>
    <fieldset><legend>Koreksi Pembayaran JHT / Selisih Pembayaran JHT</legend>
        <div class="f_1">&nbsp; </div>
        <div class="f_2"">&nbsp;</div>
        <div class="f_1">Selisih Diambil &nbsp;:</div>
        <div class="f_2"><input type="tex" style="background-color:  #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_selisih_diambil;?>" id="selisih_diambil" name="selisih_diambil" /></div>
        <div class="f_1" style="clear: left">&nbsp;</div>
        <div class="f_2">&nbsp;</div>
    <div class="f_1">Selisih Pph Pasal 21 &nbsp;:</div>
        <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_selisih_pph21;?>" id="selisih_pph21" name="selisih_pph21" /></div>
    <div class="f_1" style="clear: left">&nbsp;</div>
        <div class="f_2">&nbsp;</div>
    <div class="f_1">Selisih Pembulatan &nbsp;:</div>
        <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_selisih_pembulatan;?>" id="selisih_pembulatan" name="selisih_pembulatan" /></div>
    <div class="f_1" style="clear: left">&nbsp;</div>
        <div class="f_2">&nbsp;</div>
    <div class="f_1">Selisih Jumlah Dibayar &nbsp;:</div>
        <div class="f_2"><input type="text" style="background-color:  #F5F5F5; width: 290px;text-align: right;" readonly value="<?=$ls_selisih_jumlah_dibayar;?>" id="selisih_jumlah_dibayar" name="selisih_jumlah_dibayar" /></div>
    <div class="f_1" style="clear: left">&nbsp;</div>
        <div class="f_2">&nbsp;</div>
    <div class="f_1">Keterangan &nbsp;:</div>
        <div class="f_2"><input type="text" style="width: 290px;" value="<?=$ls_nett;?>" id="ls_selisih_keterangan" name="selisih_keterangan" /></div>
        <input type="hidden" id="j_total" value="0">
    </fieldset>
	<!-- start pembayaran -->
	<?php
	$sql_submit = "
			SELECT COUNT(*) JML_STATUS_SUBMIT_KOREKSI
			FROM PN.PN_AGENDA_KOREKSI_KLAIM
			WHERE KODE_AGENDA = '".$_REQUEST['kd_agenda']."'
			AND NVL(STATUS_SUBMIT_KOREKSI,'Y') = 'Y'
			AND NVL(STATUS_BATAL,'T') = 'T'
			"; 
	$DB->parse($sql_submit);
	if($DB->execute()){
		if($row = $DB->nextrow())
		{
			$ls_jml_submit_koreksi_data = $row['JML_STATUS_SUBMIT_KOREKSI'];
		}
	}
	
	$sql_approve = "
			SELECT COUNT(*) JML_STATUS_APPROVE_KOREKSI
			FROM PN.PN_AGENDA_KOREKSI_KLAIM
			WHERE KODE_AGENDA = '".$_REQUEST['kd_agenda']."'
			AND NVL(STATUS_SUBMIT_KOREKSI,'Y') = 'Y'
			AND NVL(STATUS_APPROVAL,'Y') = 'Y'
			AND NVL(STATUS_BATAL,'T') = 'T'
			"; 
	$DB->parse($sql_approve);
	if($DB->execute()){
		if($row = $DB->nextrow())
		{
			$ls_jml_approve_koreksi_data = $row['JML_STATUS_APPROVE_KOREKSI'];
		}
	}
	
	$sql_batal = "
			SELECT COUNT(*) JML_STATUS_BATAL_KOREKSI
			FROM PN.PN_AGENDA_KOREKSI_KLAIM
			WHERE KODE_AGENDA = '".$_REQUEST['kd_agenda']."'
			AND NVL(STATUS_BATAL,'T') = 'Y'
			"; 
	$DB->parse($sql_batal);
	if($DB->execute()){
		if($row = $DB->nextrow())
		{
			$ls_jml_batal_koreksi_data = $row['JML_STATUS_BATAL_KOREKSI'];
		}
	}
	?>
	<?php
	//if ($ls_jml_approve_koreksi_data > 0)
	{
	?>
		<div id="konten">
		<?
		//------------- pembayaran --------------------------------------------
		include "../ajax/pn6001_jht_terlambat_setupbunga_pembayaran_grid.php";
		?>
		</div>
	<?php 
	}
	?>
	<!-- end pembayaran -->
	<?php
	if((($_REQUEST['kd_agenda']!='') && ($_REQUEST['task']!='New')) )		
	{
	?>
	<br/>
	<div class="clear"></div>
	<div class="form-row_kiri">
	<?php
		if ($ls_jml_batal_koreksi_data == 0)
		{
	?>
		<?php 
			if ($ls_jml_submit_koreksi_data == 0)
			{
		?>
		<input type="button" class="btn green" id="btn_submit_koreksi_jht_kurang_bayar" name="btn_submit_koreksi_jht_kurang_bayar" value="SUBMIT" title="Klik Untuk Submit Koreksi">
		
		<?php
			}
			else
			{
				if ($ses_reg_role == 6)
				{
					if ($ls_jml_approve_koreksi_data == 0)
					{
		?>
		<input type="button" class="btn green" id="btn_approve_koreksi_jht_kurang_bayar" name="btn_approve_koreksi_jht_kurang_bayar" value="APPROVE" title="Klik Untuk Persetujuan Koreksi">
		<?php
					}
				}
				else
				{
					if($_GET['form']=='')
					{
		?>
		<input type="button" class="btn green" id="btn_cetak_penetapan_koreksi_jht_kurang_bayar" name="btn_cetak_penetapan_koreksi_jht_kurang_bayar" value="CETAK PENETAPAN KOREKSI" title="Klik Untuk Cetak Koreksi">
		<?php
					}
				}
			}
		?>
		<?php
			if ($ls_jml_approve_koreksi_data == 0)
			{
		?>
		<input type="button" class="btn green" id="btn_batal_koreksi_jht_kurang_bayar" name="btn_batal_koreksi_jht_kurang_bayar" value="BATAL" title="Klik Untuk Batal Koreksi">
		<?php 
			}
		?>
	<?php 
		}
	?>
	</div>
	<div class="clear"></div>
	<br>
	<fieldset style="background: #F2F2F2; width: 1024px"><legend style="background: #F2F2F2; border: 1px solid #CCC;">KETERANGAN</legend>
		<li>Klik tombol SUBMIT untuk submit koreksi persetujuan ke KBL/KaKCP</li>
		<li>Klik tombol CETAK PENETAPAN KOREKSI untuk cetak penatapan koreksi JHT Kurang Bayar karena keterlambatan set up bunga</li>
		<li>Klik tombol BATAL untuk batal koreksi JHT Kurang Bayar karena keterlambatan set up bunga</li>
	</fieldset>
	<br>
	<?php
	}
	?>
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
</fieldset>
</div>
<script type="text/javascript">
function getDataJhtKurangBayarSetupBunga(p_kode)
{   preload(true);
    $.ajax({
        type: 'POST',
        url: "../ajax/<?=$php_file_name;?>_query.php?"+Math.random(),
        data: {TYPE:"p",KEYWORD:p_kode},
        success: function(data){
            preload(false);
            //console.log(simdata);
            var jdata = JSON.parse(data);
            if(jdata.INFORMASI_KLAIM)
            {
                $("#kd_kantor").val(jdata.INFORMASI_KLAIM.KODE_KANTOR+' '+jdata.INFORMASI_KLAIM.NAMA_KANTOR);
				$("#kd_klaim_pilihan").val(jdata.INFORMASI_KLAIM.KODE_KLAIM);
                $("#kd_klaim").val(jdata.INFORMASI_KLAIM.KODE_KLAIM);
                $("#kd_segmen").val(jdata.INFORMASI_KLAIM.KODE_SEGMEN);
                $("#tgl_klaim").val(jdata.INFORMASI_KLAIM.TGL_KLAIM);
                $("#no_ref").val(jdata.INFORMASI_KLAIM.KPJ);
                $("#nm_tk").val(jdata.INFORMASI_KLAIM.NAMA_TK);
                $("#nik").val(jdata.INFORMASI_KLAIM.NOMOR_IDENTITAS);
                $("#tgl_penetapan").val(jdata.INFORMASI_KLAIM.TGL_PENETAPAN);
                $("#npp").val(jdata.INFORMASI_KLAIM.NPP);
                $("#nm_prs").val(jdata.INFORMASI_KLAIM.NAMA_PERUSAHAAN);
                $("#petugas_penetapan").val(jdata.INFORMASI_KLAIM.PETUGAS_PENETAPAN);
                $("#kd_unit").val(jdata.INFORMASI_KLAIM.KODE_DIVISI);
                $("#nm_unit").val(jdata.INFORMASI_KLAIM.NAMA_DIVISI);
                $("#no_penetapan").val(jdata.INFORMASI_KLAIM.NO_PENETAPAN);
                $("#tgl_lapor").val(jdata.INFORMASI_KLAIM.TGL_LAPOR);
                $("#nom_tingkat_pengembangan").val(jdata.INFORMASI_KLAIM.NOM_TINGKAT_PENGEMBANGAN);
                $("#nom_tingkat_pengembangan_kor").val(jdata.INFORMASI_KLAIM.NOM_TINGKAT_PENGEMBANGAN_KOR);
            }else{
                $("#kd_kantor").val('');
				$("#kd_klaim_pilihan").val('');
                $("#kd_klaim").val('');
                $("#kd_segmen").val('');
                $("#tgl_klaim").val('');
                $("#no_ref").val('');
                $("#nm_tk").val('');
                $("#nik").val('');
                $("#tgl_penetapan").val('');
                $("#npp").val('');
                $("#nm_prs").val('');
                $("#petugas_penetapan").val('');
                $("#kd_unit").val('');
                $("#nm_unit").val('');
                $("#no_penetapan").val('');
                $("#tgl_lapor").val('');
            }
      /*
            if(jdata.j)
            {
                $("#tp_penerima").val(jdata.j.KODE_TIPE_PENERIMA);
                $("#prosen_pengambilan").val(jdata.j.PERSENTASE_PENGAMBILAN);
                $("#saldo_awal1").val(jdata.j.TGL_SALDO_AWALTAHUN);
                $("#saldo_awal2").val(jdata.j.NOM_SALDO_AWALTAHUN);
                $("#pengambilan_berjalan").val(jdata.j.NOM_DIAMBIL_THNBERJALAN);
                $("#saldo_pengembangan1").val(jdata.j.TGL_PENGEMBANGAN);
                $("#saldo_pengembangan2").val(jdata.j.NOM_SALDO_PENGEMBANGAN);
                $("#max_pengambilan").val(jdata.j.NOM_MANFAAT_MAXBISADIAMBIL);
                $("#total_saldo").val(jdata.j.NOM_SALDO_TOTAL);
                $("#jmlh_diambil").val(jdata.j.NOM_MANFAAT_DIAMBIL);
                $("#iuran_jht").val(jdata.j.NOM_IURAN_TAHUNBERJALAN);
                $("#pph_21_1").val(jdata.j.NOM_PPN);
                $("#pph_21_2").val(jdata.j.NOM_PPH);
                $("#iuran_pengembangan1").val('');
                $("#iuran_pengembangan2").val(jdata.j.NOM_IURAN_PENGEMBANGAN);
                $("#pembulatan").val(jdata.j.NOM_IURAN_PENGEMBANGAN);
                $("#total_iuran").val(jdata.j.NOM_IURAN_TOTAL);
                $("#jmlh_dibayar").val(jdata.j.NOM_BIAYA_DISETUJUI);
                <?php if($_REQUEST['task']!='View') { ?>
                $("#pjk_sebenarnya").val('');
                $("#manfaat_sebenarnya").val(jdata.j.NOM_MANFAAT_DIAMBIL);
                <?php }?>
                $("#j_total").val(jdata.j.NOM_TOTAL1);
            }else{
                $("#tp_penerima").val('');
                $("#prosen_pengambilan").val('');
                $("#saldo_awal1").val('');
                $("#saldo_awal2").val('');
                $("#pengambilan_berjalan").val('');
                $("#saldo_pengembangan1").val('');
                $("#saldo_pengembangan2").val('');
                $("#max_pengambilan").val('');
                $("#total_saldo").val('');
                $("#jmlh_diambil").val('');
                $("#iuran_jht").val('');
                $("#pph_21_1").val('');
                $("#pph_21_2").val('');
                $("#iuran_pengembangan1").val('');
                $("#iuran_pengembangan2").val('');
                $("#pembulatan").val('');
                $("#total_iuran").val('');
                $("#jmlh_dibayar").val('');
                <?php if($_REQUEST['task']!='View') { ?>
                $("#pjk_sebenarnya").val('');
                $("#manfaat_sebenarnya").val('');
                <?php }?>
                $("#j_total").val('0');
            }
      */
        }
    });
}

function isValid(){
    if($("#kd_klaim").val()=='')
        return { val : false, msg : "Silahkan input kode klaim terlebih dahulu!"} 
    else if($("#pjk_sebenarnya").val()=='')
        return { val : false, msg : "Inputkan jumlah pajak koreksi!"} 
    return { val : true, msg : "Valid"} 
}

$(document).ready(function(){ 
    setTimeout(function(){
        <?php if($_REQUEST['task']=='View' || $_REQUEST['task']=='Submit')
        {
            echo "getDataJhtKurangBayarSetupBunga('{$ls_klaim}')";
        }?>
        
    }, 20);
	
	$('#btn_submit_koreksi_jht_kurang_bayar').click(function() {
		$('#ACTION_TYPE').val('SUBMIT_KOREKSI');
		var r = confirm('Apakah Anda Yakin Mensubmit Agenda Permintaan Koreksi Data dengan No. Agenda '+$('#kd_agenda').val()+' ?');
		if (r == true) {
		  path_ori = '<?=$_GET['path']?>';
		  path     = path_ori.replace('.php','');
		  //console.log(path);
		  $.ajax({
			type: 'POST',
			url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/'+path+'_action.php?'+Math.random(),
			data: $('#formreg').serialize(),
			success: function(data)
			{
			  preload(false);
			  //console.log(data);
			  jdata = JSON.parse(data);                 
			  if(jdata.ret==0) 
			  {                                        
				window.parent.Ext.notify.msg('Permintaan Submit Koreksi Data dengan No. Agenda '+$('#kd_agenda').val()+' berhasil diproses, session dilanjutkan...', jdata.msg);
				window.location='pn6001.php?task=View&path='+path_ori+'&kd_agenda='+$('#kd_agenda').val()+'&kd_perihal_detil='+$('#tb_kode_perihal_detil').val()+'&mid=<?=$mid;?>';
			  }else 
			  {
				alert(jdata.msg);
			  }
			}
		  });
		}
	});
	
	$('#btn_approve_koreksi_jht_kurang_bayar').click(function() {
		$('#ACTION_TYPE').val('APPROVE_KOREKSI');
		var r = confirm('Apakah Anda Yakin Melakukan Approve Agenda Permintaan Koreksi Data dengan No. Agenda '+$('#kd_agenda').val()+' ?');
		if (r == true) {
		  path_ori = '<?=$_GET['path']?>';
		  path     = path_ori.replace('.php','');
		  //console.log(path);
		  $.ajax({
			type: 'POST',
			url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/'+path+'_action.php?'+Math.random(),
			data: $('#formreg').serialize(),
			success: function(data)
			{
			  preload(false);
			  //console.log(data);
			  jdata = JSON.parse(data);                 
			  if(jdata.ret==0) 
			  {                                        
				window.parent.Ext.notify.msg('Permintaan Approve Koreksi Data dengan No. Agenda '+$('#kd_agenda').val()+' berhasil diproses, session dilanjutkan...', jdata.msg);
				window.location='pn6001.php?task=View&path='+path_ori+'&kd_agenda='+$('#kd_agenda').val()+'&kd_perihal_detil='+$('#tb_kode_perihal_detil').val()+'&mid=<?=$mid;?>';
			  }else 
			  {
				alert(jdata.msg);
			  }
			}
		  });
		}
	});
	
	$("#btn_batal_koreksi_jht_kurang_bayar").click(function() {
		$('#ACTION_TYPE').val('BATAL_KOREKSI');
		var r = confirm('Apakah Anda Yakin Membatalkan Agenda Permintaan Koreksi Data dengan No. Agenda '+$('#kd_agenda').val()+' ?');
		if (r == true) {
		  path_ori = '<?=$_GET['path']?>';
		  path     = path_ori.replace('.php','');
		  //console.log(path);
		  $.ajax({
			type: 'POST',
			url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/'+path+'_action.php?'+Math.random(),
			data: $('#formreg').serialize(),
			success: function(data)
			{
			  preload(false);
			  //console.log(data);
			  jdata = JSON.parse(data);                 
			  if(jdata.ret==0) 
			  {                                        
				window.parent.Ext.notify.msg('Permintaan Batal Koreksi Data dengan No. Agenda '+$('#kd_agenda').val()+' berhasil diproses, session dilanjutkan...', jdata.msg);
				window.location='pn6001.php?task=View&path='+path_ori+'&kd_agenda='+$('#kd_agenda').val()+'&kd_perihal_detil='+$('#tb_kode_perihal_detil').val()+'&mid=<?=$mid;?>';
			  }else 
			  {
				alert(jdata.msg);
			  }
			}
		  });
		}
	});
	
	
	$("#btn_cetak_penetapan_koreksi_jht_kurang_bayar").click(function() {
		NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_jht_terlambat_setupbunga_cetak.php?task=Cetak&kd_prg=1&kode_agenda='+$('#kd_agenda').val()+'&mid='+Math.random(),'Cetak Penetapan Agenda Koreksi',810,550,1);
		
	});
});
</script>