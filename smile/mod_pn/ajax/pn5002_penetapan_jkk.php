<ul id="nav"">					
  <li><a href="#tab1" id="t1">Informasi Agenda Klaim JKK Tahap I & II</a></li>	
	<li><a href="#tab2" id="t2">Penetapan Manfaat Biaya dan Santunan</a></li>	
</ul>

<div style="display: none;" id="tab1" class="tab_konten">
  <div id="konten">
  <?
  //------------- informasi klaim ----------------------------------------------
  include "../ajax/pn5001_view_tabinfoklaim.php";
  include "../ajax/pn5001_view_jkk_tahap1.php";
	if ($ls_kode_segmen="JAKON")
	{
	 	 include "../ajax/pn5001_agenda_jakon_tk.php";
	}
	include "../ajax/pn5001_view_jkk_pengajuan.php";
  include "../ajax/pn5001_tabadministrasi.php";
  ?>											
  </div>
</div>
		
<div style="display: none;" id="tab2" class="tab_konten">
  <div id="konten">
  <?
  //------------- penetapan manfaat --------------------------------------------
  include "../ajax/pn5002_penetapanmanfaat.php";
  ?>
  </div>
</div>		