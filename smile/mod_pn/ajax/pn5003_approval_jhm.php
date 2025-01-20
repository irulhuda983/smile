<ul id="nav"">					
  <li><a href="#tab1" id="t1">Informasi Agenda Klaim JHT/JKM</a></li>	
	<li><a href="#tab2" id="t2">Penetapan Manfaat Biaya dan Santunan</a></li>	
</ul>

<div style="display: none;" id="tab1" class="tab_konten">
  <div id="konten">
  <?
  //------------- informasi klaim ----------------------------------------------
  include "../ajax/pn5001_view_tabinfoklaim.php";
  include "../ajax/pn5001_view_jhm.php";
  include "../ajax/pn5001_tabadministrasi.php";
  ?>											
  </div>
</div>
		
<div style="display: none;" id="tab2" class="tab_konten">
  <div id="konten">
  <?
  //------------- penetapan manfaat --------------------------------------------
  include "../ajax/pn5002_view_penetapanmanfaat.php";
  ?>
  </div>
</div>		