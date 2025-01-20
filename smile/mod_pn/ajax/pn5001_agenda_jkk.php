<ul id="nav">
	<li><a href="#tab1" id="t1" class="active">Informasi Klaim</a></li>
	<?    	
  //urutan proses klaim jkk agenda I, Pengajuan I, Agenda II, Penetapan. Kecuali utk JKK Promotif dan JKK TKI Onsite						 				
  if ($ls_kode_pointer_asal =="PROMOTIF")
  {
    ?>								
    <li><a href="#tab2" id="t2">Rincian Biaya Kegiatan</a></li>
    <li><a href="#tab3" id="t3">Penggantian Biaya</a></li>
    <?
  }else if ($ls_kode_segmen =="TKI" && $ls_kode_perlindungan == "ONSITE")
  {
    ?>								
    <li><a href="#tab2" id="t2">Agenda JKK</a></li>
    <?	
  }else
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
      <li><a href="#tab3" id="t3">Info Pengajuan JKK Tahap I</a></li> 
  		<li><a href="#tab4" id="t4">Agenda JKK Tahap II</a></li> 
      <?
    }
  }
  ?>	
	
  <?
	//tad administrasi tampil jika sudah diisi jenis kasusnya ---
  if ($ls_kode_jenis_kasus!="")
	{
    ?>
    <li><a href="#tab11" id="t11">Administrasi</a></li>
  	<?
	}
	?>
</ul>

<div style="display: none;" id="tab1" class="tab_konten">
  <div id="konten">
  <?
  //------------- informasi klaim ----------------------------------------------
  include "../ajax/pn5001_tabinfoklaim.php";
  ?>						
  </div>
</div>

<?    							 				
if ($ls_kode_pointer_asal =="PROMOTIF")
{
  ?>								
  <?
}else if ($ls_kode_segmen =="TKI" && $ls_kode_perlindungan == "ONSITE")
{
  ?>								
  <div style="display: none;" id="tab2" class="tab_konten">
    <div id="konten">
    <?
    //------------- agenda jkk tki onsite --------------------------------------
    include "../ajax/pn5001_agenda_jkk_tkionsite.php";
    ?>						
    </div>
  </div>	
  <?
}else
{ 
  ?>								
  <div style="display: none;" id="tab2" class="tab_konten">
    <div id="konten">
    <?
    //------------- agenda jkk tahap 1 -----------------------------------------
    include "../ajax/pn5001_agenda_jkk_tahap1.php";
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
      //------------- tk jakons --------------------------------------
      include "../ajax/pn5001_agenda_jakon_tk.php";
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
      //------------- agenda jkk tahap 1 -----------------------------------------
      include "../ajax/pn5001_view_jkk_pengajuan.php";
      ?>						
      </div>
    </div>
		
    <div style="display: none;" id="tab4" class="tab_konten">
      <div id="konten">
      <?
      //------------- agenda jkk tahap 1 -----------------------------------------
      include "../ajax/pn5001_agenda_jkk_tahap2.php";
      ?>						
      </div>
    </div>						 
    <?
  }
}
?>	

<?
//tad administrasi tampil jika sudah diisi jenis kasusnya ---
if ($ls_kode_jenis_kasus!="")
{
?>
  <div style="display: none;" id="tab11" class="tab_konten">
    <div id="konten">
    <?
    //------------- kelengkapan administrasi -----------------------------------
    include "../ajax/pn5001_tabadministrasi.php";
    ?>						
    </div>
  </div>
<?
}
?>

