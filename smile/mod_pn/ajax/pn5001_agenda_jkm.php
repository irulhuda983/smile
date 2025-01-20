<ul id="nav">
	<li><a href="#tab1" id="t1" class="active">Informasi Klaim</a></li>		
  <li><a href="#tab2" id="t2">Agenda JKM</a></li>
  <?
  if ($ls_kode_segmen =="JAKON")
  {
    ?>								
    <li><a href="#tab5" id="t5">TK Jakon</a></li>
    <?			
  }
  ?>		
  <li><a href="#tab11" id="t11">Administrasi</a></li>
</ul>

<div style="display: none;" id="tab1" class="tab_konten">
  <div id="konten">
  <?
  //------------- informasi klaim ----------------------------------------------
  include "../ajax/pn5001_tabinfoklaim.php";
  ?>						
  </div>
</div>
							
<div style="display: none;" id="tab2" class="tab_konten">
  <div id="konten">
    <?
    $ld_jkm_tgl_kematian  = $ld_tgl_kematian;
    $ls_jkm_ket_tambahan	= $ls_ket_tambahan;
    ?>
    <div id="formKiri" style="width:800px;">
      <fieldset><legend>Input Data Klaim JKM</legend>
    		</br>
    																	
        <div class="form-row_kiri">
        <label style = "text-align:right;">Tgl Kematian &nbsp;</label>
          <input type="text" id="jkm_tgl_kematian" name="jkm_tgl_kematian" value="<?=$ld_jkm_tgl_kematian;?>" tabindex="32" size="31" maxlength="10" onblur="convert_date(jkm_tgl_kematian);" readonly class="disabled">      	
    		</div>    		
    		<div class="clear"></div>
    
        <div class="form-row_kiri">
        <label style = "text-align:right;">Keterangan &nbsp;</label>
    			<textarea cols="255" rows="1" id="jkm_ket_tambahan" name="jkm_ket_tambahan" tabindex="33" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled style=\"width:250px;background-color:#F5F5F5;\"" : " style=\"width:250px;background-color:#ffff99\"";?>><?=$ls_jkm_ket_tambahan;?></textarea>	   							
        </div>
    		<div class="clear"></div>
    				
    		</br> 	    																						  
      </fieldset>
    							
    </div>					
  </div>
</div>	

<?	
if ($ls_kode_segmen =="JAKON")
{
  ?>								
  <div style="display: none;" id="tab5" class="tab_konten">
    <div id="konten">
    <?
    //------------- tk jakons --------------------------------------
    include "../ajax/pn5001_agenda_jakon_tk.php";
    ?>						
    </div>
  </div>	
  <?	
}
?>	
	
<div style="display: none;" id="tab11" class="tab_konten">
  <div id="konten">
  <?
  //------------- kelengkapan administrasi -----------------------------------
  include "../ajax/pn5001_tabadministrasi.php";
  ?>						
  </div>
</div>
