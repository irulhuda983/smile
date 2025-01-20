<?
//query data penetapan sebelumnya ----------------------------------------------
$sql = "select 
          kode_klaim, kode_manfaat, no_urut, kategori_manfaat, kode_tipe_penerima, kd_prg, nom_biaya_disetujui, 
          pengambilan_ke, nom_manfaat_sudahdiambil, nom_diambil_thnberjalan, nom_pengembangan_estimasi, 
          tgl_pengembangan_estimasi, to_char(tgl_pengembangan,'dd/mm/yyyy') tgl_pengembangan, 
					to_char(tgl_saldo_awaltahun,'dd/mm/yyyy') tgl_saldo_awaltahun, 
          nom_saldo_awaltahun, nom_saldo_pengembangan, nom_saldo_total, 
          nom_iuran_thnberjalan, nom_iuran_pengembangan, nom_iuran_total,nom_saldo_iuran_total, 
          persentase_pengambilan, nom_manfaat_maxbisadiambil, nom_manfaat_diambil, 
          nom_manfaat_utama, nom_manfaat_tambahan, nom_manfaat_gross, 
          nom_ppn, nom_pph, nom_pembulatan, nom_manfaat_netto, kode_pajak_ppn, kode_pajak_pph, 
          keterangan
        from sijstk.pn_klaim_manfaat_detil a
        where kode_klaim = '$ls_kode_klaim_induk'
        and kode_manfaat = '18'
        and no_urut = 1 ";
//echo $sql;				
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ls_induk_kode_manfaat 	 				 		= $row["KODE_MANFAAT"];
$ls_induk_no_urut 	 				 				= $row["NO_URUT"];
$ls_induk_kategori_manfaat					= $row["KATEGORI_MANFAAT"];
$ls_induk_kode_tipe_penerima				= $row["KODE_TIPE_PENERIMA"];
$ls_induk_kd_prg										= $row["KD_PRG"];
$ln_induk_nom_biaya_disetujui				= $row["NOM_BIAYA_DISETUJUI"];
$ln_induk_pengambilan_ke						= $row["PENGAMBILAN_KE"];
$ln_induk_nom_manfaat_sudahdiambil	= $row["NOM_MANFAAT_SUDAHDIAMBIL"];
$ln_induk_nom_diambil_thnberjalan		= $row["NOM_DIAMBIL_THNBERJALAN"];
$ln_induk_nom_pengembangan_estimasi	= $row["NOM_PENGEMBANGAN_ESTIMASI"];	
$ld_induk_tgl_pengembangan_estimasi	= $row["TGL_PENGEMBANGAN_ESTIMASI"];
$ld_induk_tgl_pengembangan					= $row["TGL_PENGEMBANGAN"];
$ld_induk_tgl_saldo_awaltahun			= $row["TGL_SALDO_AWALTAHUN"];
$ln_induk_nom_saldo_awaltahun				= $row["NOM_SALDO_AWALTAHUN"];
$ln_induk_nom_saldo_pengembangan		= $row["NOM_SALDO_PENGEMBANGAN"];
$ln_induk_nom_saldo_total						= $row["NOM_SALDO_TOTAL"];
$ln_induk_nom_iuran_thnberjalan			= $row["NOM_IURAN_THNBERJALAN"];
$ln_induk_nom_iuran_pengembangan		= $row["NOM_IURAN_PENGEMBANGAN"];
$ln_induk_nom_iuran_total						= $row["NOM_IURAN_TOTAL"];
$ln_induk_nom_saldo_iuran_total			= $row["NOM_SALDO_IURAN_TOTAL"];
$ln_induk_persentase_pengambilan		= $row["PERSENTASE_PENGAMBILAN"];
$ln_induk_nom_manfaat_maxbisadiambil	= $row["NOM_MANFAAT_MAXBISADIAMBIL"];
$ln_induk_nom_manfaat_diambil				= $row["NOM_MANFAAT_DIAMBIL"];	
$ln_induk_nom_manfaat_utama					= $row["NOM_MANFAAT_UTAMA"];
$ln_induk_nom_manfaat_tambahan			= $row["NOM_MANFAAT_TAMBAHAN"];
$ln_induk_nom_manfaat_gross					= $row["NOM_MANFAAT_GROSS"];
$ln_induk_nom_ppn										= $row["NOM_PPN"];
$ln_induk_nom_pph										= $row["NOM_PPH"];
$ln_induk_nom_pembulatan						= $row["NOM_PEMBULATAN"];
$ln_induk_nom_manfaat_netto					= $row["NOM_MANFAAT_NETTO"];
$ls_induk_kode_pajak_ppn						= $row["KODE_PAJAK_PPN"];
$ls_induk_kode_pajak_pph						= $row["KODE_PAJAK_PPH"];
$ls_induk_keterangan								= $row["KETERANGAN"];	

