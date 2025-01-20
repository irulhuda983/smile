<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Penetapan Ahli Waris
Hist: - 20/07/2017 : Pembuatan Form (Tim SIJSTK)			 						 
-----------------------------------------------------------------------------*/
if($btn_task=="get_ahliwaris_jp")
{
    //ambil data ahli waris jp -------------------------------------------------
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_GET_AHLIWARISJP('$ls_kode_klaim','$username',:p_sukses,:p_mess);END;";											 	
    $proc = $DB->parse($qry);				
    oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
    oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ls_sukses = $p_sukses;
    $ls_mess = $p_mess;	
    
    $msg = $ls_mess;
    $task = "edit";   		
    
    echo "<script language=\"JavaScript\" type=\"text/javascript\">";
    echo "window.location.replace('?&task=Edit&mid=$mid&kode_klaim=$ls_kode_klaim&dataid=$ls_kode_klaim&activetab=2&msg=$msg');";
    echo "alert('$msg')";
    echo "</script>";	 															
}
?>
<script language="JavaScript">
  function fl_js_val_jp_ambildata_ahliwaris()
  {
    var form = window.document.formreg;
    form.btn_task.value="get_ahliwaris_jp";
    form.submit();    		 
  }				
</script>		
	
<div id="formKiri" style="width:955px;">
	<fieldset><legend>Penetapan Ahli Waris</legend>
    <table id="mydata1" width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>
        <tr>
        	<th colspan="8"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
        </tr>
        <tr>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;" colspan="4">&nbsp;</th>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;" colspan="2">Kondisi Akhir</th>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">&nbsp;</th>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">&nbsp;</th>
        </tr>
        <tr>
        	<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:120px;">Hubungan</th>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:200px;">Nama Lengkap</th>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:80px;">Tgl Lahir</th>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:80px;">Jns Kelamin</th>
					<th colspan="2"><hr></hr></th>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:50px;">Eligible</th>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:100px;">Action</th>
        </tr>																										
        <tr>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;" colspan="4">&nbsp;</th>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">Status</th>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:100px;">Sejak</th>
					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">&nbsp;</th>
          <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:100px;">&nbsp;</th>
        </tr>
        <tr>
        <th colspan="8"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        </tr>													
        <?		
        $sql = "select 
                    a.kode_klaim, a.kode_penerima_berkala,a.kode_hubungan, 
                    (select nama_hubungan from sijstk.kn_kode_hubungan_tk where kode_hubungan = a.kode_hubungan) nama_hubungan,
                    a.no_urut_keluarga, a.nama_lengkap, a.no_kartu_keluarga, a.nomor_identitas, 
                    a.tempat_lahir, to_char(a.tgl_lahir,'dd/mm/yyyy') tgl_lahir, a.jenis_kelamin, 
                    decode(a.jenis_kelamin,'P','PEREMPUAN','L','LAKI-LAKI',a.jenis_kelamin) nama_jenis_kelamin,
                    a.golongan_darah, a.status_kawin, a.alamat, 
                    a.rt, a.rw, a.kode_kelurahan, 
                    a.kode_kecamatan, a.kode_kabupaten, a.kode_pos, 
                    a.telepon_area, a.telepon, a.telepon_ext, 
                    a.fax_area, a.fax, a.handphone, 
                    a.email, a.npwp, a.nama_penerima, 
                    a.bank_penerima, a.no_rekening_penerima, a.nama_rekening_penerima, 
                    a.kode_bank_pembayar, a.kpj_tertanggung, a.pekerjaan, 
                    a.kode_kondisi_terakhir, 
                    (select nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir where kode_kondisi_terakhir = a.kode_kondisi_terakhir) nama_kondisi_terakhir,
                    to_char(a.tgl_kondisi_terakhir,'dd/mm/yyyy') tgl_kondisi_terakhir, 
                    nvl(a.status_layak,'T') status_layak, a.keterangan
                from sijstk.pn_klaim_penerima_berkala a
                where a.kode_klaim = '$ls_kode_klaim'
								and a.kode_hubungan <> 'T'
                order by a.no_urut_keluarga ";
        //echo $sql;
        $DB->parse($sql);
        $DB->execute();
        $i=0;
        $ln_dtl = 0;
        while ($row = $DB->nextrow())
        {
        ?>
        <?echo "<tr bgcolor=#".($i%2 ? "ffffff" : "f3f3f3").">";?>								
          <td style="text-align:left;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["NAMA_HUBUNGAN"];?></td>	
          <td style="text-align:left;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["NAMA_LENGKAP"];?></td>
          <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["TGL_LAHIR"];?></td>
          <td style="text-align:left;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["NAMA_JENIS_KELAMIN"];?></td>
          <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["NAMA_KONDISI_TERAKHIR"];?></td>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["TGL_KONDISI_TERAKHIR"];?></td>
					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
							<?=($row["STATUS_LAYAK"]=="Y" ? "<img src=../../images/file_apply.gif>" : "")?>
					</td>
          <td style="text-align:center;">
            <a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_agenda_jpn_ahliwarisentry.php?task=view&kode_tk=<?=$ls_kode_tk;?>&kode_klaim=<?=$ls_kode_klaim;?>&no_urut_keluarga=<?=$row["NO_URUT_KELUARGA"];?>&root_sender=pn5040.php&sender=pn5040.php&sender_activetab=2&sender_mid=<?=$mid;?>','Ubah Data Keluarga',880,620,'no');"><img src="../../images/app_form_edit.png" border="0" alt="Ubah Divisi" align="absmiddle" />&nbsp;View</a>
          </td>
        </tr>
        <?
        //hitung total									    							
        $i++;//iterasi i							
        }	//end while
        $ln_dtl=$i;				
        ?>									             																
      </tbody>
      <tr>
      	<td colspan="8"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td>	
      </tr>											
      <tr>
        <td colspan="2"style="text-align:left;" >
				</td>
				<td style="text-align:left" colspan="5"><b><i>&nbsp;<i></b>
          <input type="hidden" id="kounter_dtl" name="kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="count_dtl" name="count_dtl" value="<?=$ln_countdtl;?>">
          <input type="hidden" name="showmessage" style="border-width: 0;text-align:right" readonly size="5">					
        </td>
        <td style="text-align:center">	
        </td>											
      </tr>
    </table>					
	</fieldset>	 
</div>
