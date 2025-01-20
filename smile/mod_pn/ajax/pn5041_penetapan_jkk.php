<ul id="nav"">
	<?
	if ($ls_flag_agenda_12=="T")
	{
  	?>
  	<li><a href="#tab1" id="t1">Informasi Agenda Klaim JKK</a></li>
  	<?
	}else
	{
  	?>
  	<li><a href="#tab1" id="t1">Informasi Agenda Klaim JKK Tahap I & II</a></li>
  	<?	
	}
	?>							
	<li><a href="#tab2" id="t2">Penetapan Manfaat Biaya dan Santunan</a></li>	
</ul>

<div style="display: none;" id="tab1" class="tab_konten">
  <div id="konten">
  <?
  //------------- informasi klaim ----------------------------------------------
  include "../ajax/pn5040_view_tabinfoklaim.php";
	if ($ls_flag_agenda_12=="T")
	{
	 	 include "../ajax/pn5040_agenda_jkk_1tahap.php";
	}else
	{
    include "../ajax/pn5040_view_jkk_tahap1.php";
  	if ($ls_kode_segmen=="JAKON")
  	{
  	 	 include "../ajax/pn5040_agenda_jakon_tk.php";
  	}
  	include "../ajax/pn5040_view_jkk_pengajuan.php";	
	}
  include "../ajax/pn5040_view_tabadministrasi.php";
  ?>											
  </div>
</div>
		
<div style="display: none;" id="tab2" class="tab_konten">
  <div id="konten">
  <?
	if ($ls_status_submit_penetapan=="Y")
	{
    //------------- view penetapan manfaat --------------------------------------------
    include "../ajax/pn5041_view_penetapanmanfaat.php";	
	}else
	{
    //------------- penetapan manfaat --------------------------------------------
    include "../ajax/pn5041_penetapanmanfaat.php";
	}
  ?>
  </div>
</div>		