//query data penetapan ulang ----------------------------------------------
$sql = "select 
          kode_klaim, kode_manfaat, no_urut, kategori_manfaat, kode_tipe_penerima, kd_prg, nom_biaya_disetujui, 
          pengambilan_ke, nom_manfaat_sudahdiambil, nom_diambil_thnberjalan, nom_pengembangan_estimasi, 
          tgl_pengembangan_estimasi, to_char(tgl_pengembangan,'dd/mm/yyyy') tgl_pengembangan, 
					to_char(tgl_saldo_awaltahun,'dd/mm/yyyy') tgl_saldo_awaltahun, 
          nom_saldo_awaltahun, nom_saldo_pengembangan, nom_saldo_total, 
          nom_iuran_thnberjalan, nom_iuran_pengembangan, nom_iuran_total,nom_saldo_iuran_total, 
          persentase_pengambilan, nom_manfaat_maxbisadiambil, nom_manfaat_diambil, 
          nom_manfaat_utama, nom_manfaat_tambahan, nom_manfaat_gross, 
          nom_ppn, nom_pph, nom_pembulatan, nom_manfaat_netto, kode_pajak_ppn, kode_pajak_pph, 
          keterangan
        from sijstk.pn_klaim_manfaat_detil a
        where kode_klaim = '$ls_kode_klaim'
        and kode_manfaat = '18'
        and no_urut = 1 ";
//echo $sql;				
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ls_kode_manfaat 	 				 		= $row["KODE_MANFAAT"];
$ls_no_urut 	 				 				= $row["NO_URUT"];
$ls_kategori_manfaat					= $row["KATEGORI_MANFAAT"];
$ls_kode_tipe_penerima				= $row["KODE_TIPE_PENERIMA"];
$ls_kd_prg										= $row["KD_PRG"];
$ln_nom_biaya_disetujui				= $row["NOM_BIAYA_DISETUJUI"];
$ln_pengambilan_ke						= $row["PENGAMBILAN_KE"];
$ln_nom_manfaat_sudahdiambil	= $row["NOM_MANFAAT_SUDAHDIAMBIL"];
$ln_nom_diambil_thnberjalan		= $row["NOM_DIAMBIL_THNBERJALAN"];
$ln_nom_pengembangan_estimasi	= $row["NOM_PENGEMBANGAN_ESTIMASI"];	
$ld_tgl_pengembangan_estimasi	= $row["TGL_PENGEMBANGAN_ESTIMASI"];
$ld_tgl_pengembangan					= $row["TGL_PENGEMBANGAN"];
$ld_tgl_saldo_awaltahun			= $row["TGL_SALDO_AWALTAHUN"];
$ln_nom_saldo_awaltahun				= $row["NOM_SALDO_AWALTAHUN"];
$ln_nom_saldo_pengembangan		= $row["NOM_SALDO_PENGEMBANGAN"];
$ln_nom_saldo_total						= $row["NOM_SALDO_TOTAL"];
$ln_nom_iuran_thnberjalan			= $row["NOM_IURAN_THNBERJALAN"];
$ln_nom_iuran_pengembangan		= $row["NOM_IURAN_PENGEMBANGAN"];
$ln_nom_iuran_total						= $row["NOM_IURAN_TOTAL"];
$ln_nom_saldo_iuran_total			= $row["NOM_SALDO_IURAN_TOTAL"];
$ln_persentase_pengambilan		= $row["PERSENTASE_PENGAMBILAN"];
$ln_nom_manfaat_maxbisadiambil	= $row["NOM_MANFAAT_MAXBISADIAMBIL"];
$ln_nom_manfaat_diambil				= $row["NOM_MANFAAT_DIAMBIL"];	
$ln_nom_manfaat_utama					= $row["NOM_MANFAAT_UTAMA"];
$ln_nom_manfaat_tambahan			= $row["NOM_MANFAAT_TAMBAHAN"];
$ln_nom_manfaat_gross					= $row["NOM_MANFAAT_GROSS"];
$ln_nom_ppn										= $row["NOM_PPN"];
$ln_nom_pph										= $row["NOM_PPH"];
$ln_nom_pembulatan						= $row["NOM_PEMBULATAN"];
$ln_nom_manfaat_netto					= $row["NOM_MANFAAT_NETTO"];
$ls_kode_pajak_ppn						= $row["KODE_PAJAK_PPN"];
$ls_kode_pajak_pph						= $row["KODE_PAJAK_PPH"];
$ls_keterangan								= $row["KETERANGAN"];	
?>

