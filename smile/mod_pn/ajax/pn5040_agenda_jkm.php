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
	<!--<li><a href="#tab31" id="t31">Input Data Rekening</a></li>-->		
  <li><a href="#tab11" id="t11">Administrasi</a></li>
</ul>

<div style="display: none;" id="tab1" class="tab_konten">
  <div id="konten">
  <?
  //------------- informasi antrian ------------------------------------
  include_once "../ajax/pn5040_tabinfoantrian.php";
  // -------- end informasi antrian ------------------------------------
  
  //------------- informasi klaim ----------------------------------------------
  include_once "../ajax/pn5040_tabinfoklaim.php";
  ?>						
  </div>
</div>
							
<div style="display: none;" id="tab2" class="tab_konten">
  <div id="konten">
    <?
    $ld_jkm_tgl_kematian  = $ld_tgl_kematian;
    $ls_jkm_ket_tambahan	= $ls_ket_tambahan;
		$ls_jkm_tipe_negara_kejadian = $ls_tipe_negara_kejadian;
    ?>
		
		<script language="javascript">
		//update 23/01/2019 tambahan terkait TKI -----------------------------------
    function fl_js_jkm_tipe_negara_kejadian() 
    { 
    	var v_kode_segmen = window.document.getElementById("kode_segmen").value;
    	
    	if (v_kode_segmen =="TKI")
      {    
    		window.document.getElementById("jkm_span_tipe_negara_kejadian").style.display = 'block';
      }else
      {
    	 	window.document.getElementById("jkm_span_tipe_negara_kejadian").style.display = 'none';	 
    	  window.document.getElementById("jkm_tipe_negara_kejadian").value = "I";
      }
    }	
    </script>

    <div id="formKiri" style="width:800px;">
      <fieldset><legend>Input Data Klaim JKM</legend>
    		</br>
				
				<span id="jkm_span_tipe_negara_kejadian" style="display:none;">
          <div class="form-row_kiri">
          <label style = "text-align:right;">Negara Kejadian *</label>		 	    				
            <select size="1" id="jkm_tipe_negara_kejadian" name="jkm_tipe_negara_kejadian" value="<?=$ls_jkm_tipe_negara_kejadian;?>" tabindex="31" class="select_format" <?=($ls_status_submit_agenda =="Y")? " style=\"width:275px;background-color:#F5F5F5\"" : " style=\"width:275px;background-color:#ffff99\"";?>>
            <option value="">-- Pilih --</option>
            <? 
              $param_bv = [];
        			if ($ls_status_submit_agenda =="Y")
        			{
      					$sql = "select kode, keterangan from sijstk.ms_lookup where tipe='TKITIPENGR' and kode=:p_kode order by seq";
                $param_bv[':p_kode'] = $ls_jkm_tipe_negara_kejadian;
              }else
        			{
                $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='TKITIPENGR' and nvl(aktif,'T')='Y' order by seq";										
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
                if ($row["KODE"]==$ls_jkm_tipe_negara_kejadian && strlen($ls_jkm_tipe_negara_kejadian)==strlen($row["KODE"])){ echo " selected"; }
                echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
              }
            ?>
            </select>		
          </div>																																												
        	<div class="clear"></div>
		    </span>
																					
        <div class="form-row_kiri">
        <label style = "text-align:right;">Tgl Kematian &nbsp;</label>
          <input type="text" id="jkm_tgl_kematian" name="jkm_tgl_kematian" value="<?=$ld_jkm_tgl_kematian;?>" tabindex="32" size="37" maxlength="10" onblur="convert_date(jkm_tgl_kematian);" readonly class="disabled">      	
    		</div>    		
    		<div class="clear"></div>

        <?php if($ls_kode_perlindungan=="ONSITE" && $ls_kode_sebab_klaim !="SKM11" && $ls_aktif_flag_meninggal_cuti =="Y"){ ?>
        <div class="form-row_kiri">
        <label style = "text-align:right;">Apakah sedang cuti ? * &nbsp;</label>
        <select size="1" id="flag_meninggal_cuti" name="flag_meninggal_cuti" value="<?=$ls_flag_meninggal_cuti;?>" tabindex="31" class="select_format" <?=($ls_status_submit_agenda =="Y")? " style=\"width:275px;background-color:#F5F5F5\"" : " style=\"width:275px;background-color:#ffff99\"";?>>
            <option value="">-- Pilih --</option>
            <option value="Y" <?php if ($ls_flag_meninggal_cuti=="Y"){ echo " selected"; } ?> >YA</option>
            <option value="T" <?php if ($ls_flag_meninggal_cuti=="T"){ echo " selected"; } ?>>TIDAK</option>
        </select>		   
    		</div>    		
    		<div class="clear"></div>
        <?php } ?>
    
        <div class="form-row_kiri">
        <label style = "text-align:right;">Keterangan &nbsp;</label>
    			<textarea cols="255" rows="1" id="jkm_ket_tambahan" name="jkm_ket_tambahan" tabindex="33" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled style=\"width:270px;background-color:#F5F5F5;\"" : " style=\"width:270px;background-color:#ffff99\"";?>><?=$ls_jkm_ket_tambahan;?></textarea>	   							
        </div>
    		<div class="clear"></div>
    		
				<?
				echo "<script type=\"text/javascript\">fl_js_jkm_tipe_negara_kejadian();</script>";
    		?>	
				
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
    include_once "../ajax/pn5040_agenda_jakon_tk.php";
    ?>						
    </div>
  </div>	
  <?	
}
?>	

<!--
<div style="display: none;" id="tab31" class="tab_konten">
  <div id="konten">
  <?
  //------------- input data rekening ------------------------------------------
  include_once "../ajax/pn5040_tabrekening.php";
  ?>						
  </div>
</div>
-->
	
<div style="display: none;" id="tab11" class="tab_konten">
  <div id="konten" style="width: 1200px;">
  <?
  //------------- kelengkapan administrasi -----------------------------------
  include_once "../ajax/pn5040_tabadministrasi.php";
  ?>						
  </div>
</div>
