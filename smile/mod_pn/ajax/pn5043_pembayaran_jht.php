<ul id="nav"">					
  <li><a href="#tab1" id="t1">Informasi Agenda Klaim JHT</a></li>	
<li><a href="#tab2" id="t2">Pembayaran Manfaat</a></li>
</ul>

<div style="display: none;" id="tab1" class="tab_konten">
  <div id="konten">
  <?
  //------------- informasi klaim ----------------------------------------------
  include "../ajax/pn5040_view_tabinfoklaim.php";
  include "../ajax/pn5040_view_jht.php";
	include "../ajax/pn5041_view_penetapanmanfaat.php";
  include "../ajax/pn5040_view_tabadministrasi.php";
	?>										
  </div>
</div>

<div style="display: none;" id="tab2" class="tab_konten">
  <div id="konten">
  <?
  include "../ajax/pn5043_pembayaran_grid.php";
  ?>	
  </div>
</div>
		