<tr>
	<td colspan="10">	
  <table width="1060px" border="0">														
    <tr>		 
      <!-- Informasi Penetapan Klaim Sebelumnya ------------------------------->	
      <td width="50%" valign="top" align="center">
        <fieldset><legend><b><i><font color="#009999">Informasi Penetapan Klaim Sebelumnya</font></i></b></legend>

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Pengambilan Sblmnya</label>
            <input type="text" id="induk_nom_manfaat_sudahdiambil" name="induk_nom_manfaat_sudahdiambil" value="<?=number_format((float)$ln_induk_nom_manfaat_sudahdiambil,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          </div>
					<div class="clear"></div>
							
          <div class="form-row_kiri">
          <label style = "text-align:right;"><i><font color="#009999">Saldo JHT &nbsp;&nbsp;&nbsp;:</font></i></label>	    				
          </div>									
          <div class="clear"></div>	       

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Saldo Awal Tahun &nbsp;</label>
            <input type="text" id="induk_tgl_saldo_awaltahun" name="induk_tgl_saldo_awaltahun" value="<?=$ld_induk_tgl_saldo_awaltahun;?>" size="11" readonly class="disabled">
            <input type="text" id="induk_nom_saldo_awaltahun" name="induk_nom_saldo_awaltahun" value="<?=number_format((float)$ln_induk_nom_saldo_awaltahun,2,".",",");?>" size="25" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          </div>																																															
        	<div class="clear"></div>

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Saldo Pengembangan &nbsp;</label>
            <input type="text" id="induk_tgl_pengembangan" name="induk_tgl_pengembangan" value="<?=$ld_induk_tgl_pengembangan;?>" size="11" readonly class="disabled">
            <input type="text" id="induk_nom_saldo_pengembangan" name="induk_nom_saldo_pengembangan" value="<?=number_format((float)$ln_induk_nom_saldo_pengembangan,2,".",",");?>" size="25" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          </div>																																														
        	<div class="clear"></div>

          <div class="form-row_kiri">
          <label  style = "text-align:right;"><i>Total Saldo</i> &nbsp;</label>
            <input type="text" id="induk_nom_saldo_total" name="induk_nom_saldo_total" value="<?=number_format((float)$ln_induk_nom_saldo_total,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          </div>																																														
        	<div class="clear"></div>

    			<!--</br>-->
    									
          <div class="form-row_kiri">
          <label style = "text-align:right;"><i><font color="#009999">Iuran JHT &nbsp;&nbsp;&nbsp;:</font></i></label>	    				
          </div>												
          <div class="clear"></div>	
    						
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Iuran Tambahan &nbsp;</label>
            <input type="text" id="induk_nom_iuran_thnberjalan" name="induk_nom_iuran_thnberjalan" value="<?=number_format((float)$ln_induk_nom_iuran_thnberjalan,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          </div>																																															
        	<div class="clear"></div>
    
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Iuran Pengembangan &nbsp;</label>
            <input type="text" id="induk_tgl_pengembangan2" name="induk_tgl_pengembangan2" value="<?=$ld_induk_tgl_pengembangan2;?>" size="11" readonly class="disabled">
            <input type="text" id="induk_nom_iuran_pengembangan" name="induk_nom_iuran_pengembangan" value="<?=number_format((float)$ln_induk_nom_iuran_pengembangan,2,".",",");?>" size="25" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          </div>																																													
        	<div class="clear"></div>
    
          <div class="form-row_kiri">
          <label  style = "text-align:right;"><i>Total Iuran</i> &nbsp;</label>
            <input type="text" id="induk_nom_iuran_total" name="induk_nom_iuran_total" value="<?=number_format((float)$ln_induk_nom_iuran_total,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          </div>																																															
        	<div class="clear"></div>
    			
    			</br>
    			
          <div class="form-row_kiri">
          <label  style = "text-align:right;"><i>Total Manfaat JHT</i> &nbsp;</label>
            <input type="text" id="induk_nom_saldo_iuran_total" name="induk_nom_saldo_iuran_total" value="<?=number_format((float)$ln_induk_nom_saldo_iuran_total,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          </div>																																																
        	<div class="clear"></div>

          <div class="form-row_kiri">
          <label  style = "text-align:right;">PPh Pasal 21</label>
    				<input type="text" id="induk_kode_pajak_pph" name="induk_kode_pajak_pph" value="<?=$ls_induk_kode_pajak_pph;?>" size="10" maxlength="10" readonly class="disabled" style="text-align:center;">			
            <input type="text" id="induk_nom_pph" name="induk_nom_pph" value="<?=number_format((float)$ln_induk_nom_pph,2,".",",");?>" size="26" maxlength="20" readonly class="disabled" style="text-align:right;">				                					
          </div>	
					<div class="clear"></div>
					
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Pembulatan</label>
            <input type="text" id="induk_nom_pembulatan" name="induk_nom_pembulatan" value="<?=number_format((float)$ln_induk_nom_pembulatan,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          </div>																																														
        	<div class="clear"></div>

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Jumlah Dibayar</label>
            <input type="text" id="induk_nom_manfaat_netto" name="induk_nom_manfaat_netto" value="<?=number_format((float)$ln_induk_nom_manfaat_netto,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          </div>																																																
        	<div class="clear"></div>
														        																
        </fieldset>	 					
      </td>
      
      <!-- Informasi Penetapan Ulang ------------------------------------------>	
      <td width="50%" valign="top">
        <fieldset ><legend><b><i><font color="#009999">Informasi Penetapan Ulang</font></i></b></legend>
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Pengambilan Sblmnya</label>
            <input type="text" id="nom_manfaat_sudahdiambil" name="nom_manfaat_sudahdiambil" value="<?=number_format((float)$ln_nom_manfaat_sudahdiambil,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          	<?
						if ($ls_status_submit_penetapan!="Y")
						{
						 	?>
							<input type="button" class="btn green" id="btnhitungMnfJht" name="btnhitungMnfJht" value="  HITUNG MANFAAT  " onClick="if(confirm('Apakah anda yakin akan melakukan perhitungan Manfaat..?')) doHitungManfaatJht();"> 
							<?
						}
						?>
					</div>
					<div class="clear"></div>
							
          <div class="form-row_kiri">
          <label style = "text-align:right;"><i><font color="#009999">Saldo JHT &nbsp;&nbsp;&nbsp;:</font></i></label>	    				
          </div>									
          <div class="clear"></div>	       

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Saldo Awal Tahun &nbsp;</label>
            <input type="text" id="tgl_saldo_awaltahun" name="tgl_saldo_awaltahun" value="<?=$ld_tgl_saldo_awaltahun;?>" size="11" readonly class="disabled">
            <input type="text" id="nom_saldo_awaltahun" name="nom_saldo_awaltahun" value="<?=number_format((float)$ln_nom_saldo_awaltahun,2,".",",");?>" size="25" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          </div>																																															
        	<div class="clear"></div>

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Saldo Pengembangan &nbsp;</label>
            <input type="text" id="tgl_pengembangan" name="tgl_pengembangan" value="<?=$ld_tgl_pengembangan;?>" size="11" readonly class="disabled">
            <input type="text" id="nom_saldo_pengembangan" name="nom_saldo_pengembangan" value="<?=number_format((float)$ln_nom_saldo_pengembangan,2,".",",");?>" size="25" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          </div>																																														
        	<div class="clear"></div>

          <div class="form-row_kiri">
          <label  style = "text-align:right;"><i>Total Saldo</i> &nbsp;</label>
            <input type="text" id="nom_saldo_total" name="nom_saldo_total" value="<?=number_format((float)$ln_nom_saldo_total,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          </div>																																														
        	<div class="clear"></div>

    			<!--</br>-->
    									
          <div class="form-row_kiri">
          <label style = "text-align:right;"><i><font color="#009999">Iuran JHT &nbsp;&nbsp;&nbsp;:</font></i></label>	    				
          </div>												
          <div class="clear"></div>	
    						
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Iuran Tambahan &nbsp;</label>
            <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=number_format((float)$ln_nom_iuran_thnberjalan,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          </div>																																															
        	<div class="clear"></div>
    
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Iuran Pengembangan &nbsp;</label>
            <input type="text" id="tgl_pengembangan2" name="tgl_pengembangan2" value="<?=$ld_tgl_pengembangan2;?>" size="11" readonly class="disabled">
            <input type="text" id="nom_iuran_pengembangan" name="nom_iuran_pengembangan" value="<?=number_format((float)$ln_nom_iuran_pengembangan,2,".",",");?>" size="25" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          </div>																																													
        	<div class="clear"></div>
    
          <div class="form-row_kiri">
          <label  style = "text-align:right;"><i>Total Iuran</i> &nbsp;</label>
            <input type="text" id="nom_iuran_total" name="nom_iuran_total" value="<?=number_format((float)$ln_nom_iuran_total,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          </div>																																															
        	<div class="clear"></div>
    			
    			</br>
    			
          <div class="form-row_kiri">
          <label  style = "text-align:right;"><i>Total Manfaat JHT</i> &nbsp;</label>
            <input type="text" id="nom_saldo_iuran_total" name="nom_saldo_iuran_total" value="<?=number_format((float)$ln_nom_saldo_iuran_total,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          </div>																																																
        	<div class="clear"></div>

          <div class="form-row_kiri">
          <label  style = "text-align:right;">PPh Pasal 21</label>
    				<input type="text" id="kode_pajak_pph" name="kode_pajak_pph" value="<?=$ls_kode_pajak_pph;?>" size="10" maxlength="10" readonly class="disabled" style="text-align:center;">			
            <input type="text" id="nom_pph" name="nom_pph" value="<?=number_format((float)$ln_nom_pph,2,".",",");?>" size="26" maxlength="20" readonly class="disabled" style="text-align:right;">				                					
          </div>	
					<div class="clear"></div>
					
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Pembulatan</label>
            <input type="text" id="nom_pembulatan" name="nom_pembulatan" value="<?=number_format((float)$ln_nom_pembulatan,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          </div>																																														
        	<div class="clear"></div>

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Sisa JHT Dibayar</label>
            <input type="text" id="nom_manfaat_netto" name="nom_manfaat_netto" value="<?=number_format((float)$ln_nom_manfaat_netto,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
          </div>																																																
        	<div class="clear"></div>        				  
        </fieldset>										
    	</td>
  	</tr>
		
    <tr>
    	<td colspan="10">
      <fieldset style="width:1050px;"><legend><b><i><font color="#009999">Penerima Manfaat Biaya dan Santunan</font></i></b></legend>
        <table id="tblrincian1" width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
          <tbody>
    				<!--
						<tr>
    					<th colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
    				</tr>	
						-->								
            <tr>
              <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tipe</th>
              <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama</th>
              <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">NPWP</th>
              <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bank</th>
    					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No.Rek</th>
    					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">A/N</th>
    					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nominal</th>
              <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:170px;">Action</th>
            </tr>
    				<tr>
    					<th colspan="10"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
    				</tr>
            <?							
            if ($ls_kode_klaim!="")
            {			
              $sql = "select ".
                     "     a.kode_klaim, a.kode_tipe_penerima, b.nama_tipe_penerima, a.kode_hubungan, ". 
                     "     a.ket_hubungan_lainnya, a.nomor_identitas, a.nama_pemohon, ". 
                     "     a.tempat_lahir, a.tgl_lahir, a.jenis_kelamin, ". 
                     "     a.alamat, a.rt, a.rw, ". 
                     "     a.kode_kelurahan, a.kode_kecamatan, a.kode_kabupaten, ". 
                     "     a.kode_pos, a.telepon_area, a.telepon, ". 
                     "     a.telepon_ext, a.handphone, a.email, ". 
                     "     a.npwp, a.nama_penerima, a.bank_penerima, ". 
                     "     a.no_rekening_penerima, a.nama_rekening_penerima, ".
    								 "		 a.nom_manfaat_utama, a.nom_manfaat_tambahan, a.nom_manfaat_gross, ".
    								 "		 a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, ". 
                     "     a.keterangan, a.status_lunas, a.tgl_lunas, ". 
                     "     a.petugas_lunas, decode(nvl(a.status_lunas,'T'),'Y',' (* sudah dibayar','') ket_byr ".
                     "from sijstk.pn_klaim_penerima_manfaat a, sijstk.pn_kode_tipe_penerima b ".
                     "where a.kode_tipe_penerima = b.kode_tipe_penerima(+) ".
                     "and a.kode_klaim = '$ls_kode_klaim' ".								 
                     "order by b.no_urut";
              //echo $sql;
    					$DB->parse($sql);
              $DB->execute();							              					
              $i=0;		
              $ln_dtl =0;	
    					$ln_tot_d_mnftipepenerima_nom_manfaat_netto =0;								
              while ($row = $DB->nextrow())
              {
              ?>
                <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>
    							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_TIPE_PENERIMA'];?></td>
    							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_PENERIMA'];?></td>
    							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NPWP'];?></td>
    							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['BANK_PENERIMA'];?></td>
    							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NO_REKENING_PENERIMA'];?></td>
    							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_REKENING_PENERIMA'];?></td>
    							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_MANFAAT_NETTO'],2,".",",");?><i><?=$row['KET_BYR'];?></i></td>																		       																			        																												       																			        											
                  <td align="center">
                 		<a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5002_penetapanmanfaat_penerima.php?&task=Edit&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_tipe_penerima=<?=$row["KODE_TIPE_PENERIMA"];?>&root_sender=pn5007.php&sender=../form/pn5007.php&sender_activetab=2&sender_mid=<?=$mid;?>','Detil Informasi Penerima Manfaat',950,520,'no')"><img src="../../images/user_go.png" border="0" alt="Entry Detil Penerima" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DETIL PENERIMA </font></a>																																																		
                  </td>
                </tr>
                <?								    							
                $i++;//iterasi i
    						$ln_tot_d_mnftipepenerima_nom_manfaat_netto  += $row["NOM_MANFAAT_NETTO"];
              }	//end while
              $ln_dtl=$i;
            }						
            ?>									             																
          </tbody>
    			<tr><td colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
          <tr>
            <td style="text-align:right" colspan="6"><i>Total Diterima :<i>
              <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
              <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
              <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">					
            </td>
            <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_mnftipepenerima_nom_manfaat_netto,2,".",",");?></td>
    				<td>				
    				</td>				
          </tr>																		
        </table>
      </fieldset>	
    	</td>	
    </tr>
		
	</table>	
	</td>
</tr>



								
