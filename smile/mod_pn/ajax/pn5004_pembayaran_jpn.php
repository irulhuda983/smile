<ul id="nav"">					
  <li><a href="#tab1" id="t1">Informasi Agenda Klaim JP</a></li>	
<li><a href="#tab2" id="t2">Pembayaran Manfaat Lumpsum</a></li>
</ul>

<div style="display: none;" id="tab1" class="tab_konten">
  <div id="konten">
  <?
  //------------- informasi klaim ----------------------------------------------
  include "../ajax/pn5001_tabinfoklaim.php";
  include "../ajax/pn5001_agenda_jpn_inputdataklaim.php";
  include "../ajax/pn5001_tabadministrasi.php";
	
  //jika menerima manfaat lumpsum ------------------------------------------
  if ($ls_flag_lumpsum == "Y")
  {
   	 include "../ajax/pn5001_view_jpn_manfaatlumpsum.php";
  }	
  ?>											
  </div>
</div>

<div style="display: none;" id="tab2" class="tab_konten">
  <div id="konten">
  <?
  include "../ajax/pn5004_pembayaran_grid.php";
  ?>	
  </div>
</div>
		