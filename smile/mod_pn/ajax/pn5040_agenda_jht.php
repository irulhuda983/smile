<ul id="nav">
	<li><a href="#tab1" id="t1" class="active">Informasi Klaim</a></li>
	<li><a href="#tab2" id="t2">Input Data Klaim JHT</a></li>
  <li><a href="#tab11" id="t11">Administrasi</a></li>
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
						
<div style="display: none;" id="tab2" class="tab_konten">
  <div id="konten">
  <?
  //------------- agenda jht -----------------------------------------
  include "../ajax/pn5040_agenda_jht_inputdataklaim.php";
  ?>						
  </div>
</div>

<div style="display: none;" id="tab11" class="tab_konten">
  <div id="konten" style="width: 1200px;">
  <?
  //------------- kelengkapan administrasi -----------------------------------
  include "../ajax/pn5040_tabadministrasi.php";
  ?>						
  </div>
</div>
