<ul id="nav"">					
  <li><a href="#tab1" id="t1">Informasi Agenda Klaim JKM</a></li>	
<li><a href="#tab2" id="t2">Pembayaran Manfaat</a></li>
</ul>

<div style="display: none;" id="tab1" class="tab_konten">
  <div id="konten">
  <?
  //------------- informasi klaim ----------------------------------------------
  include "../ajax/pn5001_view_tabinfoklaim.php";
  include "../ajax/pn5001_view_jkm.php";
	include "../ajax/pn5002_view_penetapanmanfaat.php";
  include "../ajax/pn5001_tabadministrasi.php";
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
		