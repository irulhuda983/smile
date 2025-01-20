<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Input Agenda Klaim JKK Tahap II
Hist: - 20/07/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
$ls_jkk2_kode_kondisi_terakhir = $ls_kode_kondisi_terakhir;
$ld_jkk2_tgl_kondisi_terakhir  = $ld_tgl_kondisi_terakhir;

if ($ld_jkk2_tgl_kondisi_terakhir=="")
{
  $sql = "select to_char(sysdate,'dd/mm/yyyy') as tgl from dual ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ld_jkk2_tgl_kondisi_terakhir = $row["TGL"];	 	 
} 
?>
<div id="formKiri" style="width:800px;">
  <fieldset><legend><b><i><font color="#009999">Perkembangan Laporan Kondisi TK</font></i></b></legend>
		</br>
		
    <div class="form-row_kiri">
    <label style = "text-align:right;">Kondisi Terakhir &nbsp;</label>		 	    				
      <select size="1" id="jkk2_kode_kondisi_terakhir" name="jkk2_kode_kondisi_terakhir" value="<?=$ls_jkk2_kode_kondisi_terakhir;?>" tabindex="31" class="select_format" <?=($ls_status_submit_agenda2 =="Y")? " style=\"width:300px;background-color:#F5F5F5\"" : " style=\"width:300px;background-color:#ffff99\"";?>>
      <option value="">-- Pilih --</option>
      <? 
				if ($ls_status_submit_agenda2 =="Y")  	
  			{
				  $sql = "select kode_kondisi_terakhir, nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir where kode_kondisi_terakhir = '$ls_jkk2_kode_kondisi_terakhir'"; 			
				}else
				{
				 	$sql = "select kode_kondisi_terakhir, nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir where kode_tipe_klaim = '$ls_kode_tipe_klaim' and nvl(status_nonaktif,'T')='T' order by no_urut";	 
				}			        
        $DB->parse($sql);
        $DB->execute();
        while($row = $DB->nextrow())
        {
          echo "<option ";
          if ($row["KODE_KONDISI_TERAKHIR"]==$ls_jkk2_kode_kondisi_terakhir && strlen($ls_jkk2_kode_kondisi_terakhir)==strlen($row["KODE_KONDISI_TERAKHIR"])){ echo " selected"; }
          echo " value=\"".$row["KODE_KONDISI_TERAKHIR"]."\">".$row["NAMA_KONDISI_TERAKHIR"]."</option>";
        }
      ?>
      </select>	
    </div>																																									
  	<div class="clear"></div>
																	
    <div class="form-row_kiri">
    <label style = "text-align:right;">Tgl Kondisi TK &nbsp;</label>
      <input type="text" id="jkk2_tgl_kondisi_terakhir" name="jkk2_tgl_kondisi_terakhir" value="<?=$ld_jkk2_tgl_kondisi_terakhir;?>" tabindex="32" style="width:250px;" maxlength="10" onblur="convert_date(jkk2_tgl_kondisi_terakhir);"  <?=($ls_status_submit_agenda2 =="Y")? " style=\"background-color:#F5F5F5\"" : " style=\"background-color:#ffff99\"";?>>
  		<?
				if ($ls_status_submit_agenda2 !="Y")  	
  			{
				 	?> 
     			<input id="btn_jkk2_tgl_kondisi_terakhir" type="image" align="top" onclick="return showCalendar('jkk2_tgl_kondisi_terakhir', 'dd-mm-y');" src="../../images/calendar.gif" />									
					<?  			
				}
			?>
		</div>    		
		<div class="clear"></div>

		</br> 	    																						  
  </fieldset>
							
</div>
