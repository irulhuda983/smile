<ul id="nav">
	<li><a href="#tab1" id="t1" class="active">Informasi Klaim</a></li>
	<?
	if ($ls_flag_agenda_12=="Y")
	{
  		?>								
      <li><a href="#tab2" id="t2">Agenda JKK Tahap I</a></li>
			
  		<?
  		if ($ls_kode_segmen =="JAKON")
      {
        ?>								
        <li><a href="#tab5" id="t5">TK Jakon</a></li>
        <?			
  		}
  		?>	
					
      <?
      if ($ls_status_submit_pengajuan=="Y")
      {
        ?>
        <li><a href="#tab3" id="t3">Info Pengajuan Tahap I</a></li> 
    		<li><a href="#tab4" id="t4">Agenda JKK Tahap II</a></li>
				<!--<li><a href="#tab31" id="t31">Input Rekening</a></li>--> 
        <?
      }
			
    	//tad administrasi tampil jika sudah diisi jenis kasusnya ----------------
      if ($ls_kode_jenis_kasus!="")
    	{
        ?>
        <li><a href="#tab11" id="t11">Administrasi</a></li>
      	<?
    	}				
	}else
	{
    if ($ls_kode_pointer_asal =="PROMOTIF")
    {
      ?>								
      <li><a href="#tab2" id="t2">Rincian Biaya Kegiatan</a></li>
      <li><a href="#tab3" id="t3">Penggantian Biaya</a></li>
      <?  	
		}else
  	{
  		?>								
      <li><a href="#tab2" id="t2">Agenda JKK</a></li>
			<!--<li><a href="#tab31" id="t31">Input Data Rekening</a></li>-->
			<li><a href="#tab11" id="t11">Administrasi</a></li> 
      <?  	 
  	}	 	
	}
	?>
	
</ul>

<div style="display: none;" id="tab1" class="tab_konten">
  <div id="konten">
  <?
  //------------- informasi antrian ------------------------------------
  include "../ajax/pn5040_tabinfoantrian.php";
  // -------- end informasi antrian ------------------------------------

  //------------- informasi klaim ----------------------------------------------
  include "../ajax/pn5040_tabinfoklaim.php";
  ?>						
  </div>
</div>

<?
if ($ls_flag_agenda_12=="Y") //melalui agenda tahap1,pengajuan dan agenda tahap 2
{
  ?>								
  <div style="display: none;" id="tab2" class="tab_konten">
    <div id="konten">
    <?
    //------------- agenda jkk tahap 1 -----------------------------------------
    include "../ajax/pn5040_agenda_jkk_tahap1.php";
    ?>						
    </div>
  </div>
  
  <?
  if ($ls_kode_segmen =="JAKON")
  {
    ?>								
    <div style="display: none;" id="tab5" class="tab_konten">
      <div id="konten">
      <?
      //------------- tk jakons ------------------------------------------------
      include "../ajax/pn5040_agenda_jakon_tk.php";
      ?>						
      </div>
    </div>
    <?			
  }
  ?>	
  
  <?
  if ($ls_status_submit_pengajuan=="Y")
  {
    ?>
    <div style="display: none;" id="tab3" class="tab_konten">
      <div id="konten">
      <?
      //------------- view pengajuan jkk tahap 1 -------------------------------
      include "../ajax/pn5040_view_jkk_pengajuan.php";
      ?>						
      </div>
    </div>
		
    <div style="display: none;" id="tab4" class="tab_konten">
      <div id="konten">
      <?
      //------------- agenda jkk tahap 2 ---------------------------------------
      include "../ajax/pn5040_agenda_jkk_tahap2.php";
      ?>						
      </div>
    </div>		
		<!--
    <div style="display: none;" id="tab31" class="tab_konten">
      <div id="konten">
      <?
      //------------- input data rekening --------------------------------------
      include "../ajax/pn5040_tabrekening.php";
      ?>						
      </div>
    </div>
		-->
    <?
  }
  
  //tad administrasi tampil jika sudah diisi jenis kasusnya --------------------
  if ($ls_kode_jenis_kasus!="")
  {
    ?>
    <div style="display: none;" id="tab11" class="tab_konten">
      <div id="konten" style="width: 1200px;">
      <?
      //------------- kelengkapan administrasi ---------------------------------
      include "../ajax/pn5040_tabadministrasi.php";
      ?>						
      </div>
    </div>
    <?
  }				
}else //langsung, tanpa melalui agenda tahap1,pengajuan dan agenda tahap 2 -----
{
  if ($ls_kode_pointer_asal =="PROMOTIF")
  {
    ?>
    <?  	
  }else
  {
    ?>	
    <div style="display: none;" id="tab2" class="tab_konten">
      <div id="konten">
      <?
      //------------- agenda jkk -----------------------------------------------
      include "../ajax/pn5040_agenda_jkk_1tahap.php";		
  		?>
      </div>
    </div>
		
		<!--
    <div style="display: none;" id="tab31" class="tab_konten">
      <div id="konten">
      <?
      //------------- input data rekening --------------------------------------
      include "../ajax/pn5040_tabrekening.php";
      ?>						
      </div>
    </div>
		-->
		
    <div style="display: none;" id="tab11" class="tab_konten">
      <div id="konten" style="width: 1200px;">
      <?
      //------------- kelengkapan administrasi ---------------------------------
      include "../ajax/pn5040_tabadministrasi.php";
      ?>						
      </div>
    </div>
    <?  	 
  }	 	
}
?>
