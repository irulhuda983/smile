<ul id="nav">
	<li><a href="#tab1" id="t1" class="active">Informasi Klaim</a></li>
	<li><a href="#tab2" id="t2">Input Data Klaim JHT/JKM</a></li>
  <li><a href="#tab11" id="t11">Administrasi</a></li>
</ul>

<div style="display: none;" id="tab1" class="tab_konten">
  <div id="konten">
  <?
  //------------- informasi klaim ----------------------------------------------
  include "../ajax/pn5001_tabinfoklaim.php";
  ?>						
  </div>
</div>
						
<div style="display: none;" id="tab2" class="tab_konten">
  <div id="konten">
  <?
  //------------- agenda jht/jkm -----------------------------------------
  include "../ajax/pn5001_agenda_jhm_inputdataklaim.php";
  ?>						
  </div>
</div>

<div style="display: none;" id="tab11" class="tab_konten">
  <div id="konten">
  <?
  //------------- kelengkapan administrasi -----------------------------------
  include "../ajax/pn5001_tabadministrasi.php";
  ?>						
  </div>
</div>
